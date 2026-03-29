<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\User;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
{
    
    public function index(Request $request)
    {
        $month = $request->month ?? date('m');
        $year = $request->year ?? date('Y');

        $expenses = Expense::with('user')
            ->whereMonth('expense_date', $month)
            ->whereYear('expense_date', $year)
            ->orderBy('expense_date', 'desc')
            ->paginate(15);

        return view('admin.expenses.index', compact('expenses', 'month', 'year'));
    }

    public function create()
    {
        return view('admin.expenses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'description' => 'required|string|max:255',
            'expense_date' => 'required|date',
        ]);

        $user = Auth::user();

        if ($user->wallet_balance < $request->amount) {
            return redirect()->back()->with('error', 'Insufficient wallet balance.')->withInput();
        }

        DB::transaction(function () use ($request, $user) {
            // Create Expense
            $expense = Expense::create([
                'user_id' => $user->id,
                'amount' => $request->amount,
                'description' => $request->description,
                'expense_date' => $request->expense_date,
            ]);

            // Deduct from Wallet
            $user->decrement('wallet_balance', $request->amount);

            // Create Transaction
            WalletTransaction::create([
                'user_id' => $user->id,
                'amount' => $request->amount,
                'type' => 'debit',
                'description' => 'Expense: ' . $request->description,
                'balance_after' => $user->wallet_balance,
            ]);
        });

        return redirect()->route('admin.expenses.index')->with('success', 'Expense added successfully.');
    }

    public function edit(Expense $expense)
    {
        return view('admin.expenses.create', compact('expense'));
    }

    public function update(Request $request, Expense $expense)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'description' => 'required|string|max:255',
            'expense_date' => 'required|date',
        ]);

        $user = Auth::user();
        $diff = $request->amount - $expense->amount;

        if ($diff > 0 && $user->wallet_balance < $diff) {
            return redirect()->back()->with('error', 'Insufficient wallet balance for this update.')->withInput();
        }

        DB::transaction(function () use ($request, $expense, $user, $diff) {
            // Update Wallet
            if ($diff != 0) {
                if ($diff > 0) {
                    $user->decrement('wallet_balance', $diff);
                    $type = 'debit';
                } else {
                    $user->increment('wallet_balance', abs($diff));
                    $type = 'credit';
                }

                WalletTransaction::create([
                    'user_id' => $user->id,
                    'amount' => abs($diff),
                    'type' => $type,
                    'description' => 'Expense update diff: ' . $request->description,
                    'balance_after' => $user->wallet_balance,
                ]);
            }

            // Update Expense
            $expense->update([
                'amount' => $request->amount,
                'description' => $request->description,
                'expense_date' => $request->expense_date,
            ]);
        });

        return redirect()->route('admin.expenses.index')->with('success', 'Expense updated successfully.');
    }

    public function destroy(Expense $expense)
    {
        $user = Auth::user();

        DB::transaction(function () use ($expense, $user) {
            // Add back to Wallet
            $user->increment('wallet_balance', $expense->amount);

            // Create Transaction
            WalletTransaction::create([
                'user_id' => $user->id,
                'amount' => $expense->amount,
                'type' => 'credit',
                'description' => 'Expense deleted: ' . $expense->description,
                'balance_after' => $user->wallet_balance,
            ]);

            $expense->delete();
        });

        return redirect()->route('admin.expenses.index')->with('success', 'Expense deleted and amount returned to wallet.');
    }

    public function reports(Request $request)
    {
        $month = $request->month ?? date('m');
        $year = $request->year ?? date('Y');

        $users = User::where('role', User::$admin)->get();
        $reportData = [];

        foreach ($users as $u) {
            $monthCredits = WalletTransaction::where('user_id', $u->id)
                ->where('type', 'credit')
                ->whereMonth('created_at', $month)
                ->whereYear('created_at', $year)
                ->sum('amount');

            $monthDebits = WalletTransaction::where('user_id', $u->id)
                ->where('type', 'debit')
                ->whereMonth('created_at', $month)
                ->whereYear('created_at', $year)
                ->sum('amount');

            $reportData[] = (object)[
                'id' => $u->id,
                'name' => $u->name,
                'email' => $u->email,
                'current_balance' => $u->wallet_balance,
                'monthly_added' => $monthCredits,
                'monthly_expenses' => $monthDebits,
            ];
        }

        return view('admin.reports.expenses', compact('reportData', 'month', 'year'));
    }

    public function exportReport(Request $request)
    {
        $month = $request->month ?? date('m');
        $year = $request->year ?? date('Y');
        $fileName = "Expense_Report_{$month}_{$year}.csv";

        $users = User::where('role', User::$admin)->get();

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Admin Name', 'Email', 'Current Balance', 'Added (This Month)', 'Expenses (This Month)'];

        $callback = function() use($users, $columns, $month, $year) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($users as $u) {
                $monthCredits = WalletTransaction::where('user_id', $u->id)
                    ->where('type', 'credit')
                    ->whereMonth('created_at', $month)
                    ->whereYear('created_at', $year)
                    ->sum('amount');

                $monthDebits = WalletTransaction::where('user_id', $u->id)
                    ->where('type', 'debit')
                    ->whereMonth('created_at', $month)
                    ->whereYear('created_at', $year)
                    ->sum('amount');

                fputcsv($file, [$u->name, $u->email, $u->wallet_balance, $monthCredits, $monthDebits]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportExpenses(Request $request)
    {
        $month = $request->month ?? date('m');
        $year = $request->year ?? date('Y');
        $fileName = "Expenses_List_{$month}_{$year}.csv";

        $expenses = Expense::with('user')
            ->whereMonth('expense_date', $month)
            ->whereYear('expense_date', $year)
            ->orderBy('expense_date', 'desc')
            ->get();

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['#', 'Date', 'User', 'Description', 'Amount'];

        $callback = function() use($expenses, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($expenses as $key => $expense) {
                fputcsv($file, [
                    $key + 1,
                    $expense->expense_date,
                    $expense->user->name,
                    $expense->description,
                    $expense->amount
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}

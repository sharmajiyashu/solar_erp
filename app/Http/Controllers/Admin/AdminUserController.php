<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAdminUserRequest;
use App\Http\Requests\Admin\UpdateAdminUserRequest;
use App\Http\Resources\Admin\AdminUserResource;
use App\Models\Attendance;
use App\Models\Expense;
use App\Models\User;
use App\Models\WalletTransaction;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminUserController extends Controller
{

    public function index()
    {
        $admin_users = User::where('role', User::$admin)->paginate(10);
        $data = json_decode(json_encode(AdminUserResource::collection($admin_users)));
        return view('admin.admin_users.index', compact('admin_users', 'data'));
    }

    public function create()
    {
        $roles = Role::get();
        return view('admin.admin_users.create', compact('roles'));
    }


    public function show($id)
    {
       $month = $request->month ?? Carbon::now()->month;
        $year  = $request->year ?? Carbon::now()->year;

        $start = Carbon::create($year, $month, 1);
        $end = $start->copy()->endOfMonth();

        $dates = CarbonPeriod::create($start, $end);

        $attendances = Attendance::where('user_id', $id)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->get()
            ->keyBy(function ($item) {
                return \Carbon\Carbon::parse($item->date)->format('Y-m-d');
            });

        return view('admin.attendance.index', compact('dates', 'attendances', 'month', 'year'));
    }


    public function store(StoreAdminUserRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($request->password);
        $data['role'] = User::$admin;
        $role = Role::findById($request->role_id);
        $user = User::create($data);
        if ($user && $role) {
            $user->assignRole($role);
        }
        session()->flash('success', 'Admin User Create');
    }


    public function edit(User $user, $id)
    {
        $user = User::find($id);
        $user = json_decode(json_encode(new AdminUserResource($user)));
        $roles = Role::get();
        return view('admin.admin_users.create', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAdminUserRequest $request, $id)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($request->password);
        $data['role'] = User::$admin;
        unset($data['role_id']);
        $role = Role::findById($request->role_id);
        $user = User::where('id', $id)->update($data);
        $user = User::find($id);
        $user->roles()->detach();
        if ($user && $role) {
            $user->assignRole($role);
        }
        session()->flash('success', 'Admin User updated successfully');
    }


    function destroy($id)
    {
        User::where('id', $id)->delete();
        return redirect()->back()->with('success', 'Admin User deleted successfully');
    }

    public function myWallet()
    {
        $user = Auth::user();
        $month = request('month', date('m'));
        $year = request('year', date('Y'));

        $transactions = WalletTransaction::where('user_id', $user->id)
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.wallet.index', compact('user', 'transactions', 'month', 'year'));
    }

    public function walletManagement()
    {
        $this->middleware('permission:wallet manage');
        $users = User::permission('expenses create')->paginate(15);
        return view('admin.wallet.list', compact('users'));
    }

    public function walletHistory($id)
    {
        $user = User::findOrFail($id);
        
        // Security Check: Only 'wallet manage' can view others' wallets
        if (Auth::id() !== $user->id && !Auth::user()->can('wallet manage')) {
            abort(403, 'Unauthorized access to this wallet.');
        }

        $month = request('month', date('m'));
        $year = request('year', date('Y'));

        $transactions = WalletTransaction::where('user_id', $user->id)
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.wallet.index', compact('user', 'transactions', 'month', 'year'));
    }

    public function addBudget(Request $request)
    {
        // Only Super Admin ('wallet manage') should be able to manually update wallets
        if (!Auth::user()->can('wallet manage')) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0.01',
            'type' => 'required|in:credit,debit',
            'description' => 'required|string|max:255',
        ]);

        $targetUser = User::findOrFail($request->user_id);

        if ($request->type == 'debit' && $targetUser->wallet_balance < $request->amount) {
            return redirect()->back()->with('error', 'Insufficient wallet balance for this deduction.');
        }

        DB::transaction(function () use ($request, $targetUser) {
            if ($request->type == 'credit') {
                $targetUser->increment('wallet_balance', $request->amount);
            } else {
                $targetUser->decrement('wallet_balance', $request->amount);
            }

            WalletTransaction::create([
                'user_id' => $targetUser->id,
                'amount' => $request->amount,
                'type' => $request->type,
                'description' => 'Manually Managed: ' . $request->description,
                'balance_after' => $targetUser->wallet_balance,
            ]);
        });

        return redirect()->back()->with('success', 'Wallet updated successfully.');
    }
}

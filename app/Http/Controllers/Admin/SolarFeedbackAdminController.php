<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TechnicianReview;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SolarFeedbackAdminController extends Controller
{
    /**
     * Customer (user) reviews of technicians — not admin-submitted ratings.
     */
    public function index(Request $request): View
    {
        $query = TechnicianReview::query()
            ->with(['customer:id,name,email,mobile', 'technician:id,name,email', 'slot']);

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('technician_id')) {
            $query->where('technician_id', $request->technician_id);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $reviews = $query->orderByDesc('created_at')->paginate(30)->withQueryString();
        $users = User::query()->where('role', User::$user)->orderBy('name')->get(['id', 'name', 'email']);
        $technicians = User::query()->where('role', User::$admin)->orderBy('name')->get(['id', 'name', 'email']);

        return view('admin.solar.feedback_index', compact('reviews', 'users', 'technicians'));
    }
}

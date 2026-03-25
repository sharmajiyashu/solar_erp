<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserDashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        
        $subscriptions = \App\Models\UserSubscription::where('user_id', $userId)
            ->where('status', 'active')
            ->with(['package'])
            ->get();
            
        $subscriptionIds = $subscriptions->pluck('id');
        
        $totalSlotsCount = \App\Models\ServiceSlot::whereIn('subscription_id', $subscriptionIds)->count();
        $completedSlotsCount = \App\Models\ServiceSlot::whereIn('subscription_id', $subscriptionIds)
            ->where('status', 'completed')
            ->count();
            
        $upcomingSlots = \App\Models\ServiceSlot::whereIn('subscription_id', $subscriptionIds)
            ->where('status', 'pending')
            ->where('service_date', '>=', now()->startOfDay())
            ->with('subscription.package')
            ->orderBy('service_date', 'asc')
            ->limit(5)
            ->get();

        return view('user.dashboard', compact(
            'subscriptions', 
            'totalSlotsCount', 
            'completedSlotsCount', 
            'upcomingSlots'
        ));
    }

    public function profile()
    {
        $user = Auth::user();
        return view('user.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'required|string|max:15',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user->name = $request->name;
        $user->mobile = $request->mobile;
        $user->city = $request->city;
        $user->state = $request->state;
        $user->address = $request->address;

        if ($request->hasFile('profile_image')) {
            if ($user->profile_image) {
                $oldPath = public_path('uploads/profile/' . $user->profile_image);
                if (file_exists($oldPath)) { unlink($oldPath); }
            }

            $image = $request->file('profile_image');
            $name = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('uploads/profile');
            if (!file_exists($destinationPath)) { mkdir($destinationPath, 0777, true); }
            $image->move($destinationPath, $name);
            $user->profile_image = $name;
        }

        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Profile updated successfully!',
            'image_url' => $user->profile_image ? url('public/uploads/profile/'.$user->profile_image) : null
        ]);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Current password does not match.'
            ], 422);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Password updated successfully!'
        ]);
    }

    public function services()
    {
        $packages = \App\Models\ServicePackage::where('status', 1)->get();
        $subscriptions = \App\Models\UserSubscription::where('user_id', Auth::id())
            ->with(['package', 'slots'])
            ->latest()
            ->get();
            
        return view('user.services', compact('packages', 'subscriptions'));
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAdminUserRequest;
use App\Http\Requests\Admin\UpdateAdminUserRequest;
use App\Http\Resources\Admin\AdminUserResource;
use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminUserController extends Controller
{

    // public function __construct()
    // {
    //     $this->middleware('permission:admin_users view', ['only' => ['index', 'show']]);
    //     $this->middleware('permission:admin_users create', ['only' => ['create', 'store']]);
    //     $this->middleware('permission:admin_users edit', ['only' => ['edit', 'update']]);
    //     $this->middleware('permission:admin_users delete', ['only' => ['destroy']]);
    // }


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
}

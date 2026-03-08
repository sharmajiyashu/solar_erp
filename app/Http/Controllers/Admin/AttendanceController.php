<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function punchIn(Request $request)
    {

        $request->validate([
            'photo' => 'required'
        ]);

        $image = $request->photo;

        $image = str_replace('data:image/png;base64,', '', $image);
        $image = str_replace(' ', '+', $image);

        $imageName = time() . '.png';

        $path = public_path('uploads/punch_photos');

        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }

        file_put_contents($path . '/' . $imageName, base64_decode($image));

        Attendance::updateOrCreate(

            [
                'user_id' => Auth::id(),
                'date' => now()->toDateString()
            ],

            [
                'punch_in' => now()->format('H:i:s'),
                'punch_in_photo' => 'uploads/punch_photos/' . $imageName
            ]

        );

        return back()->with('success', 'Punch In Successful');
    }

    public function punchOut()
    {

        Attendance::where('user_id', Auth::id())
            ->whereDate('date', now())
            ->update([
                'punch_out' => now()->format('H:i:s')
            ]);

        return back()->with('success', 'Punch Out Successful');
    }


    public function index(Request $request)
    {
        $month = $request->month ?? Carbon::now()->month;
        $year  = $request->year ?? Carbon::now()->year;

        $start = Carbon::create($year, $month, 1);
        $end = $start->copy()->endOfMonth();

        $dates = CarbonPeriod::create($start, $end);

        $attendances = Attendance::where('user_id', Auth::id())
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->get()
            ->keyBy(function ($item) {
                return \Carbon\Carbon::parse($item->date)->format('Y-m-d');
            });

        return view('admin.attendance.index', compact('dates', 'attendances', 'month', 'year'));
    }
}

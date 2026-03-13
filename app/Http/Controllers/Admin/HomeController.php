<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{


    public function index()
    {
        $authUser = Auth::user();
        
        // Base queries for permission filtering
        $leadsQuery = \App\Models\Lead::query();
        $enquiryQuery = \App\Models\Enquiry::query();

        if (!$authUser->can('dashboard get-all')) {
            $leadsQuery->where('created_by', $authUser->id);
            $enquiryQuery->where('created_by', $authUser->id);
        }

        // --- Statistics Cards ---
        $totalEnquiries = (clone $enquiryQuery)->count();
        $pendingFollowups = (clone $enquiryQuery)->whereIn('status', ['pending', 'next_followup'])->count();
        $markToClose = (clone $enquiryQuery)->where('status', 'mark_to_close')->count();
        $closedEnquiries = (clone $enquiryQuery)->where('status', 'closed')->count();
        $convertedToLeads = (clone $enquiryQuery)->where('status', 'converted_to_lead')->count();
        $totalLeads = (clone $leadsQuery)->count();

        // --- Stage Statistics (for chart) ---
        $stageStats = (clone $leadsQuery)
            ->select('stage', DB::raw('count(*) as count'))
            ->groupBy('stage')
            ->pluck('count', 'stage')
            ->toArray();

        $allStages = \App\Models\Lead::$workflowStages;
        $formattedStageStats = [];
        foreach ($allStages as $stage) {
            $formattedStageStats[$stage] = $stageStats[$stage] ?? 0;
        }

        // --- Lead Generation Trends ---
        // Yearly Trend (Monthly counts for current year)
        $yearlyTrend = (clone $leadsQuery)
            ->whereYear('created_at', now()->year)
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('count(*) as count'))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->toArray();
        
        $formattedYearlyTrend = [];
        for ($i = 1; $i <= 12; $i++) {
            $formattedYearlyTrend[date('M', mktime(0, 0, 0, $i, 1))] = $yearlyTrend[$i] ?? 0;
        }

        // Monthly Trend (Daily counts for current month)
        $monthlyTrend = (clone $leadsQuery)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->select(DB::raw('DAY(created_at) as day'), DB::raw('count(*) as count'))
            ->groupBy('day')
            ->orderBy('day')
            ->pluck('count', 'day')
            ->toArray();
        
        $daysInMonth = now()->daysInMonth;
        $formattedMonthlyTrend = [];
        for ($i = 1; $i <= $daysInMonth; $i++) {
            $formattedMonthlyTrend[$i] = $monthlyTrend[$i] ?? 0;
        }

        // Today's Follow-ups
        $today = now()->startOfDay();
        $todayFollowUps = (clone $enquiryQuery)
            ->whereDate('next_followup_date', $today)
            ->with('creator')
            ->orderBy('next_followup_date', 'asc')
            ->get();

        return view('admin.dashboard.index', compact(
            'totalLeads',
            'totalEnquiries',
            'pendingFollowups',
            'markToClose',
            'closedEnquiries',
            'convertedToLeads',
            'formattedStageStats',
            'formattedYearlyTrend',
            'formattedMonthlyTrend',
            'todayFollowUps'
        ));
    }
}

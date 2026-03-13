@extends('admin.layouts.app')
@section('content')
    <style>
        .stat-card {
            border-radius: 15px;
            padding: 1.5rem;
            color: #fff;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            border: none;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .stat-card h2 {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 0.2rem;
        }

        .stat-card p {
            font-size: 1rem;
            opacity: 0.9;
            margin-bottom: 0;
            font-weight: 500;
        }

        .bg-gradient-primary { background: linear-gradient(135deg, #4e73df 0%, #224abe 100%); }
        .bg-gradient-success { background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%); }
        .bg-gradient-info { background: linear-gradient(135deg, #36b9cc 0%, #258391 100%); }
        .bg-gradient-warning { background: linear-gradient(135deg, #f6c23e 0%, #dda20a 100%); }
        .bg-gradient-danger { background: linear-gradient(135deg, #e74a3b 0%, #be2617 100%); }
        .bg-gradient-secondary { background: linear-gradient(135deg, #858796 0%, #60616f 100%); }

        .chart-container {
            position: relative;
            margin: auto;
            height: 300px;
        }
    </style>

    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row mb-2">
                <div class="col-12">
                    <h2 class="content-header-title float-start mb-0">Analytics Dashboard</h2>
                </div>
            </div>

            <div class="content-body">
                <!-- Statistics Section -->
                <div class="row mt-1">
                    <div class="col-lg-2 col-md-4 col-sm-6">
                        <div class="card stat-card bg-gradient-primary text-center">
                            <h2>{{ $totalEnquiries }}</h2>
                            <p>Total Enquiry</p>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4 col-sm-6">
                        <div class="card stat-card bg-gradient-warning text-center">
                            <h2>{{ $pendingFollowups }}</h2>
                            <p>Pending Followups</p>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4 col-sm-6">
                        <div class="card stat-card bg-gradient-danger text-center">
                            <h2>{{ $markToClose }}</h2>
                            <p>Mark to Close</p>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4 col-sm-6">
                        <div class="card stat-card bg-gradient-secondary text-center">
                            <h2>{{ $closedEnquiries }}</h2>
                            <p>Closed</p>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4 col-sm-6">
                        <div class="card stat-card bg-gradient-success text-center">
                            <h2>{{ $convertedToLeads }}</h2>
                            <p>Converted Leads</p>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4 col-sm-6">
                        <div class="card stat-card bg-gradient-info text-center">
                            <h2>{{ $totalLeads }}</h2>
                            <p>Total Leads</p>
                        </div>
                    </div>
                </div>

                <!-- Charts Section -->
                <div class="row match-height">
                    <!-- Lead Stage Breakdown -->
                    <div class="col-md-4 col-12 mb-2">
                        <div class="card h-100 shadow-sm">
                            <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                                <h4 class="card-title">Lead Stage Breakdown</h4>
                            </div>
                            <div class="card-body py-2">
                                <div class="chart-container">
                                    <canvas id="stageChart"></canvas>
                                </div>
                                <div class="mt-2 text-center">
                                    <small class="text-muted">Current distribution of leads across stages</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Yearly Trend -->
                    <div class="col-md-8 col-12 mb-2">
                        <div class="card h-100 shadow-sm">
                            <div class="card-header border-bottom">
                                <h4 class="card-title">Leads Generated ({{ now()->year }})</h4>
                            </div>
                            <div class="card-body py-2">
                                <div class="chart-container" style="height: 300px;">
                                    <canvas id="yearlyChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row match-height">
                    <!-- Monthly Trend -->
                    <div class="col-md-12 col-12 mb-2">
                        <div class="card shadow-sm">
                            <div class="card-header border-bottom">
                                <h4 class="card-title">Leads Generated ({{ now()->format('F Y') }})</h4>
                            </div>
                            <div class="card-body py-2">
                                <div class="chart-container" style="height: 250px;">
                                    <canvas id="monthlyChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stage Count Summary -->
                <div class="row mb-2">
                    <div class="col-12">
                        <div class="card shadow-sm">
                            <div class="card-header border-bottom">
                                <h4 class="card-title">Lead Stage Summary</h4>
                            </div>
                            <div class="card-body py-2">
                                <div class="row text-center">
                                    @foreach($formattedStageStats as $stage => $count)
                                        <div class="col-lg-3 col-md-4 col-6 mb-1">
                                            <div class="p-1 border rounded shadow-sm bg-light">
                                                <h5 class="mb-0 text-primary">{{ $count }}</h5>
                                                <small class="text-uppercase font-weight-bold" style="font-size: 0.7rem;">{{ str_replace('_', ' ', $stage) }}</small>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Today's Followup Table -->
                <div class="row">
                    <div class="col-12">
                        <div class="card shadow-sm">
                            <div class="card-header border-bottom">
                                <h4 class="card-title">Today's Scheduled Follow-ups</h4>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Enquiry No</th>
                                                <th>Customer Name</th>
                                                <th>Mobile</th>
                                                <th>Created By</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($todayFollowUps as $enquiry)
                                                <tr>
                                                    <td><strong>{{ $enquiry->enquiry_no }}</strong></td>
                                                    <td>{{ $enquiry->customer_name }}</td>
                                                    <td>{{ $enquiry->mobile }}</td>
                                                    <td>{{ $enquiry->creator->name ?? 'N/A' }}</td>
                                                    <td class="text-center">
                                                        <a href="{{ route('admin.enquiries.show', $enquiry->id) }}" class="btn btn-sm btn-outline-primary round">View Details</a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center py-4 text-muted">No follow-ups today</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // --- Stage Chart ---
            const stageCtx = document.getElementById('stageChart').getContext('2d');
            const stageData = @json($formattedStageStats);
            new Chart(stageCtx, {
                type: 'doughnut',
                data: {
                    labels: Object.keys(stageData).map(s => s.replace('_', ' ').toUpperCase()),
                    datasets: [{
                        data: Object.values(stageData),
                        backgroundColor: [
                            '#4e73df', // Primary Blue
                            '#1cc88a', // Success Green
                            '#36b9cc', // Info Cyan
                            '#f6c23e', // Warning Yellow
                            '#e74a3b', // Danger Red
                            '#858796', // Secondary Gray
                            '#fd7e14', // Orange
                            '#6f42c1', // Purple
                            '#e83e8c', // Pink
                            '#20c997', // Teal
                            '#ffc107', // Amber
                            '#17a2b8'  // Info Blue-Green
                        ],
                        hoverOffset: 15,
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom', labels: { boxWidth: 10, padding: 15 } }
                    },
                    cutout: '70%',
                }
            });

            // --- Yearly Trend Chart ---
            const yearlyCtx = document.getElementById('yearlyChart').getContext('2d');
            const yearlyData = @json($formattedYearlyTrend);
            new Chart(yearlyCtx, {
                type: 'line',
                data: {
                    labels: Object.keys(yearlyData),
                    datasets: [{
                        label: 'Leads Generated',
                        data: Object.values(yearlyData),
                        borderColor: '#4e73df',
                        backgroundColor: 'rgba(78, 115, 223, 0.1)',
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#4e73df',
                        pointRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } },
                        x: { grid: { display: false } }
                    }
                }
            });

            // --- Monthly Trend Chart ---
            const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
            const monthlyData = @json($formattedMonthlyTrend);
            new Chart(monthlyCtx, {
                type: 'bar',
                data: {
                    labels: Object.keys(monthlyData),
                    datasets: [{
                        label: 'Leads',
                        data: Object.values(monthlyData),
                        backgroundColor: '#1cc88a',
                        borderRadius: 5,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } },
                        x: { grid: { display: false } }
                    }
                }
            });
        });
    </script>
@endsection

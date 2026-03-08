@extends('admin.layouts.app')

@section('content')
    @push('css_links')
        <style>
            .error {
                color: #a93c3d !important;
                font-weight: 500;
            }
        </style>
    @endpush

    <div class="app-content content">
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
                <form method="GET" action="" class="row mb-3">

                    <div class="col-md-3">
                        <select name="month" class="form-control">

                            @foreach (range(1, 12) as $m)
                                <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                                    {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                </option>
                            @endforeach

                        </select>
                    </div>

                    <div class="col-md-3">
                        <select name="year" class="form-control">

                            @foreach (range(date('Y') - 5, date('Y')) as $y)
                                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>
                                    {{ $y }}
                                </option>
                            @endforeach

                        </select>
                    </div>

                    <div class="col-md-2">
                        <button class="btn btn-primary">
                            Filter
                        </button>
                    </div>

                </form>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="content-body">
                        <table class="table table-bordered">

                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Punch In</th>
                                    <th>Punch Out</th>
                                    <th>Photo</th>
                                </tr>
                            </thead>

                            <tbody>

                                @foreach ($dates as $date)
                                    @php
                                        $attendance = $attendances[$date->format('Y-m-d')] ?? null;

                                        $isFuture = $date->isFuture();
                                        $isHoliday = $date->isSunday(); // Sunday holiday
                                        $isAbsent = !$attendance && !$isFuture && !$isHoliday;
                                    @endphp

                                    <tr
                                        @if ($isAbsent) class="table-danger"
@elseif($isHoliday) class="table-secondary" @endif>

                                        <td>{{ $date->format('d M Y') }}</td>

                                        <td>
                                            @if ($attendance && $attendance->punch_in)
                                                {{ \Carbon\Carbon::parse($attendance->punch_in)->format('h:i A') }}
                                            @else
                                                -
                                            @endif
                                        </td>

                                        <td>
                                            @if ($attendance && $attendance->punch_out)
                                                {{ \Carbon\Carbon::parse($attendance->punch_out)->format('h:i A') }}
                                            @else
                                                -
                                            @endif
                                        </td>

                                        <td>

                                            @if ($attendance && $attendance->punch_in_photo)
                                                <a href="{{ url('public/' . $attendance->punch_in_photo) }}" target="_blank">
                                                    View
                                                </a>
                                            @else
                                                @if ($isHoliday)
                                                    <span class="badge bg-secondary">Holiday</span>
                                                @elseif($isFuture)
                                                    <span class="badge bg-light text-dark">Upcoming</span>
                                                @else
                                                    <span class="badge bg-danger">Absent</span>
                                                @endif
                                            @endif

                                        </td>

                                    </tr>
                                @endforeach

                            </tbody>

                        </table>
                    </div>
                </div>

            </div>




        </div>
    </div>
@endsection

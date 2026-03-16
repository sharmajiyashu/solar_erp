<?php

namespace App\Exports;

use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AttendanceUserSheet implements FromCollection, WithTitle, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    private $user;
    private $month;
    private $year;
    private $presentCount = 0;

    public function __construct($user, $month, $year)
    {
        $this->user = $user;
        $this->month = $month;
        $this->year = $year;
    }

    public function collection()
    {
        $attendances = Attendance::where('user_id', $this->user->id)
            ->whereMonth('date', $this->month)
            ->whereYear('date', $this->year)
            ->orderBy('date', 'asc')
            ->get();

        $this->presentCount = $attendances->count();

        return $attendances;
    }

    public function title(): string
    {
        return $this->user->name;
    }

    public function headings(): array
    {
        return [
            ['Employee Name: ' . $this->user->name],
            ['Month/Year: ' . date('F', mktime(0, 0, 0, $this->month, 1)) . ' ' . $this->year],
            [],
            ['Date', 'Punch In', 'Punch Out', 'Status']
        ];
    }

    public function map($attendance): array
    {
        return [
            $attendance->date,
            $attendance->punch_in ?? '-',
            $attendance->punch_out ?? '-',
            $attendance->punch_in ? 'Present' : 'Absent'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();
        
        // Add Summary Row
        $summaryRow = $lastRow + 1;
        $sheet->setCellValue('C' . $summaryRow, 'Total Present:');
        $sheet->setCellValue('D' . $summaryRow, $this->presentCount);

        return [
            // Style the header
            4 => ['font' => ['bold' => true]],
            1 => ['font' => ['bold' => true, 'size' => 14]],
            $summaryRow => ['font' => ['bold' => true]],
        ];
    }
}

<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class AttendanceExport implements WithMultipleSheets
{
    private $users;
    private $month;
    private $year;

    public function __construct($users, $month, $year)
    {
        $this->users = $users;
        $this->month = $month;
        $this->year = $year;
    }

    public function sheets(): array
    {
        $sheets = [];

        foreach ($this->users as $user) {
            $sheets[] = new AttendanceUserSheet($user, $this->month, $this->year);
        }

        return $sheets;
    }
}

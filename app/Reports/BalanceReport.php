<?php namespace App\Reports;

use Carbon\Carbon;

class BalanceReport extends Report
{
    public function generate($html = true)
    {
        return 'Hello world!';
    }

    /**
     * @return array
     */
    protected function configuration()
    {
        return [
            "date_range" => Carbon::now()->subDay(30)->format("U") . ',' . Carbon::now()->format("U"),
            "output_type" => "table"
        ];
    }
}
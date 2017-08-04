<?php namespace App\Reports;

use Carbon\Carbon;

class SpendingReport extends Report
{
    public function generate($html = true)
    {
        switch ($this->model->configuration->output_type) {

            case 'table':
                $view = 'reports._reports.spending_report.table';
                break;

            case 'graph':
                $view = 'reports._reports.spending_report.graph';
                break;

            default:

                return 'Sorry there was an error';
        }

        $dateRange = explode(',', $this->model->configuration->date_range);
        $dateRange = [
            'start' => $dateRange[0],
            'end' => $dateRange[1]
        ];

        //
        // Query for spending breakdown over time by day
        //
        $query = "SELECT * FROM transactions " .
            "WHERE transactions.`date` > ? AND transactions.`date` < ? ";

        $resultDateMap = [];
        $totalPaidIn = 0;
        $totalPaidOut = 0;
        $totalTransactions = 0;

        foreach (\DB::select($query, [$dateRange['start'], $dateRange['end']]) as $row) {
            $date = Carbon::createFromFormat('U', $row->date)->format('d-m-Y');
            if (!isset($resultDateMap[$date])) {
                $resultDateMap[$date] = new SpendingReportItem($date, $row);
            }else{
                /** @var SpendingReportItem $spendingReportItem */
                $spendingReportItem = $resultDateMap[$date];
                $spendingReportItem->setItems($spendingReportItem->getItems()->push($row));
            }
        }

        /** @var SpendingReportItem $item */
        foreach($resultDateMap as $item) {
            $totalTransactions += $item->getCount();
            $totalPaidIn += $item->getTotal('paid_in');
            $totalPaidOut += $item->getTotal('paid_out');
        }

        return view($view)
            ->with('results', $resultDateMap)
            ->with('totals', ['paid_in' => $totalPaidIn, 'paid_out' => $totalPaidOut, 'transactions' => $totalTransactions])
            ->with('configuration', $this->model->configuration);
    }

    /**
     * @return array
     */
    protected function configuration()
    {
        // @todo add dropdown for account selection
        // @todo add drop down for selecting "by" as in "by day", "by month", "by year", "by week", etc
        return [
            "date_range" => Carbon::now()->subDay(30)->format("U") . ',' . Carbon::now()->format("U"),
            "output_type" => "table"
        ];
    }
}
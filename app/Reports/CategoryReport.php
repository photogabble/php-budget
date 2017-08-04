<?php namespace App\Reports;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class CategoryReport extends Report
{
    public function generate()
    {
        switch($this->model->configuration->output_type) {

            case 'table':
                return $this->table();
                break;

            case 'graph':
                return $this->graph();
                break;

            case 'both':
                return $this->graph()->render() . $this->table()->render();

            default:
                return 'Sorry there was an error';
        }
    }

    /**
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    private function graph()
    {
        $data = $this->getData();

        $graphData = [
            'bar' => [
                'labels' => [],
                'datasets' => [
                    'paid_in' => [],
                    'paid_out' => []
                ]
            ],
            'pie' => [
                'labels' => '"Paid In (£)", "Paid Out (£)"',
                'datasets' => [
                    'data' => [0,0]
                ]
            ],
        ];

        /** @var CategoryReportItem $item */
        foreach ($data['results'] as $item) {
            array_push($graphData['bar']['labels'], $item->getName());
            array_push($graphData['bar']['datasets']['paid_in'], ($item->getPaidIn(true) / 100));
            array_push($graphData['bar']['datasets']['paid_out'], ($item->getPaidOut(true) / 100));

            $graphData['pie']['datasets']['data'][0] += $item->getPaidIn(true);
            $graphData['pie']['datasets']['data'][1] += $item->getPaidOut(true);
        }

        $graphData['bar']['labels'] = '"'. implode('","', $graphData['bar']['labels']).'"';
        $graphData['bar']['datasets']['paid_in'] = implode(',', $graphData['bar']['datasets']['paid_in']);
        $graphData['bar']['datasets']['paid_out'] = implode(',', $graphData['bar']['datasets']['paid_out']);

        $graphData['pie']['datasets']['data'][0] = number_format($graphData['pie']['datasets']['data'][0] / 100, 2, '.', '');
        $graphData['pie']['datasets']['data'][1] = number_format($graphData['pie']['datasets']['data'][1] / 100, 2, '.', '');
        $graphData['pie']['datasets']['data'] = implode(',', $graphData['pie']['datasets']['data']);


        return view('reports._reports.category_report.graph')
            ->with('graphData', $graphData)
            ->with('totals', $data['totals'])
            ->with('configuration', $this->model->configuration);
    }

    /**
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    private function table()
    {
        $data = $this->getData();

        return view('reports._reports.category_report.table')
            ->with('results', $data['results'])
            ->with('totals', $data['totals'])
            ->with('configuration', $this->model->configuration);
    }

    private function getData()
    {
        $dateRange = explode(',', $this->model->configuration->date_range);
        $dateRange = [
            'start' => $dateRange[0],
            'end'   => $dateRange[1]
        ];

        //
        // Query for category breakdown over time
        //
        $query = "SELECT categories.name, COUNT(transactions.id) as total_transactions, ".
            "SUM(transactions.paid_in) as total_paid_in, ".
            "SUM(transactions.paid_out) as total_paid_out FROM transactions " .
            "JOIN categories ON categories.id = transactions.category_id " .
            "WHERE transactions.`date` > ? AND transactions.`date` < ? ".
            "GROUP BY categories.name";

        $results = new Collection();

        $totalPaidIn  = 0;
        $totalPaidOut = 0;
        $totalTransactions = 0;

        foreach (\DB::select($query, [$dateRange['start'], $dateRange['end']]) as $record) {
            $totalPaidIn += $record->total_paid_in;
            $totalPaidOut += $record->total_paid_out;
            $totalTransactions += $record->total_transactions;
            $results->push(new CategoryReportItem($record));
        }

        // Calculate percentages
        $results->map(function(CategoryReportItem $record) use($totalTransactions){
            $record->setPercentage( ($record->getTransactionCount() / $totalTransactions) * 100);
            return $record;
        });

        $results = $results->sortBy(function(CategoryReportItem $record){
            return $record->getTransactionCount();
        });

        return [
            'results' => $results,
            'totals' => ['paid_in' => $totalPaidIn, 'paid_out' => $totalPaidOut, 'transactions' => $totalTransactions]
        ];
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
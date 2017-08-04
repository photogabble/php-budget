<?php namespace App\Repositories;

use App\Report;
use App\Reports\BalanceReport;
use App\Reports\CategoryReport;
use App\Reports\SpendingReport;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class ReportRepository
 * Used to get saved reports from datastore.
 * @package App\Repositories
 */
class ReportRepository extends Repository
{
    /**
     * @var Report|Builder
     */
    protected $model;

    private $reports = [
        SpendingReport::class => [
            'name' => 'Overall Spending Breakdown',
            'class' => SpendingReport::class,
            'description' => 'Displays overall spending breakdown'
        ],
        CategoryReport::class => [
            'name' => 'Category Breakdown',
            'class' => CategoryReport::class,
            'description' => 'Displays spending breakdown by category'
        ],
        BalanceReport::class => [
            'name' => 'Balance',
            'class' => BalanceReport::class,
            'description' => 'Displays account balance over time'
        ]
    ];

    public function __construct(Report $model)
    {
        parent::__construct($model);
    }

    public function reports()
    {
        $reports = [];
        foreach ($this->reports as $report) {
            array_push($reports, $this->report($report['class']));
        }
        return $reports;
    }

    /**
     * @param $className
     * @param Report|null $model
     * @param null $name
     * @param null $description
     * @return \App\Reports\Report|string
     */
    public function report($className, Report $model = null, $name = null, $description = null)
    {
        $class = '\\' . ltrim($className, '\\');

        if (is_null($model)) {
            $model = $this->getNew();
        }

        /** @var \App\Reports\Report $class */
        $class = new $class($model);

        $class->setName((is_null($name)) ? $this->reports[$className]['name'] : $name);
        $class->setDescription((is_null($description)) ? $this->reports[$className]['description'] : $description);
        return $class;
    }
}
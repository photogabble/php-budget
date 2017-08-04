<?php namespace App\Reports;

use Carbon\Carbon;
use Illuminate\Support\Collection;

class SpendingReportItem
{
    /** @var string */
    private $date;

    /** @var Collection */
    private $items;

    private $count;

    /**
     * SpendingReportItem constructor.
     * @param string $date
     * @param \stdClass $data
     */
    public function __construct($date, \stdClass $data)
    {
        $this->date = Carbon::createFromFormat("d-m-Y", $date);
        $this->items = new Collection([
            $data
        ]);
        $this->count = $this->items->count();
    }

    public function getCount()
    {
        return $this->count;
    }

    public function getDate($format = "d-m-Y")
    {
        return $this->date->format($format);
    }

    public function getItems()
    {
        return $this->items;
    }

    public function setItems(Collection $items)
    {
        $this->items = $items;
        $this->count = $items->count();
    }

    public function getTotal($key)
    {
        $total = 0;
        $this->items->map(function($item) use ($key, &$total){
            $total += $item->{$key};
            return $item;
        });
        return $total;
    }
}
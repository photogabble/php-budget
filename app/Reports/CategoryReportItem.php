<?php namespace App\Reports;

class CategoryReportItem
{
    private $name;

    private $transactionCount;

    private $paidIn;

    private $paidOut;

    private $percentage;

    public function __construct(\stdClass $data)
    {
        $this->name = $data->name;
        $this->paidIn = $data->total_paid_in;
        $this->paidOut = $data->total_paid_out;
        $this->transactionCount = $data->total_transactions;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param bool $raw
     * @return string|int
     */
    public function getPaidIn($raw = false)
    {
        if ($raw === false) {
            return number_format(($this->paidIn / 100), 2);
        }

        return $this->paidIn;
    }

    /**
     * @param bool $raw
     * @return string|int
     */
    public function getPaidOut($raw = false)
    {
        if ($raw === false) {
            return number_format(($this->paidOut / 100), 2);
        }

        return $this->paidOut;
    }

    /**
     * @return string
     */
    public function getPercentage()
    {
        return number_format($this->percentage, 1);
    }

    /**
     * @param mixed $percentage
     */
    public function setPercentage($percentage)
    {
        $this->percentage = $percentage;
    }

    /**
     * @return int
     */
    public function getTransactionCount()
    {
        return $this->transactionCount;
    }
}
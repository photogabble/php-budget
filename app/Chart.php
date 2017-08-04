<?php namespace App;

class Chart
{
    /**
     * @var array
     */
    private $data = [];
    private $labelKey;
    private $dataKey;

    /**
     * Chart constructor.
     * @param array $data
     * @param string $labelKey
     * @param string $dataKey
     */
    public function __construct(array $data, $labelKey, $dataKey)
    {
        $this->data     = $data;
        $this->labelKey = $labelKey;
        $this->dataKey  = $dataKey;
    }

    public function getLabels()
    {
        $labels = [];
        foreach($this->data as $item) {
            array_push($labels, $item->{$this->labelKey});
        }
        return json_encode($labels);
    }

    public function getDataSets()
    {
        $dataSets = [];
        foreach($this->data as $item) {
            array_push($dataSets, $item->{$this->dataKey});
        }
        return json_encode($dataSets);
    }
}
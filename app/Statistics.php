<?php namespace App;

class Statistics
{
    private $statistics = [];

    public function getStatistic($key) {
        return $this->statistics[$key];
    }

    public function setStatistic($key, array $value = []) {
        $this->statistics[$key] = $value;
    }

    public function top($key, $by, $limit = 5)
    {
        if (!isset($this->statistics[$key])) {
            throw new \Exception('Not account statistic with key ['. $key .'] was found.');
        }

        $arr = $this->statistics[$key];
        usort ($arr, function ($a, $b) use($by){
            if ($a->{$by} == $b->{$by}) { return 0; }
            return ($a->{$by} < $b->{$by}) ? 1 : -1;
        });

        return array_slice($arr, 0, $limit);
    }
}
<?php namespace App\Reports\Configuration;

class Configuration
{
    /**
     * @var array
     */
    private $data;

    /**
     * Configuration constructor.
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->data = $data;
    }
}
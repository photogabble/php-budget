<?php namespace App;

use Camspiers\StatisticalClassifier\Model\Model;
use Illuminate\Cache\CacheManager;
use Illuminate\Contracts\Cache\Store;

class CachedClassifierModel extends Model
{
    /**
     * @var string
     */
    private $modelName;

    /**
     * @var Store|CacheManager
     */
    private $cacheManager;

    /**
     * CachedClassifierModel constructor.
     * @param $modelName
     * @param CacheManager|Store $cacheManager
     */
    public function __construct($modelName, CacheManager $cacheManager)
    {
        $this->modelName = $modelName;
        $this->cacheManager = $cacheManager;

        if ($data = $this->cacheManager->get($this->modelName))
        {
            $this->prepared = true;
            $this->model = $data;
        }
        $n = 1;
    }

    public function setModel($model)
    {
        parent::setModel($model);
        $this->cacheManager->put($this->modelName, $model, 0);
    }

    /**
     * Forget cached data
     * @return void
     */
    public function forget()
    {
        $this->cacheManager->forget($this->modelName);
        $this->prepared = false;
    }
}
<?php namespace App;

use App\Repositories\SuggestionEngineMappingsRepository;
use Camspiers\StatisticalClassifier\Model\Model;

class EloquentClassifierModel extends Model
{
    /**
     * @var string
     */
    private $modelName;
    /**
     * @var SuggestionEngineMappingsRepository
     */
    private $repository;


    /**
     * CachedClassifierModel constructor.
     * @param $modelName
     * @param SuggestionEngineMappingsRepository $repository
     */
    public function __construct($modelName, SuggestionEngineMappingsRepository $repository)
    {
        $this->modelName = $modelName;
        $this->repository = $repository;

        if ($data = $this->repository->fetchModel($this->modelName)){
            $this->prepared = true;
            $this->model = $data;
        }
    }

    public function setModel($model)
    {
        parent::setModel($model);
        $this->repository->storeModel($this->modelName, $model);
    }

    /**
     * Forget cached data
     * @return void
     */
    public function forget()
    {
        $this->repository->destroyModel($this->modelName);
        $this->prepared = false;
    }
}
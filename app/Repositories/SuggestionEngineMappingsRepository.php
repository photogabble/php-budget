<?php namespace App\Repositories;

use App\Category;
use App\SuggestionEngineMappings;
use Illuminate\Database\Eloquent\Builder;

class SuggestionEngineMappingsRepository extends Repository
{

    /**
     * @var SuggestionEngineMappings|Builder
     */
    protected $model;

    /**
     * @var CategoryRepository|Builder
     */
    private $categoryRepository;

    /**
     * SuggestionEngineMappingsRepository constructor.
     * @param SuggestionEngineMappings $model
     * @param CategoryRepository $categoryRepository
     */
    public function __construct(SuggestionEngineMappings $model, CategoryRepository $categoryRepository)
    {
        parent::__construct($model);
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Store model into database
     *
     * @param $modelName
     * @param array $array
     * @throws \Exception
     */
    public function storeModel($modelName, array $array)
    {
        $categoryHashMap = $this->categoryRepository->getCategoryHashMap();

        foreach($array as $category => $model) {
            if (! isset($categoryHashMap[$category])) {
                continue;
            }

            $this->save(
                $this->getNew([
                    'category_id' => $categoryHashMap[$category],
                    'modelName' => $modelName,
                    'model' => $model
                ])
            );
        }
    }

    /**
     * Fetch stored model from database
     *
     * @param $modelName
     * @return array
     */
    public function fetchModel($modelName)
    {
        $records = [];
        foreach($this->model->with('category')->where('modelName',$modelName)->get() as $record) {
            $records[strtolower(trim($record->category->name))] = $record->model;
        }
        return $records;
    }

    /**
     * Destroy the model
     *
     * @param $modelName
     * @throws \Exception
     */
    public function destroyModel($modelName)
    {
        $this->model->where('modelName', $modelName)->delete();
    }
}
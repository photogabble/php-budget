<?php namespace App\Repositories;

use App\Account;
use App\Category;
use Illuminate\Database\Eloquent\Builder;


class CategoryRepository extends Repository
{
    /**
     * @var Category|Builder
     */
    protected $model;

    public function __construct(Category $model)
    {
        parent::__construct($model);
    }

    public function getCategoryList()
    {
        return $this->model->pluck('name', 'id');
    }

    public function getCategoryToTransactionDescriptionList()
    {
        $sql = "SELECT categories.name as `category_name`, transactions.description FROM categories JOIN transactions ON transactions.category_id = categories.id GROUP BY transactions.description";
        return \DB::select($sql);
    }

    public function getCategoryHashMap()
    {
        $categoryHashMap = [];
        foreach($this->model->pluck('name', 'id') as $id => $name) {
            $categoryHashMap[strtolower(trim($name))] = $id;
        }
        return $categoryHashMap;
    }

}
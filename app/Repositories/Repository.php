<?php namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Repository
{
    /**
     * @var Model|\Illuminate\Database\Eloquent\Builder;
     */
    protected $model;

    public function __construct(Model $model = null)
    {
        $this->model = $model;
    }

    public function getModel()
    {
        return $this->model;
    }
    public function setModel($model)
    {
        $this->model = $model;
    }

    public function all($columns = ['*'])
    {
        return $this->model->all($columns);
    }

    /**
     * @param array $attributes
     * @return Model|\Illuminate\Database\Eloquent\Builder|static
     */
    public function getNew($attributes = [])
    {
        return $this->model->newInstance($attributes);
    }

    public function getNewByRequest(Request $request)
    {
        return $this->model->newInstance($request->only($this->model->getFillable()));
    }

    public function save($data)
    {
        if ($data instanceOf Model) {
            return $this->storeEloquentModel($data);
        } elseif (is_array($data)) {
            return $this->storeArray($data);
        }

        throw new \Exception("Save must be passed an instance of Model or an array.");
    }

    protected function storeEloquentModel(Model $model)
    {
        if ($model->getDirty()) {
            return $model->save();
        } else {
            return $model->touch();
        }
    }

    protected function storeArray($data)
    {
        $model = $this->getNew($data);
        return $this->storeEloquentModel($model);
    }

    public function getHashForModel(Model $model)
    {
        if (!method_exists($model, 'getHashableAttributes')) {
            throw new \Exception("The model [". class_basename($model)  ."] does not have the getHashableAttributes method.");
        }

        $hash = [];

        foreach($model->getHashableAttributes() as $attribute) {
            array_push($hash, $model->getAttribute($attribute));
        }

        return sha1(implode('//', $hash));
    }
}
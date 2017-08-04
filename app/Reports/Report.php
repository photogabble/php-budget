<?php namespace App\Reports;

use App\Report as Model;

abstract class Report
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;

    /**
     * @var array
     */
    protected $configuration = [];

    /**
     * @var Model
     */
    protected $model;

    /**
     * Report constructor.
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->setModel($model);
    }

    public function getBase64EncodedClassName()
    {
        return base64_encode(get_class($this));
    }

    public function setModel(Model $model)
    {
        if ($model->exists === false && empty($model->configuration)) {
            $model->configuration = $this->configuration();
        }
        $this->configuration = $model->configuration;
        $this->model = $model;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function render()
    {
        return view('reports._partials._report')
            ->with('reportName', $this->name)
            ->with('reportClassName', $this->getBase64EncodedClassName())
            ->with('reportConfiguration', $this->configuration)
            ->with('reportModel', $this->model)
            ->with('reportContent', $this->generate(true));
    }

    /**
     * Default configuration
     *
     * @return array
     */
    abstract protected function configuration();

    /**
     * @return mixed
     */
    abstract public function generate();
}
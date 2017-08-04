<?php namespace App;

use App\Repositories\SuggestionEngineMappingsRepository;
use Camspiers\StatisticalClassifier\Classifier\ComplementNaiveBayes;
use Camspiers\StatisticalClassifier\DataSource\DataArray;
use Illuminate\Cache\CacheManager;
use Illuminate\Contracts\Cache\Store;

class Classifier
{
    /**
     * @var DataArray
     */
    private $dataArr;

    /**
     * @var ComplementNaiveBayes
     */
    private $classifier;

    /**
     * @var CachedClassifierModel
     */
    private $model;

    /**
     * @var string
     */
    private $name;

    /**
     * @var Store|CacheManager
     */
    private $cache;

    /**
     * Classifier constructor.
     * @param $name
     * @param CacheManager $cache
     * @param SuggestionEngineMappingsRepository $repository
     */
    public function __construct($name, CacheManager $cache, SuggestionEngineMappingsRepository $repository)
    {
        $this->name = $name;
        $this->cache = $cache;

        $this->dataArr = new DataArray();
        $this->model = new EloquentClassifierModel($name, $repository);
        $this->classifier = new ComplementNaiveBayes($this->dataArr, $this->model);
    }

    public function getClassifier()
    {
        return $this->classifier;
    }

    /**
     * Teach the classifier your ways
     *
     * @param array $items
     * @param bool $append
     */
    public function teach(array $items, $append = true)
    {
        if ($append === true) {
            $this->model->forget();
            $this->classifier->setModel($this->model);

            //
            // @todo cached items should ideally be in the database for long term storage and then cached for speed?
            //
            if ($cachedItems = $this->cache->get($this->name . '__dataset')) {
                $items = array_merge($items, $cachedItems);
            }
        }

        foreach ($items as $string => $classification) {
            $this->dataArr->addDocument(trim(strtolower($classification)), trim(strtolower($string)));
        }

        $this->classifier->setDataSource($this->dataArr);
        $this->classifier->prepareModel();
        $this->cache->put($this->name . '__dataset', $items, 0);
    }

    /**
     * You should only use the classification engine once it has been primed by teaching it some categorisations
     * @return bool
     */
    public function isPrimed()
    {
        if ($cachedItems = $this->cache->get($this->name . '__dataset')) {
            return count($cachedItems) > 3;
        }

        return false;
    }

    /**
     * Export current dataset
     *
     * @return \Illuminate\Contracts\Cache\Repository|mixed
     */
    public function export()
    {
        return $this->model->getModel();
    }

    /**
     * Clear the current dataset cache.
     *
     * @return bool
     */
    public function forgetDataSet()
    {
        return $this->cache->forget($this->name . '__dataset');
    }

    public function forgetAll()
    {
        $this->forgetDataSet();
        $this->model->forget();
        $this->classifier->setModel($this->model);
    }

    /**
     * Classify the document and return its category
     * @param  string      $string The document to classify
     * @return string|bool The category of the document
     */
    public function classify($string)
    {
        if (!$this->isPrimed()){ return false; }
        return $this->classifier->classify(trim(strtolower($string)));
    }

    /**
     * Returns whether or not the document is of the category
     * @param  string  $classification The category in question
     * @param  string  $string The document to check
     * @return boolean Whether or not the document is in the category
     */
    public function is($classification, $string)
    {
        if (!$this->isPrimed()){ return false; }
        return $this->classifier->is(trim(strtolower($classification)), trim(strtolower($string)));
    }
}
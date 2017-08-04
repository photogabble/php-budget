<?php

namespace App\Http\Controllers;

use App\Classifier;
use App\Repositories\CategoryRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use App\Http\Requests;

class CategoriesEngineController extends Controller
{
    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    /**
     * @var Classifier
     */
    private $classifier;

    /**
     * CategoriesEngineController constructor.
     * @param CategoryRepository $categoryRepository
     * @param Classifier $classifier
     */
    public function __construct(CategoryRepository $categoryRepository, Classifier $classifier)
    {
        $this->categoryRepository = $categoryRepository;
        $this->classifier = $classifier;
    }

    public function engine()
    {
        return view('categories.engine')
            ->with('records', $this->classifier->export())
            ->with('isPrimed', $this->classifier->isPrimed())
            ->with('actionButtons', [
                ['class' => 'btn-default', 'href' => route('categories.index'), 'text' => 'Back'],
                ['class' => 'btn-primary', 'href' => route('categories.engine.teach'), 'text' => 'Teach']
            ]);
    }

    public function wipe()
    {
        $this->classifier->forgetAll();
        return redirect()->route('categories.engine')
            ->with('success', 'The suggestion engines mappings have been erased.');
    }

    /**
     * If a category is renamed, added or removed then the categories neural network will need retraining.
     *
     * Forget is useful for re-mapping from scratch, while not forgetting is useful for appending to mapping
     *
     * @param bool $forget
     * @return \Illuminate\Http\RedirectResponse
     */
    public function teach($forget = true)
    {
        if ($forget === true) {
            $this->classifier->forgetAll();
        }

        $teachings = [];
        foreach ($this->categoryRepository->getCategoryToTransactionDescriptionList() as $record){
            $teachings[$this->tokenizeString($record->description)] = $record->category_name;
        }

        $this->classifier->teach($teachings);

        return redirect()->route('categories.engine')
            ->with('success', 'Suggestion engine taught with '.count($teachings) . ' mappings.');
    }

    public function suggest(Request $request)
    {
        $query = trim(strtolower($request->get('q')));
        $categoryHashMap = $this->categoryRepository->getCategoryHashMap();
        $suggestion = $this->classifier->classify($this->tokenizeString($query));

        return new JsonResponse([
            'q' => $query,
            'id' => (isset($categoryHashMap[$suggestion])) ? $categoryHashMap[$suggestion] : 0,
            'suggestion' => $suggestion
        ]);
    }

    /**
     * @todo add porta stemma
     * @param $string
     * @return string
     */
    private function tokenizeString($string)
    {
        $search  = array(0,1,2,3,4,5,6,7,8,9);
        $replace = array('zero ', 'one ', 'two ', 'three ', 'four ', 'five ', 'six ', 'seven ', 'eight ', 'nine ');

        $string = str_replace($search, $replace, $string);
        //$string = preg_replace("/[^A-Za-z]/", ' ', $string);
        //$string = preg_replace("@\\b[a-z]\\b ?@i", "", $string);
        return strtolower(trim(str_replace('  ', ' ', $string)));
    }
}

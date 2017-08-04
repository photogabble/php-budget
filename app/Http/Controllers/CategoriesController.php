<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use App\Http\Requests;

class CategoriesController extends Controller
{

    public function index()
    {
        return view('categories.index')
            ->with('actionButtons', [
                ['class' => 'btn-default', 'href' => route('categories.engine'), 'text' => 'Suggestion Engine'],
                ['class' => 'btn-primary', 'href' => route('categories.create'), 'text' => 'Create']
            ])
            ->with('records', Category::all());
    }

    public function create()
    {
        return view('categories.create')
            ->with('record', new Category);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:categories|max:255',
        ]);

        $category = Category::create($request->only(['name']));

        if ($isDefault = $request->get('default')) {
            $category->default = true;
            Category::where('default', true)->update(['default' => false]);
            $category->save();
        }

        //
        // If this is an ajax request return json object
        //
        if ($request->isXmlHttpRequest()){
            return response()->json($category);
        }

        return redirect()->route('categories.index')
            ->with('success', 'New category ' . $request->get('name') . ' was successfully created.');
    }

    public function edit(Category $category)
    {
        return view('categories.update')
            ->with('actionButtons',[
                ['class' => 'btn-danger delete-button', 'href' => route('categories.destroy', $category->id), 'text' => 'Delete'],
                ['class' => 'btn-default', 'href' => route('categories.index'), 'text' => 'Back']
            ])
            ->with('record', $category);
    }

    public function update(Request $request, Category $category)
    {
        $this->validate($request, [
            'name' => 'required|unique:categories,id,' . $category->id . '|max:255',
        ]);

        $category->fill($request->only($category->getFillable()));

        if ($isDefault = $request->get('default')) {
            $category->default = true;
            Category::where('default', true)->update(['default' => false]);
        }else{
            $category->default = false;
        }

        $category->save();

        return redirect()->back()
            ->with('success', 'Category ' . $request->get('name') . ' was successfully updated.');
    }

    public function destroy(Category $category)
    {
        $transactionCount = $category->transactions()->count();
        if ($transactionCount > 0) {
            return new JsonResponse(['error' => "You can't delete that category, it has {$transactionCount} transactions."], 405);
        }

        $category->delete();

        session()->flash('success', 'Category "'. $category->name .'" was successfully deleted.');

        return new JsonResponse(['location' => route('categories.index')], 200);
    }
}

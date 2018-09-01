<?php

namespace Blog\Http\Controllers\Api\Admin;

use Illuminate\Http\Request;
use Blog\Category;

class CategoryController extends Controller
{
    protected $rules = [
        'name' => [
            'required',
            'max:255',
        ],
    ];

    public function index()
    {
        $categories = Category::orderBy('name')
            ->withCount('publishedPosts')
            ->withCount('draftPosts')
            ->get([
                'id',
                'name',
            ]);

        return response()->json($categories);
    }

    public function store(Request $request)
    {
        $rules = $this->rules;
        $rules['name'][] = 'unique:categories,name';
        $request->validate($rules);

        $category = new Category();
        $category->fill($request->all());
        $category->save();

        return response()->json($category);
    }

    public function update(Request $request, Category $category)
    {
        $rules = $this->rules;
        $rules['name'][] = "unique:categories,name,{$category->id}";
        $request->validate($rules);

        $category->fill($request->all());
        $category->save();

        return response()->json($category);
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return response()->json();
    }
}

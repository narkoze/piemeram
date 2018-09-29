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
        if (!in_array(auth()->user()->id, [1,3,5]) and
            Category::where('created_by', auth()->user()->id)->count() > 1
        ) {
            abort(403, 'You can create only 2 categories');
        }

        $rules = $this->rules;
        $rules['name'][] = 'unique:blog_categories,name';
        $request->validate($rules);

        $category = new Category();
        $category->fill($request->all());
        $category->createdBy()->associate(auth()->user());
        $category->save();

        return response()->json($category);
    }

    public function update(Request $request, Category $category)
    {
        if (!in_array(auth()->user()->id, [1,3,5]) and
            $category->createdBy->id != auth()->user()->id
        ) {
            abort(403, 'You can edit only your categories');
        }

        $rules = $this->rules;
        $rules['name'][] = "unique:blog_categories,name,{$category->id}";
        $request->validate($rules);

        $category->fill($request->all());
        $category->save();

        return response()->json($category);
    }

    public function destroy(Category $category)
    {
        if (!in_array(auth()->user()->id, [1,3,5]) and
            $category->createdBy->id != auth()->user()->id
        ) {
            abort(403, 'You can delete only your categories');
        }

        $category->delete();

        return response()->json();
    }
}

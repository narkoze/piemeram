<?php

namespace Blog\Http\Controllers\Api\Admin;

use Illuminate\Http\Request;
use Blog\Category;

class CategoryController extends Controller
{
    protected $defaultParams = [
        'sortBy' => 'name',
        'sortDirection' => 'asc',
    ];

    protected $rules = [
        'name' => [
            'required',
            'max:255',
        ],
    ];

    public function index(Request $request)
    {
        $params = $request->all() + $this->defaultParams;

        $query = Category::select([
            'blog_categories.id',
            'name'
        ])
            ->selectRaw('
                sum(case when blog_posts.published_at is not null then 1 else 0 end) as published_posts_count,
                sum(case when blog_posts.id is not null and blog_posts.published_at is null then 1 else 0 end) as draft_posts_count,
                sum(case when blog_posts.id is not null then 1 else 0 end) as total
            ')
            ->leftJoin('blog_category_post', 'blog_category_post.category_id', '=', 'blog_categories.id')
            ->leftJoin('blog_posts', 'blog_posts.id', '=', 'blog_category_post.post_id')
            ->groupBy([
                'blog_categories.id',
                'name',
            ])->orderBy($params['sortBy'], $params['sortDirection']);

        $categories = $query->get();

        return response()->json(compact('categories', 'params'));
    }

    public function store(Request $request)
    {
        if (!in_array(auth()->user()->id, [2,3,5]) and
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
        if (!in_array(auth()->user()->id, [2,3,5]) and
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
        if (!in_array(auth()->user()->id, [2,3,5]) and
            $category->createdBy->id != auth()->user()->id
        ) {
            abort(403, 'You can delete only your categories');
        }

        $category->delete();

        return response()->json();
    }
}

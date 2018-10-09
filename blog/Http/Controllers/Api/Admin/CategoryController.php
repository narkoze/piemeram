<?php

namespace Blog\Http\Controllers\Api\Admin;

use Blog\Repositories\CategoryRepository;
use Illuminate\Http\Request;
use Blog\Services\Excel;
use Blog\Category;

class CategoryController extends Controller
{
    protected $rules = [
        'name' => [
            'required',
            'max:255',
        ],
    ];

    public function index(CategoryRepository $catRepo, Request $request)
    {
        $params = $request->all() + $catRepo->params();

        $categories = $catRepo->categories($params)->paginate(10);

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

    public function excel(CategoryRepository $catRepo, Request $request)
    {
        $params = $request->all() + $catRepo->params();

        $title = trans('blog/admin/views/blog-admin-view-categories.title');

        $headings = [
            trans('blog/admin/views/blog-admin-view-categories.category') => null,
            trans('blog/admin/views/blog-admin-view-categories.posts') => [
                'format' => 'number',
            ],
            trans('blog/admin/views/blog-admin-view-categories.drafts') => [
                'format' => 'number',
            ],
        ];

        $data = $catRepo->categories($params)
            ->get()
            ->transform(function ($category) {
                return [
                    $category->name,
                    $category->published_posts_count,
                    $category->draft_posts_count,
                ];
            });

        return (new Excel($title, $headings, $data))->download("$title.xlsx");
    }
}

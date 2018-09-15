<?php

namespace Blog\Http\Controllers\Api;

use Illuminate\Http\Request;
use Blog\Category;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::select([
            'id',
            'name',
        ])
        ->orderBy('name');

        if ($request->only == 'publishedPosts') {
            $query->whereHas('publishedPosts');
        }

        if ($request->only == 'posts') {
            $query->whereHas('publishedPosts')
                ->orWhereHas('draftPosts');
        }

        $categories = $query->get();

        return response()->json($categories);
    }
}

<?php

namespace Blog\Http\Controllers\Api\Admin;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Piemeram\User;
use Blog\Category;
use Blog\Comment;
use Blog\Post;
use DB;

class DashboardController extends Controller
{
    public function index()
    {
        $dashboard = [
            'users_count' => User::count(),
            'posts_count' => Post::whereNotNull('published_at')->count(),
            'drafts_count' => Post::whereNull('published_at')->count(),
            'comments_count' => Comment::count(),
            'categories_count' => Category::count(),
        ];

        return response()->json($dashboard);
    }

    public function users()
    {
        $query = User::selectRaw('
            extract(year from created_at) as year,
            extract(month from created_at) as month,
            count(*) as count
        ')
        ->whereBetween('created_at', [
            Carbon::now()->startOfMonth()->subMonths(11)->toDateString(),
            Carbon::now()->toDateTimeString()
        ])
        ->groupBy([
            'year',
            'month'
        ])
        ->orderBy('year')
        ->orderBy('month')
        ->get();

        $countPerMonth = $this->fillMonthCount($query);

        return response()->json([
            'months' => $countPerMonth->keys(),
            'counts' => $countPerMonth->values()
        ]);
    }

    public function posts()
    {
        $query = Post::groupBy([
            'year',
            'month'
        ])
        ->orderBy('year')
        ->orderBy('month');

        $posts = clone $query;
        $posts = $posts->selectRaw('
            extract(year from published_at) as year,
            extract(month from published_at) as month,
            count(*) as count
        ')
        ->whereBetween('published_at', [
            Carbon::now()->startOfMonth()->subMonths(11)->toDateString(),
            Carbon::now()->toDateTimeString()
        ])
        ->get();

        $drafts = clone $query;
        $drafts = $drafts->selectRaw('
            extract(year from updated_at) as year,
            extract(month from updated_at) as month,
            count(*) as count
        ')
        ->whereNull('published_at')
        ->whereBetween('updated_at', [
            Carbon::now()->startOfMonth()->subMonths(11)->toDateString(),
            Carbon::now()->toDateTimeString()
        ])
        ->get();

        $postCountPerMonth = $this->fillMonthCount($posts);
        $draftCountPerMonth = $this->fillMonthCount($drafts);

        return response()->json([
            'months' => $postCountPerMonth->keys(),
            'posts' => $postCountPerMonth->values(),
            'drafts' => $draftCountPerMonth->values()
        ]);
    }

    public function comments()
    {
        $query = Post::select('title')
            ->withCount('comments')
            ->orderByDesc('comments_count')
            ->limit(10)
            ->get();

        $comments = $query->mapWithKeys(function ($item) {
            return [$item['title'] => $item['comments_count']];
        });

        return response()->json([
            'posts' => $comments->keys(),
            'counts' => $comments->values()
        ]);
    }

    public function categories()
    {
        $query = Category::select('name')
            ->withCount('publishedPosts')
            ->withCount('draftPosts')
            ->orderByDesc('published_posts_count')
            ->orderByDesc('draft_posts_count')
            ->limit(10)
            ->get();

        $categories = $query->mapWithKeys(function ($item) {
            return [$item['name'] => [
                $item['published_posts_count'],
                $item['draft_posts_count'],
            ]];
        });


        return response()->json([
            'categories' => $categories->keys(),
            'counts' => $categories->values()
        ]);
    }

    protected function getMonths()
    {
        return collect()
            ->times(12)
            ->sortBy(function ($month) {
                return ($month + (12 - Carbon::now()->month - 1)) % 12;
            })
            ->values();
    }

    protected function fillMonthCount($collection)
    {
        return $this->getMonths()
            ->mapWithKeys(function ($item) use ($collection) {
                $counts = $collection->mapWithKeys(function ($item) {
                    return [$item['month'] => $item['count']];
                });

                if ($counts[$item] ?? false) {
                    return [$item => $counts[$item]];
                }
                return [$item => 0];
            });
    }
}

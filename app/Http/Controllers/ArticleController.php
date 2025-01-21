<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Source;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;


class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = Article::query();

        if($request->has('prefered-authors')) {
            $preferedAuthors = $user->preferences()
                ->where('key', 'authors')
                ->pluck('value');
            $query->whereHas('authors', function ($query) use ($preferedAuthors) {
                $query->whereIn('name', $preferedAuthors);
            });
        }

        if($request->has('search')) {
            $query->where('title', 'like', '%'.$request->search.'%');
        }

        if ($request->has('source')) {
            $source = Source::firstWhere('name', $request->source);
            $query->where('source_id', $source->id);
        }

        if ($request->has('category')) {
            $query->whereHas('categories', function ($subQuery) use ($request) {
                $subQuery->where('name', $request->category);
            });
        } elseif ($request->has('prefered-categories')) {
            $preferedCategories = $user->preferences()
                ->where('key', 'categories')
                ->pluck('value');
            $query->whereHas('categories', function ($query) use ($preferedCategories) {
                $query->whereIn('name', $preferedCategories);
            });
        }

        if ($request->has('from')) {
            $from = Carbon::parse($request->from)
                ->startOfDay()
                ->toDateTimeString();

            $to = Carbon::now()
                ->endOfDay()
                ->toDateTimeString();

            $query->where('published_date', '>=', $from);

            if($request->has('to')) {
                $to = Carbon::parse($request->to)
                    ->endOfDay()
                    ->toDateTimeString();
            }

            $query->where('published_date', '<=', $to);
        }

        return response()->json($query->paginate(10));
    }

    public function show(Article $article)
    {
        try {
            return response()->json($article);
        } catch(Exception $e) {
            return response()->json([
                'error' => 'An unexpected error occurred.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }

    public function create(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'source_id' => 'required|exists:sources,id',
                'external_link' => 'required|string|max:255',
                'published_date' => 'nullable|date_format:Y-m-d\TH:i:sP',
                'authors' => 'nullable|array',
                'authors.*' => 'string|max:255',
                'categories' => 'required|array',
                'categories.*' => 'string|max:255',
            ]);
            
            $article = Article::create($validated);
            $article->syncCategories($validated['categories']);
            $article->syncAuthors($validated['authors']);

            return response()->json([
                'message' => 'Article successfully created',
                'data' => $article
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'error' => 'An unexpected error occurred.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }


    public function update(Request $request, Article $article)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'source_id' => 'required|exists:sources,id',
                'external_link' => 'required|string|max:255',
                'published_date' => 'nullable|date_format:Y-m-d\TH:i:sP',
                'authors' => 'nullable|array',
                'authors.*' => 'string|max:255',
                'categories' => 'required|array',
                'categories.*' => 'string|max:255',
            ]);

            $article->update($validated);
            $article->syncCategories($validated['categories']);
            $article->syncAuthors($validated['authors']);

            return response()->json([
                'message' => 'Article successfully updated',
                'data' => $article
            ], 200);

        } catch(Exception $e) {
            return response()->json([
                'error' => 'An unexpected error occurred.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(Article $article)
    {
        try {
            $article->delete();

            return response()->json([
                'message' => 'Article successfully deleted'
            ], 200);
        } catch(Exception $e) {
            return response()->json([
                'error' => 'An unexpected error occurred.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }
}

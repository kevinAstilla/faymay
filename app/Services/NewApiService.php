<?php

namespace App\Services;

use App\Services\NewsApi;
use App\Models\Source;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class NewApiService implements NewsApi
{
  public function fetchArticles(): array
  {
    $yesterday = Carbon::yesterday()->format('Y-m-d');
    $source = Source::firstWhere('name', 'news api');

    if (!$source) {
      throw new \Exception('Source not found in database: News Api');
    }

    $response = Http::get('https://newsapi.org/v2/everything', [
      'from' => $yesterday,
      'to' => $yesterday,
      'language' => 'en',
      'domains' => 'bbc.co.uk',
      'apiKey' => env('NEWS_API_API_KEY')
    ]);

    if(!$response->successful()) {
      throw new \Exception('Failed to fetch News Api article.');
    }
    return collect($response->json()['articles'])->map(function ($article) use ($source) {
      return [
          'title' => $article['title'],
          'external_link' => $article['url'],
          'source_id' => $source->id,
          'published_date' => Carbon::parse($article['publishedAt'])->format('Y-m-d H:i:s'),
          'authors' => [$article['author']],
          'categories' => [],
          'data' => json_encode($article)
      ];
    })->toArray();
  }
}
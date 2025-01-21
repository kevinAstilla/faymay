<?php

namespace App\Services;

use App\Services\NewsApi;
use App\Models\Source;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class NewYorkTimesApiService implements NewsApi
{
  public function fetchArticles(): array
  {
    $yesterday = Carbon::yesterday()->format('Ymd');
    $source = Source::firstWhere('name', 'new york times');


    if (!$source) {
      throw new \Exception('Source not found in database: New York Times');
    }
    
    $response = Http::get('https://api.nytimes.com/svc/search/v2/articlesearch.json', [
      'begin_date' => $yesterday,
      'end_date' => $yesterday,
      'api-key' => env('NEW_YORK_TIMES_API_KEY')
    ]);

    if(!$response->successful()) {
      throw new \Exception('Failed to fetch New York Times article.');
    }
    return collect($response->json()['response']['docs'])->map(function ($article) use ($source)  {
      return [
          'title' => $article['abstract'],
          'external_link' => $article['web_url'],
          'source_id' => $source->id,
          'published_date' => Carbon::parse($article['pub_date'])->format('Y-m-d H:i:s'),
          'authors' => [
            (isset($article['byline']['person'][0]['firstname']) ? $article['byline']['person'][0]['firstname'] : '') . ' ' .
            (isset($article['byline']['person'][0]['lastname']) ? $article['byline']['person'][0]['lastname'] : '')
          ],
          'categories' => [$article['section_name']],
          'data' => json_encode($article)
      ];
    })->toArray();
  }
}
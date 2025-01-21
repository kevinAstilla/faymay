<?php

namespace App\Services;

use App\Services\NewsApi;
use App\Models\Source;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class GuardianApiService implements NewsApi
{
  public function fetchArticles(): array
  {
    $yesterday = Carbon::yesterday()->format('Y-m-d');
    $source = Source::firstWhere('name', 'the guardian');

    if (!$source) {
      throw new \Exception('Source not found in database: The Guardian');
    }
    
    $response = Http::get('https://content.guardianapis.com/search', [
      'from-date' => $yesterday,
      'to-date' => $yesterday,
      'api-key' => env('THE_GUARDIAN_API_KEY')
    ]);

    if(!$response->successful()) {
      throw new \Exception('Failed to fetch The Guardian article.');
    }
    return collect($response->json()['response']['results'])->map(function ($article) use ($source)  {
      return [
          'title' => $article['webTitle'],
          'external_link' => $article['webUrl'],
          'source_id' => $source->id,
          'published_date' => Carbon::parse($article['webPublicationDate'])->format('Y-m-d H:i:s'),
          'authors' => [null],
          'categories' => [$article['pillarName'], $article["sectionName"]],
          'data' => json_encode($article)
      ];
    })->toArray();
  }
}
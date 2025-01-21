<?php

namespace App\Services;

use App\Models\Article;
use App\Models\Source;
use App\Services\NewApiService;
use App\Services\NewYorkTimesApiService;
use App\Services\GuardianApiService;


class NewsStrategyService
{
  protected $services;

  public function __construct()
  {
    $this->services = [
      new NewApiService(),
      new NewYorkTimesApiService(),
      new GuardianApiService(),
    ];
  }

  public function fetchArticles()
  {
    foreach ($this->services as $service) {
      $articles = $service->fetchArticles();

      foreach($articles as $article)
      {
        $articleRec = Article::updateOrCreate(
          [
            'external_link' => $article['external_link']
          ],
          $article
        );
        !empty($article['categories']) && $articleRec->syncCategories($article['categories']);
        !empty($article['authors']) && $articleRec->syncAuthors($article['authors']);
      }
    }
  }
}
<?php

namespace App\Services;

interface NewsApi
{
  public function fetchArticles(): array;
}
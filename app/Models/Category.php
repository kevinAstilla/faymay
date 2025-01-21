<?php

namespace App\Models;

use App\Contracts\Normalizable;
use App\Models\Article;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Category extends Model implements Normalizable
{
    protected $fillable = ['name'];

    public function articles(): BelongsToMany
    {
        return $this->belongsToMany(Article::class, 'articles_categories');
    }

    public static function normalize($data): string
    {
        return Str::of($data)
            ->trim()
            ->singular()
            ->lower();
    }
}

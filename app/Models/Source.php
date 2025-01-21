<?php

namespace App\Models;

use App\Contracts\Normalizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Article;
use Illuminate\Support\Str;

class Source extends Model implements Normalizable
{
    protected $fillable = [
        'name',
        'description',
    ];

    public function articles(): HasMany {
        return $this->hasMany(Article);
    }

    public static function normalize($data): string
    {
        return Str::of($data)
            ->trim()
            ->lower();
    }

}
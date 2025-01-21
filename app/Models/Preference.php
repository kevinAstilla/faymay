<?php

namespace App\Models;

use App\Models\User;
use App\Models\Source;
use App\Models\Author;
use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Preference extends Model
{
    public $types;

    public function __construct()
    {
        $this->types = [
            'authors' => new Author(),
            'sources' => new Source(),
            'categories' => new Category(),
        ];
    }

    protected $fillable = [
        'user_id',
        'key',
        'value',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User);
    }
}
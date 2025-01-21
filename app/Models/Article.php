<?php

namespace App\Models;

use App\Models\Authors;
use App\Models\Category;
use App\Models\Source;
use Database\Factories\ArticleFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'source_id',
        'external_link',
        'published_date',
        'data',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    protected static function newFactory()
    {
        return ArticleFactory::new();
    }

    public function syncCategories($categories)
    {
        $categoryIds = [];

        foreach ($categories as $category) {
            $categoryName =  Category::normalize($category);
            $categoryRec = Category::firstOrCreate(['name' => $categoryName]);
            array_push($categoryIds, $categoryRec->id);
        }

        $this->categories()->sync($categoryIds);
    }

    public function syncAuthors($authors): void
    {
        $authorIds = [];

        foreach ($authors as $author) {
            $authorName = Author::normalize($author);
            $authorRec = Author::firstOrCreate(['name' => $authorName]);
            array_push($authorIds, $authorRec->id);
        }

        $this->authors()->sync($authorIds);
    }

    public function source(): BelongsTo
    {
        return $this->belongsTo(Source);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'articles_categories');
    }

    public function authors(): BelongsToMany
    {
        return $this->belongsToMany(Author::class, 'articles_authors');
    }
}

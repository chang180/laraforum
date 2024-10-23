<?php

namespace App\Models;

use App\Models\Concerns\ConvertMarkdownToHtml;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Str;
use Laravel\Scout\Searchable;

class Post extends Model
{
    use ConvertMarkdownToHtml;
    use HasFactory;
    use Searchable;

    protected $fillable = ['title', 'body', 'user_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function title(): Attribute
    {
        return Attribute::set(fn ($value) => Str::title($value));
    }

    public function showRoute(array $parameters = []): string
    {
        return route('posts.show', [$this, Str::slug($this->title), ...$parameters]);
    }

    public function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    /**
     * 縮小 Scout 送出的資料量
     */
    public function toSearchableArray(): array
    {
        // 只選擇需要被索引的欄位，避免過大資料量
        $array = $this->only(['id', 'title', 'user_id', 'topic_id']);

        // 添加額外的字段
        $array['excerpt'] = Str::limit($this->body, 100);  // 只存儲 body 的前 100 個字作為摘要

        return $array;
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Article extends Model
{
    protected $fillable = [
        'title', 'slug', 'body', 'subject_id',
        'tags', 'is_published', 'created_by',
        'image_url', 'youtube_url', 'canva_url',
        'ebook_url', 'file_url', 'file_name', 'notebooklm_items',
    ];

    protected $casts = [
        'is_published'     => 'boolean',
        'notebooklm_items' => 'array',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function getTagsArrayAttribute(): array
    {
        if (!$this->tags) return [];
        return array_filter(array_map('trim', explode(',', $this->tags)));
    }

    public static function generateSlug(string $title): string
    {
        $slug = Str::slug($title);
        if (empty($slug)) {
            $slug = 'article-' . time();
        }
        $base  = $slug;
        $i     = 1;
        while (self::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }
        return $slug;
    }
}

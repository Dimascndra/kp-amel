<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'title',
        'tag',
        'tag_color',
        'image',
        'excerpt',
        'content',
        'author',
        'read_time',
        'published_at',
        'is_published',
        'views_count',
    ];

    protected $appends = [
        'date',
        'readTime',
        'tagColor',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'date',
            'is_published' => 'boolean',
            'views_count' => 'integer',
        ];
    }

    public function getDateAttribute(): string
    {
        return $this->published_at ? $this->published_at->format('d F Y') : ($this->created_at ? $this->created_at->format('d F Y') : '');
    }

    public function getReadTimeAttribute(): string
    {
        return $this->attributes['read_time'] ?? '3 menit baca';
    }

    public function getTagColorAttribute(): string
    {
        return $this->attributes['tag_color'] ?? 'blue';
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true)->orderByDesc('published_at')->orderByDesc('id');
    }
}

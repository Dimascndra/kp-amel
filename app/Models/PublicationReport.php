<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PublicationReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'category',
        'category_color',
        'description',
        'file_path',
        'file_size',
        'period',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order')->orderByDesc('id');
    }
}

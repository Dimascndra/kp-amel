<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'address',
        'phone',
        'whatsapp',
        'hours',
        'is_main',
        'is_active',
        'sort_order',
    ];

    protected $appends = [
        'isMain',
    ];

    protected function casts(): array
    {
        return [
            'is_main' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function getIsMainAttribute(): bool
    {
        return (bool) ($this->attributes['is_main'] ?? false);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderByDesc('is_main')->orderBy('sort_order')->orderBy('id');
    }
}

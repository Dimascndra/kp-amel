<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'code',
        'name',
        'badge',
        'category',
        'image',
        'hero_image',
        'slogan',
        'short_desc',
        'ketentuan',
        'syarat',
        'cta_text',
        'cta_type',
        'is_active',
        'sort_order',
    ];

    protected $appends = [
        'shortDesc',
        'heroImage',
        'ctaText',
        'ctaType',
    ];

    protected function casts(): array
    {
        return [
            'ketentuan' => 'array',
            'syarat' => 'array',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function getShortDescAttribute(): ?string
    {
        return $this->attributes['short_desc'] ?? null;
    }

    public function getHeroImageAttribute(): ?string
    {
        return $this->attributes['hero_image'] ?? null;
    }

    public function getCtaTextAttribute(): ?string
    {
        return $this->attributes['cta_text'] ?? null;
    }

    public function getCtaTypeAttribute(): string
    {
        return $this->attributes['cta_type'] ?? 'whatsapp';
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order')->orderBy('id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Project extends Model
{
    protected $fillable = [
        'title',
        'title_km',
        'slug',
        'tagline',
        'tagline_km',
        'short_description',
        'short_description_km',
        'synopsis',
        'synopsis_km',
        'genre',
        'duration',
        'release_date',
        'country',
        'language',
        'status',
        'poster_image',
        'banner_image',
        'trailer_url',
        'youtube_id',
        'rating',
        'votes',
        'year',
        'director',
        'is_featured',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
    ];

    // Auto generate slug from title
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($project) {
            if (empty($project->slug)) {
                $project->slug = Str::slug($project->title) . '-' . uniqid();
            }
        });
    }

    // Relationships
    public function castMembers(): HasMany
    {
        return $this->hasMany(CastMember::class);
    }

    public function awards(): HasMany
    {
        return $this->hasMany(Award::class);
    }

    public function producers(): HasMany
    {
        return $this->hasMany(Producer::class);
    }

    // Append full image URLs
    public function getPosterImageUrlAttribute(): ?string
    {
        if (!$this->poster_image) return null;
        if (str_starts_with($this->poster_image, 'http')) return $this->poster_image;
        return asset('storage/' . $this->poster_image);
    }

    public function getBannerImageUrlAttribute(): ?string
    {
        if (!$this->banner_image) return null;
        if (str_starts_with($this->banner_image, 'http')) return $this->banner_image;
        return asset('storage/' . $this->banner_image);
    }
}
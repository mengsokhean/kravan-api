<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CastMember extends Model
{
    protected $fillable = [
        'project_id',
        'name',
        'role_name',
        'photo',
    ];

    // ✅ បន្ថែម appends
    protected $appends = ['photo_url'];

    public function getPhotoUrlAttribute(): ?string
    {
        if (!$this->photo) return null;
        if (str_starts_with($this->photo, 'http')) return $this->photo;
        return asset('storage/' . $this->photo);
    }
}
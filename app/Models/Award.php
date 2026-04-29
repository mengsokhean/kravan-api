<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Award extends Model
{
    protected $fillable = [
        'project_id',
        'festival_name',
        'award_title',
        'year',
    ];
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerStory extends Model
{
    protected $fillable = [
        'project_title',
        'category',
        'client_name',
        'short_description',
        'challenge',
        'solution',
        'key_results',
        'image_path',
        'status',
        'admin_order',
        'public_order',
        'created_by',
        'updated_by',
    ];
}

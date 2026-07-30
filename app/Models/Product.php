<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'details',
        'images',   // multiple images JSON
        'size',
        'color',
        'category',
        'price',
        'status',
        'tag_ids',
    ];

    protected $casts = [
        'images' => 'array',
        'tag_ids' => 'array',
    ];
}

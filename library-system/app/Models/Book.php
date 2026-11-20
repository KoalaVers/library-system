<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Book extends Model
{
    protected $fillable = [
        'title',
        'author',
        'year',
        'stock',
        'category_id',
        'cover',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function borrowingDetails()
    {
        return $this->hasMany('App\\Models\\BorrowingDetail');
    }
}
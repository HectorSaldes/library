<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookReview extends Model
{
    use HasFactory;

    protected $table = "book_reviews";
    protected $fillable = [
        "id",
        "comment",
        "edited",
        "books_id",
        "user_id",
    ];

    protected $casts = [
        'edited' => 'boolean',
    ];

    public $timestamps = false;
}

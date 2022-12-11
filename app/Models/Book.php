<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $table = "books";
    protected $fillable = [
        "id",
        "isbn",
        "title",
        "description",
        "publish_date",
        "category_id",
        "editorial_id",
    ];

    public $timestamps = false;


    public function editorial(){
        return $this->belongsTo(Editorial::class, 'editorial_id');
    }

    public function category(){
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function authors(){
        return $this->belongsToMany(
            Author::class, // Table relationship
            'authors_books', // Table intersection
            'books_id', // from
            'authors_id' // to
        );
    }


}

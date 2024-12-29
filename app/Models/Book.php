<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Termwind\Components\Raw;

class Book extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'author_id',
        'cover_image',
        'array_image',
        'price',
        'quantity_sell',
        'quantity_reservation',
        'pdf',
        'sound_book'
    ];

    public function author() {
        return $this->belongsTo(Author::class);
    }

    public function categories() {
        return $this->belongsToMany(Category::class);
    }
    public function Comments() {
        return $this->hasMany(Comment::class);
    }
    public function favoriteUsers()
    {
        return $this->belongsToMany(User::class, 'favorites');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Book;

class Rate extends Model
{
    use HasFactory;
    protected $fillable = ['book_id','user_id','rate'];

    public function book() {
        return $this->belongsTo(Book::class);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Favorite;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api')->except(['index','show']);
    }

    public function favorite(Request $request, $id) {
        $bookId = $id;
        $book = Book::find($id);
        if (!($book))
        {
            return response()->json([
                "status" => "error",
                "message" => "there is no book with this id"
            ],422);
        }
        $userId = $request->user()->id;
        
        $favorite = Favorite::where('book_id', $bookId)
                    ->where('user_id', $userId)
                    ->first();
        if ($favorite) {
            $favorite->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'book deleted from favorite successfully'
            ], 200);
        }
        else  {
            Favorite::create([
                'user_id' => $userId,
                'book_id' => $bookId,
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'book added to favorite successfully'
            ], 201);
        }
    }
    public function isfavorite(Request $request, $id) {
        $bookId = $id;
        $userId = $request->user()->id;

        $favorite = Favorite::where('book_id', $bookId)
                    ->where('user_id', $userId)
                    ->first();
        if ($favorite) {
            return response()->json([
                'favorite' => true
            ], 200);
        }
        else  {
            return response()->json([
                'favorite' => true
            ], 200);
        }
    }
}

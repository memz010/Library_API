<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Rate;
use Illuminate\Http\Request;

class RateController extends Controller
{

    public function __construct() {
        $this->middleware('auth:api')->except(['index','show']);
    }

    public function rate(Request $request, $id) {
        $validator = validator($request->all(), [
            'rate' => 'required|integer|min:1|max:10',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }
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

        $rate = Rate::where('book_id', $bookId)
                    ->where('user_id', $userId)
                    ->first();
        if ($rate) {
            $rate->book_id = $bookId;
            $rate->user_id = $userId;
            $rate->rate = $request->rate;
            $rate->save();
            return response()->json([
                'status' => 'success',
                'message' => 'rate updated successfully'
            ], 200);
        }
        else  {
            Rate::create([
                'user_id' => $userId,
                'book_id' => $bookId,
                'rate' => $request->rate,
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'rate created successfully'
            ], 201);
        }
    }
}

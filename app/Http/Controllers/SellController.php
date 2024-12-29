<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Sell;
use Illuminate\Http\Request;

class SellController extends Controller
{

    public function __construct() {
        $this->middleware('auth:api')->except(['index','show']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request,$id)
    {
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
        if ($book->quantity_sell == 0) {
            return response()->json([
                "meesage" => "the book is unavailable for sell now"
            ]);
        }
        if ($request->user()->balance < $book->price) {
            return response()->json([
                "message" => "you don't have enough point"
            ]);
        }

        $book->quantity_sell--;
        $book->save();
        $request->user()->balance -= $book->price;
        $request->user()->save();

        Sell::create([
            'book_id' => $bookId,
            'user_id' => $userId,
            'price' => $book->price,
            'quantity' => 1

        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'sell done successfully'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Sell $sell)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sell $sell)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sell $sell)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sell $sell)
    {
        //
    }
}

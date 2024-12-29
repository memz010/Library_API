<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct() {
        $this->middleware('auth:api')->except(['index','show']);
    }


    public function store(Request $request, $id) {
        $validator = validator($request->all(), [
            'description' => 'required|string',
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

        Comment::create([
            'user_id' => $userId,
            'book_id' => $bookId,
            'description' => $request->description,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'comment created successfully'
        ], 201);
    }


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
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $comment = Comment::find($id);
        if ($comment) 
        {
            $validator = validator($request->all(), [
                'description' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'errors' => $validator->errors(),
                ], 422);
            }
            if ($comment->user_id != $request->user()->id && $request->user()->id !=101)
                return response()->json(['error' => 'Unauthorized'], 401);
            $comment->description = $request->description;
            $comment->save();

            return response()->json([
                'status' => 'success',
                'message' => 'comment updated successfully'
            ], 201);
        }
        else {
            return response()->json([
                'status' => 'error',
                'message' => 'there is no comment with this id',
            ], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request,$id)
    {
        $comment = Comment::find($id);
        if ($comment) 
        {
            if ($comment->user_id != $request->user()->id && $request->user()->id !=101)
                return response()->json(['error' => 'Unauthorized'], 401);
            $comment->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'comment deleted successfully'
            ], 201);
        }
        else {
            return response()->json([
                'status' => 'error',
                'message' => 'there is no comment with this id',
            ], 422);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function __construct() {
        $this->middleware('auth:api')->except(['index','show','search']);
        $this->middleware('admin')->except('index','show','search');
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        $authors = Author::where('name', 'like', "%$query%")
                ->get();
        foreach ($authors as $author)
            $author->books;
        return response()->json([
            "status" => "success",
            "authors" => $authors
        ]);
    }
    
    public function index()
    {
        $authors = Author::all();
        return response()->json([
            "stuats" => "success",
            "authors" => $authors
        ]);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($Request)
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = validator($request->all(), [
            'name' => 'required|string',
            'description' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        Author::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'author created successfully'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $author = Author::with('books')->find($id);
        if ($author)
            return response()->json([
                "status" => "success",
                "author" => $author
                ]);
        else return response()->json([
            "status" => "error",
            "message" => "there is no author with this id"
        ],422);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Author $author)
    {
        //
    }    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $author = Author::find($id);
        if ($author){
            if ($request->has('name')) {
                $request->validate([
                    'name' => 'required|string',
                ]);
    
                $author->name = $request->name;
                
            }
    
            if ($request->has('description')) {
                $request->validate([
                    'description' => 'required|string',
                ]);
    
                $author->description = $request->description;
            }
            $author->save();

            return response()->json([
                'status' => 'success',
                'message' => 'author updated successfully'
            ], 201);
        }
        else return response()->json([
            "status" => "error",
            "message" => "there is no author with this id"
        ],422);
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $author = Author::find($id);
        if ($author)
        {
            $author->delete();
            return response()->json([
                "status" => "success",
                "message" => "Author deleted successfully"
                ],200);
        }
        else return response()->json([
            "status" => "error",
            "message" => "there is no author with this id"
        ],422);

    }
}

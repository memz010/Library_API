<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function __construct() {
        $this->middleware('auth:api')->except(['index','show']);
        $this->middleware('admin')->except(['index','show']);
    }


    public function index()
    {
        $categories = Category::all();
        return response()->json([
            "stuats" => "success",
            "categories" => $categories,
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
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        Category::create([
            'name' => $request->name,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'category created successfully'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $category = Category::with('books')->find($id);
        if ($category)
            return response()->json([
                "status" => "success",
                "category" => $category
                ]);
        else return response()->json([
            "status" => "error",
            "message" => "there is no category with this id"
        ],422);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
    }    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $category = Category::find($id);
        if ($category){
            if ($request->has('name')) {
                $request->validate([
                    'name' => 'required|string',
                ]);
    
                $category->name = $request->name;
            }

            $category->save();

            return response()->json([
                'status' => 'success',
                'message' => 'category updated successfully'
            ], 201);
        }
        else return response()->json([
            "status" => "error",
            "message" => "there is no category with this id"
        ],422);
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $category = Category::find($id);
        if ($category)
        {
            $category->delete();
            return response()->json([
                "status" => "success",
                "message" => "category deleted successfully"
                ],200);
        }
        else return response()->json([
            "status" => "error",
            "message" => "there is no category with this id"
        ],422);

    }
}

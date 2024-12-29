<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function __construct() {
        $this->middleware('auth:api')->except(['index','show','bestSells','bestRate','search','free','new']);
        $this->middleware('admin')->except(['index','show','favorite','bestSells','bestRate','search','free','new']);
    }
    
    public function search(Request $request)
    {
        $query = $request->input('query');

        $books = Book::where('name', 'like', "%$query%")
                    ->get();

        return response()->json([
            "status" => "success",
            "books" => $books
        ]);
    }

    public function getAverageRate($id) {
        $averageRate = DB::table('rates')
                ->where('book_id', $id)
                ->avg('rate');
        return $averageRate;
    }
    public function getSells($id) {
        $sells = DB::table('sells')
            ->where('book_id', $id)
            ->sum('quantity');
        return $sells;
    }
    public function index()
    {
        $books = Book::all();
        $books = $books->map(function ($book) {
            $book->rate = $this->getAverageRate($book->id);
            return $book;
        });
        return response()->json([
            "status" => "success",
            "books" => $books
        ]);
    }

    public function new()
    {
        $books = Book::orderBy('created_at', 'desc')->get();

        $books = $books->map(function ($book) {
            $book->rate = $this->getAverageRate($book->id);
            return $book;
        });
        return response()->json([
            "status" => "success",
            "books" => $books
        ]);
    }

    public function free()
    {
        $books = Book::where('price', 0)->get();;
        $books = $books->map(function ($book) {
            $book->rate = $this->getAverageRate($book->id);
            return $book;
        });
        return response()->json([
            "status" => "success",
            "books" => $books
        ]);
    }
    public function bestRate()
    {
        $books = Book::all();
        $books = $books->map(function ($book) {
            $book->rate = $this->getAverageRate($book->id);
            return $book;
        });
        $books = $books->sortByDesc('rate');
        return response()->json([
            "status" => "success",
            "books" => $books
        ]);
    }
    public function bestSells()
    {
        $books = Book::all();
        $books = $books->map(function ($book) {
            $book->rate = $this->getAverageRate($book->id);
            $book->sells = $this->getSells($book->id);
            return $book;
        });
        $books = $books->sortByDesc('sells');
        $books = $books->makeHidden('sells');
        return response()->json([
            "status" => "success",
            "books" => $books
        ]);
    }
    public function mostsells()
    {
        $books = Book::all();
        $books = $books->map(function ($book) {
            $book->rate = $this->getAverageRate($book->id);
            return $book;
        });
        return response()->json([
            "status" => "success",
            "books" => $books
        ]);
    }
    public function favorite(Request $request)
    {
        $id = $request->user()->id;
        $user = User::find($id);
        $books = $user->favoriteBooks;
        return response()->json([
            "status" => "success",
            "books" => $books
        ],200);
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
    public function store(Request $request)
    {
        $validator = validator($request->all(), [
            'name' => 'required|string',
            'author_id' => 'required|exists:authors,id',
            'description' => 'required|string',
            'cover_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'array_image.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'price' => 'required|integer',
            'quantity_sell' => 'required|integer',
            'quantity_reservation' => 'required|integer',
            'sound_book' => 'file|mimes:mp3,wav|max:2048',
            'pdf' => 'file|mimes:pdf|max:2048',



        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }
        $imagepath = $request->file('cover_image')->store('images');


        $imagepaths = [];
        if ($request->hasFile('array_image')) {
            foreach ($request->file('array_image') as $image) {
                $imagePath = $image->store('images'); 
                $imagepaths[] = $imagePath;
            }
        }
        $imagepathsjson = json_encode($imagepaths);
        $pdfpath = null;
        if ($request->hasFile('pdf')) {
            $pdfpath = $request->file('pdf')->store('pdfs');
        }
        $soundbookpath = null;
        if ($request->hasFile('sound_book')) {
            $soundbookpath = $request->file('sound_book')->store('audios');
        }


        Book::create([
            'name' => $request->name,
            'author_id' => $request->author_id,
            'description' => $request->description,
            'cover_image' => $imagepath,
            'array_image' => $imagepathsjson,
            'price' => $request->price,
            'quantity_sell' => $request->quantity_sell,
            'quantity_reservation' => $request->quantity_reservation,
            'pdf' => $pdfpath,
            'sound_book' => $soundbookpath,
        ]);

        

        return response()->json([
            'status' => 'success',
            'message' => 'book stored successfully'
        ], 201);
    }

    /**
     * Display the specified resource.
    */
    public function show($id)
    {
        $book = Book::with('comments.user')->find($id);
        if ($book)
        {
            $book->rate = $this->getAverageRate($book->id);
            return response()->json([
                "status" => "success",
                "book" => $book,
                ]);
        }
        else return response()->json([
            "status" => "error",
            "message" => "there is no book with this id"
        ],422);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $book = Book::find($id);
        if ($book){
            if ($request->has('name')) {
                $request->validate([
                    'name' => 'required|string',
                ]);
                $book->name = $request->name;
            }
            
            if ($request->has('author_id')) {
                $request->validate([
                    'author_id' => 'exists:authors,id'
                ]);

                $book->author_id = $request->author_id;
            }
            if ($request->has('description')) {
                $request->validate([
                    'description' => 'required|string',
                ]);
    
                $book->description = $request->description;
            }        
            if ($request->has('cover_image')) {
                $request->validate([
                    'cover_image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
                ]);
                $imagepath = $request->file('cover_image')->store('images');
                $book->cover_image = $imagepath;
            }
            if ($request->has('array_image')) {
                $request->validate([
                    'array_image.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
                ]);
                $imagepaths = [];
                foreach ($request->file('array_image') as $image) {
                    $imagePath = $image->store('images'); 
                    $imagepaths[] = $imagePath;
                }
                $imagepathsjson = json_encode($imagepaths);
                $book->array_image = $imagepathsjson;
            }
            if ($request->has('price')) {
                $request->validate([
                    'price' => 'required|integer',
                ]);
                $book->price = $request->price;
            }
            if ($request->has('quantity_sell')) {
                $request->validate([
                    'quantity_sell' => 'required|integer',
                ]);
                $book->quantity_sell = $request->quantity_sell;
            }
            if ($request->has('quantity_reservation')) {
                $request->validate([
                    'quantity_reservation' => 'required|integer',
                ]);
                $book->quantity_reservation = $request->quantity_reservation;
            }
            if ($request->has('pdf')) {
                $request->validate([
                    'pdf' => 'file|mimes:pdf|max:2048',
                ]);
                $pdfpath = $request->file('pdf')->store('pdfs');
                $book->pdf = $pdfpath;
            }
            if ($request->has('sound_book')) {
                $request->validate([
                    'sound_book' => 'file|mimes:mp3,wav|max:2048',
                ]);
                $soundbookpath = $request->file('sound_book')->store('audios');
                $book->sound_book = $soundbookpath;
            }

            $book->save();

            return response()->json([
                'status' => 'success',
                'message' => 'book updated successfully'
            ], 201);
        }
        else return response()->json([
            "status" => "error",
            "message" => "there is no book with this id"
        ],422);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $book = Book::find($id);
        if ($book)
        {
            $book->delete();
            return response()->json([
                "status" => "success",
                "message" => "book deleted successfully"
                ],200);
        }
        else return response()->json([
            "status" => "error",
            "message" => "there is no book with this id"
        ],422);
    }
}

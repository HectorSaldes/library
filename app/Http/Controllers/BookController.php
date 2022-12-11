<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookReview;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{

    public function index()
    {
        $books = Book::with('authors', 'editorial', 'category')->get();
        return [
            "error" => false,
            "message" => "Succesfull query",
            "data" => $books
        ];
    }

    public function updateBookReview(Request $request, $id)
    {
        $validator = Validator::make($request->all(), ['comment' => 'required',]);

        if (!$validator->fails()) {
            DB::beginTransaction();
            try {
                $bookReview = BookReview::find($id);
                $user = auth()->user();
                if ($user->id == $bookReview->user_id) {
                    $bookReview->comment = $request->comment;
                    $bookReview->edited = true;
                    $bookReview->save();
                    DB::commit();
                    return $this->getResponse201("Book Review", "updated", $bookReview);
                } else {
                   return $this->getResponse403();
                }
            } catch (Exception $e) {
                DB::rollBack();
                return $this->getResponse500([$e->getMessage()]);
            }
        } else {
            return $this->getResponse500([$validator->errors()]);
        }

    }

    public function addBookReview(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'comment' => 'required',
            'book_id' => 'required|numeric',
        ]);

        if (!$validator->fails()) {
            DB::beginTransaction();
            try {
                $user = auth()->user();
                $bookReview = new BookReview([
                    'comment' => $request->comment,
                    'books_id' => $request->book_id,
                    'user_id' => $user->id,
                ]);
                $bookReview->save();
                DB::commit();
                return $this->getResponse201("Book Review", "added", $bookReview);
            } catch (Exception $e) {
                DB::rollBack();
                return $this->getResponse500([$e->getMessage()]);
            }
        } else {
            return $this->getResponse500([$validator->errors()]);
        }
    }

    public function store(Request $request)
    {
        $existBook = Book::query()->where("isbn", trim($request->isbn))->exists();
        if (!$existBook) {
            $book = new Book();
            $book->isbn = trim($request->isbn);
            $book->title = $request->title;
            $book->description = $request->description;
            $book->publish_date = Carbon::now();
            $book->category_id = $request->category_id;
            $book->editorial_id = $request->editorial_id;
            $book->save();
            foreach ($request->authors as $item) {
                $book->authors()->attach($item);
            }
            return [
                "status" => true,
                "message" => "Your book has been created",
                "data" => [
                    "book_id" => $book->id,
                    "book" => $book
                ]
            ];
        } else {
            return [
                "status" => false,
                "message" => "ISBN already exist",
                "data" => []
            ];
        }
    }
}

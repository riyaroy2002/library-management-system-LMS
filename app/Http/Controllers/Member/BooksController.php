<?php

namespace App\Http\Controllers\Member;

use App\FileUpload;
use App\Http\Controllers\Controller;
use App\Models\{Category, Author, Book};
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BooksController extends Controller
{
    use FileUpload;

    public function index()
    {
        $books = Book::with(['authors', 'categories'])->latest()->paginate(10);
        return view('members.books.index', compact('books'));
    }
}

<?php

namespace App\Http\Controllers\Librarian;

use Illuminate\Http\Request;
use App\Models\{
    Book,
    BookIssue
};
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        return view('librarians.index', [
            'bookCount'         => Book::count(),
            'requestedBookCount'=> BookIssue::where('status', 'requested')->count(),
            'issuedBookCount'   => BookIssue::where('status', 'issued')->count(),
            'returnedBookCount' => BookIssue::where('status', 'returned')->count(),
            'lostBookCount'     => BookIssue::where('status', 'lost')->count()
        ]);
    }
}

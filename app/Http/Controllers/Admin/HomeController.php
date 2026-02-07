<?php

namespace App\Http\Controllers\Admin;

use App\Models\{
    Category,
    Author,
    Member,
    Book,
    BookIssue,
    Librarian
};

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        return view('admin.index', [
            'categoryCount'     => Category::count(),
            'authorCount'       => Author::count(),
            'memberCount'       => Member::count(),
            'bookCount'         => Book::count(),
            'librarianCount'    => Librarian::count(),
            'requestedBookCount'=> BookIssue::where('status', 'requested')->count(),
            'issuedBookCount'   => BookIssue::where('status', 'issued')->count(),
            'returnedBookCount' => BookIssue::where('status', 'returned')->count(),
            'lostBookCount'     => BookIssue::where('status', 'lost')->count()
        ]);
    }
}

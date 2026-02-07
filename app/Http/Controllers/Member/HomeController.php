<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\{
    Book,
    BookIssue
};

class HomeController extends Controller
{
    public function index()
    {
        return view('members.index', [
            'bookCount'         => Book::count(),
            'requestedBookCount' => BookIssue::where('status', 'requested')->count(),
            'issuedBookCount'   => BookIssue::where('status', 'issued')->count(),
            'returnedBookCount' => BookIssue::where('status', 'returned')->count(),
            'lostBookCount'     => BookIssue::where('status', 'lost')->count(),
        ]);
    }
}

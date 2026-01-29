<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{BookIssue};

class BooksIssuesController extends Controller
{
    public function index()
    {
        $bookIssues = BookIssue::with(['book.categories', 'book.authors', 'member.user', 'librarian.user'])->latest()->paginate(10);
        return view('admin.bookIssues.index',compact('bookIssues'));
    }
}

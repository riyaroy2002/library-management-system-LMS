<?php

namespace App\Http\Controllers\Member;

use App\FileUpload;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\{Category, Author, Book, BookIssue};

class RequestBooksController extends Controller
{
    use FileUpload;

    public function index()
    {
        $member   = Auth::user()->members()->first();
        $memberId = $member->id;

        $bookIssues = BookIssue::with(['book.categories', 'book.authors', 'member.user', 'librarian.user'])
            ->where('member_id', $memberId)
            ->latest()
            ->paginate(10);
        return view('members.requestBooks.index', compact('bookIssues'));
    }

    public function books()
    {
        $books = Book::with(['authors', 'categories'])->latest()->paginate(10);
        return view('members.requestBooks.request-book', compact('books'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'book_id' => 'required|exists:books,id'
        ]);

        $member   = Auth::user()->members()->first();
        $memberId = $member->id;

        $exists = BookIssue::where('book_id', $request->book_id)
            ->where('member_id', $memberId)
            ->whereIn('status', ['requested', 'issued'])
            ->exists();

        if ($exists) {
            return back()->with('error', 'This book is already requested or issued!');
        }

        try {
            BookIssue::create([
                'book_id'      => $request->book_id,
                'member_id'    => $memberId,
                'librarian_id' => null,
                'status'       => 'requested'
            ]);

            return redirect()->route('request-books.index')
                ->with('success', 'Book requested successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong.' . $e->getMessage());
        }
    }
}

<?php

namespace App\Http\Controllers\Librarian;

use App\FileUpload;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\{Category, Author, Book, BookIssue};

class IssueBooksController extends Controller
{
    use FileUpload;

    public function index()
    {
        $librarian   = Auth::user()->librarians()->first();
        $librarianId = $librarian?->id;

        $bookIssues  = BookIssue::with([
            'book.categories',
            'book.authors',
            'member.user',
            'librarian.user'
        ])
            ->where(function ($query) use ($librarianId) {
                if ($librarianId) {
                    $query->where('librarian_id', $librarianId);
                }
                $query->orWhere(function ($q) {
                    $q->whereNull('librarian_id')
                        ->where('status', 'requested');
                });
            })
            ->latest()
            ->paginate(10);

        return view('librarians.issueBooks.index', compact('bookIssues'));
    }


    public function issue(Request $request, $id)
    {
        $request->validate([
            'issue_date' => 'required|date',
            'due_date'   => 'required|date|after_or_equal:issue_date'
        ]);

        $bookIssue = BookIssue::findOrFail($id);

        if ($bookIssue->status === 'issued') {
            return back()->with('error', 'This book has already been issued.');
        }

        DB::beginTransaction();

        try {

            $librarian   = Auth::user()->librarians()->first();
            $librarianId = $librarian->id;

            $bookIssue->update([
                'issue_date'   => $request->issue_date,
                'due_date'     => $request->due_date,
                'librarian_id' => $librarianId ?? null,
                'status'       => 'issued'
            ]);

            $book = $bookIssue->book;
            if ($book->total_copies > 0) {
                $book->decrement('total_copies', 1);
            } else {
                DB::rollBack();
                return back()->with('error', 'No copies available to issue.');
            }

            DB::commit();
            return redirect()->route('librarian.issue-books.index')->with('success', 'Book issued successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong.')->withInput();
        }
    }

    public function return(Request $request, $id)
    {
        $request->validate([
            'return_date' => 'required|date|after_or_equal:issue_date',
        ]);

        $bookIssue = BookIssue::findOrFail($id);

        if ($bookIssue->status !== 'issued') {
            return back()->with('error', 'This book is not currently returned.');
        }

        DB::beginTransaction();

        try {

            $librarian   = Auth::user()->librarians()->first();
            $librarianId = $librarian?->id;

            $bookIssue->update([
                'return_date'  => $request->return_date,
                'librarian_id' => $librarianId,
                'status'       => 'returned'
            ]);

            $book = $bookIssue->book;
            $book->increment('total_copies', 1);

            DB::commit();
            return redirect()->route('librarian.issue-books.index')->with('success', 'Book returned successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }

    public function books()
    {
        $books = Book::with(['authors', 'categories'])->latest()->paginate(10);
        return view('librarians.books.index', compact('books'));
    }
}

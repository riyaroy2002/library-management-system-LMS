<?php

namespace App\Http\Controllers\Admin;

use App\FileUpload;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\{Category, Author, Book};
use Illuminate\Support\Facades\Validator;

class BooksController extends Controller
{
    use FileUpload;

    public function index()
    {
        $books = Book::with(['authors', 'categories'])->latest()->paginate(10);
        return view('admin.books.index', compact('books'));
    }

    public function trash()
    {
        $books = Book::with(['authors', 'categories'])->onlyTrashed()->paginate(10);
        return view('admin.books.trashed', compact('books'));
    }

    public function create()
    {
        $authors    = Author::get();
        $categories = Category::get();
        return view('admin.books.create', compact('authors', 'categories'));
    }

    public function store(Request $request)
    {

        $validated = Validator::make($request->all(), [
            'name'          => 'required|string',
            'title'         => 'required|string',
            'authors'       => 'required|array|min:1',
            'authors.*'     => 'exists:authors,id',
            'categories'    => 'required|array|min:1',
            'categories.*'  => 'exists:categories,id',
            'slug'          => 'required|string|unique:books,slug',
            'ISBN'          => 'nullable|string|max:50|unique:books,ISBN',
            'publish_year'  => 'nullable|digits:4',
            'edition'       => 'nullable|string|max:50',
            'languages'     => 'required|array|min:1',
            'total_copies'  => 'required|integer|min:1',
            'cover_image'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
        ]);

        if ($validated->fails()) {
            return redirect()->back()->withErrors($validated)->withInput();
        }

        DB::beginTransaction();

        try {

            $lastBook = Book::orderBy('id', 'DESC')->first();
            $lastId = $lastBook ? intval(explode('-', $lastBook->book_code)[1]) : 0;
            $bookCode = 'BOOK-' . str_pad($lastId + 1, 5, '0', STR_PAD_LEFT);

            $data = $validated->validated();
            $data['book_code'] = $bookCode;
            $data['languages'] = json_encode($data['languages']);

            if ($request->hasFile('cover_image')) {
                $data['cover_image'] = $this->customSaveImage($request->file('cover_image'), 'books/books_image');
            }

            $authors           = $data['authors'];
            $categories        = $data['categories'];
            unset($data['authors'], $data['categories']);

            $book = Book::create($data);
            $book->authors()->attach($authors);
            $book->categories()->attach($categories);

            DB::commit();
            return redirect()->route('admin.books.index')->with('success', 'Book created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong.')->withInput();
        }
    }

    public function edit($id)
    {
        $authors    = Author::get();
        $categories = Category::get();
        $book       = Book::with(['authors', 'categories'])->findOrFail($id);
        return view('admin.books.edit', compact('book', 'authors', 'categories'));
    }


    public function update(Request $request, $id)
    {
        $book      = Book::findOrFail($id);

        $validator = Validator::make($request->all(), [

            'name'          => 'required|string',
            'title'         => 'required|string',
            'authors'       => 'required|array|min:1',
            'authors.*'     => 'exists:authors,id',
            'categories'    => 'required|array|min:1',
            'categories.*'  => 'exists:categories,id',
            'slug'          => ['required', 'string', Rule::unique('books', 'slug')->ignore($book->id)],
            'ISBN'          => ['nullable', 'string', 'max:50', Rule::unique('books', 'ISBN')->ignore($book->id)],
            'publish_year'  => 'nullable|digits:4',
            'edition'       => 'nullable|string|max:50',
            'languages'     => 'required|array|min:1',
            'total_copies'  => 'required|integer|min:1',
            'cover_image'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {

            $data = $validator->validated();
            $data['languages'] = json_encode($data['languages']);
            $authors           = $data['authors'];
            $categories        = $data['categories'];
            unset($data['authors'], $data['categories']);

            if ($request->hasFile('cover_image')) {
                if ($book->cover_image && file_exists($book->cover_image)) {
                    @unlink($book->cover_image);
                }
                $data['cover_image'] = $this->customSaveImage($request->file('cover_image'), 'books/books_image');
            }

            $book->update($data);
            $book->authors()->sync($authors);
            $book->categories()->sync($categories);

            DB::commit();
            return redirect()->route('admin.books.index')->with('success', 'Book updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong. Please try again later.')->withInput();
        }
    }

    public function destroy($id)
    {
        $book = Book::findOrFail($id);
        DB::beginTransaction();
        try {

            $book->delete();
            DB::commit();
            return redirect()->route('admin.books.index')->with('success', 'Book deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.books.index')->with('error', 'Something went wrong. Please try again later.');
        }
    }

    public function restore($id)
    {
        DB::beginTransaction();
        $book = Book::onlyTrashed()->findOrFail($id);

        try {
            $book->restore();
            DB::commit();
            return redirect()->route('admin.books.index')->with('success', 'Book restored successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.books.index')->with('error', 'Something went wrong. Please try again later.');
        }
    }

    public function forceDelete($id)
    {
        DB::beginTransaction();

        try {

            $book = Book::onlyTrashed()->findOrFail($id);
            if ($book->cover_image && file_exists($book->cover_image)) {
                @unlink($book->cover_image);
            }
            $book->authors()->detach();
            $book->categories()->detach();
            $book->forceDelete();
            DB::commit();
            return redirect()->route('admin.books.index')->with('success', 'Book deleted permanently.');
        } catch (\Exception $e) {

            DB::rollBack();
            return redirect()->route('admin.books.index')->with('error', 'Something went wrong. Please try again later.');
        }
    }
}

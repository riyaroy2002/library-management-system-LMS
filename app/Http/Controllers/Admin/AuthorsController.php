<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AuthorsController extends Controller
{
    public function index()
    {
        $authors = Author::latest()->paginate(10);
        return view('admin.authors.index', compact('authors'));
    }

    public function trash()
    {
        $authors = Author::onlyTrashed()->paginate(10);
        return view('admin.authors.trashed', compact('authors'));
    }

    public function create()
    {
        return view('admin.authors.create');
    }

    public function store(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'first_name'      => 'required|string|max:100',
            'last_name'       => 'required|string|max:100',
            'contact_no'      => ['required', 'digits:10', 'regex:/^[0-9]{10}$/', Rule::unique('authors', 'contact_no')],
            'alt_contact_no'  => ['nullable', 'digits:10', 'regex:/^[0-9]{10}$/', Rule::unique('authors', 'alt_contact_no')],
            'email'           => ['required', 'email', 'max:100', Rule::unique('authors','email')],
            'gender'          => 'required|in:male,female,other',
            'slug'            => 'required|string|unique:authors,slug',
            'bio'             => 'nullable|string'
        ]);

        if ($validated->fails()){
            return redirect()->back()->withErrors($validated)->withInput();
        }

        DB::beginTransaction();
        try {

            $validated = $validated->validated();
            Author::create($validated);
            DB::commit();
            return redirect()->route('admin.authors.index')->with('success', 'Author created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.authors.index')->with('error', 'Something went wrong. Please try again later.')->withInput();
        }
    }

    public function edit($id)
    {
        $author = Author::findOrFail($id);
        return view('admin.authors.edit', compact('author'));
    }

    public function update(Request $request, $id)
    {
        $validated = Validator::make($request->all(), [
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',

            'contact_no' => [
                'required',
                'digits:10',
                'regex:/^[0-9]{10}$/',
                Rule::unique('authors', 'contact_no')->ignore($id),
            ],

            'alt_contact_no' => [
                'nullable',
                'digits:10',
                'regex:/^[0-9]{10}$/',
                Rule::unique('authors', 'alt_contact_no')->ignore($id),
            ],

            'email' => [
                'required',
                'email',
                'max:100',
                Rule::unique('authors', 'email')->ignore($id),
            ],

            'gender' => 'required|in:male,female,other',

            'slug' => [
                'required',
                'string',
                Rule::unique('authors', 'slug')->ignore($id),
            ],

            'bio' => 'nullable|string',
        ]);

        if ($validated->fails()) {
            return redirect()->back()->with('error', 'Something went wrong. Please try again later.')->withInput();
        }

        DB::beginTransaction();
        try {
            $author = Author::findOrFail($id);
            $validated = $validated->validated();
            $author->update($validated);
            DB::commit();
            return redirect()->route('admin.authors.index')->with('success', 'Author updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.authors.index')->with('error', 'Something went wrong. Please try again later.')->withInput();
        }
    }

    public function destroy($id)
    {
        $author = Author::findOrFail($id);
        DB::beginTransaction();
        try {
            $author->delete();
            DB::commit();
            return redirect()->back()->with('success', 'Author Deleted Successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong. Please try again later.')->withInput();
        }
    }

    public function restore($id)
    {
        $author = Author::onlyTrashed()->findOrFail($id);
        DB::beginTransaction();
        try {
            $author->restore();
            DB::commit();
            return redirect()->route('admin.authors.index')->with('success', 'Author Restored Successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.authors.index')->with('error', 'Something went wrong. Please try again later.');
        }
    }

    public function forceDelete($id)
    {
        $author = Author::onlyTrashed()->findOrFail($id);
        DB::beginTransaction();
        try {
            $author->forceDelete();
            DB::commit();
            return redirect()->route('admin.authors.index')->with('success', 'Author Deleted Permanently.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.authors.index')->with('error', 'Something went wrong. Please try again later.');
        }
    }
}

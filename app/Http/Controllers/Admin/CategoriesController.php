<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CategoriesController extends Controller
{
    public function index()
    {
        $categories = Category::latest()->paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    public function trash()
    {
        $categories = Category::onlyTrashed()->paginate(10);
        return view('admin.categories.trashed', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'name'        => 'required|string|max:150',
            'slug'        => 'required|string|unique:categories,slug',
            'description' => 'nullable|string'
        ]);

        if ($validated->fails()) {
            return redirect()->back()->withErrors($validated)->withInput();
        }

        DB::beginTransaction();
        try {

            $validated = $validated->validated();
            Category::create($validated);
            DB::commit();
            return redirect()->route('admin.categories.index')->with('success', 'Category created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.categories.index')->with('error', 'Something went wrong. Please try again later.')->withInput();
        }
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $validated = Validator::make($request->all(), [
            'name'        => 'required|string|max:150',
            'slug'        => ['required','string',Rule::unique('categories', 'slug')->ignore($id)],
            'description' => 'nullable|string',
            'status'      => 'required'
        ]);

        if ($validated->fails()) {
            return redirect()->back()->with('error', 'Something went wrong. Please try again later.')->withInput();
        }

        DB::beginTransaction();
        try {

            $category  = Category::findOrFail($id);
            $validated = $validated->validated();
            $category->update($validated);
            DB::commit();
            return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.categories.index')->with('error', 'Something went wrong. Please try again later.')->withInput();
        }
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        DB::beginTransaction();
        try {
            $category->delete();
            DB::commit();
            return redirect()->back()->with('success', 'Category Deleted Successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong. Please try again later.')->withInput();
        }
    }

    public function restore($id)
    {
        $category = Category::onlyTrashed()->findOrFail($id);
        DB::beginTransaction();
        try {
            $category->restore();
            DB::commit();
            return redirect()->route('admin.categories.index')->with('success', 'Category Restored Successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.categories.index')->with('error', 'Something went wrong. Please try again later.');
        }
    }

    public function forceDelete($id)
    {
        $category = Category::onlyTrashed()->findOrFail($id);
        DB::beginTransaction();
        try {
            $category->forceDelete();
            DB::commit();
            return redirect()->route('admin.categories.index')->with('success', 'Category Deleted Permanently.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.categories.index')->with('error', 'Something went wrong. Please try again later.');
        }
    }


}

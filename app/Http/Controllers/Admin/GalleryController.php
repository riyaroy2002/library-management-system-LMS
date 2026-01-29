<?php

namespace App\Http\Controllers\Admin;

use App\FileUpload;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class GalleryController extends Controller
{
    use FileUpload;

    public function index()
    {
        $gallery = Gallery::latest()->paginate(10);
        return view('admin.gallery.index', compact('gallery'));
    }

    public function trash()
    {
        $gallery = Gallery::onlyTrashed()->paginate(10);
        return view('admin.gallery.trashed', compact('gallery'));
    }

    public function create()
    {
        return view('admin.gallery.create');
    }

    public function store(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'image'       => 'required|mimes:jpg,png,jpeg|max:2048',
            'title'       => 'required',
            'description' => 'nullable'
        ]);

        if ($validated->fails()) {
            return redirect()->back()->withErrors($validated)->withInput();
        }

        DB::beginTransaction();
        try {

            $validated = $validated->validated();
            if ($request->hasFile('image')) {
                $validated['image'] = $this->customSaveImage($request->file('image'), 'gallery/gallery_image');
            }
            Gallery::create($validated);
            DB::commit();
            return redirect()->route('admin.gallery.index')->with('success', 'Gallery created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.gallery.index')->with('error', 'Something went wrong. Please try again later.')->withInput();
        }
    }

    public function edit($id)
    {
        $gallery = Gallery::findOrFail($id);
        return view('admin.gallery.edit', compact('gallery'));
    }

    public function update(Request $request, $id)
    {
        $validated = Validator::make($request->all(), [
            'image'       => 'nullable|mimes:jpg,png,jpeg|max:2048',
            'title'       => 'required',
            'description' => 'nullable'
        ]);

        if ($validated->fails()) {
            return redirect()->back()->withErrors($validated)->withInput();
        }

        DB::beginTransaction();
        try {
            $gallery   = Gallery::findOrFail($id);
            $validated = $validated->validated();
            if ($request->hasFile('image')) {
                $oldImage = $gallery->image;
                if (file_exists($oldImage)) @unlink($oldImage);
                $validated['image'] = $this->customSaveImage($request->file('image'), 'cms/cms_image');
            }
            $gallery->update($validated);
            DB::commit();
            return redirect()->route('admin.gallery.index')->with('success', 'Gallery updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.gallery.index')->with('error', 'Something went wrong. Please try again later.')->withInput();
        }
    }

    public function destroy($id)
    {
        $gallery   = Gallery::findOrFail($id);
        DB::beginTransaction();
        try {
            $gallery->delete();
            DB::commit();
            return redirect()->back()->with('success', 'Gallery Deleted Successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong. Please try again later.')->withInput();
        }
    }

    public function restore($id)
    {
        $gallery = Gallery::onlyTrashed()->findOrFail($id);
        DB::beginTransaction();
        try {
            $gallery->restore();
            DB::commit();
            return redirect()->route('admin.gallery.index')->with('success', 'Gallery Restored Successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.gallery.index')->with('error', 'Something went wrong. Please try again later.');
        }
    }

    public function forceDelete($id)
    {
        $gallery = Gallery::onlyTrashed()->findOrFail($id);
        DB::beginTransaction();
        try {
            if ($gallery->image && file_exists($gallery->image)) {
                @unlink($gallery->image);
            }
            $gallery->forceDelete();
            DB::commit();
            return redirect()->route('admin.gallery.index')->with('success', 'Gallery Deleted Permanently.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.gallery.index')->with('error', 'Something went wrong. Please try again later.');
        }
    }
}

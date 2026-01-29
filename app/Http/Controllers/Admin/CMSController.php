<?php

namespace App\Http\Controllers\Admin;

use App\FileUpload;
use App\Models\Cms;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CMSController extends Controller
{
    use FileUpload;

    public function index()
    {
        $cms = Cms::latest()->paginate(10);
        return view('admin.cms.index', compact('cms'));
    }

    public function create()
    {
        return view('admin.cms.create');
    }

    public function store(Request $request)
    {
        $validated = Validator::make($request->all(),[
            'title'       => 'required',
            'slug'        => 'required',
            'text_content'=> 'nullable',
            'image'       => 'nullable|mimes:jpg,png,jpeg|max:2048',
            'extra'       => 'nullable'
        ]);

        if ($validated->fails()) {
            return redirect()->back()->withErrors($validated)->withInput();
        }

        DB::beginTransaction();
        try {
            $validated = $validated->validated();
            if($request->hasFile('image')){
                $validated['image'] = $this->customSaveImage($request->file('image'),'cms/cms_image');
            }
            Cms::create($validated);
            DB::commit();
            return redirect()->route('admin.cms.index')->with('success','CMS created successfully.');
        } catch (\Exception $e){
            DB::rollBack();
            return redirect()->route('admin.cms.index')->with('error','Something went wrong. Please try again later.')->withInput();
        }
    }

    public function edit($id)
    {
        $cms = Cms::findOrFail($id);
        return view('admin.cms.edit', compact('cms'));
    }

    public function update(Request $request,$id)
    {
        $validated = Validator::make($request->all(),[
            'title'       => 'required',
            'slug'        => 'required',
            'text_content'=> 'nullable',
            'image'       => 'nullable|mimes:jpg,png,jpeg|max:2048',
            'extra'       => 'nullable'
        ]);

        if ($validated->fails()) {
            return redirect()->back()->withErrors($validated)->withInput();
        }

        DB::beginTransaction();
        try {
            $cms       = Cms::findOrFail($id);
            $validated = $validated->validated();
            if ($request->hasFile('image')){
                $oldImage = $cms->image;
                if (file_exists($oldImage)) @unlink($oldImage);
                $validated['image']= $this->customSaveImage($request->file('image'), 'cms/cms_image');
            }
            $cms->update($validated);
            DB::commit();
            return redirect()->route('admin.cms.index')->with('success','CMS updated successfully.');
        } catch (\Exception $e){
            DB::rollBack();
            return redirect()->route('admin.cms.index')->with('error','Something went wrong. Please try again later.')->withInput();
        }
    }
}

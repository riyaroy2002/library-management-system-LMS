<?php

namespace App;
use Illuminate\Support\Facades\Storage;

trait FileUpload
{
    public function customSaveImage($file, $path)
    {
        $filename = uniqid() . time() . rand(10, 1000000) . '.' . $file->getClientOriginalExtension();
        Storage::disk('public')->putFileAs($path, $file, $filename);
        $fileUrl = 'storage/' . $path . '/' . $filename;
        return $fileUrl;
    }
}

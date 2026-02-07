<?php

namespace App\Http\Controllers\Admin;

use App\Models\NewsLetter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;


class NewsLettersController extends Controller
{
    public function index()
    {
        $newsletters = NewsLetter::latest()->paginate(10);
        return view('admin.newsLetters.index',compact('newsletters'));
    }

}

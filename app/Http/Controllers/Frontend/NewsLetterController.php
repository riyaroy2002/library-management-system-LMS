<?php

namespace App\Http\Controllers\Frontend;

use Carbon\Carbon;
use App\Mail\NewsLetters;
use App\Models\NewsLetter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class NewsLetterController extends Controller
{
    public function store(Request $request)
    {
        $validated = Validator::make($request->all(), [
             'email' => 'required|email|max:50|unique:news_letters,email'
        ]);

        if ($validated->fails()) {
            return redirect()->back()->withErrors($validated)->withInput();
        }

        DB::beginTransaction();
        try {

            $validated = $validated->validated();
            $newsletter = NewsLetter::create([
                'email'         => $validated['email'],
                'is_subscribed' => true,
                'subscribed_at' => Carbon::now()
            ]);

            $data['email'] = $newsletter->email;
            if (filter_var($validated['email'], FILTER_VALIDATE_EMAIL)) {
                Mail::to($validated['email'])->send(new NewsLetters($data));
            }

            DB::commit();
            return redirect()->route('home')->with('success', 'Thank you for subscribing.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('home')->with('error', 'Something went wrong. Please try again later.')->withInput();
        }
    }
}

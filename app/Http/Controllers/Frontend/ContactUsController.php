<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Contact;
use App\Mail\ContactUs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ContactUsController extends Controller
{
    public function store(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'name'    => ['required', 'string', 'max:50'],
            'email'   => ['required', 'email' , 'max:50'],
            'subject' => ['required', 'string', 'max:100'],
            'message' => ['required', 'string']
        ]);

        if ($validated->fails()) {
            return redirect()->back()->withErrors($validated)->withInput();
        }

        DB::beginTransaction();
        try {

            $validated = $validated->validated();
            $contact = Contact::create([
                'name'       => $validated['name'],
                'email'      => $validated['email'],
                'subject'    => $validated['subject'],
                'message'    => $validated['message']
            ]);

            $data['name'] = $contact->name;
            if (filter_var($validated['email'],FILTER_VALIDATE_EMAIL)) {
                Mail::to($validated['email'])->send(new ContactUs($data));
            }

            DB::commit();
            return redirect()->route('contact-us')->with('success', 'Thank you for contacting with us.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('contact-us')->with('error', 'Something went wrong. Please try again later.')->withInput();
        }
    }
}

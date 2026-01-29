<?php

namespace App\Http\Controllers\Admin;

use App\Models\Contact;
use App\Mail\ReplyBack;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class InquiriesController extends Controller
{
    public function index()
    {
        $contacts = Contact::latest()->paginate(10);
        return view('admin.inquiries.index', compact('contacts'));
    }

    public function replyBack($id)
    {
        $contact = Contact::findOrFail($id);
        return view('admin.inquiries.replyBack', compact('contact'));
    }

    public function view($id)
    {
        $contact = Contact::findOrFail($id);
        return view('admin.inquiries.view', compact('contact'));
    }

    public function store(Request $request,$id)
    {
        $validated = Validator::make($request->all(),[
          'replyBack' => ['required', 'string']
        ]);

        if ($validated->fails()){
            return redirect()->back()->withErrors($validated)->withInput();
        }

        DB::beginTransaction();
        try {

            $contact   = Contact::findOrFail($id);
            $validated = $validated->validated();
            $contact->update($validated);

            $data['name']      = $contact->name;
            $data['message']   = $contact->message;
            $data['replyBack'] = $contact->replyBack;
            
            if (filter_var($contact->email,FILTER_VALIDATE_EMAIL)) {
                Mail::to($contact->email)->send(new ReplyBack($data));
            }

            DB::commit();
            return redirect()->route('admin.inquiries.index')->with('success', 'Replied sent.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.inquiries.index')->with('error', 'Something went wrong. Please try again later.'.$e)->withInput();
        }
    }
}

<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Mail\VerifyEmail;
use Illuminate\Support\Str;
use App\Mail\ForgotPassword;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\PasswordResetToken;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use App\Models\{User, Librarian, State, Address, District, Member};

class UserController extends Controller
{

    public function members()
    {
        $members = Member::with(['user', 'address'])->latest()->paginate(10);
        return view('admin.members.index', compact('members'));
    }

    public function toggleBlockMember($id)
    {
        DB::beginTransaction();
        try {

            $user           = User::findOrFail($id);
            $user->is_block = !$user->is_block;
            $user->update();
            if ($user->is_block) {
                DB::table('sessions')->where('user_id', $user->id)->delete();
            }
            DB::commit();
            return redirect()->route('admin.members.index')->with('success', 'User status updated.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.members.index')->with('error', 'Something went wrong. Please try again later.');
        }
    }

    public function librarians()
    {
        $librarians = Librarian::with(['user', 'address'])->latest()->paginate(10);
        return view('admin.librarians.index', compact('librarians'));
    }

    public function create()
    {
        $states = State::all();
        return view('admin.librarians.create', compact('states'));
    }

    public function getDistrict($state_id)
    {
        return District::where('state_id', $state_id)->get();
    }

    public function store(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'first_name'         => ['required', 'string', 'max:50'],
            'last_name'          => ['nullable', 'string', 'max:50'],
            'contact_no'         => ['required', 'digits:10', 'regex:/^[0-9]{10}$/', Rule::unique('librarians', 'contact_no'), Rule::unique('users', 'contact_no')],
            'alt_contact_no'     => ['nullable', 'digits:10', 'regex:/^[0-9]{10}$/', Rule::unique('librarians', 'alt_contact_no')],
            'email'              => ['required', 'email', 'max:100', Rule::unique('librarians', 'email'), Rule::unique('users', 'email')],
            'gender'             => ['required'],
            'date_of_birth'      => ['required', 'date', 'before_or_equal:today'],
            'joining_date'       => ['required', 'date', 'before_or_equal:today'],
            'address_line1'      => ['required', 'string'],
            'address_line2'      => ['nullable', 'string'],
            'city'               => ['nullable', 'string', 'max:100'],
            'state_id'           => ['required', 'exists:states,id'],
            'district_id'        => ['required', 'exists:districts,id'],
            'pincode'            => ['required', 'digits:6', 'regex:/^[0-9]{6}$/'],
            'user_id'            => ['nullable', 'exists:users,id'],
            'address_id'         => ['nullable', 'exists:addresses,id'],
            'password'           => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()]
        ]);

        if ($validated->fails()) {
            return redirect()->back()->withErrors($validated)->withInput();
        }

        DB::beginTransaction();
        try {

            $lastId = 0;
            $getId = Librarian::orderBy('id', 'DESC')->first();
            if (!empty($getId)) {
                $lastId = intval(explode('-', $getId->employee_code)[1]);
            }
            $presentId = $lastId + 1;
            $empCode = "LIBR-" . str_pad($presentId, 5, "0", STR_PAD_LEFT);

            //LIBR-00001
            /**
             * LIBR->0
             * 00001->1
             * intval(00001)->1
             */

            $validated = $validated->validated();

            $user = User::create([
                'first_name'     => $validated['first_name'],
                'last_name'      => $validated['last_name']      ?? null,
                'contact_no'     => $validated['contact_no'],
                'alt_contact_no' => $validated['alt_contact_no'] ?? null,
                'email'          => $validated['email'],
                'password'       => $validated['password'],
                'role'           => 'librarian',
                'verified'       => 1
            ]);

            $address = Address::create([
                'address_line1' => $validated['address_line1'],
                'address_line2' => $validated['address_line2'] ?? null,
                'city'          => $validated['city']          ?? null,
                'state_id'      => $validated['state_id'],
                'district_id'   => $validated['district_id'],
                'pincode'       => $validated['pincode']
            ]);

            Librarian::create([
                'user_id'        => $user->id,
                'address_id'     => $address->id,
                'employee_code'  => $empCode,
                'first_name'     => $validated['first_name'],
                'last_name'      => $validated['last_name']      ?? null,
                'contact_no'     => $validated['contact_no'],
                'alt_contact_no' => $validated['alt_contact_no'] ?? null,
                'email'          => $validated['email'],
                'gender'         => $validated['gender'],
                'date_of_birth'  => $validated['date_of_birth'],
                'joining_date'   => $validated['joining_date']
            ]);

            DB::commit();
            return redirect()->route('admin.librarians.index')->with('success', 'Librarian created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.librarians.index')->with('error', 'Something went wrong. Please try again later.')->withInput();
        }
    }

    public function edit($id)
    {
        $states    = State::all();
        $librarian = Librarian::with(['user', 'address'])->findOrFail($id);
        return view('admin.librarians.edit', compact('states', 'librarian'));
    }

    public function update(Request $request, $id)
    {
        $librarian = Librarian::with(['user', 'address'])->findOrFail($id);
        $user      = $librarian->user;
        $address   = $librarian->address;

        $validator = Validator::make($request->all(), [
            'first_name'     => ['required', 'string', 'max:50'],
            'last_name'      => ['nullable', 'string', 'max:50'],
            'contact_no'     => ['required', 'digits:10', 'regex:/^[0-9]{10}$/', Rule::unique('librarians', 'contact_no')->ignore($librarian->id), Rule::unique('users', 'contact_no')->ignore($user->id)],
            'alt_contact_no' => ['nullable', 'digits:10', 'regex:/^[0-9]{10}$/', Rule::unique('librarians', 'alt_contact_no')->ignore($librarian->id), Rule::unique('users', 'alt_contact_no')->ignore($user->id)],
            'email'          => ['required', 'email', 'max:100',                 Rule::unique('librarians', 'email')->ignore($librarian->id), Rule::unique('users', 'email')->ignore($user->id)],
            'gender'         => ['required'],
            'date_of_birth'  => ['required', 'date', 'before_or_equal:today'],
            'joining_date'   => ['required', 'date', 'before_or_equal:today'],
            'address_line1'  => ['required', 'string'],
            'address_line2'  => ['nullable', 'string'],
            'city'           => ['nullable', 'string', 'max:100'],
            'state_id'       => ['required', 'exists:states,id'],
            'district_id'    => ['required', 'exists:districts,id'],
            'pincode'        => ['required', 'digits:6']
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {

            $validated = $validator->validated();

            $user->update([
                'first_name'     => $validated['first_name'],
                'last_name'      => $validated['last_name']      ?? null,
                'contact_no'     => $validated['contact_no'],
                'alt_contact_no' => $validated['alt_contact_no'] ?? null,
                'email'          => $validated['email']
            ]);

            $address->update([
                'address_line1' => $validated['address_line1'],
                'address_line2' => $validated['address_line2'] ?? null,
                'city'          => $validated['city'] ?? null,
                'state_id'      => $validated['state_id'],
                'district_id'   => $validated['district_id'],
                'pincode'       => $validated['pincode']
            ]);

            $librarian->update([
                'user_id'        => $user->id,
                'address_id'     => $address->id,
                'first_name'     => $validated['first_name'],
                'last_name'      => $validated['last_name']      ?? null,
                'contact_no'     => $validated['contact_no'],
                'alt_contact_no' => $validated['alt_contact_no'] ?? null,
                'email'          => $validated['email'],
                'gender'         => $validated['gender'],
                'date_of_birth'  => $validated['date_of_birth'],
                'joining_date'   => $validated['joining_date']
            ]);

            DB::commit();
            return redirect()->route('admin.librarians.index')->with('success', 'Librarian updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong. Please try again later.')->withInput();
        }
    }

    public function toggleBlockLibrarian($id)
    {
        DB::beginTransaction();
        try {

            $user           = User::findOrFail($id);
            $user->is_block = !$user->is_block;
            $user->update();
            if ($user->is_block) {
                DB::table('sessions')->where('user_id', $user->id)->delete();
            }
            DB::commit();
            return redirect()->route('admin.librarians.index')->with('success', 'User status updated.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.librarians.index')->with('error', 'Something went wrong. Please try again later.');
        }
    }
}

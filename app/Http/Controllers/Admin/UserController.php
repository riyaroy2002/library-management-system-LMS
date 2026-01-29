<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\{Librarian,Member,User};
use App\Http\Controllers\Controller;

class UserController extends Controller
{

    public function members()
    {
        $members = Member::with(['user', 'address'])->latest()->paginate(10);
        return view('admin.members.index', compact('members'));
    }

    public function librarians()
    {
        $librarians = Librarian::with(['user', 'address'])->latest()->paginate(10);
        return view('admin.librarians.index', compact('librarians'));
    }

    public function toggleBlockMember($id)
    {
        DB::beginTransaction();
        try {

            $user           = User::findOrFail($id);
            $user->is_block = !$user->is_block;
            $user->update();
            if ($user->is_block) {
                DB::table('sessions')->where('user_id',$user->id)->delete();
            }
            DB::commit();
            return redirect()->route('admin.members.index')->with('success', 'User status updated.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.members.index')->with('error', 'Something went wrong. Please try again later.');
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
                DB::table('sessions')->where('user_id',$user->id)->delete();
            }
            DB::commit();
            return redirect()->route('admin.librarians.index')->with('success', 'User status updated.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.librarians.index')->with('error', 'Something went wrong. Please try again later.');
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;


class AdminController extends Controller{
    public function indexAdmin(){
        $user = User::all();
        return view('admin.add_admin', compact('user'));
    }

    public function storeAdmin(Request $request){
        $data = $request->validate([
            'name' => 'required|min:8|max:24|unique:users',
            'password' => 'required|min:8|max:16',
        ]);

        try {
            $data['password'] = bcrypt($data['password']);
            User::create($data);
            return redirect()->route('admin')->with('success', 'Data berhasil disimpan!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan data: ' . $e->getMessage());
        }
    }

    public function updateAdmin(Request $request, $id){
        $data = $request->validate([
            'name'     => 'required|min:8|max:24|unique:users,name,' . $id,
            'password' => 'nullable|min:8|max:16',
        ]);

        try {
            $user = User::findOrFail($id);
            $user->name = $data['name'];

            if (!empty($data['password'])) {
                $user->password = Hash::make($data['password']);
            }

            $user->save();

            return redirect()->route('admin')->with('success', 'Data berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    public function deleteAdmin($id){
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return redirect()->route('admin')->with('success', 'Data berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('admin')->with('error', 'Gagal menghapus data!');
        }
    }
}

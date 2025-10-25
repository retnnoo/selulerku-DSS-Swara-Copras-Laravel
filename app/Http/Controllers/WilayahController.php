<?php

namespace App\Http\Controllers;

use App\Models\Wilayah;
use Illuminate\Http\Request;

class WilayahController extends Controller
{
    public function indexwilayah(){
        $wilayah = Wilayah::all();
        return view('admin.wilayah', compact('wilayah'));
    }

    public function storeWilayah(Request $request){
        $data = $request->validate([
            'kode_wilayah' => 'required|max:5|string',
            'nama_wilayah' => 'required|max:50|string',
        ]);

        try {
            Wilayah::create($data);
            return redirect()->route('wilayah')->with('success', 'Data berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->route('wilayah')->with('error', 'Gagal menambahkan data wilayah!');
        }
    }

    public function updateWilayah(Request $request, $kode_wilayah){
        $data = $request->validate([
            'nama_wilayah' => 'required|max:50|string',
        ]);

        try {
            $wilayah = Wilayah::findOrFail($kode_wilayah);
            $wilayah->update($data);

            return redirect()->route('wilayah')->with('success', 'Data berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->route('wilayah')->with('error', 'Gagal memperbarui data wilayah!');
        }
    }

    public function deleteWilayah($kode_wilayah){
        try {
            Wilayah::where('kode_wilayah', $kode_wilayah)->delete();
            return redirect()->route('wilayah')->with('success', 'Data berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('wilayah')->with('error', 'Gagal menghapus data!');
        }
    }
}

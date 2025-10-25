<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use Illuminate\Http\Request;

class KriteriaController extends Controller
{
    public function indexKriteria(){
        $kriteria = Kriteria::all();
        return view('admin.data_kriteria', compact('kriteria'));
    }

    public function storeKriteria(Request $request)
    {
        $data = $request->validate([
            'kode_kriteria'  => 'required|max:5|string',
            'nama_kriteria'  => 'required|max:30|string',
            'jenis_kriteria' => 'required|in:benefit,cost',
        ]);

        try {
            Kriteria::create($data);
            return redirect()->route('kriteria')->with('success', 'Data berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->route('kriteria')->with('error', 'Gagal menambahkan data kriteria!');
        }
    }

    public function updateKriteria(Request $request, $kode_kriteria)
    {
        $data = $request->validate([
            'nama_kriteria'  => 'required|max:30|string',
            'jenis_kriteria' => 'required|in:benefit,cost',
        ]);

        try {
            $kriteria = Kriteria::findOrFail($kode_kriteria);
            $kriteria->update($data);

            return redirect()->route('kriteria')->with('success', 'Data berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->route('kriteria')->with('error', 'Gagal memeperbarui data!');
        }
    }

    public function deleteKriteria($kode_kriteria)
    {
        try {
            Kriteria::where('kode_kriteria', $kode_kriteria)->delete();
            return redirect()->route('kriteria')->with('success', 'Data berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('kriteria')->with('error', 'Gagal menghapus data!');
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Ahli;
use App\Models\Kriteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AhliController extends Controller
{
    public function indexAhli(){
        $ahli =  Ahli::orderByRaw('CAST(SUBSTRING(kode_ahli, 2) AS UNSIGNED) ASC')->get();
        $kriteria = Kriteria::all();

        return view('admin.data_ahli', compact('ahli', 'kriteria'));
    }

    public function storeAhli(Request $request)
    {
        // Validasi
        $request->validate([
            'nilai' => 'required|array',
        ], [
            'nilai.required' => 'Data nilai wajib diisi!',
        ]);

        try {
            // Buat kode baru
            $last = Ahli::orderByRaw('CAST(SUBSTRING(kode_ahli, 2) AS UNSIGNED) DESC')->first();
            $newKode = $last ? 'P' . ((int) substr($last->kode_ahli, 1) + 1) : 'P1';

            $ahli = Ahli::create([
                'kode_ahli' => $newKode,
            ]);

            // Simpan nilai
            foreach ($request->nilai as $kode_kriteria => $val) {
                DB::table('nilai_ahli')->insert([
                    'kode_ahli' => $ahli->kode_ahli,
                    'kode_kriteria' => $kode_kriteria,
                    'nilai' => $val,
                ]);
            }

            return redirect()->route('ahli')->with('success', 'Data ahli berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->route('ahli')->with('error', 'Gagal menambahkan data ahli!');
        }
    }

    // Update data Ahli
    public function updateAhli(Request $request, $kode_ahli)
    {
        // Validasi
        $request->validate([
            'nilai' => 'required|array',
        ], [
            'nilai.required' => 'Data nilai wajib diisi!',
        ]);

        try {
            $ahli = Ahli::findOrFail($kode_ahli);

            foreach ($request->nilai as $kode_kriteria => $val) {
                DB::table('nilai_ahli')->updateOrInsert(
                    [
                        'kode_ahli' => $ahli->kode_ahli,
                        'kode_kriteria' => $kode_kriteria,
                    ],
                    ['nilai' => $val]
                );
            }

            return redirect()->route('ahli')->with('success', 'Data ahli berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->route('ahli')->with('error', 'Gagal memperbarui data ahli!');
        }
    }

    // Hapus data Ahli
    public function deleteAhli($kode_ahli)
    {
        try {
            $ahli = Ahli::findOrFail($kode_ahli);
            $ahli->delete();

            return redirect()->route('ahli')->with('success', 'Data ahli berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('ahli')->with('error', 'Gagal menghapus data ahli!');
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\Wilayah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AlternatifController extends Controller
{
    // ALTERNATIF
    public function indexAlternatif(Request $request){
        $wilayah = Wilayah::all();
        $defaultWilayah = Wilayah::first()->kode_wilayah ?? null;
        $alternatif = Alternatif::with(['nilaiAlternatif' => function($query) use ($request, $defaultWilayah) {
            if ($request->has('wilayah') && $request->wilayah != '') {
                $query->where('kode_wilayah', $request->wilayah);
            }else{
                $query->where('kode_wilayah', $defaultWilayah);
            }
        }])

        ->orderByRaw('CAST(SUBSTRING(kode_alternatif, 2) AS UNSIGNED) ASC')
        ->get();
        $kriteria = Kriteria::all();

         return view('admin.data_alternatif', compact('wilayah', 'alternatif', 'kriteria'));
    }

    public function storeAlternatif(Request $request){
        // Validasi input
        $data = $request->validate([
            'nama_alternatif' => 'required|string|max:255',
            'nilai'           => 'required|array',
            'kode_wilayah'    => 'nullable|string',
        ]);

        try {
            $namaLower = strtolower($data['nama_alternatif']);

            $existingAlternatif = Alternatif::whereRaw('LOWER(nama_alternatif) = ?', [$namaLower])->first();
    
            
            if($existingAlternatif){
                $newKode = $existingAlternatif->kode_alternatif; 
            }
             else{
               $last = Alternatif::orderByRaw('CAST(SUBSTRING(kode_alternatif, 2) AS UNSIGNED) DESC')->first();
                $newKode = $last ? 'A' . ((int) substr($last->kode_alternatif, 1) + 1) : 'A1';

                // Buat data alternatif
                Alternatif::create([
                'kode_alternatif' => $newKode,
                'nama_alternatif' => $data['nama_alternatif'],
            ]);
            }

            // Jika kode wilayah kosong, ambil default wilayah pertama
            $kodeWilayah = $data['kode_wilayah'] ?? Wilayah::orderByRaw('CAST(SUBSTRING(kode_wilayah, 2) AS UNSIGNED) ASC')
                                                        ->first()
                                                        ->kode_wilayah;

            // Simpan nilai kriteria
            foreach ($data['nilai'] as $kode_kriteria => $val) {
                DB::table('nilai_alternatif')->insert([
                    'kode_alternatif' => $newKode,
                    'kode_kriteria'   => $kode_kriteria,
                    'kode_wilayah'    => $kodeWilayah,
                    'nilai'           => $val,
                ]);
            }

            return redirect()->back()->with('success', 'Data alternatif berhasil ditambahkan!');
        } catch (\Exception $e) {
            // Optional: bisa log $e->getMessage() untuk debugging
            return redirect()->back()->with('error', 'Gagal menambahkan data alternatif!' . $e);
        }
    }

    public function updateAlternatif(Request $request, $kode_alternatif){
        $data = $request->validate([
            'nama_alternatif' => 'required|string',
            'nilai'           => 'required|array',
            'kode_wilayah'    => 'nullable',
        ]);

        try {
            $alternatif = Alternatif::findOrFail($kode_alternatif);
            $alternatif->update([
                'nama_alternatif' => $data['nama_alternatif']
            ]);

            $first = Wilayah::orderByRaw('CAST(SUBSTRING(kode_wilayah, 2) AS UNSIGNED) ASC')->first();
            if ($data['kode_wilayah'] == null) {
                $data['kode_wilayah'] = $first->kode_wilayah;
            }

            foreach ($request->nilai as $kode_kriteria => $val) {
                DB::table('nilai_alternatif')->updateOrInsert(
                    [
                        'kode_alternatif' => $alternatif->kode_alternatif,
                        'kode_kriteria'   => $kode_kriteria,
                        'kode_wilayah'    => $data['kode_wilayah']
                    ],
                    ['nilai' => $val]
                );
            }

            return redirect()->route('alternatif')->with('success', 'Data berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->route('alternatif')->with('error', 'Gagal memperbarui data alternatif!');
        }
    }

    public function deleteAlternatif($kode_alternatif){
        try {
            $alternatif = Alternatif::findOrFail($kode_alternatif);
            $alternatif->delete();

            return redirect()->route('alternatif')->with('success', 'Data alternatif berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('alternatif')->with('error', 'Gagal menghapus data alternatif!');
        }
    }
}

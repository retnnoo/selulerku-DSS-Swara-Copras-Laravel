<?php

namespace App\Http\Controllers;

use App\Models\Bobot;
use App\Models\Kriteria;
use App\Models\Ahli;
use App\Models\NilaiAhli;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SwaraController extends Controller
{
    public function indexPembobotanSwara(){
        // Ambil semua data kriteria dan ahli
        $kriteria = Kriteria::pluck('kode_kriteria')->toArray();
        $swara = Bobot::pluck('kode_kriteria')->toArray();
        $ahli = Ahli::all();

        // Sinkronisasi tabel bobot dengan kriteria
        $tambahData = array_diff($kriteria, $swara);
        foreach ($tambahData as $row) {
            Bobot::create(['kode_kriteria' => $row, 'bobot_kriteria' => 0]);
        }

        $hapusData = array_diff($swara, $kriteria);
        Bobot::whereIn('kode_kriteria', $hapusData)->delete();

        $swaraData = Bobot::all();

        //Hitung rata-rata nilai dari para ahli (t_j)
        $calc = [];
        foreach ($swaraData as $row) {
            $kriteriaName = $row->kode_kriteria;
            $avg = NilaiAhli::where('kode_kriteria', $kriteriaName)->avg('nilai') ?? 0.0;

            $calc[] = [
                'id' => $row->id,
                'kode_kriteria' => $kriteriaName,
                'tj' => round((float)$avg, 5),
            ];
        }

        //Peringkat kriteria
        usort($calc, fn($a, $b) => $b['tj'] <=> $a['tj']);
        foreach ($calc as $i => &$row) {
            $row['j'] = $i + 1;
        }

        //Hitung Sj, Kj, Qj
        $averageJ = collect($calc)->avg('j');
        foreach ($calc as $i => &$row) {
            if ($i === 0) {
                $row['Sj'] = 0;
                $row['Kj'] = 1;
                $row['Qj'] = 1;
            } else {
                $row['Sj'] = round(($row['j'] - 1) / $averageJ, 5);
                $row['Kj'] = round($row['Sj'] + 1, 5);
                $row['Qj'] = round($calc[$i - 1]['Qj'] / $row['Kj'], 5);
            }
        }

        //Hitung W_j (bobot akhir) dan simpan ke DB
        $totalQj = collect($calc)->sum('Qj');
        foreach ($calc as &$row) {
            $row['Wj'] = round($row['Qj'] / $totalQj, 5);
            Bobot::where('id', $row['id'])->update(['bobot_kriteria' => $row['Wj']]);
        }

        // Ambil data untuk tabel utama
        $data = Bobot::orderBy('bobot_kriteria', 'desc')->with('kriteria')->get();

        // Kirim ke view
        return view('admin.pembobotan_swara', [
            'data' => $data,
            'calc' => $calc
        ]);
    }
}
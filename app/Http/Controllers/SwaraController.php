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
        // Ambil semua data
        $kriteria = Kriteria::pluck('kode_kriteria')->toArray();
        $swara = Bobot::pluck('kode_kriteria')->toArray();
        $ahli = Ahli::all();

        // Sinkronisasi tabel swara dengan kriteria
        $tambahData = array_diff($kriteria, $swara);
        foreach ($tambahData as $row) {
            Bobot::create(['kode_kriteria' => $row, 'bobot_kriteria' => 0]);
        }

        $hapusData = array_diff($swara, $kriteria);
        Bobot::whereIn('kode_kriteria', $hapusData)->delete();
        
        $swaraData = Bobot::all();

        $calc = [];
        foreach ($swaraData as $row) {
            $kriteriaName = $row->kode_kriteria;

            // Ambil rata-rata dari tabel nilai_ahli untuk kode_kriteria
            $avg = NilaiAhli::where('kode_kriteria', $kriteriaName)->avg('nilai');
            $avg = $avg !== null ? (float) $avg : 0.0;

            $calc[] = [
                'id' => $row->id,
                'kode_kriteria' => $kriteriaName,
                'tj' => $avg,
            ];
        }
 
    // Ranking berdasarkan tj
        foreach ($calc as $row) {
        Log::info("CHECK CALC id={$row['id']}, kriteria={$row['kode_kriteria']}, tj={$row['tj']}");
        }

        usort($calc, fn($a, $b) => $b['tj'] <=> $a['tj']);
        foreach ($calc as $i => &$row) {
            $row['j'] = $i + 1;
            Log::info('Nilai j:'. $row['j']);
        }

        $averageJ = collect($calc)->avg('j');

        // Hitung Sj, Kj, Qj
        foreach ($calc as $i => &$row) {
            if ($i === 0) {
                $row['Sj'] = 0;
                $row['Kj'] = 1;
                $row['Qj'] = 1;
            } else {
                $row['Sj'] = ($row['j'] - 1) / $averageJ;
                $row['Kj'] = $row['Sj'] + 1;
                $row['Qj'] = $calc[$i - 1]['Qj'] / $row['Kj'];
            }
            Log::info('Nilai Qj:'. $row['Qj'] . ' id =  ' . $row['id']);
        }

        // Hitung Wj
        $totalQj = collect($calc)->sum('Qj');
            foreach ($calc as &$row) {
                $wj = $row['Qj'] / $totalQj;
                Bobot::where('id', $row['id'])->update(['bobot_kriteria' => $wj]);
                Log::info('Nilai Wj:'. $wj. ' id ' .$row['id']);
            }

        $data = Bobot::all();
        
        return view('admin.pembobotan_swara', compact('data'));
    }
}

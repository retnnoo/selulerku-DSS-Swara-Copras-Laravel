<?php

namespace App\Http\Controllers;

use App\Models\Copras;
use App\Models\Alternatif;
use App\Models\NilaiAlternatif;
use App\Models\Kriteria;
use App\Models\Wilayah;
use App\Models\Bobot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class CoprasController extends Controller
{
    public function indexCopras(Request $request){
        $alternatif = Alternatif::pluck('kode_alternatif')->toArray();
        $wilayah    = Wilayah::pluck('kode_wilayah')->toArray();

        $kodeKriteria = Kriteria::pluck('kode_kriteria')->toArray();
        $bobot        = Bobot::pluck('bobot_kriteria', 'kode_kriteria')->toArray();
        $jenis        = Kriteria::pluck('jenis_kriteria', 'kode_kriteria')->toArray();

        $normalisasi = [];
        $terbobot    = [];
        $Si          = [];

        foreach ($wilayah as $w) {
            $Si[$w] = []; // reset per wilayah

            $data = NilaiAlternatif::where('kode_wilayah', $w)->get();
            if ($data->isEmpty()) continue;

            // === 1. Hitung jumlah tiap kriteria (untuk normalisasi) ===
            $sum = [];
            foreach ($kodeKriteria as $kriteria) {
                $sum[$kriteria] = $data->where('kode_kriteria', $kriteria)->sum('nilai');
            }

            // === 2. Normalisasi & terbobot ===
            foreach ($data as $row) {
                $kode      = $row->kode_kriteria;
                $nilaiNorm = $sum[$kode] ? $row->nilai / $sum[$kode] : 0;

                $normalisasi[$w][$row->kode_alternatif][$kode] = $nilaiNorm;

                $nilaiTerbobot = $nilaiNorm * ($bobot[$kode] ?? 0);
                $terbobot[$w][$row->kode_alternatif][$kode] = $nilaiTerbobot;
            }

            // === 3. Hitung Si+ (benefit) dan Si- (cost) ===
            foreach ($terbobot[$w] as $alt => $kriteriaValues) {
                $SiPlus = 0;
                $SiMin  = 0;

                foreach ($kriteriaValues as $k => $val) {
                    if (($jenis[$k] ?? 'benefit') === 'benefit') {
                        $SiPlus += $val;
                    } else {
                        $SiMin += $val;
                    }
                }

                $Si[$w][$alt] = [
                    'Si+' => $SiPlus,
                    'Si-' => $SiMin
                ];
            }

            // === 4. Hitung variabel pendukung (ΣSi- dan Σ(1/Si-)) ===
            $sumSiMin   = array_sum(array_column($Si[$w], 'Si-')); 
            $sumInverse = array_sum(array_map(fn($x) => $x['Si-'] > 0 ? 1/$x['Si-'] : 0, $Si[$w])); 

            // === 5. Hitung Qi ===
            foreach ($Si[$w] as $alt => $val) {
                if ($val['Si-'] > 0) {
                    $pembagi  = $val['Si-'] * $sumInverse;
                    $costPart = $sumSiMin / $pembagi;
                    $Qi       = $val['Si+'] + $costPart;
                } else {
                    $Qi = $val['Si+'];
                }

                $Si[$w][$alt]['Qi'] = round($Qi, 9);
            }

            // === 6. Hitung Ui (normalisasi ke 0–100) ===
            $maxQi = max(array_column($Si[$w], 'Qi'));
            foreach ($Si[$w] as $alt => $val) {
                $Ui = $maxQi > 0 ? ($val['Qi'] / $maxQi) * 100 : 0;
                $Ui = round($Ui, 6);

                Copras::where('kode_wilayah', $w)
                    ->where('kode_alternatif', $alt)
                    ->update([
                        'nilai_copras' => $Ui
                    ]);
            }
        }

        // === Ambil data untuk ditampilkan ke view ===
        $wilayah = Wilayah::all();
        $defaultWilayah = Wilayah::first()->kode_wilayah ?? null;

        $copras = Copras::with(['alternatif', 'wilayah'])
            ->when($request->has('wilayah') && $request->wilayah != '', function ($query) use ($request) {
                $query->where('kode_wilayah', $request->wilayah);
            }, function ($query) use ($defaultWilayah) {
                $query->where('kode_wilayah', $defaultWilayah);
            })
            ->orderByDesc('nilai_copras')
            ->get();

        return view('admin.copras', compact('wilayah', 'copras'));
    }
}

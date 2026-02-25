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

        $hasilProses = [];
        foreach ($wilayah as $w) {
            $data = NilaiAlternatif::where('kode_wilayah', $w)->get();
            if ($data->isEmpty()) continue;

            // Hitung jumlah tiap kriteria (untuk normalisasi)
            $sum = [];
            foreach ($kodeKriteria as $kriteria) {
                $sum[$kriteria] = $data->where('kode_kriteria', $kriteria)->sum('nilai');
            }

            foreach ($data as $row) {
                $kode = $row->kode_kriteria;
                $nilaiNorm = $sum[$kode] ? $row->nilai / $sum[$kode] : 0;
                $nilaiNorm = round($nilaiNorm, 5);
                $normalisasi[$row->kode_alternatif][$kode] = $nilaiNorm;

                $nilaiTerbobot = $nilaiNorm * ($bobot[$kode] ?? 0);
                $nilaiTerbobot = round($nilaiTerbobot, 5);
                $terbobot[$row->kode_alternatif][$kode] = $nilaiTerbobot;
            }

            // Hitung Si+ dan Si-
            foreach ($terbobot as $alt => $kriteriaValues) {
                $SiPlus = 0;
                $SiMin  = 0;
                foreach ($kriteriaValues as $k => $val) {
                    if (($jenis[$k] ?? 'benefit') === 'benefit') {
                        $SiPlus += $val;
                    } else {
                        $SiMin += $val;
                    }
                }
                $Si[$alt] = [
                    'Si+' => round($SiPlus, 5),
                    'Si-' => round($SiMin, 5)
                ];
            }

            // Hitung Qi
            $sumSiMin   = array_sum(array_column($Si, 'Si-')); 
            $sumInverse = array_sum(array_map(fn($x) => $x['Si-'] > 0 ? 1/$x['Si-'] : 0, $Si)); 

            foreach ($Si as $alt => $val) {
                if ($val['Si-'] > 0) {
                    $pembagi  = $val['Si-'] * $sumInverse;
                    $costPart = $sumSiMin / $pembagi;
                    $Qi       = $val['Si+'] + $costPart;
                } else {
                    $Qi = $val['Si+'];
                }
                $Si[$alt]['Qi'] = round($Qi, 5);
            }

            // Hitung Ui
            $maxQi = max(array_column($Si, 'Qi'));
            foreach ($Si as $alt => $val) {
                $Ui = $maxQi > 0 ? ($val['Qi'] / $maxQi) * 100 : 0;
                $Ui = round($Ui, 5);
                $Si[$alt]['Ui'] = $Ui;

                // Simpan Ui akhir ke database
                Copras::updateOrCreate(
                    ['kode_wilayah' => $w, 'kode_alternatif' => $alt],
                    ['nilai_copras' => $Ui]
                );
            }

            $hasilProses[$w] = [
                'normalisasi' => $normalisasi,
                'terbobot'    => $terbobot,
                'Si'          => $Si
            ];
        }

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

        return view('admin.copras', compact('wilayah', 'copras', 'hasilProses'));
    }
}

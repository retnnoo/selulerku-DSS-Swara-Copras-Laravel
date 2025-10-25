<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use App\Models\Alternatif;
use App\Models\User;
use App\Models\Wilayah;
use App\Models\Copras;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(){
        $countKriteria   = Kriteria::count();
        $countAlternatif = Alternatif::count();
        $countAdmin      = User::count();
        $countWilayah    = Wilayah::count();

        $wilayahList = Wilayah::all();
        $rankingPerWilayah = [];
        foreach ($wilayahList as $wil) {
            $ranking = Copras::with('alternatif')
                ->where('kode_wilayah', $wil->kode_wilayah)
                ->orderByDesc('nilai_copras')
                ->get()
                ->map(function ($row) {
                    return [
                        'name'  => $row->alternatif->nama_alternatif ?? $row->kode_alternatif,
                        'score' => round($row->nilai_copras, 2),
                    ];
                });

            $rankingPerWilayah[$wil->nama_wilayah] = $ranking;
        }

        return view('admin.home_admin', compact(
            'countKriteria',
            'countAlternatif',
            'countAdmin',
            'countWilayah',
            'rankingPerWilayah'
        ));
    }
}

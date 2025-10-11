<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wilayah;
use App\Models\Copras;
use Illuminate\Support\Facades\Log;

class UserController extends Controller{
    public function index(Request $request){
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
                        'logo'  => $row->alternatif->logo ?? 'default.png',
                    ];
                });

            $rankingPerWilayah[$wil->nama_wilayah] = $ranking;
        }

    

        $defaultWilayah = $wilayahList->first()->nama_wilayah ?? 'Gang Buntu';
        Log::info('wilayah : ' . $defaultWilayah);
        $defaultRanking = $rankingPerWilayah[$defaultWilayah] ?? collect();
        $best = $defaultRanking->first();

        return view('beranda', compact(
            'rankingPerWilayah',
            'wilayahList',
            'defaultWilayah',
            'best'
        ));
    }
}

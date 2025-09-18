<?php

namespace App\Http\Controllers;

use App\Models\Ahli;
use App\Models\Alternatif;
use App\Models\Bobot;
use App\Models\Kriteria;
use App\Models\NilaiAhli;
use App\Models\User;
use App\Models\Wilayah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    public function create(){
        return view('admin.add_admin');
    }

    public function storeAdmin(Request $request){
        $data = $request->validate([
            'name' => 'required|min:5|max:24|unique:users',
            'password' => 'required|min:8|max:255',

        ]);

        try {
            $data['password'] = bcrypt($data['password']);
            User::create($data);
            return redirect()->route('')->with('success', 'Data berhasil disimpan!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Gagal menambahkan data: ' . $e->getMessage()]);
        }
    }

    public function index(){
        return view('admin.home_admin');
    }

    public function indexKriteria(){
        $kriteria = Kriteria::all();
        return view('admin.data_kriteria', compact('kriteria'));
    }

    public function storeKriteria(Request $request){
        $data = $request->validate([
            'kode_kriteria' => 'required|max:5|string',
            'nama_kriteria' => 'required|max:30|string',
            'jenis_kriteria' => 'required|in:benefit,cost',
        ]);

        

        Kriteria::create($data);
        return redirect()->route('kriteria')->with('success', 'Data berhasil disimpan!');
    }

    public function updateKriteria(Request $request, $kode_kriteria){
        $data = $request->validate([
            'nama_kriteria' => 'required|max:30|string',
            'jenis_kriteria' => 'required|in:benefit,cost',
        ]);

       $kriteria = Kriteria::findOrFail($kode_kriteria);
       $kriteria->update($data);

        return redirect()->route('kriteria')->with('success', 'Data berhasil diedit!');
    }

     public function deleteKriteria($kode_kriteria)
    {
        try {
            $deleted = Kriteria::where('kode_kriteria', $kode_kriteria)->delete();

            return redirect()->route('kriteria')->with('success', 'Data berhasil dihapus!');
            // if (!$deleted) {
            //     return response()->json(['message' => 'Data tidak ditemukan'], 404);
            // }
            // return response()->json(['message' => 'Data berhasil dihapus']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal menghapus data: ' . $e->getMessage()], 500);
        }
    }

    public function indexAhli(){
        $ahli =  Ahli::orderByRaw('CAST(SUBSTRING(kode_ahli, 2) AS UNSIGNED) ASC')->get();
        $kriteria = Kriteria::all();

        return view('admin.data_ahli', compact('ahli', 'kriteria'));
    }

    public function storeAhli(Request $request){
       $request->validate([
            'nilai' => 'required|array',
        ]);

        // Ambil kode terakhir
       $last = Ahli::orderByRaw('CAST(SUBSTRING(kode_ahli, 2) AS UNSIGNED) DESC')->first();


        if ($last) {
            // Ambil angka setelah huruf P
            $num = (int) substr($last->kode_ahli, 1);
            $newKode = 'P' . ($num + 1);
        } else {
            // Kalau belum ada data, mulai dari P1
            $newKode = 'P1';
        }

        $ahli = Ahli::create([
            'kode_ahli' => $newKode,
        ]);

        foreach ($request->nilai as $kode_kriteria => $val) {
            DB::table('nilai_ahli')->insert([
                'kode_ahli' => $ahli->kode_ahli,
                'kode_kriteria' => $kode_kriteria,
                'nilai' => $val,
            ]);
        }

        return redirect()->route('ahli')->with('success', 'Data ahli berhasil ditambahkan!');
    }

    public function updateAhli(Request $request, $kode_ahli){
        // dd($kode_ahli); 
         $data = $request->validate([
            'nilai' => 'required|array',
        ]);


        // Cari data ahli berdasarkan id
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

        return redirect()->route('ahli')->with('success', 'Data berhasil diperbarui!');
    }
    
    public function deleteAhli($kode_ahli){
        $ahli = Ahli::findOrFail($kode_ahli);
        $ahli->delete();

        return redirect()->route('ahli')->with('success', 'Data ahli berhasil dihapus!'); 
    }



    public function indexPembobotanSwara(){
        // 1. Ambil semua data
        $kriteria = Kriteria::pluck('kode_kriteria')->toArray();
        $swara = Bobot::pluck('kode_kriteria')->toArray();
        $ahli = Ahli::all();

        // 2. Sinkronisasi tabel swara dengan kriteria
        $tambahData = array_diff($kriteria, $swara);
        foreach ($tambahData as $row) {
            Bobot::create(['kode_kriteria' => $row, 'bobot_kriteria' => 0]);
        }

        $hapusData = array_diff($swara, $kriteria);
        Bobot::whereIn('kode_kriteria', $hapusData)->delete();

        // Refresh data bobot
        $swaraData = Bobot::all();

        // --- PAKAI ARRAY, BUKAN ATTRIBUT MODEL ---
        $calc = [];
        foreach ($swaraData as $row) {
            $kriteriaName = $row->kode_kriteria;

            // Ambil rata-rata dari tabel nilai_ahli untuk kode_kriteria ini
            $avg = NilaiAhli::where('kode_kriteria', $kriteriaName)->avg('nilai');
            // jika null (tidak ada data), pakai 0
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
       $request->validate([
            'nama_alternatif' => 'string',
            'nilai' => 'required|array',
            'kode_wilayah' => 'string'
        ]);

        // Ambil kode terakhir
       $last = Alternatif::orderByRaw('CAST(SUBSTRING(kode_alternatif, 2) AS UNSIGNED) DESC')->first();
       


        if ($last) {
            $num = (int) substr($last->kode_alternatif, 1);
            $newKode = 'A' . ($num + 1);
        } else {
            $newKode = 'A1';
        }

        $alternatif = Alternatif::create([
            'kode_alternatif' => $newKode,
            'nama_alternatif' => $request->nama_alternatif

        ]);

        foreach ($request->nilai as $kode_kriteria => $val) {
            DB::table('nilai_alternatif')->insert([
                'kode_alternatif' => $alternatif->kode_alternatif,
                'kode_kriteria' => $kode_kriteria,
                'kode_wilayah' => $request->kode_wilayah,
                'nilai' => $val,
            ]);
        }

        return redirect()->route('alternatif')->with('success', 'Data ahli berhasil ditambahkan!');
    }

    public function indexCopras(){
        return view('admin.copras');
    }


    public function indexwilayah(){
        $wilayah = Wilayah::all();
        return view('admin.wilayah', compact('wilayah'));
    }

    public function storewilayah(Request $request){
        $data = $request->validate([
            'kode_wilayah' => 'required|max:5|string',
            'nama_wilayah' => 'required|max:20|string',
        ]);

        Wilayah::create($data);
        return redirect()->route('wilayah')->with('success', 'Data berhasil disimpan!');
    }

    public function updatewilayah(Request $request, $kode_wilayah){
        $data = $request->validate([
            'nama_wilayah' => 'required|max:20|string',
        ]);

       $wilayah = Wilayah::findOrFail($kode_wilayah);
       $wilayah->update($data);

        return redirect()->route('wilayah')->with('success', 'Data berhasil diedit!');
    }

     public function deletewilayah($kode_wilayah)
    {
        try {
            $deleted = Wilayah::where('kode_wilayah', $kode_wilayah)->delete();

            return redirect()->route('wilayah')->with('success', 'Data berhasil dihapus!');
            // if (!$deleted) {
            //     return response()->json(['message' => 'Data tidak ditemukan'], 404);
            // }
            // return response()->json(['message' => 'Data berhasil dihapus']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal menghapus data: ' . $e->getMessage()], 500);
        }
    }
}

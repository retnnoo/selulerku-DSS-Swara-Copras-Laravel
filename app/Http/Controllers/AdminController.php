<?php

namespace App\Http\Controllers;

use App\Models\Ahli;
use App\Models\Bobot;
use App\Models\Kriteria;
use App\Models\NilaiAhli;
use App\Models\User;
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

    public function indexAhli(){
        $ahli = Ahli::with('nilai.kriteria')->get();
        $kriteria = Kriteria::all();

        return view('admin.data_ahli', compact('ahli', 'kriteria'));
    }

    public function storeAhli(Request $request){
       $request->validate([
            'nilai' => 'required|array',
        ]);

        // Ambil kode terakhir
        $last = Ahli::orderBy('kode_ahli', 'desc')->first();

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

    public function indexAlternatif(){
        return view('admin.data_alternatif');
    }

    public function indexCopras(){
        return view('admin.copras');
    }
}
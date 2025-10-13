<?php

namespace App\Http\Controllers;

use App\Models\Ahli;
use App\Models\Alternatif;
use App\Models\Bobot;
use App\Models\Kriteria;
use App\Models\NilaiAhli;
use App\Models\NilaiAlternatif;
use App\Models\User;
use App\Models\Wilayah;
use App\Models\Copras;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;


class AdminController extends Controller{

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

    public function indexKriteria(){
        $kriteria = Kriteria::all();
        return view('admin.data_kriteria', compact('kriteria'));
    }

    public function storeKriteria(Request $request)
    {
        $data = $request->validate([
            'kode_kriteria'  => 'required|max:5|string',
            'nama_kriteria'  => 'required|max:30|string',
            'jenis_kriteria' => 'required|in:benefit,cost',
        ]);

        try {
            Kriteria::create($data);
            return redirect()->route('kriteria')->with('success', 'Data berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->route('kriteria')->with('error', 'Gagal menambahkan data kriteria!');
        }
    }

    public function updateKriteria(Request $request, $kode_kriteria)
    {
        $data = $request->validate([
            'nama_kriteria'  => 'required|max:30|string',
            'jenis_kriteria' => 'required|in:benefit,cost',
        ]);

        try {
            $kriteria = Kriteria::findOrFail($kode_kriteria);
            $kriteria->update($data);

            return redirect()->route('kriteria')->with('success', 'Data berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->route('kriteria')->with('error', 'Gagal memeperbarui data!');
        }
    }

    public function deleteKriteria($kode_kriteria)
    {
        try {
            Kriteria::where('kode_kriteria', $kode_kriteria)->delete();
            return redirect()->route('kriteria')->with('success', 'Data berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('kriteria')->with('error', 'Gagal menghapus data!');
        }
    }


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
            // Buat kode alternatif baru
            $last = Alternatif::orderByRaw('CAST(SUBSTRING(kode_alternatif, 2) AS UNSIGNED) DESC')->first();
            $newKode = $last ? 'A' . ((int) substr($last->kode_alternatif, 1) + 1) : 'A1';

            // Buat data alternatif
            $alternatif = Alternatif::create([
                'kode_alternatif' => $newKode,
                'nama_alternatif' => $data['nama_alternatif'],
            ]);

            // Jika kode wilayah kosong, ambil default wilayah pertama
            $kodeWilayah = $data['kode_wilayah'] ?? Wilayah::orderByRaw('CAST(SUBSTRING(kode_wilayah, 2) AS UNSIGNED) ASC')
                                                        ->first()
                                                        ->kode_wilayah;

            // Simpan nilai kriteria
            foreach ($data['nilai'] as $kode_kriteria => $val) {
                DB::table('nilai_alternatif')->insert([
                    'kode_alternatif' => $alternatif->kode_alternatif,
                    'kode_kriteria'   => $kode_kriteria,
                    'kode_wilayah'    => $kodeWilayah,
                    'nilai'           => $val,
                ]);
            }

            return redirect()->route('alternatif')->with('success', 'Data alternatif berhasil ditambahkan!');
        } catch (\Exception $e) {
            // Optional: bisa log $e->getMessage() untuk debugging
            return redirect()->route('alternatif')->with('error', 'Gagal menambahkan data alternatif!');
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


    public function indexwilayah(){
        $wilayah = Wilayah::all();
        return view('admin.wilayah', compact('wilayah'));
    }

    public function storeWilayah(Request $request){
        $data = $request->validate([
            'kode_wilayah' => 'required|max:5|string',
            'nama_wilayah' => 'required|max:20|string',
        ]);

        try {
            Wilayah::create($data);
            return redirect()->route('wilayah')->with('success', 'Data berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->route('wilayah')->with('error', 'Gagal menambahkan data wilayah!');
        }
    }

    public function updateWilayah(Request $request, $kode_wilayah){
        $data = $request->validate([
            'nama_wilayah' => 'required|max:20|string',
        ]);

        try {
            $wilayah = Wilayah::findOrFail($kode_wilayah);
            $wilayah->update($data);

            return redirect()->route('wilayah')->with('success', 'Data berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->route('wilayah')->with('error', 'Gagal memperbarui data wilayah!');
        }
    }

    public function deleteWilayah($kode_wilayah){
        try {
            Wilayah::where('kode_wilayah', $kode_wilayah)->delete();
            return redirect()->route('wilayah')->with('success', 'Data berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('wilayah')->with('error', 'Gagal menghapus data!');
        }
    }

    public function indexAdmin(){
        $user = User::all();
        return view('admin.add_admin', compact('user'));
    }

    public function storeAdmin(Request $request){
        $data = $request->validate([
            'name' => 'required|min:8|max:24|unique:users',
            'password' => 'required|min:8|max:16',
        ]);

        try {
            $data['password'] = bcrypt($data['password']);
            User::create($data);
            return redirect()->route('admin')->with('success', 'Data berhasil disimpan!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan data: ' . $e->getMessage());
        }
    }

    public function updateAdmin(Request $request, $id){
        $data = $request->validate([
            'name'     => 'required|min:8|max:24|unique:users,name,' . $id,
            'password' => 'nullable|min:8|max:16',
        ]);

        try {
            $user = User::findOrFail($id);
            $user->name = $data['name'];

            if (!empty($data['password'])) {
                $user->password = Hash::make($data['password']);
            }

            $user->save();

            return redirect()->route('admin')->with('success', 'Data berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    public function deleteAdmin($id){
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return redirect()->route('admin')->with('success', 'Data berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('admin')->with('error', 'Gagal menghapus data!');
        }
    }


    public function dashboard() {
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
                        'name' => optional($row->alternatif)->nama_alternatif ?? $row->kode_alternatif,
                        'score' => round($row->nilai_copras, 2)
                    ];
                });
            $rankingPerWilayah[$wil->nama_wilayah] = $ranking;
        }

        return view('admin.dashboard', compact('countKriteria','countAlternatif','countAdmin','countWilayah','rankingPerWilayah'));
    }


    public function boot(){
        View::share('countKriteria', Kriteria::count());
        View::share('countAlternatif', Alternatif::count());
        View::share('countAdmin', User::count());
        View::share('countWilayah', Wilayah::count());
    }
    

}

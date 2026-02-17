<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Data Ahli</title>
  <!--icon-->
  <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@300;400;500;700&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @vite('resources/css/app.css')
</head>

<body class="bg-slate-50 text-slate-900">
  <div class="min-h-screen flex">
    <!-- Sidebar -->
    <x-sidebar></x-sidebar>

    <div class="flex-1 min-w-0 lg:ml-64">
      <!-- Topbar -->
      <x-topbar></x-topbar>

      <!-- Main -->
      <main class="max-w-[1200px] mx-auto px-4 lg:px-8 py-6 space-y-6">
        <h1 class="text-3xl font-bold text-(--warna1)">Daftar Ahli</h1>
        <p class="text-slate-500 mb-10">
          Berikut merupakan daftar ahli atau pengambil keputusan yang berperan dalam proses penilaian kriteria pada sistem pengambilan keputusan. 
          Pengambil keputusan merupakan pengguna operator seluler dari setiap wilayah yang memberikan penilaian berdasarkan pengalaman dan pengetahuan mereka. 
          Hasil penilaian tersebut digunakan sebagai dasar dalam menentukan bobot kriteria agar sistem dapat menghasilkan keputusan yang lebih akurat.
        </p>

        <div class="bg-white rounded-xl p-6 border border-slate-200 shadow-lg">
          <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-semibold text-(--warna1)">Tabel Ahli</h2>
            <button 
              onclick="openModal('modalTambah')"  
              class="bg-gradient-to-r from-blue-400 to-(--warna1) hover:from-blue-400 hover:to-blue-400 text-white font-bold px-4 py-2 rounded-lg shadow transition">
              <i class="fa-solid fa-plus text-xl"></i>
              <span class="hidden md:inline">Tambah Ahli</span>
            </button>
          </div>

          <!-- Tabel -->
          <div class="overflow-x-auto">
            <table id="kriteriaTable" class="display w-full text-sm text-slate-700">
              <thead class="bg-gradient-to-r from-blue-400 to-(--warna1)">
                <tr>
                    <th class="px-4 py-3 text-center">No</th>
                    <th class="px-4 py-3 text-center">Kode Ahli</th>
                        @foreach ($kriteria as $k)
                            <th class="px-4 py-3 text-center">{{ $k->nama_kriteria }}</th>
                        @endforeach
                  <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($ahli as $a)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $a->kode_ahli }}</td>
                    @foreach ($kriteria as $k)
                        @php
                            $nilai = $a->nilai->firstWhere('kode_kriteria', $k->kode_kriteria);
                        @endphp
                        <td class="text-center">
                            {{ $nilai ? $nilai->nilai : '-' }}
                        </td>
                    @endforeach
                        <td  
                          class="text-center">
                          <button onclick="openModalEdit('{{ $a->kode_ahli }}', {{ $a->nilai->pluck('nilai', 'kode_kriteria') }})" class="px-3 py-1 text-xs font-medium bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition my-2">
                            <i class="fa-solid fa-pen-to-square text-xl"></i>
                          </button>
                          <form id="delete-form-{{ $a->kode_ahli }}" action="{{ route('delete.ahli', $a->kode_ahli) }}" method="POST" style="display:inline;">
                              @csrf
                              @method('DELETE')
                              <button type="button" onclick="konfirmasiHapus('delete-form-{{ $a->kode_ahli }}')" 
                                  class="btn-hapus px-3 py-1 text-xs font-medium bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition">
                                  <i class="fa-solid fa-trash text-xl"></i>
                              </button>
                          </form>
                        </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </main>
    </div>
  </div>

<!-- Modal Tambah -->
<div id="modalTambah" class="hidden fixed inset-0 z-50 bg-black/50 items-center justify-center overflow-hidden">
  <div class="bg-white rounded-lg shadow-lg w-full max-w-md max-h-[90vh] overflow-y-auto p-6">
    <h3 class="text-xl font-bold text-(--warna1) mb-4">Tambah Nilai Ahli</h3>
    <form action="{{ route('store.ahli') }}" method="POST">
      @csrf
      @foreach ($kriteria as $k)
        <label class="font-semibold block mb-1">{{ $k->nama_kriteria }}</label>
        <input type="number" placeholder="Nilai {{ $k->nama_kriteria }}" name="nilai[{{ $k->kode_kriteria }}]" 
          class="w-full border border-gray-300 px-3 py-2 rounded mb-3 focus:ring-2 focus:ring-blue-400 focus:outline-none">    
      @endforeach
      <div class="flex justify-end gap-2 mt-4">
        <button type="button" onclick="closeModal('modalTambah')" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300 transition">
          Batal
        </button>
        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition">
          Simpan
        </button>
      </div>
    </form>
  </div>
</div>

<!-- Modal Edit -->
<div id="modalEdit" class="hidden fixed inset-0 z-50 bg-black/50 items-center justify-center overflow-hidden">
  <div class="bg-white rounded-lg shadow-lg w-full max-w-md max-h-[90vh] overflow-y-auto p-6">
    <h3 class="text-xl font-bold text-yellow-500 mb-4">Edit Kriteria</h3>
    <form method="POST">
      @csrf
      @foreach ($kriteria as $k)
        <label class="font-semibold block mb-1">{{ $k->nama_kriteria }}</label>
        <input type="number" placeholder="Nilai {{ $k->nama_kriteria }}" name="nilai[{{ $k->kode_kriteria }}]" value="" 
          class="w-full border border-gray-300 px-3 py-2 rounded mb-3 focus:ring-2 focus:ring-yellow-400 focus:outline-none">    
      @endforeach
      <div class="flex justify-end gap-2 mt-4">
        <button type="button" onclick="closeModal('modalEdit')" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300 transition">
          Batal
        </button>
        <button type="submit" class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600 transition">
          Update
        </button>
      </div>
    </form>
  </div>
</div>

<x-loading></x-loading>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
    // Flash success dari session
    window.flashSuccess = @json(session('success'));

    // Flash error dari session atau validasi
    @if ($errors->any())
        let messages = "";
        @foreach ($errors->all() as $error)
            messages += "{{ $error }}\n";
        @endforeach
        window.flashError = messages;
    @else
        window.flashError = @json(session('error'));
    @endif
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('/js/script.js') }}"></script>

<script>
  function openModalEdit(kodeAhli, nilaiData) {
    const modal = document.getElementById('modalEdit');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.classList.add('overflow-hidden');

    for (const kodeKriteria in nilaiData) {
        const input = modal.querySelector(`input[name="nilai[${kodeKriteria}]"]`);
        if (input) {
            input.value = nilaiData[kodeKriteria];
        }
    }
    document.querySelector('#modalEdit form').action = "{{ url('/dashboard-admin/ahli/update-data') }}/" + kodeAhli;
  }

</script>
</body>
</html>

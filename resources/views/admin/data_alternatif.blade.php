<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Data Alternatif</title>
  <!--icon-->
  <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@300;400;500;700&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">
  <!--select-->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
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
        <h1 class="text-3xl font-bold text-(--warna1)">Daftar Alternatif</h1>
        <p class="text-slate-500 mb-10">
          Operator berikut merupakan alternatif dalam proses pengambilan keputusan di setiap wilayah.
          Setiap alternatif dinilai berdasarkan sejumlah kriteria, yang mencerminkan performa operator 
          pada masing-masing faktor penilaian.
        </p>

            <!-- Bagian Pilih Wilayah -->
            <div class="max-w-md flex-col justify-start">
                <p class="text-gray-500 text-base md:text-lg font-medium mb-4">Pilih wilayah</p>
                <form action="{{ route('alternatif') }}" method="GET" id="formWilayah">
                <select id="wilayah" name="wilayah"
                    class="select2 w-full rounded-lg border border-gray-300 p-3 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @foreach ($wilayah as $items )
                       <option value="{{$items->kode_wilayah}}"  {{ request('wilayah') == $items->kode_wilayah ? 'selected' : '' }}>{{$items->nama_wilayah}}</option>
                    @endforeach
                </select>
                    </form>
            </div>

        <div class="bg-white rounded-xl p-6 border border-slate-200 shadow-lg">
          <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-semibold text-(--warna1)">Tabel Alternatif</h2>
            <button 
              onclick="openModal('modalTambah')"  
              class="bg-gradient-to-r from-blue-400 to-(--warna1) hover:from-blue-400 hover:to-blue-400 text-white font-bold px-4 py-2 rounded-lg shadow transition">
              <i class="fa-solid fa-plus text-xl"></i>
              <span class="hidden md:inline">Tambah Alternatif</span>
            </button>
          </div>

          <!-- Tabel -->
          <div class="overflow-x-auto">
            <table id="kriteriaTable" class="display w-full text-sm text-slate-700">
              <thead class="bg-gradient-to-r from-blue-400 to-(--warna1)">
                <tr>
                  <th class="px-4 py-3 text-center">Kode Alternatif</th>
                  <th class="px-4 py-3 text-center">Alternatif</th>
                @foreach ($kriteria as $k )
                     <th class="px-4 py-3 text-center">{{ $k->nama_kriteria }}</th>
                @endforeach
                  <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
              </thead>
              <tbody>
               @foreach ($alternatif as $a)
           @if ($a->nilaiAlternatif->isNotEmpty())
                 <tr>
                    <td class="text-center font-medium">{{ $a->kode_alternatif }}</td>
                    <td class="text-center font-medium">{{ $a->nama_alternatif }}</td>
                  @foreach ($kriteria as $k)
                      @php
                          $nilai = $a->nilaiAlternatif->firstWhere('kode_kriteria', $k->kode_kriteria);
                      @endphp
                      <td class="text-center">
                          {{ $nilai ? $nilai->nilai : '-' }}
                      </td>
                  @endforeach
                    <td class="text-center">
                  <button 
                    onclick="openModalEdit('{{ $a->kode_alternatif }}', {{ $a->nilaiAlternatif->pluck('nilai', 'kode_kriteria') }}, '{{ $a->nama_alternatif }}')"
                    class="px-3 py-1 text-xs font-medium bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition my-2">
                    <i class="fa-solid fa-pen-to-square text-xl"></i>
                  </button>
                      <form id="delete-form-{{ $a->kode_alternatif }}" action="{{route('delete.alternatif', $a->kode_alternatif)}}" method="POST">
                        @method('DELETE')
                        @csrf
                        <button type="button" onclick="konfirmasiHapus('delete-form-{{ $a->kode_alternatif }}')"
                          class="btn-hapus px-3 py-1 text-xs font-medium bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition">
                          <i class="fa-solid fa-trash text-xl"></i>
                        </button>
                      </form>
                    </td>
                  </tr>
                   @endif
               @endforeach
                  
              </tbody>
            </table>
          </div>
        </div>
      </main>
    </div>
  </div>

<div id="modalTambah" class="hidden fixed inset-0 bg-black/50 items-center justify-center z-50 overflow-y-auto">
  <div class="bg-white rounded-lg p-6 w-full max-w-md my-10 max-h-[90vh] overflow-y-auto">
    <h3 class="text-xl font-bold text-(--warna1) mb-4">Tambah Alternatif</h3>
    <form action="{{ route('store.alternatif') }}" method="POST">
      @csrf

      <label class="font-semibold">Nama Alternatif</label>
      <input type="text" placeholder="Nama Alternatif" name="nama_alternatif" class="w-full border px-3 py-2 rounded mb-3">

      @foreach ($kriteria as $k)
        <label class="font-semibold">{{ $k->nama_kriteria }}</label>
        <input type="text" placeholder="Nilai {{ $k->nama_kriteria }}" name="nilai[{{ $k->kode_kriteria }}]" class="w-full border px-3 py-2 rounded mb-3">
      @endforeach

      <input type="hidden" name="kode_wilayah" value="{{ request('wilayah') }}">

      <div class="flex justify-end gap-2 mt-4">
        <button type="button" onclick="closeModal('modalTambah')" 
                class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">
          Batal
        </button>
        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
          Simpan
        </button>
      </div>
    </form>
  </div>
</div>

<div id="modalEdit" class="hidden fixed inset-0 bg-black/50 items-center justify-center z-50 overflow-y-auto">
  <div class="bg-white rounded-lg p-6 w-full max-w-md my-10 max-h-[90vh] overflow-y-auto">
    <h3 class="text-xl font-bold text-yellow-600 mb-4">Edit Alternatif</h3>
    <form method="POST">
      @csrf

      <label class="font-semibold">Nama Alternatif</label>
      <input type="text" name="nama_alternatif" id="inputNamaAlternatif"
             class="w-full border px-3 py-2 rounded mb-3 input-edit">

      @foreach ($kriteria as $k)
        <label class="font-semibold">{{ $k->nama_kriteria }}</label>
        <input type="text" placeholder="Nilai {{ $k->nama_kriteria }}"
               name="nilai[{{ $k->kode_kriteria }}]" value=""
               class="w-full border px-3 py-2 rounded mb-3 input-edit">
      @endforeach

      <input type="hidden" name="kode_wilayah" value="{{ request('wilayah') }}">

      <div class="flex justify-end gap-2 mt-4">
        <button type="button" onclick="closeModal('modalEdit')" 
                class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">
          Batal
        </button>
        <button type="submit" 
                class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">
          Update
        </button>
      </div>
    </form>
  </div>
</div>

<!-- Loading Overlay -->
<x-loading></x-loading>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

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
    document.addEventListener('DOMContentLoaded', function() {
        const overlay = document.getElementById('loadingOverlay');
        const formWilayah = document.getElementById('formWilayah');

        // Inisialisasi Select2
        $('#wilayah').select2({
            placeholder: "Pilih Wilayah",
            width: '100%',
        });

        // Event khusus Select2
        $('#wilayah').on('change.select2', function() {
            if (overlay) {
                overlay.classList.remove('invisible','opacity-0');
                overlay.style.pointerEvents = 'none'; // biar submit tetap jalan
            }
            formWilayah.submit();
        });
    });

    
    $(document).ready(function () {
      $('#kriteriaTable').DataTable({
        paging: true,
        searching: true,
        ordering: true,
        info: true,
        lengthMenu: [10, 20],
        language: {
          lengthMenu: "Tampilkan _MENU_ entri",
          search: "Cari:",
          info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
          paginate: {
            previous: "«",
            next: "»"
          }
        }
      });
    });
  
  // --- Sidebar Toggle ---
  const sidebar = document.getElementById('sidebar');
  const overlay = document.getElementById('overlay');
  const sidebarBtn = document.getElementById('sidebarBtn');

  function openSidebar() {
    sidebar.classList.remove('hidden');
    sidebar.classList.add('flex','flex-col');
    overlay.classList.remove('hidden');
  }

  function closeSidebar() {
    sidebar.classList.add('hidden');
    sidebar.classList.remove('flex','flex-col');
    overlay.classList.add('hidden');
  }

  sidebarBtn?.addEventListener('click', openSidebar);
  overlay?.addEventListener('click', closeSidebar);

  const links = document.querySelectorAll("nav a");
  const currentPath = window.location.pathname;

  links.forEach(link => {
    if (link.getAttribute("href") === currentPath) {
      link.classList.add("bg-blue-100", "text-blue-500");
    }
  });

  function openModal(id) {
    const modal = document.getElementById(id);
    modal.classList.remove("hidden");
    modal.classList.add("flex");
    document.body.classList.add('overflow-hidden');
  }

  function openModalEdit(kodeAlternatif, nilaiData, namaAlternatif) {
    const modal = document.getElementById('modalEdit');
    modal.classList.add("flex");
    modal.classList.remove("hidden");
    document.body.classList.add('overflow-hidden');

    modal.querySelectorAll('.input-edit').forEach(input => {
        input.value = '';
    });

    
     // Isi nama alternatif
    document.getElementById('inputNamaAlternatif').value = namaAlternatif;

    // Isi nilai tiap kriteria
    for (const kodeKriteria in nilaiData) {
        const input = modal.querySelector(`input[name="nilai[${kodeKriteria}]"]`);
        if (input) {
            input.value = nilaiData[kodeKriteria];
        }
    }
    document.querySelector('#modalEdit form').action = "{{ url('/dashboard-admin/alternatif/update-data') }}/" + kodeAlternatif;

  }

  function closeModal(id) {
    const modal = document.getElementById(id);
    modal.classList.add("hidden");
    modal.classList.remove("flex");
    document.body.classList.remove('overflow-hidden');
  }

    document.addEventListener('DOMContentLoaded', function() {
    const overlay = document.getElementById('loadingOverlay');

    // Tangkap semua form
    const semuaForm = document.querySelectorAll('form');

    semuaForm.forEach(form => {
      form.addEventListener('submit', function(e) {
        // Tampilkan overlay
        overlay.classList.remove('invisible', 'opacity-0');
        overlay.classList.add('pointer-events-auto');
      });
    });
  });

  document.addEventListener('DOMContentLoaded', function() {
    const overlay = document.getElementById('loadingOverlay');
    const tombolHapus = document.querySelectorAll('.btn-hapus');

    tombolHapus.forEach(btn => {
      btn.addEventListener('click', function() {
        // Tampilkan overlay
        overlay.classList.remove('invisible', 'opacity-0');
        overlay.classList.add('pointer-events-auto');
      });
    });
  });

</script>
</body>
</html>

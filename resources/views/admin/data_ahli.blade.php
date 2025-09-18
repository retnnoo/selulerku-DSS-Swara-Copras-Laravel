<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Data Ahli</title>
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
        <p class="text-slate-500 mb-5">
          Daftar kriteria yang digunakan untuk pengambilan keputusan.
        </p>

        <div class="bg-white rounded-xl p-6 border border-slate-200 shadow-lg">
          <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-semibold text-(--warna1)">Tabel Ahli</h2>
            <button 
              onclick="openModal('modalTambah')"  
              class="bg-gradient-to-r from-blue-400 to-(--warna1) hover:from-blue-400 hover:to-blue-400 text-white font-medium px-4 py-2 rounded-lg shadow transition">
              Tambah Ahli
            </button>
          </div>

          <!-- Tabel -->
          <div class="overflow-x-auto">
            <table id="kriteriaTable" class="display w-full text-sm text-slate-700">
              <thead class="bg-gradient-to-r from-blue-400 to-(--warna1)">
                <tr>
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
                <td>{{ $a->kode_ahli }}</td>
                @foreach ($kriteria as $k)
                    @php
                        $nilai = $a->nilai->firstWhere('kode_kriteria', $k->kode_kriteria);
                    @endphp
                    <td class="text-center">
                        {{ $nilai ? $nilai->nilai : '-' }}
                    </td>
                @endforeach
                    <td class="text-center">
                    <button class="px-3 py-1 text-xs font-medium bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition">Edit</button>
                    <button class="px-3 py-1 text-xs font-medium bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition">Hapus</button>
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

  <!--Modal Tambah--->
  <div id="modalTambah" class="hidden fixed inset-0 bg-black/50 items-center justify-center z-50">
  <div class="bg-white rounded-lg p-6 w-full max-w-md">
    <h3 class="text-lg font-semibold mb-4">Tambah Nilai Ahli</h3>
    <form action="{{ route('store.ahli') }}" method="POST">
        @csrf
        @foreach ( $kriteria as $k )
              <input type="text" placeholder="Nilai {{ $k->nama_kriteria }}" name="nilai[{{ $k->kode_kriteria }}]" class="w-full border px-3 py-2 rounded mb-3">    
        @endforeach
      <div class="flex justify-end gap-2">
        <button type="button" onclick="closeModal('modalTambah')" 
                class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Batal</button>
        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Simpan</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal Edit -->
<div id="modalEdit" class="hidden fixed inset-0 bg-black/50 items-center justify-center z-50">
  <div class="bg-white rounded-lg p-6 w-full max-w-md">
    <h3 class="text-lg font-semibold mb-4">Edit Kriteria</h3>
    <form>
      <input type="text" value="C1" class="w-full border px-3 py-2 rounded mb-3">
      <input type="text" value="Kecepatan Sinyal" class="w-full border px-3 py-2 rounded mb-3">
      <input type="text" value="C1" class="w-full border px-3 py-2 rounded mb-3">
      <input type="text" value="Kecepatan Sinyal" class="w-full border px-3 py-2 rounded mb-3">
      <input type="text" value="C1" class="w-full border px-3 py-2 rounded mb-3">
      <input type="text" value="Kecepatan Sinyal" class="w-full border px-3 py-2 rounded mb-3">
      <input type="text" value="C1" class="w-full border px-3 py-2 rounded mb-3">
      <input type="text" value="Kecepatan Sinyal" class="w-full border px-3 py-2 rounded mb-3">
      <div class="flex justify-end gap-2">
        <button type="button" onclick="closeModal('modalEdit')" 
                class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Batal</button>
        <button type="submit" class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">Update</button>
      </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>


<script>
    $(document).ready(function () {
      $('#kriteriaTable').DataTable({
        paging: true,
        searching: true,
        ordering: true,
        info: true,
        lengthMenu: [5, 10, 20],
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
  }

  function closeModal(id) {
    const modal = document.getElementById(id);
    modal.classList.add("hidden");
    modal.classList.remove("flex");
  }

</script>
</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Daftar Wilayah</title>
  <!--icon-->
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
        <h1 class="text-3xl font-bold text-(--warna1)">Daftar Wilayah</h1>
        <p class="text-slate-500 mb-10">
          Daftar wilayah atau lokasi yang menjadi cakupan sistem.
          Setiap wilayah berfungsi sebagai titik referensi untuk mengelola dan memantau aktivitas 
          pengambilan keputusan yang terkait dengan alternatif, serta kriteria seperti kecepatan sinyal, 
          cakupan jaringan, layanan operator, dan faktor terkait lainnya.
        </p>
        <div class="bg-white rounded-xl p-6 border border-slate-200 shadow-lg">
          <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-semibold text-(--warna1)">Tabel Wilayah</h2>
            <button 
              onclick="openModal('modalTambah')"  
              class="bg-gradient-to-r from-blue-400 to-(--warna1) hover:from-blue-400 hover:to-blue-400 text-white font-bold px-4 py-2 rounded-lg shadow transition">
              <i class="fa-solid fa-plus text-xl"></i>
              <span class="hidden md:inline">Tambah Wilayah</span>
            </button>
          </div>

          <!-- Tabel -->
          <div class="overflow-x-auto">
            <table id="kriteriaTable" class="display w-full text-sm text-slate-700">
              <thead class="bg-gradient-to-r from-blue-400 to-(--warna1)">
                <tr>
                  <th class="px-4 py-3 text-center">No</th>
                  <th class="px-4 py-3 text-center">Kode Wilayah</th>
                  <th class="px-4 py-3 text-left">Nama Wilayah</th>
                  <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @foreach ( $wilayah as $items )
                  <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="text-center font-medium">{{ $items->kode_wilayah }}</td>
                    <td>{{ $items->nama_wilayah }}</td>
                    <td class="text-center">
                      <button onclick="openModal('modalEdit', this)"
                           data-id="{{ $items->kode_wilayah }}"
                           data-nama="{{ $items->nama_wilayah }}"
                      class="px-3 py-1 text-xs font-medium bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition my-2">
                        <i class="fa-solid fa-pen-to-square text-xl"></i>
                        <span class="hidden md:inline font-bold ml-1 text-base">Edit</span>
                      </button>
                      <form id="delete-form-{{ $items->kode_wilayah }}" action="{{ route('delete.wilayah', $items->kode_wilayah) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="button" onclick="konfirmasiHapus('delete-form-{{ $items->kode_wilayah }}')" 
                          class="btn-hapus px-3 py-1 text-xs font-medium bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition">
                          <i class="fa-solid fa-trash text-xl"></i>
                          <span class="hidden md:inline font-bold ml-1 text-base">Hapus</span>
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

  <!--Modal Tambah--->
  <div id="modalTambah" class="hidden fixed inset-0 bg-black/50 items-center justify-center z-50">
  <div class="bg-white rounded-lg p-6 w-full max-w-md">
    <h3 class="text-xl font-bold text-(--warna1) mb-4">Tambah Wilayah</h3>
    <form action="{{ route('store.wilayah') }}" method="POST">
      @csrf
      <label class="font-semibold">Kode Wilayah</label>
      <input type="text" placeholder="Kode Wilayah" name="kode_wilayah" class="w-full border px-3 py-2 rounded mb-3">
      <label class="font-semibold">Nama Wilayah</label>
      <input type="text" placeholder="Nama wilayah" name="nama_wilayah" class="w-full border px-3 py-2 rounded mb-3">
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
    <h3 class="text-xl font-bold text-(--warna1) mb-4">Edit Wilayah</h3>
    <form method="POST">
      @csrf
      <label class="font-semibold">Nama Wilayah</label>
      <input type="text" id="edit_nama" name="nama_wilayah" class="w-full border px-3 py-2 rounded mb-3">
      <div class="flex justify-end gap-2">
        <button type="button" onclick="closeModal('modalEdit')" 
          class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Batal</button>
        <button type="submit" class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">Update</button>
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

  function openModal(id, button=null) {
    const modal = document.getElementById(id);
    modal.classList.remove("hidden");
    modal.classList.add("flex");

    if (button) {
      // Ambil data dari tombol
      const id = button.getAttribute('data-id');
      const nama = button.getAttribute('data-nama');

      // Isi ke input modal
      document.getElementById('edit_nama').value = nama;

      document.querySelector('#modalEdit form').action = "{{ url('/dashboard-admin/wilayah/update-data') }}/" + id;
    }
  }

  function closeModal(id) {
    const modal = document.getElementById(id);
    modal.classList.add("hidden");
    modal.classList.remove("flex");
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

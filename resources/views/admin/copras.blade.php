<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>COPRAS</title>
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
  <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
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
        <h1 class="text-3xl font-bold text-(--warna1)">Hasil Rangking Alternatif</h1>
        <p class="text-slate-500 mb-10">
          Berikut merupakan hasil perangkingan alternatif atau operator untuk setiap wilayah dengan metode COPRAS. 
          Metode COPRAS digunakan untuk mengevaluasi dan membandingkan setiap alternatif secara menyeluruh, 
          sehingga sistem dapat menentukan alternatif terbaik dengan mempertimbangkan semua kriteria secara proporsional.
        </p>

        <!-- Bagian Pilih Wilayah -->
            <div class="max-w-md flex-col justify-start">
              <p class="text-gray-500 text-base md:text-lg font-medium mb-4">Pilih wilayah</p>
              <form action="{{ route('copras') }}" method="GET" id="formWilayah">
                  <select id="wilayah" name="wilayah" onchange="document.getElementById('formWilayah').submit()"
                      class="select2 w-full rounded-lg border border-gray-300 p-3 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                      @foreach ($wilayah as $items)
                          <option value="{{ $items->kode_wilayah }}" {{ request('wilayah') == $items->kode_wilayah ? 'selected' : '' }}>
                              {{ $items->nama_wilayah }}
                          </option>
                      @endforeach
                  </select>
              </form>
          </div>

        <div class="bg-white rounded-xl p-6 border border-slate-200 shadow-lg">
          <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-semibold text-(--warna1)">Tabel Rangking</h2>
          </div>

          <!-- Tabel -->
          <div class="overflow-x-auto">
            <table id="kriteriaTable" class="display w-full text-sm text-slate-700">
              <thead class="bg-gradient-to-r from-blue-400 to-(--warna1)">
                <tr>
                  <th class="px-4 py-3 text-center">Kode Alternatif</th>
                  <th class="px-4 py-3 text-center">Nama Alternatif</th>
                  <th class="px-4 py-3 text-center">Skor COPRAS</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($copras as $row)
                  <tr>
                    <td class="text-center">{{ $row->kode_alternatif }}</td>
                    <td class="text-center">{{ $row->alternatif->nama_alternatif ?? '-' }}</td>
                    <td class="text-center">{{ number_format($row->nilai_copras, 2) }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </main>
    </div>
  </div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        $('#wilayah').select2({
            placeholder: "Pilih Wilayah",
            width: '100%',
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

</script>
</body>
</html>

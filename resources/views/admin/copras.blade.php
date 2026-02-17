<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Perangkingan Alternatif</title>
  <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@300;400;500;700&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">
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
          Berikut merupakan hasil perangkingan alternatif atau operator pada setiap wilayah menggunakan metode COPRAS. 
          Metode ini digunakan untuk mengevaluasi dan membandingkan seluruh alternatif berdasarkan bobot dan kriteria yang telah ditentukan, 
          sehingga sistem dapat menentukan operator terbaik secara proporsional dan objektif.
        </p>

        <!-- Bagian Pilih Wilayah -->
            <div class="max-w-md flex-col justify-start">
              <p class="text-gray-500 text-base md:text-lg font-medium mb-4">Pilih wilayah</p>
              <form action="{{ route('copras') }}" method="GET" id="formWilayah">
                  <select id="wilayah" name="wilayah"
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

<!-- Loading Overlay -->
<x-loading></x-loading>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
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
</script>
</body>
</html>

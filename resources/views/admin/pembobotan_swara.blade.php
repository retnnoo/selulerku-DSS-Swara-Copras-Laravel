<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Pembobotan Kriteria</title>
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
        <h1 class="text-3xl font-bold text-(--warna1)">Hasil Pembobotan SWARA</h1>
        <p class="text-slate-500 mb-10">
          Berikut ini adalah hasil pembobotan kriteria menggunakan metode SWARA.
          Metode ini menentukan bobot kepentingan setiap kriteria berdasarkan penilaian ahli, 
          sehingga setiap kriteria memberikan kontribusi yang proporsional dalam proses pengambilan 
          keputusan.
        </p>

        <div class="bg-white rounded-xl p-6 border border-slate-200 shadow-lg">
          <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-semibold text-(--warna1)">Bobot Kriteria</h2>
          </div>

          <!-- Tabel -->
          <div class="overflow-x-aut">
            <table id="kriteriaTable" class="display w-full text-sm text-slate-700">
              <thead class="bg-gradient-to-r from-blue-400 to-(--warna1)">
                <tr>
                  <th class="px-4 py-3 text-center">No</th>
                  <th class="px-4 py-3 text-center">Kriteria</th>
                  <th class="px-4 py-3 text-left">Bobot</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($data as $d )
                <tr>
                    <td class="text-center font-medium">{{ $loop->iteration }}</td>
                    <td class="text-center font-medium">{{ $d->kode_kriteria }}</td>
                    <td class="text-center font-medium">{{ $d->bobot_kriteria }}</td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </main>
    </div>
  </div>

  <x-loading></x-loading>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

</body>
</html>

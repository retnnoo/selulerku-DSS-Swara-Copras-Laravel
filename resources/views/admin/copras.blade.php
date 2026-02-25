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

          <div class="max-w-7xl mx-auto mt-6">
            <!-- Tabel Rangking COPRAS (tetap sama) -->
            <div class="bg-white rounded-xl p-6 border border-slate-200 shadow-lg mt-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-2xl font-semibold text-(--warna1)">Tabel Rangking</h2>
                </div>

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

            <!-- Tombol tampilkan detail -->
     
            <div class="mt-4">
                <form action="{{ route('copras') }}" method="GET">
                    <input type="hidden" name="wilayah" value="{{ request('wilayah') ?? "W1" }}">
                    <input type="hidden" name="detail" value="1">
                    <button type="submit" class="px-4 py-2 bg-(--warna1) text-white rounded-lg hover:bg-blue-700">
                        Tampilkan Detail
                    </button>
                </form>
            </div>
       
            @php
                $data = request('wilayah') && isset($hasilProses[request('wilayah')]) ? $hasilProses[request('wilayah')] : null;
            @endphp

            @if(request('detail') == 1 && $data)
                <!-- Normalisasi -->
                <div class="bg-white rounded-xl p-4 border border-gray-200 shadow-lg overflow-x-auto mt-6">
                    <h3 class="text-lg font-semibold mb-2 text-(--warna1)">Tabel Normalisasi</h3>
                    <table class="min-w-full text-sm text-white border border-gray-200">
                        <thead class="bg-(--warna1)">
                            <tr>
                                <th class="px-4 py-2 text-center">Alternatif</th>
                                @foreach(array_keys($data['normalisasi'][array_key_first($data['normalisasi'])]) as $k)
                                    <th class="px-4 py-2 text-center">{{ $k }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data['normalisasi'] as $alt => $vals)
                                <tr class="border-t text-slate-700 border-gray-200 hover:bg-gray-50">
                                    <td class="text-center font-medium">{{ $alt }}</td>
                                    @foreach($vals as $v)
                                        <td class="text-center">{{ round($v, 6) }}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Terbobot -->
                <div class="bg-white rounded-xl p-4 border border-gray-200 shadow-lg overflow-x-auto mt-6">
                    <h3 class="text-lg font-semibold mb-2 text-(--warna1)">Tabel Terbobot</h3>
                    <table class="min-w-full text-sm text-white border border-gray-200">
                        <thead class="bg-(--warna1)">
                            <tr>
                                <th class="px-4 py-2 text-center">Alternatif</th>
                                @foreach(array_keys($data['terbobot'][array_key_first($data['terbobot'])]) as $k)
                                    <th class="px-4 py-2 text-center">{{ $k }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data['terbobot'] as $alt => $vals)
                                <tr class="border-t text-slate-700 border-gray-200 hover:bg-gray-50">
                                    <td class="text-center font-medium">{{ $alt }}</td>
                                    @foreach($vals as $v)
                                        <td class="text-center">{{ round($v, 6) }}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">

                    <!-- Si+ / Si- -->
                    <div class="bg-white rounded-xl p-4 border border-gray-200 shadow-lg overflow-x-auto">
                        <h3 class="text-lg font-semibold mb-2 text-(--warna1)">Indeks Benefit & Cost (Si+ / Si-)</h3>
                        <table class="min-w-full text-sm text-white border border-gray-200">
                            <thead class="bg-(--warna1)">
                                <tr>
                                    <th class="px-4 py-2 text-center">Alternatif</th>
                                    <th class="px-4 py-2 text-center">Si+</th>
                                    <th class="px-4 py-2 text-center">Si-</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data['Si'] as $alt => $vals)
                                    <tr class="border-t text-slate-700 border-gray-200 hover:bg-gray-50">
                                        <td class="text-center font-medium">{{ $alt }}</td>
                                        <td class="text-center">{{ round($vals['Si+'], 6) }}</td>
                                        <td class="text-center">{{ round($vals['Si-'], 6) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Qi + Ui -->
                    <div class="bg-white rounded-xl p-4 border border-gray-200 shadow-lg overflow-x-auto">
                        <h3 class="text-lg font-semibold mb-2 text-(--warna1)">Bobot Relatif & Utilitas</h3>
                        <table class="min-w-full text-sm text-white border border-gray-200">
                            <thead class="bg-(--warna1)">
                                <tr>
                                    <th class="px-4 py-2 text-center">Alternatif</th>
                                    <th class="px-4 py-2 text-center">Qi</th>
                                    <th class="px-4 py-2 text-center">Ui</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data['Si'] as $alt => $vals)
                                    <tr class="border-t text-slate-700 border-gray-200 hover:bg-gray-50">
                                        <td class="text-center font-medium">{{ $alt }}</td>
                                        <td class="text-center">{{ round($vals['Qi'], 6) }}</td>
                                        <td class="text-center">{{ round($vals['Ui'], 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            @endif
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
          ordering: false,
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

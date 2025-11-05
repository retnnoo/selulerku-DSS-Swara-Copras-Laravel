<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
      <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@300;400;500;700&display=swap" rel="stylesheet">
    <!--select-->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <!--icon-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
     @vite('resources/css/app.css')
    <title>Beranda Utama</title>
</head>

<body class="bg-gray-50">
  <!-- Navbar -->
  <nav class="bg-white shadow-md fixed w-full h-20 z-10 top-0 left-0">
    <div class="max-w-7xl mx-auto px-4 mt-2 flex justify-between items-center h-16"> 
      <div class="flex items-center">
        <img src="./img/logo.png " alt="Logo Image" class="w-27 h-15 mr-2">
      </div>

      <a href="{{ route('login') }}" class="flex items-center rounded-lg text-(--warna1) hover:bg-blue-100 p-2 transition">
        <i class="fa-solid fa-right-to-bracket text-xl"></i>
        <span class="hidden md:inline font-bold ml-2 text-base">Login Admin</span>
      </a>

    </div>
  </nav>

  <section class="pt-20 bg-blue-50 mt-10 ">
    <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-2 gap-5 items-center">
      <div class="space-y-5">
        <p class="text-gray-500 uppercase tracking-wide font-medium">SMART NETWORK CHOICE</p>
        <h1 class="text-4xl md:text-5xl font-bold leading-tight text-(--warna1)">
          Temukan Operator Seluler Terbaik Untukmu
        </h1>
        <p class="text-gray-600">
          SelulerKu bantu kamu nemuin operator terbaik di wilayah Timbangan Indralaya Utara. 
          Mulai dari kecepatan sinyal, stabilitas sinyal, cakupan sinyal, harga paket, bonus, masa aktif, sampai layanan 
          operator semuanya dibandingin biar kamu dapet rekomendasi yang paling pas buat kebutuhan kamu. 
          Sistem ini merupakan sistem pendukung keputusan yang pakai metode SWARA buat nentuin seberapa penting tiap kriteria, 
          terus COPRAS buat nge-rangking operator, jadi rekomendasinya lebih akurat dan sesuai sama kebutuhanmu. Data yang digunakan 
          dalam sistem ini juga bisa diupdate secara otomatis oleh admin pengelola sistemnya.
        </p>

        <div class="grid grid-cols-3 md:grid-cols-6 gap-3 pt-4 mb-10">
          <div class="bg-white shadow rounded-lg flex items-center justify-center p-1">
            <img src="./img/telkomsel.png" alt="telkomsel" class="h-10">
          </div>
          <div class="bg-white shadow rounded-lg flex items-center justify-center p-1">
            <img src="./img/indosat.png" alt="Indosat" class="h-10">
          </div>
          <div class="bg-white shadow rounded-lg flex items-center justify-center p-1">
            <img src="./img/axis.png" alt="axis" class="h-10">
          </div>
          <div class="bg-white shadow rounded-lg flex items-center justify-center p-1">
            <img src="./img/xl.png" alt="xl" class="h-10">
          </div>
          <div class="bg-white shadow rounded-lg flex items-center justify-center p-1">
            <img src="./img/tri.png" alt="tri" class="h-10">
          </div>
          <div class="bg-white shadow rounded-lg flex items-center justify-center p-2">
            <img src="./img/smartfren.png" alt="smartfren" class="h-10">
          </div>
        </div>
      </div>

      <!-- Right Image -->
      <div class="flex justify-center md:justify-end">
        <img src="{{ asset('img/beranda.png') }}" alt="Hero Image" class="w-full max-w-md rounded-lg">
      </div>
    </div>
  </section>

  <section class="pt-20 bg-gray-50">
    <div class="max-w-5xl mx-auto px-6 space-y-12">
      <!-- Bagian Pilih Wilayah -->
      <div class="text-center">
        <h1 class="text-3xl md:text-4xl font-bold leading-tight text-(--warna1) mb-4">
          Temukan Rekomendasi Operator Paling Oke
        </h1>
        <p class="text-gray-500 text-base md:text-lg font-medium mb-6">
          Pilih wilayahmu!
        </p>
        <div class="max-w-md mx-auto">
          <label for="wilayah" class="sr-only">Pilih Wilayahmu</label>
          <select id="wilayah" name="wilayah"
            class="select2 w-full rounded-lg border border-gray-300 p-3 shadow-sm">
            @foreach($wilayahList as $wil)
              <option value="{{ $wil->nama_wilayah }}"
                {{ $wil->nama_wilayah == $defaultWilayah ? 'selected' : '' }}>
                {{ $wil->nama_wilayah }}
              </option>
            @endforeach
          </select>
        </div>
      </div>

      <!-- Grid Hasil Rekomendasi + Grafik -->
      <div class="max-w-7xl mx-auto px-4 mb-20">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">

          <!-- Bagian Hasil Rekomendasi -->
          <div class="text-center bg-white shadow-md rounded-2xl p-8 flex flex-col items-center justify-center">
            <h2 class="text-2xl font-bold mb-6">
              Rekomendasi Operator Terbaik di 
              <span id="wilayah-rekom" class="text-(--warna1)">{{ $defaultWilayah }}</span>
            </h2>
            <img id="operator-logo" src="./img/default.png" alt="Operator" class="h-16 mb-4">
            <h3 id="operator-nama" class="text-3xl font-extrabold">-</h3>
            <p class="mt-4 text-gray-600 text-base max-w-xl mx-auto">
              <span id="operator-nama2"></span> direkomendasikan karena memperoleh skor COPRAS tertinggi di wilayah ini,
              yang dihitung berdasarkan pertimbangan seluruh nilai kriteria yang meliputi kecepatan sinyal, stabilitas sinyal, 
              cakupan sinyal, harga paket, bonus, masa aktif, sampai layanan 
              operator dari data tahun 2025.
            </p>
          </div>

          <!-- Bagian Grafik Ranking -->
          <div class="bg-white shadow-md rounded-2xl p-8">
            <h2 class="text-2xl text-center font-bold text-gray-800 mb-2">
              Ranking Operator di <span id="wilayah-ranking">{{ $defaultWilayah }}</span>
            </h2>
            <p class="text-gray-600 mb-6 text-center">
              Lihat perbandingan semua operator dari yang paling unggul sampai yang masih perlu ditingkatkan
            </p>

            <div class="h-[250px]">
              <canvas id="chartRanking"></canvas>
            </div>
          </div>
        </div>
      </div>
    </div>
</section>

<footer class="bg-(--warna1) text-white py-4 text-center">
  <p class="text-sm">
    SelulerKu<span class="text-white">&copy; 2025</span> Universitas Sriwijaya.
  </p>
</footer>

<x-loading></x-loading>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
  <script>
    const rankingData = @json($rankingPerWilayah);
    let chartInstance = null;

    function renderWilayah(wilayah) {
      const ranking = rankingData[wilayah] || [];
      if (!ranking.length) return;

      // Update teks wilayah
      document.getElementById('wilayah-rekom').textContent = wilayah;
      document.getElementById('wilayah-ranking').textContent = wilayah;

      // Operator terbaik (skor tertinggi)
      const best = [...ranking].sort((a, b) => b.score - a.score)[0];
      if (best) {
        const logo = document.getElementById('operator-logo');
        document.getElementById('operator-nama').textContent = best.name;
        document.getElementById('operator-nama2').textContent = best.name;
        logo.src = `/img/${best.name.toLowerCase()}.png`;
        logo.alt = best.name;
      }

      const ctx = document.getElementById('chartRanking').getContext('2d');
      if (chartInstance) chartInstance.destroy();

      chartInstance = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: ranking.map(r => r.name),
          datasets: [{
            label: 'Skor',
            data: ranking.map(r => r.score),
            backgroundColor: 'rgba(43, 135, 255, 0.5)',
            borderColor: '#2b87ff',
            borderWidth: 1
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          scales: {
            y: { beginAtZero: true }
          }
        }
      });
    }

    $(document).ready(function() {
        $('.select2').select2();
        renderWilayah("{{ $defaultWilayah }}");

        $('#wilayah').on('change', function() {
            renderWilayah(this.value);
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        const overlay = document.getElementById('loadingOverlay');

        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function() {
                if (overlay) {
                    overlay.classList.remove('invisible','opacity-0');
                    overlay.style.pointerEvents = 'none'; // biar submit tetap jalan
                }
            });
        });
    });

  </script>
</body>
</html>
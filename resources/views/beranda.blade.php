<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
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
        <span class="hidden md:inline font-bold ml-2 text-base">Login</span>
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
          SelulerKu bantu kamu nemuin operator terbaik di wilayah Timbangan Indralaya Utara. Mulai dari kecepatan dan stabilitas sinyal, jangkauan, harga paket, bonus, masa aktif, sampai layanan operator semuanya dibandingin biar kamu dapet rekomendasi yang paling pas buat kebutuhan kamu.
        </p>

        <div class="grid grid-cols-6 gap-3 pt-4 mb-10">
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
            class="select2 w-full rounded-lg border border-gray-300 p-3 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">-- Pilih Wilayah --</option>
            <option value="wilayah2">Gang Buntu/Jalan Nusantara</option>
            <option value="wilayah3">Gang Lampung</option>
            <option value="wilayah4">Perumahan Griya Sejahtera</option>
            <option value="wilayah5">Timbangan</option>
            <option value="wilayah6">Sarjana</option>
          </select>
        </div>
      </div>

      <div class="max-w-7xl mx-auto px-4 mb-20">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
          <!-- Bagian Hasil Rekomendasi -->
          <div class="text-center bg-white shadow-md rounded-2xl p-8 flex flex-col justify-center">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">
              Rekomendasi Operator Terbaik di <span id="">Wilayah Kamu</span>
            </h2>
            <div class="flex flex-col items-center space-y-4">
              <img src="./img/telkomsel.png" alt="Operator-Terbaik" class="h-16">
              <h3 class="text-3xl font-extrabold text-(--warna1)">Telkomsel</h3>
              <p class="text-gray-600 max-w-md">
                Pilihan tersebut dipilh berdasarkan 
              </p>
            </div>
          </div>

          <!-- Bagian Grafik Ranking -->
          <div class="bg-white shadow-md rounded-2xl p-8">
            <h2 class="text-2xl text-center font-bold text-gray-800 mb-2">
              Ranking Operator di <span id="wilayah-ranking">Indralaya Utara</span>
            </h2>
            <p class="text-gray-600 mb-6 text-center">
              Lihat perbandingan semua operator dari yang paling unggul sampai yang masih perlu ditingkatkan
            </p>

            <!-- Grafik -->
            <div class="space-y-4 max-w-lg mx-auto">
              <div class="flex items-center">
                <span class="w-28 font-semibold text-blue-600">Telkomsel</span>
                <div class="flex-1 bg-gray-200 h-6 rounded-lg ml-2">
                  <div class="bg-blue-600 h-6 rounded-lg w-4/5"></div>
                </div>
                <span class="ml-2 text-gray-700 font-medium">80%</span>
              </div>

              <div class="flex items-center">
                <span class="w-28 font-semibold text-gray-700">XL</span>
                <div class="flex-1 bg-gray-200 h-6 rounded-lg ml-2">
                  <div class="bg-gray-500 h-6 rounded-lg w-3/5"></div>
                </div>
                <span class="ml-2 text-gray-700 font-medium">60%</span>
              </div>

              <div class="flex items-center">
                <span class="w-28 font-semibold text-gray-700">Indosat</span>
                <div class="flex-1 bg-gray-200 h-6 rounded-lg ml-2">
                  <div class="bg-gray-400 h-6 rounded-lg w-2/5"></div>
                </div>
                <span class="ml-2 text-gray-700 font-medium">40%</span>
              </div>

              <div class="flex items-center">
                <span class="w-28 font-semibold text-blue-600">Telkomsel</span>
                <div class="flex-1 bg-gray-200 h-6 rounded-lg ml-2">
                  <div class="bg-blue-600 h-6 rounded-lg w-4/5"></div>
                </div>
                <span class="ml-2 text-gray-700 font-medium">80%</span>
              </div>

              <div class="flex items-center">
                <span class="w-28 font-semibold text-gray-700">XL</span>
                <div class="flex-1 bg-gray-200 h-6 rounded-lg ml-2">
                  <div class="bg-gray-500 h-6 rounded-lg w-3/5"></div>
                </div>
                <span class="ml-2 text-gray-700 font-medium">60%</span>
              </div>

              <div class="flex items-center">
                <span class="w-28 font-semibold text-gray-700">Indosat</span>
                <div class="flex-1 bg-gray-200 h-6 rounded-lg ml-2">
                  <div class="bg-gray-400 h-6 rounded-lg w-2/5"></div>
                </div>
                <span class="ml-2 text-gray-700 font-medium">40%</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>




  <!--select-->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#wilayah').select2({
                placeholder: "Pilih Wilayah",
                width: '100%',
            });
        });
    </script>

</body>


</html>
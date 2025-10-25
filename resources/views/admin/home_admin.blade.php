<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Dashboard Admin</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@300;400;500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @vite('resources/css/app.css')
</head>
<body class="bg-slate-50 text-slate-900">
  <!-- App Shell -->
  <div class="min-h-screen flex">
    <x-sidebar></x-sidebar>
    <div class="flex-1 min-w-0 lg:ml-64">
      <x-topbar></x-topbar>
      <!-- Main -->
      <main class="max-w-[1200px] mx-auto px-4 lg:px-8 py-6 space-y-6">
        <!-- Heading -->
        <h1 class="text-3xl font-bold text-(--warna1)">Beranda Admin</h1>
        <p class="text-slate-500">Gambaran umum sistem & perangkingan operator seluler.</p>

        <!-- Stat Cards -->
        <section class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
          <div class="bg-gradient-to-r from-blue-100 to-blue-300 rounded-lg p-5 border border-slate-200 shadow">
            <span class="text-lg font-bold text-slate-700"><i class="fa-solid fa-list mr-2"></i>Jumlah Kriteria</span>
            <div class="mt-2 text-3xl font-bold text-slate-800" id="countKriteria">
              {{ $countKriteria }}
            </div>
          </div>

          <div class="bg-gradient-to-r from-orange-100 to-orange-300 rounded-lg p-5 border border-slate-200 shadow">
            <span class="text-lg font-bold text-slate-700"><i class="fa-solid fa-tower-cell mr-2"></i> Alternatif</span>
            <div class="mt-2 text-3xl font-bold text-slate-800" id="countAlternatif">
              {{ $countAlternatif }}
            </div>
          </div>

          <div class="bg-gradient-to-r from-red-100 to-red-300 rounded-lg p-5 border border-slate-200 shadow">
            <span class="text-lg font-bold text-slate-700"><i class="fa-solid fa-location-dot mr-2"></i> Wilayah</span>
            <div class="mt-2 text-3xl font-bold text-slate-800">
              {{ $countWilayah }}
            </div>
          </div>

          <div class="bg-gradient-to-r from-pink-100 to-pink-300 rounded-lg p-5 border border-slate-200 shadow">
            <span class="text-lg font-bold text-slate-700"><i class="fa-solid fa-user mr-2"></i>Jumlah Admin</span>
            <div class="mt-2 text-3xl font-bold text-slate-800" id="countAdmin">
              {{ $countAdmin }}
            </div>
          </div>
        </section>

        <section class="bg-white rounded-lg border border-slate-200 p-5 shadow">
          <h2 class="text-xl font-bold text-(--warna1) mb-3">Grafik Analisis Operator Seluler</h2>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($rankingPerWilayah as $wilayah => $ranking)
            <div>
              <h3 class="text-base font-medium mb-2">Wilayah {{ $wilayah }}</h3>
              <div class="h-[300px]">
                <canvas id="{{ Str::slug($wilayah, '') }}"></canvas>
              </div>
            </div>
            @endforeach
          </div>
        </section>
      </main>
    </div>
  </div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const rankingData = @json($rankingPerWilayah);
    Object.keys(rankingData).forEach(wilayah => {
        const ctx = document.getElementById(wilayah.replace(/[^a-zA-Z0-9]/g, '').toLowerCase());
        if (ctx) {
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: rankingData[wilayah].map(r => r.name),
                    datasets: [{
                        label: 'Skor',
                        data: rankingData[wilayah].map(r => r.score),
                        backgroundColor: 'rgba(43,135,255,0.5)',
                        borderColor: '#2b87ff',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: { y: { beginAtZero: true, ticks: { stepSize: 10 } } },
                    plugins: { legend: { display: false } }
                }
            });
        }
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

</script>
</body>
</html>

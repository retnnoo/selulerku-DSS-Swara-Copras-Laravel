<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Dashboard Admin</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
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
            <span class="text-sm text-slate-700">Jumlah Kriteria</span>
            <div class="mt-2 text-3xl font-bold text-slate-800" id="countKriteria">12</div>
          </div>

          <div class="bg-gradient-to-r from-orange-100 to-orange-300 rounded-lg p-5 border border-slate-200 shadow">
            <span class="text-sm text-slate-700">Jumlah Alternatif</span>
            <div class="mt-2 text-3xl font-bold text-slate-800" id="countAlternatif">48</div>
          </div>

          <div class="bg-gradient-to-r from-pink-100 to-pink-300 rounded-lg p-5 border border-slate-200 shadow">
            <span class="text-sm text-slate-700">Jumlah Admin</span>
            <div class="mt-2 text-3xl font-bold text-slate-800" id="countAdmin">5</div>
          </div>

          <div class="bg-gradient-to-r from-red-100 to-red-300 rounded-lg p-5 border border-slate-200 shadow">
            <span class="text-sm text-slate-700">Jumlah Wilayah</span>
            <div class="mt-2 text-3xl font-bold text-slate-800">5</div>
          </div>
        </section>

        <section class="bg-white rounded-lg border border-slate-200 p-5 shadow">
          <h2 class="text-xl font-bold text-(--warna1) mb-3">Grafik Analisis Operator Seluler</h2>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Baris 1 -->
            <div>
              <h3 class="text-base font-medium mb-2">Wilayah Gang Buntu</h3>
              <div class="h-[300px]"><canvas id="ggBuntu"></canvas></div>
            </div>
            <div>
              <h3 class="text-base font-medium mb-2">Wilayah Gang Lampung</h3>
              <div class="h-[300px]"><canvas id="ggLampung"></canvas></div>
            </div>

            <!-- Baris 2 -->
            <div>
              <h3 class="text-base font-medium mb-2">Wilayah Griya Sejahtera</h3>
              <div class="h-[300px]"><canvas id="griya"></canvas></div>
            </div>
            <div>
              <h3 class="text-base font-medium mb-2">Wilayah Timbangan</h3>
              <div class="h-[300px]"><canvas id="timbangan"></canvas></div>
            </div>

            <!-- Baris 3 -->
            <div class="md:col-span-2">
              <h3 class="text-base font-medium mb-2">Wilayah Sarjana</h3>
              <div class="h-[300px]"><canvas id="sarjana"></canvas></div>
            </div>
          </div>
        </section>
      </main>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const state = {
    kriteria: 12,
    alternatif: 48,
    admin: 5,
    ranking: [
      { name: 'Telkomsel', score: 92 },
      { name: 'XL', score: 83 },
      { name: 'Indosat', score: 78 },
      { name: 'Tri', score: 71 },
      { name: 'Smartfren', score: 66 },
      { name: 'Axis', score: 44 },
    ],
  };

  // isi stat cards
  document.getElementById('countKriteria').textContent = state.kriteria;
  document.getElementById('countAlternatif').textContent = state.alternatif;
  document.getElementById('countAdmin').textContent = state.admin;

  // 1. Wilayah Gang Buntu
  new Chart(document.getElementById('ggBuntu'), {
    type: 'bar',
    data: {
      labels: state.ranking.map(r => r.name),
      datasets: [{
        label: 'Skor',
        data: state.ranking.map(r => r.score),
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

  // 2. Wilayah Gang Lampung
  new Chart(document.getElementById('ggLampung'), {
    type: 'bar',
    data: {
      labels: state.ranking.map(r => r.name),
      datasets: [{
        label: 'Skor',
        data: state.ranking.map(r => r.score),
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


  // 3. Wilayah Griya Sejahtera
  new Chart(document.getElementById('griya'), {
    type: 'bar',
    data: {
      labels: state.ranking.map(r => r.name),
      datasets: [{
        label: 'Skor',
        data: state.ranking.map(r => r.score),
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


  // 4. Wilayah Timbangan
  new Chart(document.getElementById('timbangan'), {
    type: 'bar',
    data: {
      labels: state.ranking.map(r => r.name),
      datasets: [{
        label: 'Skor',
        data: state.ranking.map(r => r.score),
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

  // 5. Wilayah Sarjana
  new Chart(document.getElementById('sarjana'), {
    type: 'bar',
    data: {
      labels: state.ranking.map(r => r.name),
      datasets: [{
        label: 'Skor',
        data: state.ranking.map(r => r.score),
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

  // links.forEach(link => {
  //   if (link.getAttribute("href") === currentPath) {
  //     link.classList.add("bg-blue-100", "text-blue-800");
  //   }
  // });
</script>
</body>
</html>

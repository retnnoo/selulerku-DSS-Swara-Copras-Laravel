<!-- Sidebar -->
    <aside id="sidebar" 
          class="w-64 fixed inset-y-0 top-0 z-50 bg-gradient-to-b from-blue-200 to-(--warna1) text-white shadow-lg hidden lg:flex flex-col">

      <!-- Logo -->
      <div class="m-5 py-5 border-b border-blue-200 flex-shrink-0">
        <img src="{{ asset('img/logo.png') }}" alt="Logo Image" class="h-12">
      </div>

      <!-- Menu -->
      <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-1">
        <a class="block px-3 py-2 rounded-lg hover:bg-blue-100 font-bold hover:text-blue-800 {{ Request::is('dashboard-login-admin') ? 'bg-blue-100 text-blue-800' : '' }}" href="{{ route('dashboard') }}"><i class="fa-solid fa-house-laptop mr-2"></i>Beranda</a>
        <a class="block px-3 py-2 rounded-lg hover:bg-blue-100 font-bold hover:text-blue-800 {{ Request::is('dashboard-admin/kriteria') ? 'bg-blue-100 text-blue-800' : '' }}" href="{{ route('kriteria') }}"><i class="fa-solid fa-list mr-2"></i>Data Kriteria</a>
        <a class="block px-3 py-2 rounded-lg hover:bg-blue-100 font-bold hover:text-blue-800 {{ Request::is('dashboard-admin/alternatif') ? 'bg-blue-100 text-blue-800' : '' }}" href="{{ route('alternatif') }}"><i class="fa-solid fa-tower-cell mr-2"></i>Data Alternatif</a>
        <a class="block px-3 py-2 rounded-lg hover:bg-blue-100 font-bold hover:text-blue-800 {{ Request::is('dashboard-admin/ahli') ? 'bg-blue-100 text-blue-800' : '' }}" href="{{ route('ahli') }}"><i class="fa-solid fa-users-gear mr-2"></i>Data Ahli</a>
        <a class="block px-3 py-2 rounded-lg hover:bg-blue-100 font-bold hover:text-blue-800 {{ Request::is('dashboard-admin/pembobotan') ? 'bg-blue-100 text-blue-800' : '' }}" href="{{ route('pembobotan') }}"><i class="fa-solid fa-sliders mr-2"></i>Pembobotan Kriteria</a>
        <a class="block px-3 py-2 rounded-lg hover:bg-blue-100 font-bold hover:text-blue-800 {{ Request::is('dashboard-admin/rangking-copras') ? 'bg-blue-100 text-blue-800' : '' }}" href="{{ route('copras') }}"><i class="fa-solid fa-chart-simple mr-2"></i>Perankingan Alternatif</a>
        <a class="block px-3 py-2 rounded-lg hover:bg-blue-100 font-bold hover:text-blue-800 {{ Request::is('dashboard-admin/wilayah') ? 'bg-blue-100 text-blue-800' : '' }}" href="{{ route('wilayah') }}"><i class="fa-solid fa-location-dot mr-2"></i>Wilayah</a>
        <a class="block px-3 py-2 rounded-lg hover:bg-blue-100 font-bold hover:text-blue-800 {{ Request::is('dashboard-admin/admin') ? 'bg-blue-100 text-blue-800' : '' }}" href="{{ route('admin') }}"><i class="fa-solid fa-user mr-2"></i>Data Admin</a>
      </nav>

      <!-- Logout -->
      <div class="p-4 border-t border-(--warna1) flex-shrink-0">
        <form action="{{ route('logout') }}" method="POST">
          @csrf
          <button type="submit"
            class="w-full px-4 py-2 rounded-lg bg-red-500 hover:bg-red-600 transition font-semibold text-white shadow">
            Logout
          </button>
        </form>
      </div>
    </aside>

    <!-- Overlay untuk mobile -->
    <div id="overlay" class="hidden fixed inset-0 bg-black/40 z-40 lg:hidden"></div>

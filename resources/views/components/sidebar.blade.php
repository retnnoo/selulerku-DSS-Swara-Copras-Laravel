<!-- Sidebar -->
    <aside id="sidebar" 
      class="w-64 hidden lg:flex lg:flex-col fixed inset-y-0 top-0 z-50 h-screen 
            bg-gradient-to-b from-blue-200 to-(--warna1) text-white shadow-lg">

      <!-- Logo -->
      <div class="m-5 py-5 border-b border-blue-200">
        <img src="{{ asset('img/logo.png') }}" alt="Logo Image" class="h-12">
      </div>

      <!-- Menu -->
      <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-1">
        <a class="block px-3 py-2 rounded-lg hover:bg-blue-100 font-bold hover:text-blue-800 {{ Request::is('dashboard-admin') ? 'bg-blue-100 text-blue-800' : '' }}" href="{{ route('dashboard') }}">Beranda</a>
        <a class="block px-3 py-2 rounded-lg hover:bg-blue-100 font-bold hover:text-blue-800 {{ Request::is('dashboard-admin/kriteria') ? 'bg-blue-100 text-blue-800' : '' }}" href="{{ route('kriteria') }}">Data Kriteria</a>
        <a class="block px-3 py-2 rounded-lg hover:bg-blue-100 font-bold hover:text-blue-800 {{ Request::is('dashboard-admin/alternatif') ? 'bg-blue-100 text-blue-800' : '' }}" href="{{ route('alternatif') }}">Data Alternatif</a>
        <a class="block px-3 py-2 rounded-lg hover:bg-blue-100 font-bold hover:text-blue-800 {{ Request::is('dashboard-admin/ahli') ? 'bg-blue-100 text-blue-800' : '' }}" href="{{ route('ahli') }}">Data Ahli</a>
        <a class="block px-3 py-2 rounded-lg hover:bg-blue-100 font-bold hover:text-blue-800 {{ Request::is('dashboard-admin/pembobotan') ? 'bg-blue-100 text-blue-800' : '' }}" href="{{ route('pembobotan') }}">SWARA & Pembobotan</a>
        <a class="block px-3 py-2 rounded-lg hover:bg-blue-100 font-bold hover:text-blue-800 {{ Request::is('dashboard-admin/rangking-copras') ? 'bg-blue-100 text-blue-800' : '' }}" href="{{ route('copras') }}">COPRAS</a>
        <a class="block px-3 py-2 rounded-lg hover:bg-blue-100 font-bold hover:text-blue-800 {{ Request::is('dashboard-admin/wilayah') ? 'bg-blue-100 text-blue-800' : '' }}" href="{{ route('wilayah') }}">Wilayah</a>
        <a class="block px-3 py-2 rounded-lg hover:bg-blue-100 font-bold hover:text-blue-800 {{ Request::is('dashboard/admin') ? 'bg-blue-100 text-blue-800' : '' }}" href="{{ route('admin') }}">Data Admin</a>
      </nav>

      <!-- Logout -->
      <div class="p-4 border-t border-(--warna1)">
        <button class="w-full px-4 py-2 rounded-lg bg-red-500 hover:bg-red-600 transition font-semibold text-white shadow">
          Keluar
        </button>
      </div>
    </aside>
    <!-- Overlay untuk mobile -->
    <div id="overlay" class="hidden fixed inset-0 bg-black/40 z-40 lg:hidden"></div>
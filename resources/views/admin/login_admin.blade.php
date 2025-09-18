<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login</title>
  @vite('resources/css/app.css')
</head>
<body class="min-h-screen flex items-center justify-center bg-gray-50">

    <div class="flex flex-col lg:flex-row bg-white shadow-xl rounded-2xl overflow-hidden max-w-5xl w-full">
    
        <div class="hidden lg:flex w-1/2 bg-gradient-to-br from-blue-300 via-blue-500 to-(--warna1) items-center justify-center p-6">
            <img src="{{ asset('img/login.png') }}" alt="Ilustrasi Login" class="w-10/5 h-auto">
        </div>

        <div class="w-full lg:w-1/2 p-10">
            <div class="flex justify-center mb-8">
                <h1 class="text-3xl md:text-5xl font-bold text-(--warna1)">SelulerKu</h1>
            </div>

            <h2 class="text-2xl font-extrabold text-gray-800 mb-2">Selamat Datang</h2>
            <p class="text-gray-500 mb-6">Silahkan Login Untuk Melanjutkan!</p>
                 @if (session()->has('error'))
                        <div class="text-red-600 text-center -mt-3 mb-3" role="">
                            {{ session('error') }}
                        </div>
                    @endif
            <form action="{{ route('login.post') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700">Username</label>
                        <input type="" name="name" placeholder="Masukkan username"
                            class="mt-1 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-(--warna1) focus:outline-none" required>
                        @error('name')
                            <div class="invalid-feedback" style="display: block">
                                {{ $message }}
                            </div>
                        @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Password</label>
                        <input type="password" name="password" placeholder="Masukkan Password"
                            class="mt-1 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-(--warna1) focus:outline-none" required>
                         @error('password')
                            <div class="invalid-feedback" style="display: block">
                                {{ $message }}
                            </div>
                        @enderror
                </div>

                <button type="submit"
                    class="w-full py-3 rounded-lg bg-(--warna1) text-white font-semibold hover:bg-blue-500 transition">
                    Login
                </button>
            </form>
        </div>
    </div>

</body>
</html>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Monitoring & Ticketing System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/png">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen">

    <div class="flex min-h-screen">

        <div class="w-full lg:w-1/2 flex flex-col justify-center items-center p-8 lg:p-12 bg-white relative z-10">
            <div class="w-full max-w-md">

                <div class="text-center mb-10">
                    <img src="/images/logo.png" alt="UIN Logo"
                        class="w-20 h-20 mx-auto mb-4 object-contain drop-shadow-md">
                    <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Selamat Datang</h1>
                    <p class="text-gray-500 mt-2 text-sm">Masuk untuk mengakses Monitoring & Tracking Access Point</p>
                </div>

                @if ($errors->any())
                    <div
                        class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r-lg text-sm shadow-sm">
                        <p class="font-bold">Gagal Masuk:</p>
                        <ul class="list-disc list-inside mt-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                </svg>
                            </div>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                autofocus
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all outline-none bg-gray-50 focus:bg-white"
                                placeholder="Masukkan email">
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input type="password" id="password" name="password" required
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all outline-none bg-gray-50 focus:bg-white"
                                placeholder="••••••••">
                        </div>
                    </div>

                    <div class="flex items-center justify-between text-sm">
                        <div class="flex items-center">
                            <input type="checkbox" id="remember" name="remember"
                                class="h-4 w-4 text-teal-600 focus:ring-teal-500 border-gray-300 rounded cursor-pointer">
                            <label for="remember" class="ml-2 block text-gray-700 cursor-pointer">Ingat saya</label>
                        </div>
                        <a href="#" class="font-medium text-teal-600 hover:text-teal-500 hover:underline">Lupa
                            password?</a>
                    </div>

                    <button type="submit"
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-gradient-to-r from-teal-600 to-teal-500 hover:from-teal-700 hover:to-teal-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transform transition hover:-translate-y-0.5">
                        Masuk Sistem
                    </button>
                </form>

                <p class="mt-8 text-center text-xs text-gray-400">
                    &copy; {{ date('Y') }} UTIPD UIN Antasari Banjarmasin. <br>System Monitoring & Ticketing.
                </p>
            </div>
        </div>

        <div class="hidden lg:block relative w-1/2 bg-gray-900">
            <div class="absolute inset-0 bg-[url('/images/gedung_kampus.png')] bg-cover bg-center"></div>

            <div class="absolute inset-0 bg-gradient-to-t from-teal-900/90 to-blue-900/40 mix-blend-multiply"></div>

            <div class="relative h-full flex flex-col justify-end p-16 text-white z-10">
                <div class="mb-4">
                    <div
                        class="h-12 w-12 bg-white/20 backdrop-blur-md rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h2 class="text-4xl font-bold mb-4 leading-tight">Layanan Prima,<br>Kampus Terintegritas</h2>
                    <p class="text-teal-100 text-lg max-w-md">Platform terpadu untuk monitoring access point dan
                        manajemen tiket kendala jaringan di lingkungan UIN.</p>
                </div>
            </div>
        </div>

    </div>

</body>

</html>

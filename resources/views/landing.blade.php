<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" style="scroll-behavior: smooth;">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Monitoring Access Point & Handling Rangkaian Gangguan Umum</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/intersect@3.x.x/dist/cdn.min.js"></script>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        .hero-pattern {
            background-color: #f0fdfa;
            background-image: radial-gradient(#14b8a6 0.5px, transparent 0.5px);
            background-size: 10px 10px;
        }
    </style>
</head>

<body class="antialiased font-sans text-gray-900 bg-white">

    <nav class="fixed w-full z-50 bg-white/90 backdrop-blur-md border-b border-gray-100 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo UIN Antasari" class="h-12 w-auto">

                    <div class="flex flex-col">
                        <span class="font-extrabold text-2xl tracking-tighter text-teal-700 leading-none">
                            MAHARAGU
                        </span>
                        <span
                            class="text-[10px] sm:text-xs font-medium text-gray-500 uppercase tracking-wide leading-tight mt-0.5">
                            UIN Antasari Banjarmasin
                        </span>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    @auth
                        <a href="{{ url('/dashboard') }}"
                            class="text-sm font-semibold text-gray-900 hover:text-teal-600 transition-colors">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                            class="px-5 py-2.5 text-sm font-bold text-white bg-teal-600 rounded-full hover:bg-teal-700 transition-all shadow-lg shadow-teal-600/20 hover:shadow-teal-600/40">
                            Login
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <section class="relative pt-36 pb-24 overflow-hidden">

        <div class="absolute inset-0 z-0">
            <img src="{{ asset('images/hero-background.png') }}" alt="Background Kampus"
                class="w-full h-full object-cover">

            <div class="absolute inset-0 bg-gradient-to-b from-teal-100/80 via-white/90 to-white"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">

            <h1 class="text-4xl md:text-6xl font-extrabold text-gray-900 tracking-tight mb-6 leading-tight">
                MAHARAGU <br>
                <span class="text-5xl text-transparent bg-clip-text bg-gradient-to-r from-teal-600 to-emerald-600">
                    Monitoring Access Point & Handling Rangkaian Gangguan Umum
                </span>
            </h1>

            <p class="mt-4 text-lg text-gray-600 max-w-2xl mx-auto mb-10 leading-relaxed">
                Pantau kualitas jaringan Access Point di seluruh gedung kampus dan laporkan kendala koneksi secara
                real-time. Infrastruktur handal untuk akademik yang lebih baik.
            </p>

            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ route('login') }}"
                    class="px-8 py-4 text-base font-bold text-white bg-gray-900 rounded-xl hover:bg-gray-800 transition-all shadow-xl hover:shadow-2xl hover:-translate-y-1 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                        </path>
                    </svg>
                    Buat Laporan
                </a>
                <a href="#check-ticket"
                    class="px-8 py-4 text-base font-bold text-gray-700 bg-white/80 backdrop-blur-sm border border-gray-200 rounded-xl hover:bg-white transition-all shadow-sm hover:shadow-md flex items-center justify-center gap-2">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Cek Tiket
                </a>
            </div>
        </div>
    </section>

    <section class="py-12 bg-white border-y border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

                <div
                    class="p-6 bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow flex items-center gap-4">
                    <div class="p-4 bg-blue-50 text-blue-600 rounded-xl">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Access Point</p>
                        <h3 class="text-3xl font-bold text-gray-900" x-data="countUp({{ $stats['total_ap'] }})" x-intersect.once="start()"
                            x-text="current">
                            0
                        </h3>
                    </div>
                </div>

                <div
                    class="p-6 bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow flex items-center gap-4">
                    <div class="p-4 bg-emerald-50 text-emerald-600 rounded-xl">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total AP Aktif</p>
                        <h3 class="text-3xl font-bold text-gray-900" x-data="countUp({{ $stats['active_ap'] }})" x-intersect.once="start()"
                            x-text="current">
                            0
                        </h3>
                    </div>
                </div>

                <div
                    class="p-6 bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow flex items-center gap-4">
                    <div class="p-4 bg-indigo-50 text-indigo-600 rounded-xl">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Gedung</p>
                        <h3 class="text-3xl font-bold text-gray-900" x-data="countUp({{ $stats['total_buildings'] }})" x-intersect.once="start()"
                            x-text="current">
                            0
                        </h3>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-24 bg-white overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                
                <div class="order-2 lg:order-1">
                    <div class="inline-flex items-center px-3 py-1 rounded-full bg-teal-50 text-teal-700 text-xs font-bold uppercase tracking-wide mb-4 border border-teal-100">
                        Dual-View Visualization
                    </div>
                    <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl mb-6">
                        Dari Skala Kampus hingga <br>
                        <span class="text-teal-600">Detail Ruangan</span>
                    </h2>
                    <p class="text-lg text-gray-500 mb-8 leading-relaxed">
                        MAHARAGU memberikan fleksibilitas pemantauan. Lihat kesehatan jaringan secara makro di peta kampus, lalu <em>drill-down</em> ke denah lantai untuk melihat posisi AP secara presisi.
                    </p>
                    
                    <div class="space-y-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center h-12 w-12 rounded-xl bg-teal-100 text-teal-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-lg leading-6 font-bold text-gray-900">Campus View</h4>
                                <p class="mt-2 text-base text-gray-500">Pantau status seluruh gedung dalam satu layar. Indikator warna memudahkan deteksi area bermasalah.</p>
                            </div>
                        </div>

                        <div class="flex">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center h-12 w-12 rounded-xl bg-indigo-100 text-indigo-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-lg leading-6 font-bold text-gray-900">Floor & Room Detail</h4>
                                <p class="mt-2 text-base text-gray-500">Klik gedung untuk melihat denah lantai. Posisi Access Point digambarkan sesuai lokasi fisik asli.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="order-1 lg:order-2 relative h-[450px] w-full flex items-center justify-center">
                    
                    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-[120%] h-[120%] bg-gradient-to-tr from-teal-50 to-indigo-50 rounded-full blur-3xl opacity-60 z-0"></div>

                    <div class="absolute top-0 left-0 w-3/4 shadow-2xl rounded-xl border border-gray-100 overflow-hidden transform -rotate-2 hover:rotate-0 transition-all duration-500 z-10 bg-white">
                        <img src="{{ asset('images/c.png') }}" alt="Peta Kampus" class="w-full h-auto">
                        
                    </div>

                    <div class="absolute bottom-0 right-0 w-2/3 shadow-2xl rounded-xl border-4 border-white overflow-hidden transform rotate-3 hover:rotate-0 transition-all duration-500 z-20 bg-white hover:scale-105">
                        <img src="{{ asset('images/b.png') }}" alt="Detail Ruangan" class="w-full h-auto">
                        
                    </div>

                </div>
            </div>
        </div>
    </section>

    <section id="check-ticket" class="py-20 bg-gray-50" scroll-mt-28 x-data="ticketChecker()">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Cek Status Laporan</h2>
            <p class="text-gray-600 mb-8">Masukkan nomor tiket yang Anda dapatkan saat melapor untuk mengetahui
                progress
                pengerjaan.</p>

            <div class="bg-white p-2 rounded-2xl shadow-lg border border-gray-200 flex flex-col sm:flex-row gap-2">
                <input type="text" x-model="ticketNumber" @keydown.enter="checkStatus()"
                    placeholder="Contoh: TKT-202310-001"
                    class="flex-1 border-0 focus:ring-0 text-lg px-4 py-3 rounded-xl text-gray-900 placeholder-gray-400 bg-transparent">
                <button @click="checkStatus()" :disabled="loading"
                    class="px-8 py-3 bg-teal-600 text-white font-bold rounded-xl hover:bg-teal-700 transition-colors disabled:opacity-50 flex items-center justify-center">
                    <span x-show="!loading">Cek Status</span>
                    <span x-show="loading" x-cloak
                        class="animate-spin h-5 w-5 border-2 border-white border-t-transparent rounded-full"></span>
                </button>
            </div>

            <div x-show="result" x-transition x-cloak
                class="mt-8 text-left bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-bold tracking-wider">Nomor Tiket</p>
                        <p class="text-xl font-mono font-bold text-gray-900" x-text="result?.ticket_number"></p>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide"
                        :class="{
                            'bg-red-100 text-red-700': result?.status === 'open',
                            'bg-yellow-100 text-yellow-700': result?.status === 'in_progress',
                            'bg-green-100 text-green-700': result?.status === 'resolved' || result
                                ?.status === 'closed'
                        }"
                        x-text="formatStatus(result?.status)">
                    </span>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Lokasi Gangguan</p>
                        <p class="font-medium text-gray-900 flex items-center">
                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span x-text="result?.location"></span>
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Kategori Masalah</p>
                        <p class="font-medium text-gray-900" x-text="result?.category"></p>
                    </div>
                    <div class="col-span-full">
                        <p class="text-sm text-gray-500 mb-1">Tanggal Laporan</p>
                        <p class="font-medium text-gray-900" x-text="result?.created_at"></p>
                    </div>
                </div>
            </div>

            <div x-show="error" x-transition x-cloak
                class="mt-8 p-4 bg-red-50 text-red-700 rounded-xl border border-red-100 flex items-center justify-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span x-text="error"></span>
            </div>
        </div>
    </section>

    <footer class="bg-gray-900 text-white pt-16 pb-8 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 mb-12">
                
                <div class="space-y-6">
                    <div>
                        <h3 class="text-2xl font-extrabold text-white tracking-tight mb-2">MAHARAGU</h3>
                        <p class="text-teal-400 font-medium">UIN Antasari Banjarmasin</p>
                    </div>
                    
                    <p class="text-gray-400 leading-relaxed max-w-md">
                        Unit Teknologi Informasi dan Pangkalan Data (UTIPD).<br>
                        Menyediakan layanan monitoring infrastruktur jaringan untuk menunjang kegiatan akademik yang unggul dan berakhlak.
                    </p>

                    <div class="space-y-3">
                        <div class="flex items-start gap-3 text-gray-400">
                            <svg class="w-6 h-6 text-teal-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span>Jl. Jenderal Ahmad Yani Km. 4,5,<br>Banjarmasin, Kalimantan Selatan 70235</span>
                        </div>
                        <div class="flex items-center gap-3 text-gray-400">
                            <svg class="w-6 h-6 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            <span>utipd@uin-antasari.ac.id</span>
                        </div>
                    </div>
                </div>

                <div class="relative h-64 w-full rounded-2xl overflow-hidden shadow-2xl border border-gray-700 group">
                    <div class="absolute inset-0 bg-gray-900/20 group-hover:bg-transparent transition-colors z-10 pointer-events-none"></div>
                    
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3983.1026569107936!2d114.6083323147576!3d-3.324225997605963!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2de423c8a93a3a9b%3A0x6e3a985f54c2565!2sUIN%20Antasari%20Banjarmasin!5e0!3m2!1sid!2sid!4v1679801234567!5m2!1sid!2sid" 
                        width="100%" 
                        height="100%" 
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade"
                        class="filter grayscale hover:grayscale-0 transition-all duration-500">
                    </iframe>
                </div>

            </div>

            <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center text-sm text-gray-500">
                <p>&copy; {{ date('Y') }} UTIPD UIN Antasari. All rights reserved.</p>
                <div class="flex space-x-6 mt-4 md:mt-0">
                    <a href="#" class="hover:text-teal-400 transition-colors">Privacy Policy</a>
                    <a href="#" class="hover:text-teal-400 transition-colors">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('countUp', (target, duration = 2000) => ({
                current: 0,
                target: target,
                time: duration,
                start() {
                    let startTimestamp = null;
                    const step = (timestamp) => {
                        if (!startTimestamp) startTimestamp = timestamp;
                        const progress = Math.min((timestamp - startTimestamp) / this.time, 1);
                        this.current = Math.floor(progress * this.target);
                        if (progress < 1) {
                            window.requestAnimationFrame(step);
                        }
                    };
                    window.requestAnimationFrame(step);
                }
            }))
        })
        function ticketChecker() {
            return {
                ticketNumber: '',
                loading: false,
                result: null,
                error: null,

                checkStatus() {
                    if (!this.ticketNumber) return;
                    this.loading = true;
                    this.result = null;
                    this.error = null;

                    fetch('{{ route('landing.check_ticket') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                ticket_number: this.ticketNumber
                            })
                        })
                        .then(response => {
                            if (!response.ok) throw new Error('Tiket tidak ditemukan.');
                            return response.json();
                        })
                        .then(data => {
                            this.result = data.data;
                        })
                        .catch(err => {
                            this.error = 'Nomor tiket tidak ditemukan atau terjadi kesalahan.';
                        })
                        .finally(() => {
                            this.loading = false;
                        });
                },
                formatStatus(status) {
                    const map = {
                        'open': 'Menunggu Konfirmasi',
                        'in_progress': 'Sedang Dikerjakan',
                        'resolved': 'Selesai',
                        'closed': 'Ditutup'
                    };
                    return map[status] || status;
                }
            }
        }
    </script>
</body>

</html>

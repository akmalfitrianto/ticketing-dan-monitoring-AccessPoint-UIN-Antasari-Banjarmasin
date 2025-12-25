<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Monitoring Access Point & Rangkaian Gangguan Umum</title>

    <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <style>
        [x-cloak] {
            display: none !important;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        /* Custom Scrollbar for Sidebar */
        .sidebar-scroll::-webkit-scrollbar {
            width: 5px;
        }

        .sidebar-scroll::-webkit-scrollbar-track {
            background: #0f172a;
        }

        .sidebar-scroll::-webkit-scrollbar-thumb {
            background: #334155;
            border-radius: 5px;
        }

        .sidebar-scroll::-webkit-scrollbar-thumb:hover {
            background: #475569;
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-800 antialiased">

    <div class="min-h-screen flex" x-data="{ sidebarOpen: true }">

        <aside :class="sidebarOpen ? 'w-72' : 'w-20'"
            class="bg-slate-900 text-white transition-all duration-300 ease-in-out flex flex-col fixed inset-y-0 left-0 z-50 lg:relative shadow-xl">

            <div class="h-20 flex items-center justify-between px-4 border-b border-slate-800 bg-slate-900">
                <div class="flex items-center space-x-3 overflow-hidden" x-show="sidebarOpen" x-transition.opacity>
                    <img src="/images/logo.png" alt="UIN Antasari Logo" class="w-10 h-10 object-contain drop-shadow-lg">
                    <div class="flex flex-col">
                        <span class="font-bold text-lg tracking-wide text-teal-400">MAHARAGU</span>
                        <span class="text-[10px] text-slate-400 uppercase tracking-wider leading-none">MONITORING AP & HANDLING RANGKAIAN GANGGUAN UMUM</span>
                    </div>
                </div>

                <button @click="sidebarOpen = !sidebarOpen"
                    class="p-2 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800 transition-colors focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>

            <nav class="flex-1 px-3 py-6 space-y-1 overflow-y-auto sidebar-scroll">

                <a href="{{ route('dashboard') }}"
                    class="flex items-center space-x-3 px-3 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('dashboard') ? 'bg-teal-600 text-white shadow-lg shadow-teal-900/50' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                        </path>
                    </svg>
                    <span x-show="sidebarOpen"
                        class="font-medium whitespace-nowrap transition-opacity duration-300">Dashboard</span>
                </a>

                <div x-show="sidebarOpen"
                    class="px-3 mt-6 mb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                    Operasional
                </div>

                <div x-data="{ open: {{ request()->routeIs('campus.map') ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                        class="w-full flex items-center justify-between px-3 py-3 rounded-xl transition-colors duration-200 text-slate-300 hover:bg-slate-800 hover:text-white group">
                        <div class="flex items-center space-x-3">
                            <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                </path>
                            </svg>
                            <span x-show="sidebarOpen" class="font-medium whitespace-nowrap">Monitoring</span>
                        </div>
                        <svg x-show="sidebarOpen" :class="open ? 'rotate-180' : ''"
                            class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>
                    <div x-show="open" x-collapse x-cloak class="mt-1 space-y-1">
                        <a href="{{ route('campus.map') }}"
                            class="flex items-center space-x-3 px-3 py-2 rounded-lg text-sm transition-colors duration-200 {{ request()->routeIs('campus.map') ? 'text-teal-400 bg-slate-800' : 'text-slate-400 hover:text-white hover:bg-slate-800' }} ml-4">
                            <span
                                class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('campus.map') ? 'bg-teal-400' : 'bg-slate-600' }}"></span>
                            <span x-show="sidebarOpen">Denah Kampus</span>
                        </a>
                    </div>
                </div>

                <div x-data="{ open: {{ request()->routeIs('tickets.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                        class="w-full flex items-center justify-between px-3 py-3 rounded-xl transition-colors duration-200 text-slate-300 hover:bg-slate-800 hover:text-white group">
                        <div class="flex items-center space-x-3">
                            <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z">
                                </path>
                            </svg>
                            <span x-show="sidebarOpen" class="font-medium whitespace-nowrap">Ticketing</span>
                        </div>
                        <svg x-show="sidebarOpen" :class="open ? 'rotate-180' : ''"
                            class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>
                    <div x-show="open" x-collapse x-cloak class="mt-1 space-y-1">
                        <a href="{{ route('tickets.my') }}"
                            class="flex items-center space-x-3 px-3 py-2 rounded-lg text-sm transition-colors duration-200 ml-4 {{ request()->routeIs('tickets.my') ? 'text-teal-400 bg-slate-800' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                            <span
                                class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('tickets.my') ? 'bg-teal-400' : 'bg-slate-600' }}"></span>
                            <span x-show="sidebarOpen">Tiket Saya</span>
                        </a>
                        @if (auth()->user()->isSuperAdmin())
                            <a href="{{ route('tickets.index') }}"
                                class="flex items-center space-x-3 px-3 py-2 rounded-lg text-sm transition-colors duration-200 ml-4 {{ request()->routeIs('tickets.index') ? 'text-teal-400 bg-slate-800' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                                <span
                                    class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('tickets.index') ? 'bg-teal-400' : 'bg-slate-600' }}"></span>
                                <span x-show="sidebarOpen">Semua Tiket</span>
                            </a>
                        @endif
                    </div>
                </div>

                @if (auth()->user()->isSuperAdmin())
                    <div x-show="sidebarOpen"
                        class="px-3 mt-6 mb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                        Administrasi
                    </div>
                    <div x-data="{ open: {{ request()->routeIs('admin.*') ? 'true' : 'false' }} }">
                        <button @click="open = !open"
                            class="w-full flex items-center justify-between px-3 py-3 rounded-xl transition-colors duration-200 text-slate-300 hover:bg-slate-800 hover:text-white group">
                            <div class="flex items-center space-x-3">
                                <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span x-show="sidebarOpen" class="font-medium whitespace-nowrap">Manajemen</span>
                            </div>
                            <svg x-show="sidebarOpen" :class="open ? 'rotate-180' : ''"
                                class="w-4 h-4 transition-transform duration-200" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="open" x-collapse x-cloak class="mt-1 space-y-1">
                            <a href="{{ route('admin.buildings.index') }}"
                                class="flex items-center space-x-3 px-3 py-2 rounded-lg text-sm transition-colors duration-200 ml-4 {{ request()->routeIs('admin.buildings.*') ? 'text-teal-400 bg-slate-800' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                                <span
                                    class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('admin.buildings.*') ? 'bg-teal-400' : 'bg-slate-600' }}"></span>
                                <span x-show="sidebarOpen">Data Gedung</span>
                            </a>
                            <a href="{{ route('admin.admins.index') }}"
                                class="flex items-center space-x-3 px-3 py-2 rounded-lg text-sm transition-colors duration-200 ml-4 {{ request()->routeIs('admin.admins.*') ? 'text-teal-400 bg-slate-800' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                                <span
                                    class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('admin.admins.*') ? 'bg-teal-400' : 'bg-slate-600' }}"></span>
                                <span x-show="sidebarOpen">Data Admin</span>
                            </a>
                        </div>
                    </div>
                @endif

            </nav>

            <div class="p-4 bg-slate-900 border-t border-slate-800">
                <p x-show="sidebarOpen" class="text-xs text-center text-slate-500">
                    &copy; {{ date('Y') }} UTIPD UIN Antasari
                </p>
            </div>
        </aside>

        <div class="flex-1 flex flex-col h-screen overflow-hidden">

            <header
                class="h-20 bg-white/80 backdrop-blur-md border-b border-gray-200 sticky top-0 z-40 px-6 flex items-center justify-between">

                <div>
                    <h1 class="text-2xl font-bold text-slate-800 tracking-tight">@yield('header', 'Dashboard')</h1>
                    <p class="text-xs text-slate-500 mt-1">@yield('subheader', 'Monitoring Access Point & Handling Rangkaian Gangguan Umum')</p>
                </div>

                <div class="flex items-center space-x-4">

                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                            class="relative p-2 text-gray-500 hover:text-teal-600 hover:bg-teal-50 rounded-full transition-colors focus:outline-none">
                            <span class="sr-only">Notifikasi</span>
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                                </path>
                            </svg>
                            @if (auth()->user()->unreadNotificationsCount() > 0)
                                <span
                                    class="absolute top-1 right-1 h-2.5 w-2.5 bg-red-500 rounded-full ring-2 ring-white animate-pulse"></span>
                            @endif
                        </button>

                        <div x-show="open" @click.away="open = false" x-transition.origin.top.right x-cloak
                            class="absolute right-0 mt-3 w-80 bg-white rounded-xl shadow-2xl border border-gray-100 overflow-hidden z-50">
                            <div
                                class="px-4 py-3 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                                <h3 class="text-sm font-semibold text-gray-700">Notifikasi</h3>
                                @if (auth()->user()->unreadNotificationsCount() > 0)
                                    <button onclick="markAllAsRead()"
                                        class="text-xs font-medium text-teal-600 hover:text-teal-800">Tandai
                                        dibaca</button>
                                @endif
                            </div>
                            <div class="max-h-[300px] overflow-y-auto" id="notification-list">
                                @forelse(auth()->user()->notifications()->latest()->take(5)->get() as $notification)
                                    <div id="notification-{{ $notification->id }}"
                                        class="relative group px-4 py-3 hover:bg-gray-50 border-b border-gray-50 transition-colors {{ $notification->is_unread ? 'bg-blue-50/50' : '' }}">
                                        <a href="{{ route('tickets.show', $notification->ticket_id) }}"
                                            class="block pr-6">
                                            <p class="text-sm font-medium text-gray-800 truncate">
                                                {{ $notification->title }}</p>
                                            <p class="text-xs text-gray-500 mt-0.5 line-clamp-2">
                                                {{ $notification->message }}</p>
                                            <p class="text-[10px] text-gray-400 mt-1">
                                                {{ $notification->created_at->diffForHumans() }}</p>
                                        </a>

                                        <div
                                            class="absolute top-3 right-3 flex space-x-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                            @if ($notification->is_unread)
                                                <button onclick="markAsRead({{ $notification->id }}, event)"
                                                    class="p-1 text-gray-400 hover:text-teal-600"
                                                    title="Tandai dibaca">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                </button>
                                            @endif
                                            <button onclick="deleteNotification({{ $notification->id }}, event)"
                                                class="p-1 text-gray-400 hover:text-red-600" title="Hapus">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                @empty
                                    <div class="py-8 text-center text-gray-500">
                                        <p class="text-sm">Tidak ada notifikasi baru</p>
                                    </div>
                                @endforelse
                            </div>
                            <a href="{{ route('notifications.index') }}"
                                class="block py-2 text-center text-xs font-medium text-gray-500 bg-gray-50 hover:bg-gray-100 hover:text-teal-600 transition-colors">Lihat
                                Semua Notifikasi</a>
                        </div>
                    </div>

                    <div class="h-8 w-px bg-gray-200"></div>

                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center space-x-3 focus:outline-none group">
                            <div class="text-right hidden md:block">
                                <p
                                    class="text-sm font-semibold text-gray-700 group-hover:text-teal-600 transition-colors">
                                    {{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-400 uppercase">{{ auth()->user()->role }}</p>
                            </div>
                            <div class="h-10 w-10 rounded-full bg-gradient-to-br from-teal-400 to-blue-500 p-0.5">
                                <div
                                    class="h-full w-full rounded-full bg-white flex items-center justify-center text-teal-600 font-bold">
                                    {{ substr(auth()->user()->name, 0, 2) }}
                                </div>
                            </div>
                            <svg :class="open ? 'rotate-180' : ''"
                                class="w-4 h-4 text-gray-400 transition-transform duration-200" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <div x-show="open" @click.away="open = false" x-transition.origin.top.right x-cloak
                            class="absolute right-0 mt-3 w-48 bg-white rounded-xl shadow-xl border border-gray-100 py-1 z-50">
                            {{-- <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-teal-50 hover:text-teal-600">Profile Saya</a> --}}
                            {{-- <div class="border-t border-gray-100 my-1"></div> --}}
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                    Sign Out
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-slate-50 p-6">
                <div class="max-w-7xl mx-auto">
                    @yield('content')
                </div>
            </main>

        </div>
    </div>

    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        // --- 1. CONFIG SWEETALERT ---
        const SwalCustom = Swal.mixin({
            customClass: {
                popup: 'rounded-2xl',
                confirmButton: 'px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors mx-1',
                cancelButton: 'px-4 py-2 bg-slate-200 text-slate-700 rounded-lg hover:bg-slate-300 transition-colors mx-1'
            },
            buttonsStyling: false
        });

        // Flash Messages (Success/Error)
        @if (session('success'))
            SwalCustom.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '{{ session('success') }}',
                timer: 3000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        @endif

        @if (session('error'))
            SwalCustom.fire({
                icon: 'error',
                title: 'Gagal',
                text: '{{ session('error') }}',
            });
        @endif

        @if ($errors->any())
            SwalCustom.fire({
                icon: 'error',
                title: 'Periksa Kembali',
                html: '<ul class="text-left text-sm">@foreach ($errors->all() as $error)<li>• {{ $error }}</li>@endforeach</ul>',
            });
        @endif

        // --- 2. LOGIKA NOTIFIKASI (AJAX) ---

        // Mark Single Read
        async function markAsRead(notificationId, event) {
            event.preventDefault();
            event.stopPropagation();
            try {
                const response = await fetch(`/notifications/${notificationId}/read`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                });
                if (response.ok) {
                    const item = document.getElementById(`notification-${notificationId}`);
                    if (item) {
                        item.classList.remove('bg-blue-50/50');
                        event.target.closest('button').remove(); // Hapus tombol centang
                        updateNotificationCounter();
                    }
                }
            } catch (e) {
                console.error(e);
            }
        }

        // Delete Notification
        async function deleteNotification(notificationId, event) {
            event.preventDefault();
            event.stopPropagation();
            if (!confirm('Hapus notifikasi ini?')) return;
            try {
                const response = await fetch(`/notifications/${notificationId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                });
                if (response.ok) {
                    const result = await response.json();
                    if (result.success) {
                        const item = document.getElementById(`notification-${notificationId}`);
                        if (item) {
                            item.style.opacity = '0';
                            setTimeout(() => {
                                item.remove();
                                updateNotificationCounter();
                            }, 300);
                        }
                    }
                }
            } catch (e) {
                console.error(e);
            }
        }

        // Mark All Read
        async function markAllAsRead() {
            try {
                const response = await fetch('/notifications/read-all', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                });
                if (response.ok) window.location.reload();
            } catch (e) {
                console.error(e);
            }
        }

        // Update Red Badge
        function updateNotificationCounter() {
            const list = document.getElementById('notification-list');
            const unreadCount = list.querySelectorAll('.bg-blue-50/50').length;
            const badge = document.querySelector('.animate-pulse'); // badge merah

            // Update badge existence
            if (unreadCount === 0 && badge) badge.remove();

            // Check empty state
            if (list.children.length === 0) {
                list.innerHTML =
                    '<div class="py-8 text-center text-gray-500"><p class="text-sm">Tidak ada notifikasi baru</p></div>';
            }
        }
    </script>
</body>

</html>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - UIN Ticketing System</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
</head>

<body class="bg-gray-50 font-sans antialiased">
    <div class="min-h-screen flex" x-data="{ sidebarOpen: true, notificationOpen: false }">
        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'w-64' : 'w-20'"
            class="bg-gray-900 text-white transition-all duration-300 flex flex-col">
            <!-- Logo -->
            <div class="p-4 flex items-center justify-between border-b border-gray-800">
                <div class="flex items-center space-x-3" x-show="sidebarOpen">
                    <div class="w-12 h-10 rounded-lg flex items-center justify-center">
                        <img src="/images/logo.png" alt="logo-uin" class="w-12 h-12 object-contain">
                    </div>
                    <span class="font-bold text-lg">UIN Ticketing</span>
                </div>
                <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-lg hover:bg-gray-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
                <!-- Dashboard -->
                <a href="{{ route('dashboard') }}"
                    class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-800 {{ request()->routeIs('dashboard') ? 'bg-gray-800' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                        </path>
                    </svg>
                    <span x-show="sidebarOpen">Dashboard</span>
                </a>

                <!-- Monitoring Section -->
                <div x-data="{ open: true }">
                    <button @click="open = !open"
                        class="w-full flex items-center justify-between p-3 rounded-lg hover:bg-gray-800">
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7">
                                </path>
                            </svg>
                            <span x-show="sidebarOpen">Monitoring</span>
                        </div>
                        <svg x-show="sidebarOpen" :class="open ? 'rotate-180' : ''" class="w-4 h-4 transition-transform"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>
                    <div x-show="open" x-collapse class="ml-8 mt-2 space-y-2">
                        <a href="{{ route('campus.map') }}"
                            class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-800 {{ request()->routeIs('campus.map') ? 'bg-gray-800' : '' }}">
                            <span x-show="sidebarOpen">Denah Kampus</span>
                        </a>
                    </div>
                </div>

                <!-- Ticketing Section -->
                <div x-data="{ open: true }">
                    <button @click="open = !open"
                        class="w-full flex items-center justify-between p-3 rounded-lg hover:bg-gray-800">
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z">
                                </path>
                            </svg>
                            <span x-show="sidebarOpen">Ticketing</span>
                        </div>
                        <svg x-show="sidebarOpen" :class="open ? 'rotate-180' : ''" class="w-4 h-4 transition-transform"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>
                    <div x-show="open" x-collapse class="ml-8 mt-2 space-y-2">
                        <a href="{{ route('tickets.my') }}"
                            class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-800 {{ request()->routeIs('tickets.my') ? 'bg-gray-800' : '' }}">
                            <span x-show="sidebarOpen">My Tickets</span>
                        </a>
                        @if (auth()->user()->isSuperAdmin())
                            <a href="{{ route('tickets.index') }}"
                                class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-800 {{ request()->routeIs('tickets.index') ? 'bg-gray-800' : '' }}">
                                <span x-show="sidebarOpen">All Tickets</span>
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Superadmin Only -->
                @if (auth()->user()->isSuperAdmin())
                    <div x-data="{ open: true }">
                        <button @click="open = !open"
                            class="w-full flex items-center justify-between p-3 rounded-lg hover:bg-gray-800">
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                    </path>
                                </svg>
                                <span x-show="sidebarOpen">Management</span>
                            </div>
                            <svg x-show="sidebarOpen" :class="open ? 'rotate-180' : ''"
                                class="w-4 h-4 transition-transform" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="open" x-collapse class="ml-8 mt-2 space-y-2">
                            <a href="{{ route('admin.buildings.index') }}"
                                class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-800 {{ request()->routeIs('admin.buildings.*') ? 'bg-gray-800' : '' }}">
                                <span x-show="sidebarOpen">Gedung</span>
                            </a>
                            {{-- <a href="{{ route('admin.rooms.index') }}"
                                class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-800 {{ request()->routeIs('admin.rooms.*') || request()->routeIs('admin.floors.rooms.*') ? 'bg-gray-800' : '' }}">
                                <span x-show="sidebarOpen">Ruangan</span>
                            </a> --}}
                            <a href="{{ route('admin.admins.index') }}"
                                class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-800 {{ request()->routeIs('admin.admins.*') ? 'bg-gray-800' : '' }}">
                                <span x-show="sidebarOpen">Admin</span>
                            </a>
                        </div>
                    </div>
                @endif
            </nav>

            <!-- User Profile -->
            <div class="p-4 border-t border-gray-800">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-teal-500 rounded-full flex items-center justify-center">
                        <span class="text-sm font-bold">{{ substr(auth()->user()->name, 0, 2) }}</span>
                    </div>
                    <div x-show="sidebarOpen" class="flex-1">
                        <p class="text-sm font-medium">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-400">{{ ucfirst(auth()->user()->role) }}</p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Top Bar -->
            <header class="bg-white shadow-sm border-b border-gray-200 p-4">
                <div class="flex items-center justify-between">
                    <h1 class="text-2xl font-bold text-gray-800">@yield('header', 'Dashboard')</h1>

                    <div class="flex items-center space-x-4">
                        <!-- Notifications -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open"
                                class="relative p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                                    </path>
                                </svg>
                                @if (auth()->user()->unreadNotificationsCount() > 0)
                                    <span
                                        class="absolute top-0 right-0 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">
                                        {{ auth()->user()->unreadNotificationsCount() }}
                                    </span>
                                @endif
                            </button>

                            <!-- Notification Dropdown -->
                            <div x-show="open" @click.away="open = false" x-cloak
                                class="absolute right-0 mt-2 w-96 bg-white rounded-lg shadow-lg border border-gray-200 z-50">
                                <div class="p-4 border-b border-gray-200 flex items-center justify-between">
                                    <h3 class="font-semibold text-gray-900">Notifikasi</h3>
                                    @if (auth()->user()->unreadNotificationsCount() > 0)
                                        <button onclick="markAllAsRead()"
                                            class="text-xs text-teal-600 hover:text-teal-700 font-medium">
                                            Tandai Semua Dibaca
                                        </button>
                                    @endif
                                </div>
                                <div class="max-h-96 overflow-y-auto" id="notification-list">
                                    @forelse(auth()->user()->notifications()->latest()->take(5)->get() as $notification)
                                        <div id="notification-{{ $notification->id }}"
                                            class="relative group p-4 hover:bg-gray-50 border-b border-gray-100 {{ $notification->is_unread ? 'bg-blue-50' : '' }}">
                                            <a href="{{ route('tickets.show', $notification->ticket_id) }}"
                                                class="block pr-16">
                                                <p class="font-medium text-sm text-gray-900">
                                                    {{ $notification->title }}
                                                    @if ($notification->is_unread)
                                                        <span
                                                            class="inline-block w-2 h-2 bg-blue-600 rounded-full ml-1"></span>
                                                    @endif
                                                </p>
                                                <p class="text-xs text-gray-600 mt-1">{{ $notification->message }}</p>
                                                <p class="text-xs text-gray-400 mt-1">
                                                    {{ $notification->created_at->diffForHumans() }}
                                                </p>
                                            </a>

                                            <!-- Action Buttons -->
                                            <div
                                                class="absolute top-4 right-4 flex items-center space-x-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                                @if ($notification->is_unread)
                                                    <button onclick="markAsRead({{ $notification->id }}, event)"
                                                        class="p-1.5 text-gray-400 hover:text-teal-600 hover:bg-teal-50 rounded transition"
                                                        title="Tandai sudah dibaca">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                        </svg>
                                                    </button>
                                                @endif
                                                <button onclick="deleteNotification({{ $notification->id }}, event)"
                                                    class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded transition"
                                                    title="Hapus notifikasi">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="p-4 text-center text-gray-500 text-sm">
                                            Tidak ada notifikasi
                                        </div>
                                    @endforelse
                                </div>
                                <div class="p-2 border-t border-gray-200">
                                    <a href="{{ route('notifications.index') }}"
                                        class="block text-center text-sm text-teal-600 hover:text-teal-700 font-medium py-2">
                                        Lihat Semua
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Logout -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                    </path>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="flex-1 p-6 overflow-y-auto">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{--  Notification Actions Script --}}
    <script>
        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        // Mark single notification as read
        async function markAsRead(notificationId, event) {
            event.preventDefault();
            event.stopPropagation();

            try {
                const response = await fetch(`/notifications/${notificationId}/read`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json' // Penting untuk Laravel detect AJAX
                    }
                });

                if (response.ok) {
                    // Remove blue background and dot indicator
                    const notifElement = document.getElementById(`notification-${notificationId}`);
                    if (notifElement) {
                        notifElement.classList.remove('bg-blue-50');

                        // Remove the blue dot
                        const dotIndicator = notifElement.querySelector('.bg-blue-600');
                        if (dotIndicator) {
                            dotIndicator.remove();
                        }

                        // Remove mark as read button
                        event.target.closest('button').remove();

                        // Update counter
                        updateNotificationCounter();
                    }
                } else {
                    console.error('Failed to mark as read:', response.status);
                    alert('Gagal menandai sebagai dibaca');
                }
            } catch (error) {
                console.error('Error marking notification as read:', error);
                alert('Terjadi kesalahan. Silakan coba lagi.');
            }
        }

        // Delete notification
        async function deleteNotification(notificationId, event) {
            event.preventDefault();
            event.stopPropagation();

            if (!confirm('Hapus notifikasi ini?')) {
                return;
            }

            try {
                const response = await fetch(`/notifications/${notificationId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json' // Penting untuk Laravel detect AJAX
                    }
                });

                if (response.ok) {
                    const result = await response.json();

                    if (result.success) {
                        // Remove notification element with animation
                        const notifElement = document.getElementById(`notification-${notificationId}`);
                        if (notifElement) {
                            notifElement.style.opacity = '0';
                            notifElement.style.transform = 'translateX(100%)';
                            notifElement.style.transition = 'all 0.3s ease';

                            setTimeout(() => {
                                notifElement.remove();

                                // Check if no notifications left
                                const notificationList = document.getElementById('notification-list');
                                if (notificationList && notificationList.children.length === 0) {
                                    notificationList.innerHTML = `
                                        <div class="p-4 text-center text-gray-500 text-sm">
                                            Tidak ada notifikasi
                                        </div>
                                    `;
                                }

                                // Update counter
                                updateNotificationCounter();
                            }, 300);
                        }
                    }
                } else {
                    console.error('Failed to delete:', response.status);
                    alert('Gagal menghapus notifikasi');
                }
            } catch (error) {
                console.error('Error deleting notification:', error);
                alert('Terjadi kesalahan. Silakan coba lagi.');
            }
        }

        // Mark all as read
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

                if (response.ok) {
                    // Reload page to update all notifications
                    window.location.reload();
                } else {
                    alert('Gagal menandai semua sebagai dibaca');
                }
            } catch (error) {
                console.error('Error marking all as read:', error);
                alert('Terjadi kesalahan. Silakan coba lagi.');
            }
        }

        // Update notification counter
        function updateNotificationCounter() {
            const unreadCount = document.querySelectorAll('#notification-list .bg-blue-50').length;
            const counterElement = document.querySelector('.absolute.top-0.right-0.bg-red-500');

            if (unreadCount === 0) {
                if (counterElement) {
                    counterElement.remove();
                }
            } else if (counterElement) {
                counterElement.textContent = unreadCount;
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Custom Swal Theme
        const SwalCustom = Swal.mixin({
            confirmButtonColor: '#14b8a6', // Teal-500
            cancelButtonColor: '#6b7280', // Gray-500
            customClass: {
                popup: 'rounded-xl',
                confirmButton: 'rounded-lg',
                cancelButton: 'rounded-lg'
            }
        });

        // Success Messages with Custom Theme
        @if (session('success'))
            SwalCustom.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                toast: true,
                position: 'top-end'
            });
        @endif

        // Error Messages
        @if (session('error'))
            SwalCustom.fire({
                icon: 'error',
                title: 'Oops...',
                text: '{{ session('error') }}',
                confirmButtonColor: '#ef4444'
            });
        @endif

        // Validation Errors
        @if ($errors->any())
            SwalCustom.fire({
                icon: 'error',
                title: 'Validation Error',
                html: `
                <ul class="text-left text-sm space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            `,
                confirmButtonColor: '#ef4444'
            });
        @endif
    </script>
</body>

</html>

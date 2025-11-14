@extends('layouts.app')

@section('title', 'Tambah Access Point')
@section('header', 'Tambah Access Point Baru')

@section('content')
<div class="max-w-3xl mx-auto">
    <!-- Breadcrumb -->
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="flex items-center space-x-2 text-sm">
            <li>
                <a href="{{ route('admin.buildings.index') }}" class="text-gray-500 hover:text-gray-700">Manajemen Gedung</a>
            </li>
            <li>
                <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                </svg>
            </li>
            <li>
                <a href="{{ route('admin.buildings.show', $room->floor->building) }}" class="text-gray-500 hover:text-gray-700">{{ $room->floor->building->name }}</a>
            </li>
            <li>
                <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                </svg>
            </li>
            <li>
                <a href="{{ route('admin.floors.rooms.index', $room->floor) }}" class="text-gray-500 hover:text-gray-700">{{ $room->floor->display_name }}</a>
            </li>
            <li>
                <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                </svg>
            </li>
            <li>
                <a href="{{ route('admin.rooms.access-points.index', $room) }}" class="text-gray-500 hover:text-gray-700">{{ $room->name }}</a>
            </li>
            <li>
                <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                </svg>
            </li>
            <li>
                <span class="text-gray-900 font-medium">Tambah AP</span>
            </li>
        </ol>
    </nav>

    @if($errors->any())
    <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg">
        <ul class="list-disc list-inside text-sm">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Room Info Card -->
    <div class="bg-gradient-to-r from-teal-500 to-green-600 rounded-xl shadow-lg p-6 text-white mb-6">
        <h2 class="text-xl font-bold mb-2">{{ $room->name }}</h2>
        <div class="flex items-center space-x-6 text-sm text-teal-100">
            <span>{{ $room->floor->building->name }}</span>
            <span>{{ $room->floor->display_name }}</span>
            <span>{{ $room->width }} x {{ $room->height }} px</span>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-6">Form Access Point Baru</h2>

        <form method="POST" action="{{ route('admin.rooms.access-points.store', $room) }}" class="space-y-6">
            @csrf

            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    Nama Access Point <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="name" 
                       id="name"
                       value="{{ old('name') }}"
                       required
                       placeholder="Contoh: AP-GedungA-L1-R1-01"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                <p class="text-xs text-gray-500 mt-1">
                    Format rekomendasi: AP-NamaGedung-Lantai-NamaRuangan-Nomor
                </p>
            </div>

            <!-- Status -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                    Status <span class="text-red-500">*</span>
                </label>
                <select name="status" 
                        id="status"
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                    <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active (Aktif)</option>
                    <option value="offline" {{ old('status') === 'offline' ? 'selected' : '' }}>Offline (Mati)</option>
                    <option value="maintenance" {{ old('status') === 'maintenance' ? 'selected' : '' }}>Maintenance (Pemeliharaan)</option>
                </select>
            </div>

            <!-- Position -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="position_x" class="block text-sm font-medium text-gray-700 mb-2">
                        Posisi X <span class="text-red-500">*</span>
                    </label>
                    <input type="number" 
                           name="position_x" 
                           id="position_x"
                           value="{{ old('position_x', 50) }}"
                           min="0"
                           max="{{ $room->width }}"
                           required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                    <p class="text-xs text-gray-500 mt-1">Relatif ke ruangan (0 - {{ $room->width }})</p>
                </div>
                <div>
                    <label for="position_y" class="block text-sm font-medium text-gray-700 mb-2">
                        Posisi Y <span class="text-red-500">*</span>
                    </label>
                    <input type="number" 
                           name="position_y" 
                           id="position_y"
                           value="{{ old('position_y', 50) }}"
                           min="0"
                           max="{{ $room->height }}"
                           required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                    <p class="text-xs text-gray-500 mt-1">Relatif ke ruangan (0 - {{ $room->height }})</p>
                </div>
            </div>

            <!-- Notes -->
            <div>
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                    Catatan
                    <span class="text-gray-500 font-normal">(Opsional)</span>
                </label>
                <textarea name="notes" 
                          id="notes"
                          rows="4"
                          placeholder="Catatan tambahan tentang Access Point ini..."
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 resize-none">{{ old('notes') }}</textarea>
                <p class="text-xs text-gray-500 mt-1">
                    Contoh: Dekat pintu masuk, Di pojok ruangan, Perlu pengecekan berkala, dll.
                </p>
            </div>

            <!-- Info Box -->
            <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <div class="flex">
                    <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="text-sm text-blue-800">
                        <p class="font-medium mb-1">Informasi Posisi:</p>
                        <ul class="list-disc list-inside space-y-1 text-xs">
                            <li>Posisi X dan Y adalah koordinat relatif terhadap pojok kiri atas ruangan</li>
                            <li>Nilai (0, 0) berada di pojok kiri atas ruangan {{ $room->name }}</li>
                            <li>Access Point akan muncul sebagai titik berwarna di denah lantai</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex space-x-3 pt-4 border-t border-gray-200">
                <a href="{{ route('admin.rooms.access-points.index', $room) }}" 
                   class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 text-center transition">
                    Batal
                </a>
                <button type="submit"
                        class="flex-1 px-6 py-3 bg-teal-500 text-white font-medium rounded-lg hover:bg-teal-600 transition shadow-lg hover:shadow-xl">
                    Simpan Access Point
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
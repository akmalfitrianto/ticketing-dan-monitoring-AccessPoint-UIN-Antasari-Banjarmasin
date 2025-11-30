@extends('layouts.app')

@section('title', $building->name)
@section('header', 'Detail ' . $building->name)

@section('content')
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    <div class="space-y-6" x-data="buildingDetail()">
        <!-- Breadcrumb -->
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2 text-sm">
                <li>
                    <a href="{{ route('admin.buildings.index') }}" class="text-gray-500 hover:text-gray-700">Manajemen
                        Gedung</a>
                </li>
                <li>
                    <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                            clip-rule="evenodd"></path>
                    </svg>
                </li>
                <li>
                    <span class="text-gray-900 font-medium">{{ $building->name }}</span>
                </li>
            </ol>
        </nav>

        <!-- Building Info Header -->
        <div class="bg-gradient-to-r from-teal-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold mb-2">{{ $building->name }}</h1>
                    <p class="text-teal-100">{{ ucfirst(str_replace('_', ' ', $building->shape_type)) }} Shape</p>
                </div>
                <a href="{{ route('admin.buildings.edit', $building) }}"
                    class="px-4 py-2 bg-white text-teal-600 font-medium rounded-lg hover:bg-teal-50 transition">
                    Edit Gedung
                </a>
            </div>
            <div class="mt-6 grid grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                    <p class="text-teal-100 text-sm">Total Lantai</p>
                    <p class="text-2xl font-bold">{{ $building->total_floors }}</p>
                </div>
                <div>
                    <p class="text-teal-100 text-sm">Total Ruangan</p>
                    <p class="text-2xl font-bold">{{ $building->total_rooms }}</p>
                </div>
                <div>
                    <p class="text-teal-100 text-sm">Total AP</p>
                    <p class="text-2xl font-bold">{{ $building->total_access_points }}</p>
                </div>
                <div>
                    <p class="text-teal-100 text-sm">AP Status</p>
                    <div class="flex items-center space-x-2 mt-1">
                        <span class="text-sm">🟢 {{ $building->active_access_points }}</span>
                        <span class="text-sm">🔴 {{ $building->offline_access_points }}</span>
                        <span class="text-sm">🟡 {{ $building->maintenance_access_points }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Floors List -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Daftar Lantai & Ruangan</h2>
            </div>
            <div class="divide-y divide-gray-200">
                @foreach ($building->floors()->orderBy('floor_number')->get() as $floor)
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">{{ $floor->display_name }}</h3>
                                <p class="text-sm text-gray-600">
                                    {{ $floor->total_rooms }} ruangan • {{ $floor->total_access_points }} access points
                                </p>
                            </div>
                            <div class="flex items-center space-x-3">
                                <button
                                    @click="openFloorMap({{ $floor->id }}, '{{ $floor->display_name }}', {{ $floor->rooms->toJson() }})"
                                    class="px-4 py-2 bg-blue-500 text-white text-sm font-medium rounded-lg hover:bg-blue-600 transition flex items-center space-x-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7">
                                        </path>
                                    </svg>
                                    <span>Lihat Denah</span>
                                </button>
                                <a href="{{ route('admin.floors.rooms.index', $floor) }}"
                                    class="px-4 py-2 bg-teal-500 text-white text-sm font-medium rounded-lg hover:bg-teal-600 transition">
                                    Kelola Ruangan
                                </a>
                            </div>
                        </div>

                        @if ($floor->rooms->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach ($floor->rooms as $room)
                                    <div
                                        class="p-4 bg-gray-50 rounded-lg border border-gray-200 hover:border-teal-500 transition">
                                        <div class="flex items-center justify-between mb-2">
                                            <h4 class="font-medium text-gray-900">{{ $room->name }}</h4>
                                            <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded">
                                                {{ $room->total_access_points }} AP
                                            </span>
                                        </div>
                                        <div class="flex items-center space-x-3 text-xs text-gray-600 mb-3">
                                            <span>🟢 {{ $room->active_access_points }}</span>
                                            <span>🔴 {{ $room->offline_access_points }}</span>
                                            <span>🟡 {{ $room->maintenance_access_points }}</span>
                                        </div>
                                        <a href="{{ route('admin.rooms.access-points.index', $room) }}"
                                            class="text-sm text-teal-600 hover:text-teal-700 font-medium">
                                            Kelola AP →
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8 text-gray-500">
                                <p class="text-sm">Belum ada ruangan di lantai ini</p>
                                <a href="{{ route('admin.floors.rooms.create', $floor) }}"
                                    class="inline-block mt-2 text-sm text-teal-600 hover:text-teal-700 font-medium">
                                    Tambah Ruangan →
                                </a>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <a href="{{ route('building.show', $building) }}"
                class="p-6 bg-white rounded-xl shadow-sm border border-gray-200 hover:border-teal-500 transition group">
                <div class="flex items-center space-x-4">
                    <div
                        class="w-12 h-12 bg-teal-100 rounded-lg flex items-center justify-center group-hover:bg-teal-200 transition">
                        <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">Lihat Denah</p>
                        <p class="text-sm text-gray-600">View building map</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.buildings.edit', $building) }}"
                class="p-6 bg-white rounded-xl shadow-sm border border-gray-200 hover:border-blue-500 transition group">
                <div class="flex items-center space-x-4">
                    <div
                        class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center group-hover:bg-blue-200 transition">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">Edit Gedung</p>
                        <p class="text-sm text-gray-600">Update building info</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.buildings.index') }}"
                class="p-6 bg-white rounded-xl shadow-sm border border-gray-200 hover:border-gray-500 transition group">
                <div class="flex items-center space-x-4">
                    <div
                        class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center group-hover:bg-gray-200 transition">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">Kembali</p>
                        <p class="text-sm text-gray-600">Back to buildings list</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Floor Map Modal -->
        <div x-show="showFloorMap" x-cloak
            class="fixed inset-0 z-50 bg-black bg-opacity-75 flex items-center justify-center p-4"
            @click.self="closeFloorMap()">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-5xl flex flex-col" style="height: 85vh;">
                <!-- Header -->
                <div class="flex items-center justify-between p-4 border-b border-gray-200">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900" x-text="'Denah ' + currentFloorName"></h3>
                        <p class="text-sm text-gray-600" x-text="currentRooms.length + ' ruangan'"></p>
                    </div>
                    <button type="button" @click="closeFloorMap()"
                        class="p-2 hover:bg-gray-100 rounded-lg transition-colors" title="Close">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Canvas -->
                <div class="flex-1 p-6 overflow-hidden flex items-center justify-center">
                    <div
                        class="bg-gray-50 rounded-lg border-2 border-gray-300 w-full h-full flex items-center justify-center">
                        <!-- Debug Info -->
                        <div class="p-4" x-show="currentRooms.length === 0">
                            <div class="text-center py-12">
                                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                                    </path>
                                </svg>
                                <p class="text-gray-600 font-medium mb-2">Belum ada ruangan di lantai ini</p>
                                <p class="text-sm text-gray-500">Tambahkan ruangan terlebih dahulu untuk melihat denah</p>
                            </div>
                        </div>
                        <svg viewBox="0 0 1000 700" class="max-w-full max-h-full" x-show="currentRooms.length > 0"
                            style="width: 90%; height: 90%;">
                            <defs>
                                <pattern id="floorGrid" width="50" height="50" patternUnits="userSpaceOnUse">
                                    <path d="M 50 0 L 0 0 0 50" fill="none" stroke="#e5e7eb" stroke-width="1" />
                                </pattern>
                            </defs>

                            <!-- Grid Background -->
                            <rect width="100%" height="100%" fill="url(#floorGrid)" />

                            <g x-html="renderRooms()"></g>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function buildingDetail() {
            return {
                showFloorMap: false,
                currentFloorId: null,
                currentFloorName: '',
                currentRooms: [],

                openFloorMap(floorId, floorName, rooms) {
                    this.currentFloorId = floorId;
                    this.currentFloorName = floorName;
                    this.currentRooms = rooms;
                    this.showFloorMap = true;
                    document.body.style.overflow = 'hidden';
                },

                closeFloorMap() {
                    this.showFloorMap = false;
                    document.body.style.overflow = '';
                },
                renderRooms() {
                    if (this.currentRooms.length === 0) return '';

                    // Calculate bounding box of all rooms
                    let minX = Infinity,
                        minY = Infinity;
                    let maxX = -Infinity,
                        maxY = -Infinity;

                    this.currentRooms.forEach(room => {
                        const x = room.position_x || 100;
                        const y = room.position_y || 100;
                        const w = room.width || 450;
                        const h = room.height || 250;

                        minX = Math.min(minX, x);
                        minY = Math.min(minY, y);
                        maxX = Math.max(maxX, x + w);
                        maxY = Math.max(maxY, y + h);
                    });

                    // Calculate center and scale
                    const contentWidth = maxX - minX;
                    const contentHeight = maxY - minY;
                    const centerX = minX + contentWidth / 2;
                    const centerY = minY + contentHeight / 2;

                    // Target center of viewBox (1000x700)
                    const viewBoxCenterX = 500;
                    const viewBoxCenterY = 350;

                    // Calculate offset to center content
                    const offsetX = viewBoxCenterX - centerX;
                    const offsetY = viewBoxCenterY - centerY;

                    return this.currentRooms.map(room => {
                        const x = (room.position_x || 100) + offsetX;
                        const y = (room.position_y || 100) + offsetY;
                        const w = room.width || 450;
                        const h = room.height || 250;
                        const color = room.color || '#93c5fd';

                        return `
                <g>
                    <!-- Room Rectangle -->
                    <rect 
                        x="${x}" 
                        y="${y}" 
                        width="${w}" 
                        height="${h}"
                        fill="${color}"
                        stroke="#1e40af" 
                        stroke-width="3"
                        opacity="0.8" />
                    
                    <!-- Room Label -->
                    <text 
                        x="${x + w / 2}" 
                        y="${y + h / 2}"
                        text-anchor="middle" 
                        dominant-baseline="middle"
                        font-size="18"
                        font-weight="bold"
                        fill="#1e3a8a">
                        ${room.name}
                    </text>
                </g>
            `;
                    }).join('');
                }
            }
        }
    </script>
@endsection

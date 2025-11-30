@extends('layouts.app')

@section('title', 'Edit Ruangan')
@section('header', 'Edit Ruangan')

@section('content')
    <div class="max-w-6xl mx-auto">
        <!-- Breadcrumb -->
        <nav class="flex mb-6" aria-label="Breadcrumb">
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
                    <a href="{{ route('admin.buildings.show', $room->floor->building) }}"
                        class="text-gray-500 hover:text-gray-700">{{ $room->floor->building->name }}</a>
                </li>
                <li>
                    <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                            clip-rule="evenodd"></path>
                    </svg>
                </li>
                <li>
                    <a href="{{ route('admin.floors.rooms.index', $room->floor) }}"
                        class="text-gray-500 hover:text-gray-700">{{ $room->floor->display_name }}</a>
                </li>
                <li>
                    <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                            clip-rule="evenodd"></path>
                    </svg>
                </li>
                <li>
                    <span class="text-gray-900 font-medium">Edit {{ $room->name }}</span>
                </li>
            </ol>
        </nav>

        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.rooms.update', $room) }}" class="space-y-6" x-data="roomForm()">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Form -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-6">
                    <h2 class="text-lg font-semibold text-gray-900">Edit Informasi Ruangan</h2>

                    <!-- Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Ruangan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" x-model="name" required value="{{ old('name', $room->name) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                    </div>

                    <!-- Shape Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Bentuk Ruangan <span class="text-red-500">*</span>
                        </label>
                        <select name="shape_type" x-model="shape_type" @change="updatePreview()" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                            <option value="rectangle" {{ $room->shape_type === 'rectangle' ? 'selected' : '' }}>Rectangle
                                (Persegi Panjang)</option>
                            <option value="square" {{ $room->shape_type === 'square' ? 'selected' : '' }}>Square (Persegi)
                            </option>
                            <option value="l_shape" {{ $room->shape_type === 'l_shape' ? 'selected' : '' }}>L-Shape
                            </option>
                            <option value="u_shape" {{ $room->shape_type === 'u_shape' ? 'selected' : '' }}>U-Shape
                            </option>
                            <option value="custom" {{ $room->shape_type === 'custom' ? 'selected' : '' }}>Custom SVG Path
                            </option>
                        </select>
                    </div>

                    <!-- Custom SVG Path -->
                    <div x-show="shape_type === 'custom'" x-cloak>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            SVG Path
                        </label>
                        <textarea name="svg_path" x-model="svg_path" rows="3"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 resize-none font-mono text-sm">{{ old('svg_path', $room->svg_path) }}</textarea>
                    </div>

                    <!-- Dimensions -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Lebar (px) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="width" x-model.number="width" @input="updatePreview()"
                                min="50" max="500" required value="{{ old('width', $room->width) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Tinggi (px) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="height" x-model.number="height" @input="updatePreview()"
                                min="50" max="500" required value="{{ old('height', $room->height) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                        </div>
                    </div>

                    <!-- Position -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Posisi X <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="position_x" x-model.number="position_x" min="0"
                                max="1000" required value="{{ old('position_x', $room->position_x) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                            <p class="text-xs text-gray-500 mt-1">Atau drag ruangan di preview</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Posisi Y <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="position_y" x-model.number="position_y" min="0"
                                max="600" required value="{{ old('position_y', $room->position_y) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                            <p class="text-xs text-gray-500 mt-1">Atau drag ruangan di preview</p>
                        </div>
                    </div>

                    <!-- Color -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Warna
                        </label>
                        <div class="flex items-center space-x-3">
                            <input type="color" name="color" x-model="color" @input="updatePreview()"
                                value="{{ old('color', $room->color) }}"
                                class="h-10 w-20 border border-gray-300 rounded cursor-pointer">
                            <input type="text" x-model="color" @input="updatePreview()"
                                value="{{ old('color', $room->color) }}"
                                class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                        </div>
                    </div>

                    <!-- Room Stats -->
                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <p class="text-sm font-medium text-gray-700 mb-2">Statistik Ruangan:</p>
                        <div class="grid grid-cols-2 gap-3 text-sm text-gray-600">
                            <div>
                                <span class="font-medium">Access Points:</span> {{ $room->total_access_points }}
                            </div>
                            <div>
                                <span class="font-medium">AP Aktif:</span> {{ $room->active_access_points }}
                            </div>
                            <div>
                                <span class="font-medium">AP Offline:</span> {{ $room->offline_access_points }}
                            </div>
                            <div>
                                <span class="font-medium">Maintenance:</span> {{ $room->maintenance_access_points }}
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex space-x-3 pt-4 border-t border-gray-200">
                        <a href="{{ route('admin.floors.rooms.index', $room->floor) }}"
                            class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 text-center transition">
                            Batal
                        </a>
                        <button type="submit"
                            class="flex-1 px-6 py-3 bg-teal-500 text-white font-medium rounded-lg hover:bg-teal-600 transition shadow-lg hover:shadow-xl">
                            Update Ruangan
                        </button>
                    </div>
                </div>

                <!-- Live Preview -->
                <div class="space-y-6">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-6">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-semibold text-gray-900">Live Preview</h2>
                            <span class="text-xs px-3 py-1 bg-teal-100 text-teal-700 rounded-full font-medium">
                                Klik dan Drag untuk memindahkan
                            </span>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4 border-2 border-gray-300 relative" style="height: 500px;">
                            <svg id="previewCanvas" viewBox="0 0 1000 600" class="w-full h-full"
                                @mousedown="startDrag($event)" @mousemove="drag($event)" @mouseup="stopDrag()"
                                @mouseleave="stopDrag()" style="cursor: grab;">
                                <!-- Grid -->
                                <defs>
                                    <pattern id="previewGrid" width="50" height="50"
                                        patternUnits="userSpaceOnUse">
                                        <path d="M 50 0 L 0 0 0 50" fill="none" stroke="#e5e7eb" stroke-width="1" />
                                    </pattern>
                                </defs>
                                <rect width="100%" height="100%" fill="url(#previewGrid)" />

                                <!-- Other Existing Rooms (Background Context) -->
                                @foreach ($existingRooms as $existingRoom)
                                    <g opacity="0.5">
                                        <path d="{{ $existingRoom->generateSvgPath() }}" fill="#e0e7ff" stroke="#818cf8"
                                            stroke-width="1.5" stroke-dasharray="5,5" />
                                        <text x="{{ $existingRoom->position_x + $existingRoom->width / 2 }}"
                                            y="{{ $existingRoom->position_y + $existingRoom->height / 2 }}"
                                            text-anchor="middle" class="text-xs" fill="#6366f1" opacity="0.7"
                                            style="pointer-events: none;">
                                            {{ $existingRoom->name }}
                                        </text>
                                    </g>
                                @endforeach

                                <!-- Current Room Being Edited (Highlighted) -->
                                <g id="roomPreview" :style="isDragging ? 'cursor: grabbing;' : 'cursor: grab;'">
                                    <path id="roomPath" :d="previewPath" :fill="color" stroke="#14b8a6"
                                        stroke-width="3" class="drop-shadow-lg" />

                                    <!-- Glow effect -->
                                    <path :d="previewPath" fill="none" stroke="#14b8a6" stroke-width="6"
                                        opacity="0.3" />

                                    <text :x="position_x + (width / 2)" :y="position_y + (height / 2)" text-anchor="middle"
                                        class="text-sm font-bold" fill="#0f766e" style="pointer-events: none;"
                                        x-text="name">
                                    </text>
                                </g>
                            </svg>
                        </div>

                        <!-- Context Info -->
                        <div class="mt-4 space-y-3">
                            <!-- Statistics -->
                            <div class="p-3 bg-gradient-to-r from-gray-50 to-teal-50 border border-gray-200 rounded-lg">
                                <div class="grid grid-cols-2 gap-2 text-xs">
                                    <div class="flex items-center">
                                        <span class="w-2 h-2 bg-indigo-400 rounded-full mr-2"></span>
                                        <span class="text-gray-700">Ruangan Lain:
                                            <strong>{{ $existingRooms->count() }}</strong></span>
                                    </div>
                                    <div class="flex items-center">
                                        <span class="w-2 h-2 bg-teal-500 rounded-full mr-2"></span>
                                        <span class="text-gray-700">Sedang Diedit: <strong>1</strong></span>
                                    </div>
                                </div>
                            </div>
                            <!-- Room Stats -->
                            <div class="p-4 bg-gray-50 border border-gray-200 rounded-lg">
                                <p class="text-sm font-medium text-gray-700 mb-2">Statistik Ruangan:</p>
                                <div class="grid grid-cols-2 gap-3 text-xs text-gray-600">
                                    <div>
                                        <span class="font-medium">Access Points:</span> {{ $room->total_access_points }}
                                    </div>
                                    <div>
                                        <span class="font-medium">AP Aktif:</span> {{ $room->active_access_points }}
                                    </div>
                                    <div>
                                        <span class="font-medium">AP Offline:</span> {{ $room->offline_access_points }}
                                    </div>
                                    <div>
                                        <span class="font-medium">Maintenance:</span>
                                        {{ $room->maintenance_access_points }}
                                    </div>
                                </div>
                            </div>

                            <!-- Warning if has APs -->
                            @if ($room->accessPoints()->count() > 0)
                                <div class="p-3 bg-yellow-50 border border-yellow-200 rounded-lg text-xs text-yellow-800">
                                    <p class="font-medium mb-1">Perhatian:</p>
                                    <p>Ruangan ini memiliki <strong>{{ $room->accessPoints()->count() }} Access
                                            Point</strong>.
                                        Mengubah ukuran atau posisi akan mempengaruhi tampilan AP di denah.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        function roomForm() {
            return {
                name: '{{ old('name', $room->name) }}',
                shape_type: '{{ old('shape_type', $room->shape_type) }}',
                svg_path: '{{ old('svg_path', $room->svg_path) }}',
                width: {{ old('width', $room->width) }},
                height: {{ old('height', $room->height) }},
                position_x: {{ old('position_x', $room->position_x) }},
                position_y: {{ old('position_y', $room->position_y) }},
                color: '{{ old('color', $room->color) }}',
                previewPath: '',

                // Drag state
                isDragging: false,
                dragOffsetX: 0,
                dragOffsetY: 0,

                init() {
                    this.updatePreview();
                },

                updatePreview() {
                    const w = this.width;
                    const h = this.height;
                    const x = this.position_x;
                    const y = this.position_y;

                    switch (this.shape_type) {
                        case 'rectangle':
                        case 'square':
                            this.previewPath = `M ${x} ${y} L ${x + w} ${y} L ${x + w} ${y + h} L ${x} ${y + h} Z`;
                            break;
                        case 'l_shape':
                            const legW = w * 0.4;
                            const legH = h * 0.6;
                            this.previewPath =
                                `M ${x} ${y} L ${x + w} ${y} L ${x + w} ${y + legH} L ${x + legW} ${y + legH} L ${x + legW} ${y + h} L ${x} ${y + h} Z`;
                            break;
                        case 'u_shape':
                            this.previewPath =
                                `M ${x} ${y} L ${x + w} ${y} L ${x + w} ${y + h} L ${x + w - (w * 0.35)} ${y + h} L ${x + w - (w * 0.35)} ${y + (h * 0.5)} L ${x + (w * 0.35)} ${y + (h * 0.5)} L ${x + (w * 0.35)} ${y + h} L ${x} ${y + h} Z`;
                            break;
                        case 'custom':
                            this.previewPath = this.svg_path ||
                                `M ${x} ${y} L ${x + w} ${y} L ${x + w} ${y + h} L ${x} ${y + h} Z`;
                            break;
                    }
                },

                startDrag(event) {
                    const svg = document.getElementById('previewCanvas');
                    const rect = svg.getBoundingClientRect();

                    // Convert mouse position to SVG coordinates
                    const svgX = ((event.clientX - rect.left) / rect.width) * 1000;
                    const svgY = ((event.clientY - rect.top) / rect.height) * 600;

                    // Check if click is inside room bounds
                    if (svgX >= this.position_x && svgX <= this.position_x + this.width &&
                        svgY >= this.position_y && svgY <= this.position_y + this.height) {

                        this.isDragging = true;
                        this.dragOffsetX = svgX - this.position_x;
                        this.dragOffsetY = svgY - this.position_y;

                        event.preventDefault();
                    }
                },

                drag(event) {
                    if (!this.isDragging) return;

                    const svg = document.getElementById('previewCanvas');
                    const rect = svg.getBoundingClientRect();

                    // Convert mouse position to SVG coordinates
                    let svgX = ((event.clientX - rect.left) / rect.width) * 1000;
                    let svgY = ((event.clientY - rect.top) / rect.height) * 600;

                    // Calculate new position with offset
                    let newX = svgX - this.dragOffsetX;
                    let newY = svgY - this.dragOffsetY;

                    // Constrain to canvas bounds
                    newX = Math.max(0, Math.min(newX, 1000 - this.width));
                    newY = Math.max(0, Math.min(newY, 600 - this.height));

                    // Round to nearest integer for cleaner values
                    this.position_x = Math.round(newX);
                    this.position_y = Math.round(newY);

                    this.updatePreview();
                },

                stopDrag() {
                    this.isDragging = false;
                }
            }
        }
    </script>
@endsection

@extends('layouts.app')

@section('title', 'Tambah Ruangan')
@section('header', 'Tambah Ruangan Baru')

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
                    <a href="{{ route('admin.buildings.show', $floor->building) }}"
                        class="text-gray-500 hover:text-gray-700">{{ $floor->building->name }}</a>
                </li>
                <li>
                    <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                            clip-rule="evenodd"></path>
                    </svg>
                </li>
                <li>
                    <a href="{{ route('admin.floors.rooms.index', $floor) }}"
                        class="text-gray-500 hover:text-gray-700">{{ $floor->display_name }}</a>
                </li>
                <li>
                    <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                            clip-rule="evenodd"></path>
                    </svg>
                </li>
                <li>
                    <span class="text-gray-900 font-medium">Tambah Ruangan</span>
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

        <form method="POST" action="{{ route('admin.floors.rooms.store', $floor) }}" class="space-y-6"
            x-data="roomForm()">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Form -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-6">
                    <h2 class="text-lg font-semibold text-gray-900">Informasi Ruangan</h2>

                    <!-- Floor Info -->
                    <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <p class="text-sm font-medium text-blue-900">Lokasi:</p>
                        <p class="text-sm text-blue-800">{{ $floor->building->name }} - {{ $floor->display_name }}</p>
                    </div>

                    <!-- Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Ruangan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" x-model="name" required placeholder="Contoh: Lab Komputer 1"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                    </div>

                    <!-- Shape Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Bentuk Ruangan <span class="text-red-500">*</span>
                        </label>
                        <select name="shape_type" x-model="shape_type" @change="updatePreview()" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                            <option value="rectangle">Rectangle (Persegi Panjang)</option>
                            <option value="square">Square (Persegi)</option>
                            <option value="l_shape">L-Shape</option>
                            <option value="u_shape">U-Shape</option>
                            <option value="custom">Custom SVG Path</option>
                        </select>
                    </div>

                    <!-- Custom SVG Path -->
                    <div x-show="shape_type === 'custom'" x-cloak>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            SVG Path
                        </label>
                        <textarea name="svg_path" x-model="svg_path" rows="3" placeholder="M 100 200 L 100 100..."
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 resize-none font-mono text-sm"></textarea>
                    </div>

                    <!-- Dimensions -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Lebar (px) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="width" x-model.number="width" @input="updatePreview()"
                                min="50" max="500" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Tinggi (px) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="height" x-model.number="height" @input="updatePreview()"
                                min="50" max="500" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                        </div>
                    </div>

                    <!-- Position -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Posisi X <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="position_x" x-model.number="position_x" @input="updatePreview()"
                                min="0" max="1000" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Posisi Y <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="position_y" x-model.number="position_y" @input="updatePreview()"
                                min="0" max="600" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                        </div>
                    </div>

                    <!-- Color -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Warna
                        </label>
                        <div class="flex items-center space-x-3">
                            <input type="color" name="color" x-model="color" @input="updatePreview()"
                                class="h-10 w-20 border border-gray-300 rounded cursor-pointer">
                            <input type="text" x-model="color" @input="updatePreview()" placeholder="#bfdbfe"
                                class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex space-x-3 pt-4 border-t border-gray-200">
                        <a href="{{ route('admin.floors.rooms.index', $floor) }}"
                            class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 text-center transition">
                            Batal
                        </a>
                        <button type="submit"
                            class="flex-1 px-6 py-3 bg-teal-500 text-white font-medium rounded-lg hover:bg-teal-600 transition shadow-lg hover:shadow-xl">
                            Simpan Ruangan
                        </button>
                    </div>
                </div>

                <!-- Live Preview -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Live Preview</h2>

                    <div class="bg-gray-50 rounded-lg p-4 border-2 border-gray-300">
                        <svg id="previewCanvas" viewBox="0 0 1000 600" class="w-full" style="height: 500px;">
                            <!-- Grid -->
                            <defs>
                                <pattern id="previewGrid" width="50" height="50" patternUnits="userSpaceOnUse">
                                    <path d="M 50 0 L 0 0 0 50" fill="none" stroke="#e5e7eb" stroke-width="1" />
                                </pattern>
                            </defs>
                            <rect width="100%" height="100%" fill="url(#previewGrid)" />

                            <!-- Existing Rooms -->
                            @foreach ($existingRooms as $existingRoom)
                                <g opacity="0.5">
                                    <path d="{{ $existingRoom->generateSvgPath() }}" fill="#e0e7ff" stroke="#818cf8"
                                        stroke-width="1.5" stroke-dasharray="5,5" />
                                    <text x="{{ $existingRoom->position_x + $existingRoom->width / 2 }}"
                                        y="{{ $existingRoom->position_y + $existingRoom->height / 2 }}" text-anchor="middle"
                                        class="text-xs" fill="#6366f1" opacity="0.7">
                                        {{ $existingRoom->name }}
                                    </text>
                                </g>
                            @endforeach

                            <!--  New Room -->
                            <g>
                                <path id="roomPath" :d="previewPath" :fill="color" stroke="#14b8a6"
                                    stroke-width="3" class="drop-shadow-lg" />

                                <!-- Glow effect for new room -->
                                <path :d="previewPath" fill="none" stroke="#14b8a6" stroke-width="6"
                                    opacity="0.3" />

                                <text :x="position_x + (width / 2)" :y="position_y + (height / 2)" text-anchor="middle"
                                    class="text-sm font-bold" fill="#0f766e" x-text="name || 'Ruangan Baru'">
                                </text>
                            </g>
                        </svg>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        function roomForm() {
            return {
                name: '',
                shape_type: 'rectangle',
                svg_path: '',
                width: 150,
                height: 150,
                position_x: 100,
                position_y: 100,
                color: '#bfdbfe',
                previewPath: '',

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
                }
            }
        }
    </script>
@endsection

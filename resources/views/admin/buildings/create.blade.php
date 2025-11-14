@extends('layouts.app')

@section('title', 'Tambah Gedung')
@section('header', 'Tambah Gedung Baru')

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
                    <span class="text-gray-900 font-medium">Tambah Gedung</span>
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

        <form method="POST" action="{{ route('admin.buildings.store') }}" class="space-y-6" x-data="buildingForm()">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Form -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-6">
                    <h2 class="text-lg font-semibold text-gray-900">Informasi Gedung</h2>

                    <!-- Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Gedung <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" x-model="name" required placeholder="Contoh: Gedung Rektorat"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                    </div>

                    <!-- Total Floors -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Jumlah Lantai <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="total_floors" x-model.number="total_floors" min="1"
                            max="20" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                    </div>

                    <!-- Shape Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Bentuk Gedung <span class="text-red-500">*</span>
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

                    {{-- Rotation --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Rotasi Gedung</label>
                        <select name="rotation" x-model.number="rotation" @change="updatePreview()"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                            <option value="0">Normal</option>
                            <option value="90">Rotate Right</option>
                            <option value="180">Rotate Down</option>
                            <option value="270">Rotate Left</option>
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Pilih arah gedung pada denah kampus</p>
                    </div>

                    <!-- Custom SVG Path (if custom) -->
                    <div x-show="shape_type === 'custom'" x-cloak>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            SVG Path <span class="text-red-500">*</span>
                        </label>
                        <textarea name="svg_path" x-model="svg_path" rows="3" placeholder="M 100 200 L 100 100..."
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 resize-none font-mono text-sm"></textarea>
                        <p class="text-xs text-gray-500 mt-1">Paste SVG path dari software design</p>
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
                                min="0" max="1200" required
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
                            <input type="text" x-model="color" @input="updatePreview()" placeholder="#5eead4"
                                class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex space-x-3 pt-4 border-t border-gray-200">
                        <a href="{{ route('admin.buildings.index') }}"
                            class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 text-center transition">
                            Batal
                        </a>
                        <button type="submit"
                            class="flex-1 px-6 py-3 bg-teal-500 text-white font-medium rounded-lg hover:bg-teal-600 transition shadow-lg hover:shadow-xl">
                            Simpan Gedung
                        </button>
                    </div>
                </div>

                <!-- Live Preview -->
                <div class="space-y-6">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Live Preview</h2>

                        <div class="bg-gray-50 rounded-lg border-2 border-gray-300 p-4" style="height: 400px;">
                            <svg id="previewSvg" viewBox="0 0 1200 600" class="w-full h-full">
                                <!-- Grid Background -->
                                <defs>
                                    <pattern id="previewGrid" width="50" height="50"
                                        patternUnits="userSpaceOnUse">
                                        <path d="M 50 0 L 0 0 0 50" fill="none" stroke="#e5e7eb" stroke-width="1" />
                                    </pattern>
                                </defs>
                                <rect width="100%" height="100%" fill="url(#previewGrid)" />

                                <!-- Existing Buildings -->
                                @foreach ($existingBuildings as $existingBuilding)
                                    <g opacity="0.4">
                                        @if ($existingBuilding->rotation > 0)
                                            <g
                                                transform="rotate({{ $existingBuilding->rotation }} {{ $existingBuilding->position_x + $existingBuilding->width / 2 }} {{ $existingBuilding->position_y + $existingBuilding->height / 2 }})">
                                                <path d="{{ $existingBuilding->generateSvgPath() }}" fill="#e0e7eb"
                                                    stroke="#9ca3af" stroke-width="2" stroke-dasharray="8,4" />
                                            </g>
                                        @else
                                            <path d="{{ $existingBuilding->generateSvgPath() }}" fill="#e0e7eb"
                                                stroke="#9ca3af" stroke-width="2" stroke-dasharray="8,4" />
                                        @endif

                                        <text x="{{ $existingBuilding->position_x + $existingBuilding->width / 2 }}"
                                            y="{{ $existingBuilding->position_y + $existingBuilding->height / 2 }}"
                                            text-anchor="middle" class="text-xs" fill="#6b7280" opacity="0.7">
                                            {{ $existingBuilding->name }}
                                        </text>
                                    </g>
                                @endforeach

                                <!--  New Building -->
                                <g id="buildingPreview">
                                    <g id="buildingShape"
                                        :transform="rotation > 0 ?
                                            `rotate(${rotation} ${position_x + width/2} ${position_y + height/2})` : ''">
                                        <path id="buildingPath" :d="previewPath" :fill="color"
                                            stroke="#14b8a6" stroke-width="3" />

                                        <!-- Glow effect -->
                                        <path :d="previewPath" fill="none" stroke="#14b8a6" stroke-width="6"
                                            opacity="0.3" />
                                    </g>

                                    <!-- Label (tidak ikut rotate) -->
                                    <text id="buildingLabel" :x="position_x + width / 2" :y="position_y + height / 2"
                                        text-anchor="middle" class="text-sm font-bold pointer-events-none" fill="#0f766e"
                                        x-text="name || 'Gedung Baru'">
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
                                        <span class="w-2 h-2 bg-gray-400 rounded-full mr-2"></span>
                                        <span class="text-gray-700">Gedung Existing:
                                            <strong>{{ $existingBuildings->count() }}</strong></span>
                                    </div>
                                    <div class="flex items-center">
                                        <span class="w-2 h-2 bg-teal-500 rounded-full mr-2"></span>
                                        <span class="text-gray-700">Gedung Baru: <strong>1</strong></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Rotation Info -->
                            <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                <p class="text-xs text-blue-800">
                                    <strong>Preview Rotation:</strong><br>
                                    <span
                                        x-text="rotation + '° - ' + (rotation === 0 ? 'Normal' : rotation === 90 ? 'Ke Kanan' : rotation === 180 ? 'Terbalik' : 'Ke Kiri')"></span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Info -->
                <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg text-sm text-blue-800">
                    <p class="font-medium mb-2">Preview Info:</p>
                    <div class="space-y-1 text-xs">
                        <p><strong>Rotation:</strong> <span x-text="rotation + '°'"></span>
                            <span
                                x-text="rotation === 0 ? '(Normal)' : rotation === 90 ? '(Ke Kanan)' : rotation === 180 ? '(Terbalik)' : '(Ke Kiri)'"></span>
                        </p>
                        <p><strong>Tips:</strong> Sesuaikan posisi X dan Y untuk menempatkan gedung di denah</p>
                        <p><strong>Canvas size:</strong> 1200x600 px</p>
                    </div>
                </div>
            </div>
    </div>
    </form>
    </div>

    <script>
        function buildingForm() {
            return {
                name: '',
                total_floors: 1,
                shape_type: 'rectangle',
                svg_path: '',
                width: 150,
                height: 200,
                position_x: 200,
                position_y: 150,
                color: '#5eead4',
                rotation: 0,
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
                            const innerW = w * 0.3;
                            const innerH = h * 0.5;
                            this.previewPath =
                                `M ${x} ${y} L ${x + w} ${y} L ${x + w} ${y + h} L ${x + w - (w * 0.35)} ${y + h} L ${x + w - (w * 0.35)} ${y + innerH} L ${x + (w * 0.35)} ${y + innerH} L ${x + (w * 0.35)} ${y + h} L ${x} ${y + h} Z`;
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

@extends('layouts.admin')
@section('title', 'Relasi Orang Tua - Siswa')
@section('icon', 'fas fa-people-roof')

@section('content')

<div class="mb-8">
    <p class="text-xs text-gray-400 uppercase tracking-widest mb-1">
        <i class="fas fa-people-roof mr-1"></i> Admin / Relasi Orang Tua
    </p>
    <h1 class="text-2xl font-extrabold text-gray-900">Relasi Orang Tua → Siswa</h1>
    <p class="text-sm text-gray-500 mt-1">Kelola siswa yang dimonitor oleh masing-masing akun orang tua.</p>
</div>

@if(session('success'))
    <div class="flex items-center gap-2 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl mb-6 text-sm font-medium">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="flex items-start gap-3 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl mb-6">
        <i class="fas fa-exclamation-circle mt-0.5 flex-shrink-0"></i>
        <span class="text-sm">{{ session('error') }}</span>
    </div>
@endif
@if($errors->any())
    <div class="flex items-start gap-3 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl mb-6">
        <i class="fas fa-exclamation-circle mt-0.5 flex-shrink-0"></i>
        <ul class="text-sm space-y-0.5">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
@endif

<div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="h-1 bg-gradient-to-r from-[#A41E35] to-rose-400"></div>
    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gray-50">
        <h2 class="font-bold text-gray-900">Daftar Orang Tua</h2>
        <span class="bg-gray-900 text-white text-xs font-bold px-3 py-1 rounded-full">{{ $parents->total() }}</span>
    </div>

    @php
        $studentsJson = $students->map(fn($s) => ['id' => $s->id, 'name' => $s->name])->values()->toJson();
    @endphp

    {{-- DESKTOP TABLE --}}
    <div class="hidden md:block overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-48">Orang Tua</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-52">Email</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Siswa Dimonitor</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-64">Ubah Relasi</th>
                    <th class="px-5 py-3 w-24"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($parents as $p)
                    @php
                        $selectedIds  = $p->children->pluck('id')->values()->toArray();
                        $selectedJson = json_encode($selectedIds);
                    @endphp
                    <tr class="hover:bg-gray-50 transition align-top">
                        <td class="px-5 py-4">
                            <p class="font-bold text-gray-900">{{ $p->name }}</p>
                        </td>
                        <td class="px-5 py-4 text-gray-500 text-xs">{{ $p->email }}</td>
                        <td class="px-5 py-4">
                            @if($p->children->count() > 0)
                                <div class="flex flex-wrap gap-1.5">
                                    @foreach($p->children as $child)
                                        <span class="inline-flex items-center gap-1 bg-blue-50 text-blue-700 border border-blue-200 text-xs font-semibold px-2.5 py-1 rounded-full">
                                            <i class="fas fa-user-graduate text-[10px]"></i>
                                            {{ $child->name }}
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-xs text-gray-400 italic">Belum ada siswa</span>
                            @endif
                        </td>
                        <td class="px-5 py-4">
                            <div x-data="multiSelect({{ $selectedJson }}, {{ $studentsJson }})">
                                <form method="POST" action="{{ route('admin.parent-students.update', $p) }}" @submit="prepareSubmit" class="space-y-2">
                                    @csrf @method('PUT')

                                    {{-- Hidden inputs (persist the selection reliably) --}}
                                    <template x-for="id in selected" :key="id">
                                        <input type="hidden" name="student_ids[]" :value="id">
                                    </template>
                                    <template x-if="selected.length === 0">
                                        <input type="hidden" name="student_ids" value="">
                                    </template>

                                    {{-- Selected summary badges (compact for table cell) --}}
                                    <div class="flex flex-wrap gap-1.5 min-h-[28px]">
                                        <template x-for="id in selected" :key="id">
                                            <span class="inline-flex items-center gap-1 bg-blue-50 text-blue-700 border border-blue-200 text-[11px] font-semibold px-2 py-0.5 rounded-full">
                                                <span x-text="nameById(id)"></span>
                                                <button type="button" @click="remove(id)" class="text-blue-400 hover:text-red-500 transition ml-0.5 leading-none">
                                                    <i class="fas fa-times text-[9px]"></i>
                                                </button>
                                            </span>
                                        </template>
                                        <span x-show="selected.length === 0" class="text-xs text-gray-400 italic self-center">Belum ada siswa dipilih</span>
                                    </div>

                                    {{-- Dropdown trigger --}}
                                    <div class="relative">
                                        <button type="button" @click="open = !open"
                                            class="w-full flex items-center justify-between px-3 py-2 border border-gray-200 rounded-xl text-sm transition"
                                            :class="open ? 'border-[#A41E35] ring-2 ring-red-100' : 'hover:border-gray-300'">
                                            <span class="text-gray-500 text-xs" x-text="selected.length ? selected.length + ' siswa dipilih' : 'Pilih siswa...'"></span>
                                            <i class="fas fa-chevron-down text-gray-400 text-xs transition-transform" :class="open ? 'rotate-180' : ''"></i>
                                        </button>

                                        {{-- Dropdown panel --}}
                                        <div x-show="open" x-transition:enter="transition ease-out duration-100"
                                            x-transition:enter-start="opacity-0 scale-y-95" x-transition:enter-end="opacity-100 scale-y-100"
                                            @click.outside="open = false"
                                            class="absolute z-20 mt-1 w-full bg-white border border-gray-200 rounded-xl shadow-lg overflow-hidden">

                                            {{-- Search --}}
                                            <div class="p-2 border-b border-gray-100">
                                                <div class="relative">
                                                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-300 text-xs"></i>
                                                    <input type="text" x-model="query" placeholder="Cari siswa..."
                                                        class="w-full pl-8 pr-3 py-2 text-xs border border-gray-200 rounded-lg focus:outline-none focus:border-[#A41E35] focus:ring-2 focus:ring-red-100 transition"
                                                        @click.stop>
                                                </div>
                                            </div>

                                            {{-- Select all / clear --}}
                                            <div class="flex items-center justify-between px-3 py-1.5 border-b border-gray-100 bg-gray-50">
                                                <button type="button" @click.stop="selectAll()" class="text-xs text-blue-600 hover:text-blue-800 font-semibold transition">
                                                    Pilih Semua
                                                </button>
                                                <button type="button" @click.stop="clearAll()" class="text-xs text-gray-400 hover:text-red-500 font-semibold transition">
                                                    Hapus Semua
                                                </button>
                                            </div>

                                            {{-- Options --}}
                                            <div class="max-h-48 overflow-y-auto">
                                                <template x-for="s in filtered" :key="s.id">
                                                    <label class="flex items-center gap-3 px-3 py-2 cursor-pointer transition-colors"
                                                        :class="isSelected(s.id) ? 'bg-blue-50' : 'hover:bg-gray-50'">
                                                        <input type="checkbox" class="sr-only" :checked="isSelected(s.id)" @change="toggle(s.id)">
                                                        <div class="w-4 h-4 rounded border-2 flex items-center justify-center flex-shrink-0 transition-colors"
                                                            :class="isSelected(s.id) ? 'bg-[#A41E35] border-[#A41E35]' : 'border-gray-300'">
                                                            <i class="fas fa-check text-white text-[8px]" x-show="isSelected(s.id)"></i>
                                                        </div>
                                                        <span class="text-xs" :class="isSelected(s.id) ? 'font-semibold text-gray-900' : 'text-gray-700'" x-text="s.name"></span>
                                                    </label>
                                                </template>
                                                <div x-show="filtered.length === 0" class="px-3 py-4 text-center text-xs text-gray-400">
                                                    Tidak ada hasil untuk "<span x-text="query"></span>"
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <button type="submit"
                                        class="inline-flex items-center justify-center gap-1.5 bg-[#A41E35] hover:bg-[#7D1627] text-white font-semibold px-3 py-2 rounded-xl text-xs transition shadow-sm whitespace-nowrap">
                                        <i class="fas fa-save text-[10px]"></i> Simpan
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- MOBILE CARDS --}}
    <div class="md:hidden divide-y divide-gray-100">
        @foreach($parents as $p)
            @php
                $selectedIds  = $p->children->pluck('id')->values()->toArray();
                $selectedJson = json_encode($selectedIds);
            @endphp
            <div class="p-5 space-y-4" x-data="multiSelect({{ $selectedJson }}, {{ $studentsJson }})">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="font-bold text-gray-900">{{ $p->name }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $p->email }}</p>
                    </div>
                    <span class="inline-flex items-center text-xs font-semibold px-2.5 py-1 rounded-full border"
                        :class="selected.length > 0 ? 'bg-blue-50 text-blue-600 border-blue-200' : 'bg-gray-100 text-gray-400 border-gray-200'">
                        <span x-text="selected.length"></span> siswa
                    </span>
                </div>

                <div class="flex flex-wrap gap-1.5 min-h-[28px]">
                    <template x-for="id in selected" :key="id">
                        <span class="inline-flex items-center gap-1 bg-blue-50 text-blue-700 border border-blue-200 text-xs font-semibold px-2.5 py-1 rounded-full">
                            <i class="fas fa-user-graduate text-[10px]"></i>
                            <span x-text="nameById(id)"></span>
                            <button type="button" @click="remove(id)" class="text-blue-400 hover:text-red-500 transition ml-0.5 leading-none">
                                <i class="fas fa-times text-[9px]"></i>
                            </button>
                        </span>
                    </template>
                    <span x-show="selected.length === 0" class="text-xs text-gray-400 italic self-center">Belum ada siswa dipilih</span>
                </div>

                <form method="POST" action="{{ route('admin.parent-students.update', $p) }}" @submit="prepareSubmit" class="space-y-2">
                    @csrf @method('PUT')

                    <template x-for="id in selected" :key="id">
                        <input type="hidden" name="student_ids[]" :value="id">
                    </template>
                    <template x-if="selected.length === 0">
                        <input type="hidden" name="student_ids" value="">
                    </template>

                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Ubah Relasi Siswa</label>

                    <div class="relative">
                        <button type="button" @click="open = !open"
                            class="w-full flex items-center justify-between px-4 py-2.5 border border-gray-200 rounded-xl text-sm transition"
                            :class="open ? 'border-[#A41E35] ring-2 ring-red-100' : 'hover:border-gray-300'">
                            <span class="text-gray-500" x-text="selected.length ? selected.length + ' siswa dipilih' : 'Pilih siswa...'"></span>
                            <i class="fas fa-chevron-down text-gray-400 text-xs transition-transform" :class="open ? 'rotate-180' : ''"></i>
                        </button>

                        <div x-show="open" x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="opacity-0 scale-y-95" x-transition:enter-end="opacity-100 scale-y-100"
                            @click.outside="open = false"
                            class="absolute z-20 mt-1 w-full bg-white border border-gray-200 rounded-xl shadow-lg overflow-hidden">

                            <div class="p-2 border-b border-gray-100">
                                <div class="relative">
                                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-300 text-xs"></i>
                                    <input type="text" x-model="query" placeholder="Cari siswa..."
                                        class="w-full pl-8 pr-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:border-[#A41E35] focus:ring-2 focus:ring-red-100 transition"
                                        @click.stop>
                                </div>
                            </div>

                            <div class="flex items-center justify-between px-3 py-1.5 border-b border-gray-100 bg-gray-50">
                                <button type="button" @click.stop="selectAll()" class="text-xs text-blue-600 hover:text-blue-800 font-semibold transition">
                                    Pilih Semua
                                </button>
                                <button type="button" @click.stop="clearAll()" class="text-xs text-gray-400 hover:text-red-500 font-semibold transition">
                                    Hapus Semua
                                </button>
                            </div>

                            <div class="max-h-56 overflow-y-auto">
                                <template x-for="s in filtered" :key="s.id">
                                    <label class="flex items-center gap-3 px-3 py-2.5 cursor-pointer transition-colors"
                                        :class="isSelected(s.id) ? 'bg-blue-50' : 'hover:bg-gray-50'">
                                        <input type="checkbox" class="sr-only" :checked="isSelected(s.id)" @change="toggle(s.id)">
                                        <div class="w-4 h-4 rounded border-2 flex items-center justify-center flex-shrink-0 transition-colors"
                                            :class="isSelected(s.id) ? 'bg-[#A41E35] border-[#A41E35]' : 'border-gray-300'">
                                            <i class="fas fa-check text-white text-[8px]" x-show="isSelected(s.id)"></i>
                                        </div>
                                        <span class="text-sm" :class="isSelected(s.id) ? 'font-semibold text-gray-900' : 'text-gray-700'" x-text="s.name"></span>
                                    </label>
                                </template>
                                <div x-show="filtered.length === 0" class="px-3 py-4 text-center text-xs text-gray-400">
                                    Tidak ada hasil untuk "<span x-text="query"></span>"
                                </div>
                            </div>
                        </div>
                    </div>

                    <p class="text-xs text-gray-400"><i class="fas fa-info-circle mr-1"></i>Centang untuk pilih lebih dari satu</p>

                    <button type="submit"
                        class="w-full inline-flex justify-center items-center gap-2 bg-[#A41E35] hover:bg-[#7D1627] text-white font-semibold px-4 py-2.5 rounded-xl text-sm transition shadow-sm">
                        <i class="fas fa-save text-xs"></i> Simpan Relasi
                    </button>
                </form>
            </div>
        @endforeach
    </div>

    @if($parents->hasPages())
        <div class="px-5 py-4 border-t border-gray-100">
            {{ $parents->links() }}
        </div>
    @endif
</div>

<script>
function multiSelect(initialSelected, allStudents) {
    return {
        open:     false,
        query:    '',
        selected: (initialSelected || []).map(id => Number(id)),
        students: allStudents || [],

        get filtered() {
            const q = (this.query || '').toLowerCase().trim();
            return q ? this.students.filter(s => (s.name || '').toLowerCase().includes(q)) : this.students;
        },

        isSelected(id) { return this.selected.includes(Number(id)); },

        toggle(id) {
            id = Number(id);
            if (this.isSelected(id)) this.selected = this.selected.filter(x => x !== id);
            else this.selected = [...this.selected, id];
        },

        remove(id) { this.selected = this.selected.filter(x => x !== Number(id)); },

        nameById(id) {
            const s = this.students.find(x => Number(x.id) === Number(id));
            return s ? s.name : '';
        },

        selectAll() { this.selected = this.filtered.map(s => Number(s.id)); },
        clearAll()  { this.selected = []; },

        prepareSubmit() { this.open = false; },
    };
}
</script>

@endsection
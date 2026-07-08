@extends('layouts.admin')

@section('page_title', 'Master Template Form')

@section('content')
<div class="space-y-6">
    
    <!-- Action Header -->
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-base font-bold text-slate-800 title-font">Daftar Template Form</h3>
            <p class="text-xs text-slate-500 mt-0.5">Kelola template form Change Request dinamis</p>
        </div>
        <a href="{{ route('admin.templates.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-xl shadow-md shadow-blue-600/10 transition-all duration-150">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Buat Template Baru
        </a>
    </div>

    <!-- Templates Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @if($templates->isEmpty())
            <div class="col-span-full bg-white p-12 rounded-2xl border border-slate-200 shadow-sm text-center">
                <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h4 class="text-sm font-bold text-slate-700">Belum ada template form</h4>
                <p class="text-xs text-slate-500 mt-1">Mulai dengan membuat template form change request pertama Anda.</p>
                <a href="{{ route('admin.templates.create') }}" class="inline-flex items-center gap-1.5 text-xs text-blue-600 hover:text-blue-700 hover:underline font-semibold mt-4">
                    Buat Sekarang
                </a>
            </div>
        @else
            @foreach($templates as $tpl)
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 relative flex flex-col justify-between hover:shadow-md hover:border-slate-300 transition-all duration-200">
                    <div>
                        <!-- Active status badge -->
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-[10px] font-bold px-2 py-0.5 uppercase tracking-wider rounded-full {{ $tpl->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-slate-100 text-slate-500 border border-slate-200' }}">
                                {{ $tpl->is_active ? 'Aktif' : 'Non-Aktif' }}
                            </span>
                            <span class="text-xs text-slate-400 font-semibold">Rev. {{ $tpl->revision }}</span>
                        </div>

                        <!-- Template Title -->
                        <h4 class="text-base font-bold text-slate-800 line-clamp-2 title-font tracking-tight mb-2">{{ $tpl->title }}</h4>
                        
                        <!-- Description -->
                        <p class="text-xs text-slate-500 line-clamp-3 leading-relaxed mb-6">{{ $tpl->description }}</p>

                        <!-- Details list -->
                        <div class="space-y-2 border-t border-slate-50 pt-4 mb-6">
                            <div class="flex items-center justify-between text-xs text-slate-500">
                                <span>Pembuat (Author):</span>
                                <span class="font-medium text-slate-700">{{ $tpl->author ?? '-' }}</span>
                            </div>
                            <div class="flex items-center justify-between text-xs text-slate-500">
                                <span>Tanggal Buat:</span>
                                <span class="font-medium text-slate-700">{{ $tpl->created_date ? $tpl->created_date->format('d-m-Y') : '-' }}</span>
                            </div>
                            <div class="flex items-center justify-between text-xs text-slate-500">
                                <span>Total Submission:</span>
                                <span class="font-bold text-blue-600">{{ $tpl->submissions_count }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-2 pt-4 border-t border-slate-100">
                        <a href="{{ route('admin.templates.edit', $tpl->id) }}" class="flex-grow inline-flex items-center justify-center px-4 py-2 bg-blue-50 text-blue-600 hover:bg-blue-100 text-xs font-semibold rounded-xl transition-all duration-150 gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Susun Form
                        </a>

                        <a href="{{ route('form.fill', $tpl->id) }}" target="_blank" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-slate-50 text-slate-500 hover:bg-slate-100 hover:text-slate-700 transition-colors" title="Buka Form Public">
                            <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                        </a>

                        <form action="{{ route('admin.templates.destroy', $tpl->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus template ini? Semua data pengisian user juga akan terhapus.')" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-rose-50 text-rose-500 hover:bg-rose-100 hover:text-rose-700 transition-colors" title="Hapus Template">
                                <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

</div>
@endsection

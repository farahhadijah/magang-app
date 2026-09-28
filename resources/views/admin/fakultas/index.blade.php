<x-app-layout>
<x-slot name="title">
    Fakultas - MagangApp
</x-slot>

@if(session('error'))
<div class="p-3 mb-4 text-red-800 bg-red-100 rounded-lg">
❌ {{ session('error') }}
</div>
@endif

@if(session('success'))
<div class="p-3 mb-4 text-green-800 bg-green-100 rounded-lg">
✅ {{ session('success') }}
</div>
@endif

<div class="px-4 py-6 mx-auto space-y-5 max-w-7xl">

{{-- HEADER: Judul + Aksi Utama --}}
<div class="p-5 border border-indigo-200 rounded-xl bg-gradient-to-r from-indigo-50 to-blue-50">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-lg font-bold text-gray-800">Manajemen Fakultas</h1>
            <p class="text-sm text-gray-600">
                Kelola data fakultas atau sinkronkan langsung dengan SIAKAD.
            </p>
        </div>

        <form action="{{ route('admin.fakultas.sync') }}" method="POST">
            @csrf
            <button
                type="submit"
                class="inline-flex items-center gap-2 px-6 py-3 text-base font-semibold text-white bg-indigo-600 rounded-lg shadow-lg animate-pulse hover:bg-indigo-700 hover:animate-none focus:ring-4 focus:ring-indigo-300">
                Sinkronisasi SIAKAD
            </button>
        </form>
    </div>
</div>

{{-- TOOLBAR: Tambah + Hapus Terpilih --}}
<div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

    {{-- Bulk Delete (kiri, hanya muncul logis saat dibutuhkan) --}}
    <form id="bulkDeleteForm"
          action="{{ route('admin.fakultas.bulkDelete') }}"
          method="POST"
          onsubmit="return confirm('Yakin ingin menghapus data terpilih?')">
        @csrf
        @method('DELETE')
        <input type="hidden" name="ids" id="bulkIds">
        <button type="submit"
                class="inline-flex items-center gap-2 px-4 py-2 text-sm text-white bg-red-600 rounded-lg hover:bg-red-700">
            Hapus Terpilih
        </button>
    </form>

    {{-- Tambah --}}
    <a href="{{ route('admin.fakultas.create') }}"
       class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">
        + Tambah Fakultas
    </a>
</div>

{{-- TABLE --}}
<div class="overflow-x-auto bg-white border border-gray-200 rounded-lg shadow-sm">
    <table class="w-full text-sm">
        <thead class="text-white bg-green-700">
            <tr>
                <th class="w-10 p-3 text-center border">
                    <input type="checkbox" id="selectAll">
                </th>
                <th class="w-16 p-3 text-left border">ID</th>
                <th class="p-3 text-left border">Nama</th>
                <th class="w-32 p-3 text-left border">Status</th>
                <th class="w-40 p-3 text-center border">Aksi</th>
            </tr>
        </thead>

        <tbody class="bg-white divide-y">
            @forelse ($fakultas as $f)
            <tr class="hover:bg-gray-50">
                <td class="p-3 text-center border">
                    <input type="checkbox"
                           value="{{ $f->id }}"
                           class="rowCheckbox">
                </td>
                <td class="p-3 border">{{ $f->id }}</td>
                <td class="p-3 border">{{ $f->nama }}</td>
                <td class="p-3 border">
                    <span class="inline-block px-2 py-1 text-xs rounded-full
                        {{ $f->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ $f->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </td>
                <td class="p-3 border">
                    <div class="flex items-center justify-center gap-2">
                        <a href="{{ route('admin.fakultas.edit',$f) }}"
                           class="px-3 py-1 text-xs text-white bg-yellow-500 rounded hover:bg-yellow-600">
                            Edit
                        </a>
                        <form action="{{ route('admin.fakultas.destroy',$f) }}"
                              method="POST"
                              onsubmit="return confirm('Yakin hapus data ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="px-3 py-1 text-xs text-white bg-red-600 rounded hover:bg-red-700">
                                Hapus
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="p-6 text-center text-gray-500">
                    Data fakultas belum tersedia.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- PAGINATION --}}
<div class="flex justify-center mt-4">
    {{ $fakultas->links() }}
</div>

</div>

<script>
// select all checkbox
document.getElementById('selectAll').addEventListener('click', function(){
    let checkboxes = document.querySelectorAll('.rowCheckbox');
    checkboxes.forEach(cb=>{
        cb.checked = this.checked;
    });
});

// bulk delete
document.getElementById('bulkDeleteForm').addEventListener('submit',function(e){
    let ids=[];
    document.querySelectorAll('.rowCheckbox:checked').forEach(cb=>{
        ids.push(cb.value);
    });
    if(ids.length===0){
        alert('Pilih data terlebih dahulu');
        e.preventDefault();
        return;
    }
    document.getElementById('bulkIds').value = ids.join(',');
});
</script>

</x-app-layout>
@extends('layouts.app')
@section('title', 'Edit Produk')
@section('content')
<div class="pg-h"><h1>Edit: {{ $product->name }}</h1><a href="{{ route('products.index') }}" class="btn btn-outline">Kembali</a></div>
<div class="g2"><div class="card"><div class="card-b">
    <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
            <div style="grid-column:1/-1">
                <label class="form-l">Nama Produk *</label><input type="text" name="name" class="form-i w-full" value="{{ old('name', $product->name) }}" required>
            </div>
            <div>
                <label class="form-l">Satuan *</label><input type="text" name="unit" class="form-i w-full" value="{{ old('unit', $product->unit) }}" required>
            </div>
            <div>
                <label class="form-l">Kategori *</label><select name="category_id" class="form-s w-full" required>@foreach($categories as $cat)<option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>@endforeach</select>
            </div>
            <div>
                <label class="form-l">SKU *</label><input type="text" name="sku" class="form-i w-full" value="{{ old('sku', $product->sku) }}" required>
            </div>
            <div>
                <label class="form-l">Harga Jual *</label><input type="number" name="selling_price" class="form-i w-full" value="{{ old('selling_price', $product->selling_price) }}" required>
            </div>
            <div style="grid-column:1/-1">
                <label class="form-l">Diskon</label>
                <div class="fx g-2">
                    <select name="discount_type" class="form-s" style="flex:0 0 80px">
                        <option value="">-</option>
                        <option value="percent" {{ $product->discount_type == 'percent' ? 'selected' : '' }}>%</option>
                        <option value="nominal" {{ $product->discount_type == 'nominal' ? 'selected' : '' }}>Rp</option>
                    </select>
                    <input type="number" name="discount_value" class="form-i" style="flex:1" value="{{ $product->discount_value }}" min="0">
                </div>
            </div>
            <div style="grid-column:1/-1">
                <label class="form-l">Deskripsi</label><textarea name="description" class="form-i w-full" rows="2" placeholder="Deskripsi produk">{{ $product->description }}</textarea>
            </div>
            <div>
                <label class="form-l">Min Stok</label><input type="number" name="minimum_stock" class="form-i w-full" value="{{ old('minimum_stock', $product->minimum_stock) }}">
            </div>
            <div style="grid-column:1/-1">
                <label class="form-l">Gambar Produk</label>
                @if($product->image)
                <div class="fx ai-c g-3 mb-3">
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" style="width:80px;height:80px;border-radius:var(--sm);object-fit:cover;border:1px solid var(--200)">
                    <span style="font-size:.78rem;color:var(--500)">Gambar saat ini</span>
                </div>
                @endif
                <input type="file" name="image" class="form-i w-full" accept="image/jpeg,image/png,image/gif,image/webp" onchange="validateImageSize(this, 2)">
                <small class="text-gray-400 text-xs mt-1 block">Format: JPEG, PNG, GIF, WebP. Maksimal 2MB. Otomatis dikonversi ke WebP.</small>
            </div>
            <div style="grid-column:1/-1">
                <label class="form-l">Status Produk</label>
                <div class="mt-2">
                    <label class="tog">
                        <input type="checkbox" name="is_active" value="1" {{ $product->is_active ? 'checked' : '' }}>
                        <span class="tog-sl"></span>
                    </label>
                    <span style="font-size:.85rem;font-weight:500;margin-left:8px" id="status-label">{{ $product->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                </div>
            </div>
        </div>
        <div class="fx jc-e g-2 mt-4">
            <a href="{{ route('products.index') }}" class="btn btn-outline">Batal</a>
            <button type="submit" class="btn btn-primary">Update Produk</button>
        </div>
    </form>
    @if($product->image)
    <form action="{{ route('products.delete-image', $product) }}" method="POST" onsubmit="return confirm('Hapus gambar produk ini?')" class="mt-3">
        @csrf
        <button type="submit" class="btn btn-outline btn-sm" style="color:#EF4444;border-color:#FECACA;display:inline-flex;align-items:center;gap:6px">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            Hapus Gambar
        </button>
    </form>
    @endif
</div></div></div>

@push('scripts')
<script>
document.querySelector('input[name="is_active"]')?.addEventListener('change', function() {
    document.getElementById('status-label').textContent = this.checked ? 'Aktif' : 'Nonaktif';
});
function validateImageSize(input, maxMB) {
    const file = input.files[0];
    if (file) {
        const sizeMB = file.size / 1024 / 1024;
        if (sizeMB > maxMB) {
            input.value = '';
            alert('Ukuran gambar maksimal ' + maxMB + 'MB! File yang dipilih: ' + sizeMB.toFixed(2) + 'MB');
            return false;
        }
    }
    return true;
}
</script>
@endpush
@endsection
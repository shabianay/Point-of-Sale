@extends('layouts.app')
@section('title', 'Tambah Produk')
@section('content')
<div class="pg-h"><h1>Tambah Produk</h1><a href="{{ route('products.index') }}" class="btn btn-outline">Kembali</a></div>
<div class="g2"><div class="card"><div class="card-b">
    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="grid-2" style="grid-template-columns:1fr;gap:12px">
            @php $cols = '1fr 1fr'; @endphp
            <div class="g2">
                <label class="form-l">Nama Produk *</label><input type="text" name="name" class="form-i w-full" value="{{ old('name') }}" required>
            </div>
            <div class="g2">
                <label class="form-l">Satuan *</label><input type="text" name="unit" class="form-i w-full" value="{{ old('unit', 'pcs') }}" required>
            </div>
            <div class="g2">
                <label class="form-l">Kategori *</label><select name="category_id" class="form-s w-full" required><option value="">Pilih</option>@foreach($categories as $cat)<option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>@endforeach</select>
            </div>
            <div class="g2">
                <label class="form-l">SKU</label><input type="text" name="sku" class="form-i w-full" value="{{ old('sku') }}">
            </div>
            <div style="grid-column:1/-1">
                <label class="form-l">Deskripsi</label><textarea name="description" class="form-i w-full" rows="2" placeholder="Contoh: Kopi susu dengan gula aren asli">{{ old('description') }}</textarea>
            </div>
            <div class="g2">
                <label class="form-l">Gambar Produk</label><input type="file" name="image" class="form-i w-full" accept="image/jpeg,image/png,image/gif,image/webp" onchange="validateImageSize(this, 2)"><small class="text-gray-400 text-xs mt-1 block">Format: JPEG, PNG, GIF, WebP. Maksimal 2MB. Otomatis dikonversi ke WebP.</small>
            </div>
            <div class="g2">
                <label class="form-l">Harga Jual *</label><input type="number" name="selling_price" class="form-i w-full" value="{{ old('selling_price') }}" required>
            </div>
            <div class="g2">
                <label class="form-l">Diskon</label>
                <div class="fx g-2">
                    <select name="discount_type" class="form-s" style="flex:0 0 80px">
                        <option value="">-</option>
                        <option value="percent" {{ old('discount_type') == 'percent' ? 'selected' : '' }}>%</option>
                        <option value="nominal" {{ old('discount_type') == 'nominal' ? 'selected' : '' }}>Rp</option>
                    </select>
                    <input type="number" name="discount_value" class="form-i" style="flex:1" value="{{ old('discount_value', 0) }}" min="0">
                </div>
            </div>
            <div class="g2">
                <label class="form-l">Stok</label><input type="number" name="stock" class="form-i w-full" value="{{ old('stock', 0) }}">
            </div>
            <div class="g2">
                <label class="form-l">Min Stok</label><input type="number" name="minimum_stock" class="form-i w-full" value="{{ old('minimum_stock', 0) }}">
            </div>
        </div>
        <div class="fx jc-e g-2 mt-4">
            <a href="{{ route('products.index') }}" class="btn btn-outline">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan Produk</button>
        </div>
    </form>
</div></div></div>
@endsection
@push('scripts')
<script>
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
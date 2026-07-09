@extends('layouts.app')
@section('title', 'Pengaturan')
@push('styles')
<style>
.pm-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
    gap: 10px;
    margin-top: 8px;
}
.pm-item {
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    padding: 16px 12px 14px;
    border-radius: var(--radius-md);
    border: 1.5px solid var(--border);
    background: var(--surface);
    cursor: pointer;
    transition: all .2s var(--ease);
    user-select: none;
}
.pm-item:hover {
    border-color: var(--accent);
    background: var(--accent-subtle);
}
.pm-item.is-active {
    border-color: var(--accent);
    background: var(--accent-subtle);
}
.pm-item input {
    display: none;
}
.pm-icon {
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: var(--surface-secondary);
    color: var(--text-tertiary);
    transition: all .2s var(--ease);
    flex-shrink: 0;
}
.pm-item.is-active .pm-icon {
    background: var(--accent);
    color: #fff;
}
.pm-item:hover .pm-icon {
    color: var(--accent);
}
.pm-item.is-active:hover .pm-icon {
    color: #fff;
}
.pm-label {
    font-size: .8125rem;
    font-weight: 600;
    color: var(--text-secondary);
    transition: color .2s var(--ease);
    text-align: center;
    line-height: 1.2;
}
.pm-item.is-active .pm-label {
    color: var(--accent);
}
.pm-check {
    position: absolute;
    top: 6px;
    right: 6px;
    width: 18px;
    height: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: transparent;
    color: transparent;
    transition: all .2s var(--ease);
}
.pm-item.is-active .pm-check {
    background: var(--accent);
    color: #fff;
}
</style>
@endpush
@section('content')
<div class="pg-h"><h1>Pengaturan Toko</h1></div>
<div class="card">
    <div class="card-b">
        <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">@csrf @method('PUT')
        <div class="mb-4"><label class="form-l">Nama Toko *</label><input type="text" name="store_name" class="form-i w-full" value="{{ $setting->store_name }}" required></div>
        <div class="mb-4"><label class="form-l">Alamat</label><textarea name="store_address" class="form-i w-full" rows="2">{{ $setting->store_address }}</textarea></div>
        
        <div class="mb-4"><label class="form-l">Telepon</label><input type="text" name="store_phone" class="form-i w-full" value="{{ $setting->store_phone }}"></div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <div><label class="form-l">Pajak PPN (%)</label><input type="number" name="tax_rate" class="form-i w-full" value="{{ $setting->tax_rate }}" step="0.01"></div>
            <div><label class="form-l">Service Charge (%)</label><input type="number" name="service_charge" class="form-i w-full" value="{{ $setting->service_charge }}" step="0.01"></div>
            <div>
                <label class="form-l">Logo</label>
                <input type="file" name="logo" class="form-i w-full" accept="image/jpeg,image/png,image/gif,image/webp" onchange="validateImageSize(this, 2)">
                <small class="text-gray-400 text-xs mt-1 block">Format: JPEG, PNG, GIF, WebP. Maksimal 2MB. Otomatis dikonversi ke WebP.</small>
                @if($setting->logo_path)
                <div class="mt-2 w-20 h-20 rounded-lg overflow-hidden border border-gray-200">
                    <img src="{{ asset('storage/'.$setting->logo_path) }}" class="w-full h-full object-cover">
                </div>
                @endif
            </div>
        </div>
        
        <div class="mb-4"><label class="form-l">Metode Pembayaran Aktif</label>
        <div class="pm-grid">
        @php $icons = ['cash'=>'💰','qris'=>'📱','card'=>'💳','transfer'=>'🏦']; @endphp
        @foreach(config('payment.methods') as $v=>$l)
        <label class="pm-item {{ in_array($v,(array)$setting->active_payment_methods) ? 'is-active' : '' }}">
            <input type="checkbox" name="active_payment_methods[]" value="{{ $v }}" {{ in_array($v,(array)$setting->active_payment_methods) ? 'checked' : '' }}>
            <span class="pm-check"><svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3.5" d="M5 13l4 4L19 7"/></svg></span>
            <span class="pm-icon">{{ $icons[$v] ?? '💳' }}</span>
            <span class="pm-label">{{ $l }}</span>
        </label>
        @endforeach
        </div>
        <small class="text-gray-400 text-xs mt-2 block">Klik untuk mengaktifkan/nonaktifkan metode pembayaran</small>
        </div>
        <div class="mb-4"><label class="form-l">Footer Struk</label><textarea name="receipt_footer" class="form-i w-full" rows="3" placeholder="Contoh: WiFi: nama_wifi&#10;Password: 12345678&#10;Terima kasih">{{ $setting->receipt_footer }}</textarea><small class="text-gray-400 text-xs mt-1 block">Teks yang akan muncul di bagian bawah struk. Gunakan &#10; untuk baris baru.</small></div>
        <button type="submit" class="btn btn-primary">Simpan Pengaturan</button>
        </form>
    </div>
</div>
@push('scripts')
<script>
function validateImageSize(input, maxMB) {
    var file = input.files[0];
    if (file) {
        var sizeMB = file.size / 1024 / 1024;
        if (sizeMB > maxMB) {
            input.value = '';
            alert('Ukuran gambar maksimal ' + maxMB + 'MB! File yang dipilih: ' + sizeMB.toFixed(2) + 'MB');
            return false;
        }
    }
    return true;
}
document.querySelectorAll('.pm-item').forEach(function(el) {
    el.addEventListener('click', function(e) {
        if (e.target.closest('.pm-item')) {
            var cb = this.querySelector('input[type=checkbox]');
            cb.checked = !cb.checked;
            this.classList.toggle('is-active', cb.checked);
        }
    });
});
</script>
@endpush
@endsection
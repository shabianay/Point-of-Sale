@extends('layouts.app')
@section('title', 'POS')
@push('styles')
<style>
.ot-tabs{display:flex;gap:4px;margin-bottom:12px;background:var(--surface-secondary);padding:3px;border-radius:var(--radius-sm);border:1px solid var(--border-light)}
.ot-tab{flex:1;padding:7px 0;text-align:center;font-size:.75rem;font-weight:600;border:none;border-radius:5px;cursor:pointer;transition:all .2s var(--ease);font-family:inherit;color:var(--text-secondary);background:transparent;text-decoration:none}
.ot-tab.active{background:var(--surface);color:var(--accent);box-shadow:var(--shadow-xs)}
.ot-tab:hover:not(.active){color:var(--text-primary)}
</style>
@endpush

@section('content')
    <div class="toast-c" id="toast-container"></div>

    {{-- Confirm Modal --}}
    <div id="confirmModal" class="fixed inset-0 z-[9999] flex items-center justify-center p-4 opacity-0 invisible pointer-events-none transition-all duration-200 ease-out">
        <div class="fixed inset-0 bg-black/40" onclick="closeConfirm()"></div>
        <div data-modal-card class="relative w-full bg-white flex flex-col mx-auto scale-95 translate-y-2 transition-all duration-200 ease-out overflow-hidden"
            style="max-width:340px;border-radius:var(--radius-xl);box-shadow:var(--shadow-xl);">
            <div class="flex-1 overflow-y-auto" style="padding:28px 24px 8px;text-align:center;">
                <div style="width:48px;height:48px;margin:0 auto 16px;display:flex;align-items:center;justify-content:center;border-radius:50%;background:var(--accent-subtle);color:var(--accent);">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <h3 style="font-size:1rem;font-weight:700;color:var(--text-primary);margin-bottom:8px;letter-spacing:-.02em;" id="confirmTitle">Konfirmasi</h3>
                <div style="font-size:.8125rem;color:var(--text-secondary);line-height:1.6;margin-bottom:20px;" id="confirmMessage">Apakah Anda yakin?</div>
            </div>
            <div class="flex-shrink-0" style="padding:12px 24px 20px;border-top:1px solid var(--border-light);">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                    <button onclick="closeConfirm()" class="btn btn-outline" style="width:100%;padding:11px;">Batal</button>
                    <button class="btn btn-primary" style="width:100%;padding:11px;" id="confirmOk">Ya</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Success Modal --}}
    <div id="successModal" class="fixed inset-0 z-[9999] flex items-center justify-center p-4 opacity-0 invisible pointer-events-none transition-all duration-200 ease-out">
        <div class="fixed inset-0 bg-black/40" onclick="closeSuccess()"></div>
        <div data-modal-card class="relative w-full bg-white flex flex-col mx-auto scale-95 translate-y-2 transition-all duration-200 ease-out overflow-hidden"
            style="max-width:360px;border-radius:var(--radius-xl);box-shadow:var(--shadow-xl);">
            <div class="flex-1 overflow-y-auto" style="padding:24px 20px;">
                <div class="w-14 h-14 mx-auto mb-4 flex items-center justify-center rounded-full bg-green-50 text-green-600">
                    <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
                <h3 style="font-size:1.1rem;font-weight:700;color:var(--text-primary);margin-bottom:4px;text-align:center;">Transaksi Berhasil!</h3>
                <p style="font-size:.8125rem;color:var(--text-secondary);margin-bottom:20px;text-align:center;">Pesanan telah tersimpan dengan sukses</p>
                <div style="background:var(--surface-secondary);border-radius:var(--radius-md);padding:18px;border:1px solid var(--border-light);">
                    <div style="display:flex;justify-content:space-between;align-items:center;padding:6px 0;font-size:.875rem;">
                        <span style="color:var(--text-secondary);font-weight:500;">Kode</span>
                        <span style="font-weight:600;color:var(--text-primary);" id="successCode">-</span>
                    </div>
                    <div style="border-top:1px solid var(--border);margin:4px 0;"></div>
                    <div style="display:flex;justify-content:space-between;align-items:center;padding:6px 0;font-size:.875rem;">
                        <span style="color:var(--text-secondary);font-weight:500;">Kembalian</span>
                        <span style="font-weight:700;color:#059669;font-size:.95rem;" id="successChange">Rp 0</span>
                    </div>
                </div>
            </div>
            <div class="flex-shrink-0" style="padding:12px 20px 16px;border-top:1px solid var(--border-light);">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                    <button onclick="closeSuccess()" class="btn btn-outline" style="width:100%;padding:11px;">Tutup</button>
                    <button onclick="printReceipt()" class="btn btn-primary" id="successPrintBtn" style="width:100%;padding:11px;">Cetak Struk</button>
                </div>
            </div>
        </div>
    </div>

    <div class="pg-h">
        <h1>Order Line</h1>
    </div>

    <div class="search-section">
        <div class="srch">
            <div class="srch-w">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" id="search-product" class="srch-i" placeholder="Search menu...">
            </div>
        </div>

        <div class="cat-t" id="category-filters">
            <button class="cat-tb active" data-category="all">Semua</button>
            @foreach ($categories as $cat)
                <button class="cat-tb" data-category="{{ $cat->id }}">{{ $cat->name }}</button>
            @endforeach
        </div>
    </div>

    <div class="g-main">
        <div class="menu-section">
            <div class="prod-g" id="product-grid">
                @foreach ($products as $p)
                    <div class="prod-c" data-product-id="{{ $p->id }}" data-price="{{ $p->final_price }}"
                        data-original-price="{{ $p->selling_price }}" data-name="{{ $p->name }}"
                        data-desc="{{ $p->description ?? '' }}" data-stock="{{ $p->stock }}"
                        data-category="{{ $p->category_id }}"
                        data-image="{{ $p->image ? asset('storage/' . $p->image) : '' }}">
                        <div class="prod-c-img menu-card-img">
                            @if ($p->image)
                                <img src="{{ asset('storage/' . $p->image) }}" alt="{{ $p->name }}">
                            @else
                                <span style="font-size:2.5rem;opacity:0.3">🍽️</span>
                            @endif
                            @if ($p->has_discount)
                                <span class="disc-badge">DISKON</span>
                            @endif
                        </div>
                        <div class="prod-c-b">
                            <div class="prod-c-n" title="{{ $p->name }}">{{ $p->name }}</div>
                            <div class="prod-c-d">
                                {{ $p->description ? Str::limit($p->description, 30) : $p->category->name }} &bull; Stok
                                {{ $p->stock }}</div>
                            <div class="prod-c-f">
                                <span class="prod-c-p">
                                    @if ($p->has_discount)
                                        <span
                                            style="text-decoration:line-through;color:var(--400);font-size:.75rem;margin-right:4px">Rp
                                            {{ number_format($p->selling_price, 0, ',', '.') }}</span>
                                        Rp {{ number_format($p->final_price, 0, ',', '.') }}
                                    @else
                                        Rp {{ number_format($p->selling_price, 0, ',', '.') }}
                                    @endif
                                </span>
                                <button class="prod-c-a" title="Tambah ke keranjang">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="cart">
            <div class="cart-h">
                <h2>Current Order</h2>
                <button class="btn-icon" onclick="clearCart()" title="Kosongkan keranjang"
                    style="width:30px;height:30px;border:none;background:var(--100)">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            </div>

            <div class="cust-c">
                <div class="ot-tabs">
                    @php $ot = request('type', 'dine_in'); @endphp
                    <a href="{{ request()->fullUrlWithQuery(['type' => 'dine_in']) }}" class="ot-tab {{ $ot === 'dine_in' ? 'active' : '' }}">Dine In</a>
                    <a href="{{ request()->fullUrlWithQuery(['type' => 'takeaway']) }}" class="ot-tab {{ $ot === 'takeaway' ? 'active' : '' }}">Takeaway</a>
                </div>
                <div class="cust-l">Nama Pelanggan</div>
                <input type="text" id="customer-name" class="form-i w-full cust-input" value="Customer">
                <div id="table-number-wrap" style="display:{{ $ot === 'dine_in' ? '' : 'none' }}">
                    <div class="cust-l" style="margin-bottom:4px">No Meja</div>
                    <input type="text" id="table-number" class="form-i w-full cust-sm-input" value="1">
                </div>
            </div>

            <div class="cart-items" id="cart-items">
                <div class="cart-empty" id="empty-cart">
                    <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:48px;height:48px;margin:0 auto 12px;opacity:.2;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z" />
                        </svg>
                        <p style="font-size:.8125rem;font-weight:500;">No orders yet</p>
                    </div>
                </div>
                <div id="cart-items-list"></div>
            </div>

            <div class="cart-sum" id="cart-summary" style="display:none">
                <div class="cart-sum-r"><span class="l">Items</span><span class="v" id="items-count">0
                        item</span></div>
                <div class="cart-sum-r"><span class="l">Sub Total</span><span class="v"
                        id="subtotal-display">Rp 0</span></div>
                <div class="cart-sum-r">
                    <span class="l">Discount</span>
                    <div class="fx g-1 ai-c">
                        <input type="text" id="discount-input" class="disc-i" value="0" placeholder="0"
                            oninput="updateTotal()">
                        <div class="disc-mode">
                            <span style="background:var(--accent-subtle);color:var(--accent)" id="discount-mode-rp"
                                onclick="setDiscountMode('rp')">Rp</span>
                            <span style="color:var(--400)" id="discount-mode-pct"
                                onclick="setDiscountMode('pct')">%</span>
                        </div>
                    </div>
                </div>
                <div class="cart-sum-r" id="tax-row"><span class="l">Tax ({{ $store->tax_rate ?? 11 }}%)</span><span
                        class="v" id="tax-display">Rp 0</span></div>
                <div class="cart-sum-r" id="sc-row"><span class="l">Service ({{ $store->service_charge ?? 0 }}%)</span><span
                        class="v" id="sc-display">Rp 0</span></div>
                <div class="cart-sum-r ttl"><span class="l">Total</span><span class="v" id="total-display">Rp
                        0</span></div>
            </div>

            <div class="cart-ft" id="cart-footer" style="display:none">
                <div class="pay-s active" id="payment-section">
                    <div class="payment-section">
                        <label class="form-l">Metode Pembayaran</label>
                        <select id="payment-method" class="form-s w-full">
                            @php $pm = (array)($store->active_payment_methods ?? array_keys(config('payment.methods'))); @endphp
                            @foreach(config('payment.methods') as $v=>$l)
                            @if(in_array($v, $pm))<option value="{{ $v }}">{{ $l }}</option>@endif
                            @endforeach
                        </select>
                    </div>
                    <div class="payment-section">
                        <label class="form-l mt-3">Jumlah Dibayar</label>
                        <input type="number" id="paid-amount" class="form-i w-full" placeholder="Masukkan jumlah..."
                            min="0" oninput="calculateChange()">
                    </div>
                    <div class="fx jc-b mt-3 mb-3">
                        <span style="font-size:.82rem;color:var(--400);font-weight:500">Kembalian</span>
                        <span style="font-size:20px" class="change-d" id="change-display">Rp 0</span>
                    </div>
                </div>
                <button class="btn-chk" id="checkout-btn" onclick="checkout()" disabled>Process Transactions</button>
            </div>
        </div>
    </div>

    {{-- Product Detail Modal --}}
    <div id="noteModal" class="fixed inset-0 z-[9999] flex items-center justify-center p-4 opacity-0 invisible pointer-events-none transition-all duration-200 ease-out">
        <div class="fixed inset-0 bg-black/40" onclick="closeNoteModal()"></div>
        <div data-modal-card class="relative w-full bg-white flex flex-col mx-auto scale-95 translate-y-2 transition-all duration-200 ease-out overflow-hidden"
            style="max-width:400px;border-radius:var(--radius-xl);box-shadow:var(--shadow-xl);">
            <div id="modalProductImage" class="relative w-full flex items-center justify-center overflow-hidden"
                style="height:200px;background:linear-gradient(135deg, #FFE0B2, #FFCC80);">
                <img id="modalProductImg" class="w-full h-full hidden" alt="" style="object-fit:cover;">
                <span id="modalProductEmoji" style="font-size:4rem;opacity:.3;">🍽️</span>
                <button onclick="closeNoteModal()"
                    style="position:absolute;top:10px;right:10px;width:30px;height:30px;display:flex;align-items:center;justify-content:center;border-radius:50%;border:none;background:rgba(255,255,255,.85);color:var(--text-secondary);cursor:pointer;transition:all .15s var(--ease);box-shadow:var(--shadow-sm);"
                    onmouseover="this.style.background='var(--surface)';this.style.color='var(--text-primary)'"
                    onmouseout="this.style.background='rgba(255,255,255,.85)';this.style.color='var(--text-secondary)'">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
                <span id="modalProductBadge" class="disc-badge hidden">DISKON</span>
            </div>
            <div class="flex-1 overflow-y-auto" style="padding:16px 20px 8px;">
                <h3 style="font-size:1rem;font-weight:700;color:var(--text-primary);letter-spacing:-.02em;margin-bottom:2px;" id="noteModalTitle">Nama Produk</h3>
                <p style="font-size:.75rem;color:var(--text-tertiary);margin-bottom:12px;" id="noteModalDesc">Deskripsi produk</p>
                <div style="display:flex;align-items:baseline;gap:8px;margin-bottom:16px;">
                    <span style="font-size:1.15rem;font-weight:700;color:var(--accent);" id="modalProductPrice">Rp 0</span>
                    <span style="font-size:.75rem;color:var(--text-tertiary);text-decoration:line-through;display:none;" id="modalProductOriginalPrice"></span>
                </div>

                <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 14px;margin-bottom:16px;border-radius:var(--radius-md);background:var(--surface-secondary);border:1px solid var(--border-light);">
                    <span style="font-size:.8125rem;font-weight:550;color:var(--text-secondary);">Jumlah</span>
                    <div style="display:flex;align-items:center;gap:10px;">
                        <button onclick="modalQtyChange(-1)" style="width:32px;height:32px;display:flex;align-items:center;justify-content:center;border-radius:50%;border:1.5px solid var(--border);background:var(--surface);color:var(--text-secondary);cursor:pointer;transition:all .15s var(--ease);padding:0;" onmouseover="this.style.borderColor='var(--accent)';this.style.color='var(--accent)'" onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--text-secondary)'">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 12H4"/></svg>
                        </button>
                        <span style="font-size:.95rem;font-weight:700;color:var(--text-primary);width:28px;text-align:center;" id="modalQty">1</span>
                        <button onclick="modalQtyChange(1)" style="width:32px;height:32px;display:flex;align-items:center;justify-content:center;border-radius:50%;border:1.5px solid var(--border);background:var(--surface);color:var(--text-secondary);cursor:pointer;transition:all .15s var(--ease);padding:0;" onmouseover="this.style.borderColor='var(--accent)';this.style.color='var(--accent)'" onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--text-secondary)'">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                        </button>
                    </div>
                </div>

                <div style="margin-bottom:12px;">
                    <p style="font-size:.6875rem;text-transform:uppercase;letter-spacing:.04em;color:var(--text-tertiary);font-weight:600;margin-bottom:6px;">Catatan (opsional)</p>
                    <input type="text" id="note-input" class="form-i" style="width:100%;padding:9px 12px;border:1.5px solid var(--border);border-radius:var(--radius-sm);font-size:.8125rem;font-family:inherit;color:var(--text-primary);background:var(--surface);outline:none;transition:all .2s var(--ease);box-sizing:border-box;" placeholder="Contoh: less sugar, no ice, pedas..." maxlength="500">
                </div>
                <div style="margin-bottom:4px;">
                    <p style="font-size:.6875rem;text-transform:uppercase;letter-spacing:.04em;color:var(--text-tertiary);font-weight:600;margin-bottom:6px;">Quick Notes</p>
                    <div style="display:flex;flex-wrap:wrap;gap:6px;" id="quickNotesContainer"></div>
                </div>
            </div>
            <div class="flex-shrink-0" style="padding:12px 20px 16px;border-top:1px solid var(--border-light);">
                <button onclick="confirmAddToCart()" class="btn-chk" style="width:100%;padding:12px 24px;border:none;border-radius:var(--radius-sm);background:var(--accent-gradient);color:var(--text-inverse);font-size:.875rem;font-weight:600;font-family:inherit;cursor:pointer;transition:all .25s var(--ease);display:flex;align-items:center;justify-content:center;gap:6px;position:relative;overflow:hidden;">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/>
                    </svg>
                    <span id="modalAddBtnText">Tambah ke Keranjang</span>
                </button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let cart = [];
        let storeTaxRate = {{ $store->tax_rate ?? 11 }};
        let storeScRate = {{ $store->service_charge ?? 0 }};
        let trxCount = 0;
        let confirmCallback = null;
        let pendingNoteProduct = null;
        let modalCount = 0;
        let lastTransactionData = null;
        let lastReceiptUrl = null;

        function modalOpen() {
            modalCount++;
            document.body.style.overflow = 'hidden';
        }

        function modalClose() {
            modalCount = Math.max(0, modalCount - 1);
            if (modalCount === 0) document.body.style.overflow = '';
        }

        function modalShow(id) {
            const el = document.getElementById(id);
            el.classList.remove('opacity-0', 'invisible', 'pointer-events-none');
            el.classList.add('opacity-100', 'visible', 'pointer-events-auto');
            const card = el.querySelector('[data-modal-card]');
            if (card) {
                card.classList.remove('scale-95', 'translate-y-2');
                card.classList.add('scale-100', 'translate-y-0');
            }
            modalOpen();
        }

        function modalHide(id) {
            const el = document.getElementById(id);
            el.classList.add('opacity-0', 'invisible', 'pointer-events-none');
            el.classList.remove('opacity-100', 'visible', 'pointer-events-auto');
            const card = el.querySelector('[data-modal-card]');
            if (card) {
                card.classList.add('scale-95', 'translate-y-2');
                card.classList.remove('scale-100', 'translate-y-0');
            }
            modalClose();
        }

        function showToast(type, title, message) {
            const container = document.getElementById('toast-container');
            const icons = {
                success: '<svg class="toast-ic" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>',
                error: '<svg class="toast-ic" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>',
                warning: '<svg class="toast-ic" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>',
                info: '<svg class="toast-ic" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
            };
            const toast = document.createElement('div');
            toast.className = 'toast ' + type;
            toast.innerHTML = `
        ${icons[type]}
        <div class="toast-cnt">
            <div class="toast-t">${title}</div>
            <div class="toast-m">${message}</div>
        </div>
        <button class="toast-x" onclick="removeToast(this.parentElement)">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    `;
            container.appendChild(toast);
            setTimeout(() => removeToast(toast), 3500);
        }

        function removeToast(toast) {
            if (!toast || toast.classList.contains('rm')) return;
            toast.classList.add('rm');
            setTimeout(() => toast.remove(), 300);
        }

        function showConfirm(title, message, onConfirm) {
            document.getElementById('confirmTitle').textContent = title;
            document.getElementById('confirmMessage').textContent = message;
            modalShow('confirmModal');
            confirmCallback = onConfirm;
        }

        function closeConfirm() {
            modalHide('confirmModal');
            confirmCallback = null;
        }

        document.getElementById('confirmOk').addEventListener('click', function() {
            try { if (confirmCallback) confirmCallback(); } catch(e) { showToast('error', 'Error', e.message); }
            closeConfirm();
        });

        function showSuccess(code, change, receiptUrl, transactionData) {
            document.getElementById('successCode').textContent = code;
            document.getElementById('successChange').textContent = 'Rp ' + numberFormat(change);
            lastTransactionData = transactionData || null;
            lastReceiptUrl = receiptUrl;
            modalShow('successModal');
        }

        function printReceipt() {
            if (lastTransactionData) {
                printFromData(lastTransactionData);
            } else if (lastReceiptUrl) {
                window.open(lastReceiptUrl, '_blank');
            }
        }

        function printFromData(d) {
            function nf(n) { return new Intl.NumberFormat('id-ID').format(Math.round(n)); }

            var html = '<!DOCTYPE html><html><head><title>Struk - ' + d.code + '</title>';
            html += '<style>';
            html += '@page{size:58mm auto;margin:0}';
            html += 'body{font-family:Courier New,monospace;font-size:10px;margin:0;padding:6px}';
            html += '.r{max-width:58mm;margin:0 auto}';
            html += 'table{width:100%;border-collapse:collapse}';
            html += 'td{padding:1px 3px;vertical-align:top}';
            html += '.ct{text-align:center}';
            html += '.lt{text-align:left}';
            html += '.rt{text-align:right}';
            html += '.b{font-weight:bold}';
            html += '.d{border-top:1px dashed #000;margin:3px 0}';
            html += '.s{font-size:14px;font-weight:bold}';
            html += '</style></head><body>';

            html += '<div class="r">';

            // Header
            html += '<div class="ct"><b style="font-size:14px">' + (d.store_name||'Toko') + '</b><br>';
            html += (d.store_address||'') + '<br>';
            html += 'Telp: ' + (d.store_phone||'') + '</div>';
            html += '<div class="d"></div>';

            // Transaction Info
            html += '<table>';
            html += '<tr><td>No</td><td class="rt">' + d.code + '</td></tr>';
            html += '<tr><td>Kasir</td><td class="rt">' + d.cashier + '</td></tr>';
            html += '<tr><td>Pelanggan</td><td class="rt">' + d.customer_name + '</td></tr>';
            html += '<tr><td>Meja</td><td class="rt">' + (d.order_type==='dine_in' ? d.table_number + ' | Dine In' : 'Takeaway') + '</td></tr>';
            html += '<tr><td>Tgl</td><td class="rt">' + d.date + '</td></tr>';
            html += '</table>';
            html += '<div class="d"></div>';

            // Header Items
            html += '<table>';
            html += '<tr><td class="b">Item</td><td class="rt b">Qty</td><td class="rt b">Harga</td><td class="rt b">Subtotal</td></tr>';

            // Items
            for (var i = 0; i < d.items.length; i++) {
                var it = d.items[i];
                html += '<tr><td class="lt">' + it.name + '</td><td class="rt">' + it.qty + '</td><td class="rt">' + nf(it.price) + '</td><td class="rt">' + nf(it.subtotal) + '</td></tr>';
                if (it.notes) {
                    html += '<tr><td colspan="4" style="font-size:9px;color:#666;">  * ' + it.notes + '</td></tr>';
                }
            }
            html += '</table>';
            html += '<div class="d"></div>';

            // Totals
            html += '<table>';
            html += '<tr><td>Subtotal</td><td class="rt">Rp ' + nf(d.subtotal) + '</td></tr>';
            if (d.discount_amount > 0) html += '<tr><td>Diskon</td><td class="rt">(Rp ' + nf(d.discount_amount) + ')</td></tr>';
            if (d.tax_amount > 0) html += '<tr><td>Pajak (' + d.tax_rate + '%)</td><td class="rt">Rp ' + nf(d.tax_amount) + '</td></tr>';
            if (d.service_charge_amount > 0) html += '<tr><td>Service (' + d.service_charge_rate + '%)</td><td class="rt">Rp ' + nf(d.service_charge_amount) + '</td></tr>';
            html += '</table>';
            html += '<div class="d"></div>';

            // Grand Total
            html += '<table>';
            html += '<tr class="s"><td class="b">TOTAL</td><td class="rt b">Rp ' + nf(d.total) + '</td></tr>';
            html += '</table>';
            html += '<div class="d"></div>';

            // Payment
            html += '<table>';
            html += '<tr><td>Tunai</td><td class="rt">Rp ' + nf(d.paid_amount) + '</td></tr>';
            html += '<tr><td>Kembalian</td><td class="rt">Rp ' + nf(d.change_amount) + '</td></tr>';
            html += '</table>';
            html += '<div class="d"></div>';

            // Footer
            html += '<div class="ct">' + (d.receipt_footer || 'Terima kasih').replace(/\n/g, '<br>') + '</div>';

            html += '</div></body></html>';

            // Remove old print frame if any
            var old = document.getElementById('print-frame');
            if (old) old.remove();

            var iframe = document.createElement('iframe');
            iframe.id = 'print-frame';
            iframe.style.cssText = 'position:fixed;top:-9999px;left:-9999px;width:0;height:0;border:0;opacity:0';
            document.body.appendChild(iframe);
            iframe.contentDocument.write(html);
            iframe.contentDocument.close();
            iframe.contentWindow.focus();
            iframe.contentWindow.print();

            // Clean up after print
            function rmFrame() { if (iframe.parentNode) iframe.remove(); }
            if (iframe.contentWindow.onafterprint !== undefined) {
                iframe.contentWindow.onafterprint = rmFrame;
            } else {
                setTimeout(rmFrame, 1000);
            }
        }

        function closeSuccess() {
            modalHide('successModal');
            location.reload();
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.prod-c').forEach(card => {
                card.addEventListener('click', function(e) {
                    if (e.target.closest('.prod-c-a')) e.preventDefault();
                    openNoteModal(
                        this.dataset.productId,
                        this.dataset.name,
                        parseFloat(this.dataset.price),
                        parseInt(this.dataset.stock),
                        this.dataset.image
                    );
                });
            });

            document.querySelectorAll('.cat-tb').forEach(tab => {
                tab.addEventListener('click', function() {
                    document.querySelectorAll('.cat-tb').forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                    filterProducts(this.dataset.category);
                });
            });

            document.getElementById('search-product').addEventListener('input', function() {
                filterProducts(document.querySelector('.cat-tb.active')?.dataset?.category || 'all');
            });

        });

        function filterProducts(category) {
            const search = document.getElementById('search-product').value.toLowerCase();
            document.querySelectorAll('.prod-c').forEach(card => {
                const matchCat = category === 'all' || card.dataset.category === category;
                const matchSearch = card.dataset.name.toLowerCase().includes(search);
                card.style.display = matchCat && matchSearch ? '' : 'none';
            });
        }

        function openNoteModal(id, name, price, stock, image) {
            pendingNoteProduct = { id, name, price, stock, image };
            modalQty = 1;
            document.getElementById('noteModalTitle').textContent = name;
            document.getElementById('noteModalDesc').textContent = stock > 0 ? 'Stok tersedia: ' + stock + ' item' : 'Stok habis';
            document.getElementById('modalProductPrice').textContent = 'Rp ' + numberFormat(price);
            document.getElementById('modalQty').textContent = '1';
            document.getElementById('note-input').value = '';

            const imgEl = document.getElementById('modalProductImg');
            const emojiEl = document.getElementById('modalProductEmoji');
            const badgeEl = document.getElementById('modalProductBadge');
            if (image) {
                imgEl.src = image;
                imgEl.classList.remove('hidden');
                emojiEl.classList.add('hidden');
            } else {
                imgEl.classList.add('hidden');
                emojiEl.classList.remove('hidden');
            }

            modalShow('noteModal');
            renderQuickNotes();
            setTimeout(() => document.getElementById('note-input').focus(), 100);
        }

        let modalQty = 1;

        function modalQtyChange(delta) {
            if (!pendingNoteProduct) return;
            const newQty = modalQty + delta;
            if (newQty < 1) return;
            if (newQty > pendingNoteProduct.stock) {
                showToast('warning', 'Stok Tidak Cukup', 'Maksimal ' + pendingNoteProduct.stock + ' item');
                return;
            }
            modalQty = newQty;
            document.getElementById('modalQty').textContent = modalQty;
            document.getElementById('modalAddBtnText').textContent = 'Tambah ke Keranjang (' + modalQty + 'x)';
        }

        function closeNoteModal() {
            modalHide('noteModal');
            pendingNoteProduct = null;
            modalQty = 1;
        }

        function renderQuickNotes() {
            const items = ['Less Sugar', 'Less Ice', 'No Ice', 'Extra Ice', 'Less Milk', 'Extra Milk', 'Pedas',
                'Tidak Pedas', 'Tanpa Sayur', 'Extra'
            ];
            document.getElementById('quickNotesContainer').innerHTML = items.map(i =>
                `<button onclick="addQuickNote('${i}')" style="padding:6px 10px;border-radius:var(--radius-sm);font-size:.6875rem;font-weight:550;border:1.5px solid var(--border);background:var(--surface);color:var(--text-secondary);cursor:pointer;transition:all .15s var(--ease);font-family:inherit;" onmouseover="this.style.borderColor='var(--accent)';this.style.color='var(--accent)';this.style.background='var(--accent-subtle)'" onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--text-secondary)';this.style.background='var(--surface)'">${i}</button>`
            ).join('');
        }

        function addQuickNote(note) {
            const input = document.getElementById('note-input');
            input.value = input.value.trim() ? input.value.trim() + ', ' + note : note;
            input.focus();
        }

        function confirmAddToCart() {
            if (!pendingNoteProduct) return;
            const notes = document.getElementById('note-input').value.trim();
            const product = { ...pendingNoteProduct };
            const qty = modalQty;
            closeNoteModal();
            addToCartQty(product.id, product.name, product.price, product.stock, product.image, notes, qty);
        }

        function addToCartQty(id, name, price, stock, image, notes, qty) {
            const existing = cart.find(item => item.id === id && item.notes === (notes || ''));
            if (existing) {
                const newQty = existing.qty + qty;
                if (newQty > stock) {
                    showToast('warning', 'Stok Tidak Cukup', 'Stok ' + name + ' tidak mencukupi');
                    return;
                }
                existing.qty = newQty;
                showToast('success', 'Ditambahkan', name + ' (' + existing.qty + 'x)');
            } else {
                if (stock < 1) {
                    showToast('error', 'Stok Habis', name + ' sedang habis');
                    return;
                }
                cart.push({ id, name, price, stock, qty, image, notes: notes || '' });
                const msg = notes ? name + ' (' + notes + ')' : name + ' ke keranjang';
                showToast('success', 'Ditambahkan', msg);
            }
            renderCart();
        }

        function removeFromCart(index) {
            cart.splice(index, 1);
            renderCart();
        }

        function updateQty(index, delta) {
            const item = cart[index];
            const newQty = item.qty + delta;
            if (newQty < 1) {
                removeFromCart(index);
                return;
            }
            if (newQty > item.stock) {
                showToast('warning', 'Stok Tidak Cukup', 'Maksimal ' + item.stock + ' ' + item.name);
                return;
            }
            item.qty = newQty;
            renderCart();
        }

        function clearCart() {
            if (cart.length === 0) return;
            showConfirm('Kosongkan Keranjang?', 'Semua item di keranjang akan dihapus', function() {
                cart = [];
                renderCart();
                showToast('info', 'Direset', 'Keranjang dikosongkan');
            });
        }

        function renderCart() {
            const list = document.getElementById('cart-items-list');
            const empty = document.getElementById('empty-cart');
            const summary = document.getElementById('cart-summary');
            const footer = document.getElementById('cart-footer');
            list.innerHTML = '';

            if (cart.length === 0) {
                empty.style.display = 'block';
                summary.style.display = 'none';
                footer.style.display = 'none';
            } else {
                empty.style.display = 'none';
                summary.style.display = 'block';
                footer.style.display = 'block';
            }

            let totalQty = 0;
            cart.forEach((item, i) => {
                totalQty += item.qty;
                list.innerHTML += `
            <div class="cart-item">
                <div class="cart-item-img cart-item-placeholder" style="background:${item.image ? 'transparent' : 'linear-gradient(135deg, #FFE0B2 0%, #FFCC80 100%)'};padding:0">
                    ${item.image ? '<img src="' + item.image + '" alt="' + item.name + '">' : '🍽️'}
                </div>
                <div class="cart-item-info">
                    <div class="cart-item-n">${item.name}</div>
                    <div class="cart-item-d">Rp ${numberFormat(item.price)}</div>
                    ${item.notes ? '<div class="note-txt">📝 ' + item.notes + '</div>' : ''}
                </div>
                <div class="cart-item-r">
                    <div class="cart-item-p">Rp ${numberFormat(item.qty * item.price)}</div>
                    <div class="cart-item-q">${item.qty}x</div>
                    <button class="cart-item-rm" onclick="removeFromCart(${i})">&times;</button>
                </div>
            </div>
        `;
            });

            document.getElementById('items-count').textContent = totalQty + ' item';
            updateTotal();
        }

        function updateTotal() {
            const subtotal = cart.reduce((sum, item) => sum + (item.qty * item.price), 0);
            const discountRaw = document.getElementById('discount-input').value.trim();
            let discountAmount = 0;
            if (discountRaw.endsWith('%')) {
                const pct = parseFloat(discountRaw) || 0;
                discountAmount = subtotal * (pct / 100);
            } else {
                discountAmount = parseFloat(discountRaw) || 0;
            }
            discountAmount = Math.min(discountAmount, subtotal);
            const tax = subtotal * storeTaxRate / 100;
            const sc = subtotal * storeScRate / 100;
            const total = Math.max(0, subtotal + tax + sc - discountAmount);
            document.getElementById('subtotal-display').textContent = 'Rp ' + numberFormat(Math.round(subtotal));
            document.getElementById('tax-row').style.display = storeTaxRate > 0 ? '' : 'none';
            document.getElementById('tax-display').textContent = 'Rp ' + numberFormat(Math.round(tax));
            document.getElementById('sc-row').style.display = storeScRate > 0 ? '' : 'none';
            document.getElementById('sc-display').textContent = 'Rp ' + numberFormat(Math.round(sc));
            document.getElementById('total-display').textContent = 'Rp ' + numberFormat(Math.round(total));
            calculateChange();
        }

        function setDiscountMode(mode) {
            const rpBtn = document.getElementById('discount-mode-rp');
            const pctBtn = document.getElementById('discount-mode-pct');
            const input = document.getElementById('discount-input');
            let val = input.value.replace('%', '');
            if (mode === 'pct') {
                rpBtn.style.background = 'transparent';
                rpBtn.style.color = 'var(--400)';
                pctBtn.style.background = 'var(--accent-subtle)';
                pctBtn.style.color = 'var(--accent)';
                if (val && !input.value.includes('%')) input.value = val + '%';
            } else {
                pctBtn.style.background = 'transparent';
                pctBtn.style.color = 'var(--400)';
                rpBtn.style.background = 'var(--accent-subtle)';
                rpBtn.style.color = 'var(--accent)';
                input.value = val;
            }
            updateTotal();
        }

        function calculateChange() {
            const totalText = document.getElementById('total-display').textContent.replace(/[^0-9]/g, '') || '0';
            const total = parseInt(totalText);
            const paid = parseFloat(document.getElementById('paid-amount').value) || 0;
            const change = Math.max(0, paid - total);
            document.getElementById('change-display').textContent = 'Rp ' + numberFormat(Math.round(change));
            document.getElementById('checkout-btn').disabled = (paid < total && document.getElementById('payment-method')
                .value === 'cash');
        }

        function checkout() {
            if (cart.length === 0) {
                showToast('warning', 'Keranjang Kosong', 'Tambahkan item terlebih dahulu');
                return;
            }
            const totalText = document.getElementById('total-display').textContent.replace(/[^0-9]/g, '') || '0';
            const total = parseInt(totalText);
            const paidAmount = parseFloat(document.getElementById('paid-amount').value) || 0;
            const paymentMethod = document.getElementById('payment-method').value;
            const change = Math.max(0, paidAmount - total);

            let html = '<div style="background:var(--surface-secondary);border-radius:var(--radius-md);padding:14px;border:1px solid var(--border-light);text-align:left;">';
            html += '<div style="display:flex;justify-content:space-between;align-items:center;padding:6px 0;font-size:.875rem;"><span style="font-weight:550;color:var(--text-primary);">Total</span><span style="font-weight:700;color:var(--accent);font-size:.95rem;">Rp ' + numberFormat(total) + '</span></div>';
            if (paymentMethod === 'cash') {
                html += '<div style="border-top:1px solid var(--border);margin:4px 0;"></div>';
                html += '<div style="display:flex;justify-content:space-between;align-items:center;padding:6px 0;font-size:.875rem;"><span style="font-weight:550;color:var(--text-primary);">Bayar</span><span style="font-weight:600;color:var(--text-primary);">Rp ' + numberFormat(paidAmount) + '</span></div>';
                html += '<div style="border-top:1px solid var(--border);margin:4px 0;"></div>';
                html += '<div style="display:flex;justify-content:space-between;align-items:center;padding:6px 0;font-size:.875rem;"><span style="font-weight:550;color:var(--text-primary);">Kembalian</span><span style="font-weight:700;color:#059669;font-size:.95rem;">Rp ' + numberFormat(change) + '</span></div>';
            }
            html += '</div>';

            document.getElementById('confirmTitle').textContent = 'Proses Transaksi?';
            document.getElementById('confirmMessage').innerHTML = html;
            modalShow('confirmModal');
            confirmCallback = processCheckout;
        }

        function processCheckout() {
            const paidAmount = parseFloat(document.getElementById('paid-amount').value) || 0;
            const paymentMethod = document.getElementById('payment-method').value;
            const totalText = document.getElementById('total-display').textContent.replace(/[^0-9]/g, '') || '0';
            const total = parseInt(totalText);
            const customerName = document.getElementById('customer-name').value || 'Customer';
            const tableNumber = document.getElementById('table-number').value || '-';
            const orderType = '{{ $ot }}';

            showLoading('Memproses transaksi...');

            const items = cart.map(item => ({
                product_id: item.id,
                quantity: item.qty,
                price: item.price,
                notes: item.notes || ''
            }));
            const discount = document.getElementById('discount-input').value.trim() || '0';

            fetch('{{ route('pos.checkout') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        items,
                        payment_method: paymentMethod,
                        paid_amount: paidAmount,
                        discount,
                        customer_name: customerName,
                        table_number: tableNumber,
                        order_type: orderType
                    })
                })
                .then(res => {
                    if (!res.ok) {
                        return res.text().then(t => { throw new Error('Server error: ' + res.status + ' ' + t.substring(0,100)); });
                    }
                    return res.json();
                })
                .then(data => {
                    if (data.success) {
                        trxCount++;
                        if (data.deactivated_products && data.deactivated_products.length > 0) {
                            showToast('warning', 'Stok Habis', 'Produk berikut telah dinonaktifkan: ' + data.deactivated_products.join(', '));
                        }
                        try {
                            showSuccess(data.code, data.change_amount, data.receipt_url, data.print_data);
                        } catch (e) {
                            showToast('error', 'Error', e.message);
                        }
                        cart = [];
                        renderCart();
                        document.getElementById('paid-amount').value = '';
                        document.getElementById('discount-input').value = 0;
                    } else {
                        showToast('error', 'Gagal', data.message || 'Transaksi gagal');
                    }
                })
                .catch(err => { hideLoading(); showToast('error', 'Error', err.message); })
                .finally(() => {
                    hideLoading();
                    document.getElementById('checkout-btn').disabled = false;
                    document.getElementById('checkout-btn').textContent = 'Process Transactions';
                });
        }

        function numberFormat(num) {
            return new Intl.NumberFormat('id-ID').format(Math.round(num));
        }
    </script>
@endpush

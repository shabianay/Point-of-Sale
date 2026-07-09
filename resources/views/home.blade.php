@extends('layouts.app')
@section('title', 'Dashboard')
@push('styles')
    <style>
        .chart-w {
            position: relative;
            height: 220px;
            width: 100%
        }

        .pm-chart {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            padding: 16px 0
        }

        .pm-item-chart {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: .8rem;
            color: var(--text-secondary)
        }

        .pm-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            flex-shrink: 0
        }

        .chart-tab,
        .bp-tab {
            background: transparent;
            border: 1.5px solid var(--border);
            border-radius: var(--radius-sm);
            padding: 5px 12px;
            font-size: .72rem;
            font-weight: 600;
            cursor: pointer;
            font-family: inherit;
            color: var(--text-tertiary);
            transition: all .15s var(--ease)
        }

        .chart-tab.active,
        .bp-tab.active {
            border-color: var(--accent);
            color: var(--accent);
            background: var(--accent-subtle)
        }

        .chart-tab:hover,
        .bp-tab:hover {
            color: var(--accent)
        }
    </style>
@endpush
@section('content')
    <div class="pg-h">
        <h1>Dashboard</h1>
        <a href="{{ route('pos.index') }}" class="btn btn-primary">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Transaksi Baru
        </a>
    </div>

    <div class="grid-4">
        <div class="stat-c">
            <div class="fx jc-b ai-s">
                <div>
                    <div class="stat-l">Penjualan Hari Ini</div>
                    <div class="stat-v mt-2">Rp {{ number_format($todaySales, 0, ',', '.') }}</div>
                    <div class="mt-2"><span class="b b-o">{{ $todayTransactions }} transaksi</span></div>
                </div>
                <div class="stat-ic" style="background:var(--ob)">
                    <svg width="22" height="22" fill="none" stroke="var(--o)" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>
        <div class="stat-c">
            <div class="fx jc-b ai-s">
                <div>
                    <div class="stat-l">Minggu Ini</div>
                    <div class="stat-v mt-2">Rp {{ number_format($thisWeekSales, 0, ',', '.') }}</div>
                </div>
                <div class="stat-ic" style="background:#ECFDF5">
                    <svg width="22" height="22" fill="none" stroke="#059669" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </div>
            </div>
        </div>
        <div class="stat-c">
            <div class="fx jc-b ai-s">
                <div>
                    <div class="stat-l">Bulan Ini</div>
                    <div class="stat-v mt-2">Rp {{ number_format($thisMonthSales, 0, ',', '.') }}</div>
                </div>
                <div class="stat-ic" style="background:#EFF6FF">
                    <svg width="22" height="22" fill="none" stroke="#2563EB" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
            </div>
        </div>
        <div class="stat-c">
            <div class="fx jc-b ai-s">
                <div>
                    <div class="stat-l">Total Produk</div>
                    <div class="stat-v mt-2">{{ $totalProducts }}</div>
                    @if ($lowStockProducts > 0)
                        <div class="mt-2"><span class="b b-r">{{ $lowStockProducts }} stok menipis</span></div>
                    @endif
                </div>
                <div class="stat-ic" style="background:#F5F3FF">
                    <svg width="22" height="22" fill="none" stroke="#7C3AED" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    @if($outOfStockProducts->count() > 0)
    <div style="background:#FEF2F2;border:1px solid #FECACA;border-radius:var(--radius-md);padding:12px 16px;margin-bottom:20px;display:flex;align-items:center;gap:10px">
        <svg width="20" height="20" fill="none" stroke="#DC2626" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        <div style="flex:1">
            <div style="font-size:.85rem;font-weight:600;color:#DC2626">Produk Nonaktif (Stok Habis)</div>
            <div style="font-size:.78rem;color:#991B1B;margin-top:2px">{{ $outOfStockProducts->pluck('name')->implode(', ') }}</div>
        </div>
        <a href="{{ route('products.index') }}" style="font-size:.78rem;color:#DC2626;font-weight:600;white-space:nowrap">Lihat Produk →</a>
    </div>
    @endif

    <div class="grid-2 mb-5">
        <div class="card">
            <div class="card-h">
                <h6>Penjualan 7 Hari</h6>
            </div>
            <div class="card-b">
                <div class="chart-w"><canvas id="dailyChart"></canvas></div>
            </div>
        </div>
        <div class="card">
            <div class="card-h">
                <h6>Penjualan 12 Bulan</h6>
            </div>
            <div class="card-b">
                <div class="chart-w"><canvas id="monthlyChart"></canvas></div>
            </div>
        </div>
    </div>

    <div class="grid-2">
        <div class="card">
            <div class="card-h">
                <h6>Metode Pembayaran</h6>
                <div class="fx g-1">
                    <button class="chart-tab active" data-period="today">Hari Ini</button>
                    <button class="chart-tab" data-period="week">Minggu Ini</button>
                    <button class="chart-tab" data-period="month">Bulan Ini</button>
                    <button class="chart-tab" data-period="year">Tahun Ini</button>
                </div>
            </div>
            <div class="card-b">
                <div class="chart-w" style="height:200px"><canvas id="paymentChart"></canvas></div>
            </div>
        </div>
        <div class="card">
            <div class="card-h">
                <h6>Produk Terlaris</h6>
                <div class="fx g-1">
                    <button class="bp-tab active" data-period="today">Hari Ini</button>
                    <button class="bp-tab" data-period="week">Minggu Ini</button>
                    <button class="bp-tab" data-period="month">Bulan Ini</button>
                    <button class="bp-tab" data-period="year">Tahun Ini</button>
                </div>
            </div>
            <div id="best-products-wrap">
                @if ($bestProducts->count() > 0)
                    <div class="t-wrap">
                        <table class="tbl">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Produk</th>
                                    <th>Terjual</th>
                                </tr>
                            </thead>
                            <tbody id="best-products-body">
                                @foreach ($bestProducts as $i => $p)
                                    <tr>
                                        <td><span class="b b-o">{{ $i + 1 }}</span></td>
                                        <td>{{ $p->name }}</td>
                                        <td>{{ $p->total_qty }} unit</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="ta-c" style="padding:32px;color:var(--300)">
                        <p style="font-size:.85rem;margin:0">Belum ada data</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="card mt-5">
        <div class="card-h">
            <h6>Transaksi Terbaru</h6>
            <a href="{{ route('transactions.index') }}" class="btn btn-outline btn-sm">Lihat Semua</a>
        </div>
        @if ($recentTransactions->count() > 0)
            <div class="t-wrap">
                <table class="tbl">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Kasir</th>
                            <th>Total</th>
                            <th>Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($recentTransactions as $t)
                            <tr>
                                <td><span class="code code-o">{{ $t->code }}</span></td>
                                <td>{{ $t->user->name }}</td>
                                <td>Rp {{ number_format($t->total, 0, ',', '.') }}</td>
                                <td style="font-size:.8rem;color:var(--400)">{{ $t->created_at->format('H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="ta-c" style="padding:32px;color:var(--300)">
                <p style="font-size:.85rem;margin:0">Belum ada transaksi</p>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        (function() {
            var colors = ['#FF6A00', '#059669', '#2563EB', '#7C3AED', '#DC2626', '#D97706'];
            var labels7 = @json($dailySales->pluck('date'));
            var data7 = @json($dailySales->pluck('total'));
            new Chart(document.getElementById('dailyChart'), {
                type: 'bar',
                data: {
                    labels: labels7,
                    datasets: [{
                        label: 'Penjualan',
                        data: data7,
                        backgroundColor: 'rgba(255,106,0,.2)',
                        borderColor: '#FF6A00',
                        borderWidth: 2,
                        borderRadius: 4,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(v) {
                                    return 'Rp' + (v / 1000).toFixed(0) + 'k';
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });

            var labels12 = @json($monthlySales->pluck('month'));
            var data12 = @json($monthlySales->pluck('total'));
            new Chart(document.getElementById('monthlyChart'), {
                type: 'line',
                data: {
                    labels: labels12,
                    datasets: [{
                        label: 'Penjualan',
                        data: data12,
                        borderColor: '#FF6A00',
                        backgroundColor: 'rgba(255,106,0,.1)',
                        fill: true,
                        tension: .3,
                        pointBackgroundColor: '#FF6A00',
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(v) {
                                    return 'Rp' + (v / 1000).toFixed(0) + 'k';
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });

            var pmLabels = @json($paymentMethods->keys());
            var pmData = @json($paymentMethods->values());
            var bgColors = pmLabels.map(function(_, i) {
                return colors[i % colors.length];
            });
            var paymentChart = new Chart(document.getElementById('paymentChart'), {
                type: 'doughnut',
                data: {
                    labels: pmLabels,
                    datasets: [{
                        data: pmData,
                        backgroundColor: bgColors,
                        borderWidth: 0,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 10,
                                padding: 10,
                                font: {
                                    size: 11
                                }
                            }
                        }
                    }
                }
            });

            // Payment chart filter
            document.querySelectorAll('.chart-tab').forEach(function(tab) {
                tab.addEventListener('click', function() {
                    document.querySelectorAll('.chart-tab').forEach(function(t) {
                        t.classList.remove('active');
                    });
                    this.classList.add('active');

                    fetch('/home/payment-chart?period=' + this.dataset.period)
                        .then(function(r) {
                            return r.json();
                        })
                        .then(function(res) {
                            var c = res.labels.map(function(_, i) {
                                return colors[i % colors.length];
                            });
                            paymentChart.data.labels = res.labels;
                            paymentChart.data.datasets[0].data = res.data;
                            paymentChart.data.datasets[0].backgroundColor = c;
                            paymentChart.update();
                        });
                });
            });

            // Best products filter
            document.querySelectorAll('.bp-tab').forEach(function(tab) {
                tab.addEventListener('click', function() {
                    document.querySelectorAll('.bp-tab').forEach(function(t) {
                        t.classList.remove('active');
                    });
                    this.classList.add('active');

                    fetch('/home/best-products?period=' + this.dataset.period)
                        .then(function(r) {
                            return r.json();
                        })
                        .then(function(data) {
                            var wrap = document.getElementById('best-products-wrap');
                            if (data.length === 0) {
                                wrap.innerHTML =
                                    '<div class="ta-c" style="padding:32px;color:var(--300)"><p style="font-size:.85rem;margin:0">Belum ada data</p></div>';
                                return;
                            }
                            var html =
                                '<div class="t-wrap"><table class="tbl"><thead><tr><th>#</th><th>Produk</th><th>Terjual</th></tr></thead><tbody>';
                            data.forEach(function(p, i) {
                                html += '<tr><td><span class="b b-o">' + (i + 1) +
                                    '</span></td><td class="fw-6">' + p.name +
                                    '</td><td class="fw-7">' + p.total_qty +
                                    ' unit</td></tr>';
                            });
                            html += '</tbody></table></div>';
                            wrap.innerHTML = html;
                        });
                });
            });
        })();
    </script>
@endpush

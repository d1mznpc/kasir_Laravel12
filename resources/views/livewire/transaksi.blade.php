<div>
    <style>
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100vh;
            background: rgba(255, 255, 255, 0.7);
            z-index: 9999;
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>
    <div class="container">
        <div class="row my-2">
            <div class="col-12">

                @if ($transaksiSudahSelesai)
                    <!-- Tampilan setelah transaksi selesai -->
                    <div class="alert alert-success" role="alert">
                        <div class="d-flex flex-column justify-content-center align-items-center" style="height: 200px;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="bi bi-check-circle-fill" width="64"
                                height="64" fill="currentColor" viewBox="0 0 16 16" role="img"
                                aria-label="Success:">
                                <path
                                    d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM6.97 11.03a.75.75 0 0 0 1.07.02l3.992-4.99a.75.75 0 1 0-1.14-.98L7.477 9.417 5.383 7.323a.75.75 0 0 0-1.06 1.06l2.647 2.647z" />
                            </svg>
                            <h4 class="mt-3 mb-0">Selamat!</h4>
                            <p class="mb-0">Transaksi Anda selesai dengan sukses.</p>
                        </div>
                    </div>

                    <button class="btn btn-primary" wire:click='transaksiBaru'>Mulai Transaksi Baru</button>
                @elseif (!$transaksiAktif)
                    <!-- Tampilan sebelum transaksi -->
                    <div class="card text-center border-info mb-3" style="max-width: 30rem; margin: auto;">
                        <div class="card-body">
                            <i class="fas fa-info-circle fa-4x text-primary mb-2"></i>
                            <h5 class="card-title">Belum ada transaksi</h5>
                            <p class="card-text">Silakan mulai transaksi baru dengan menekan tombol di bawah.</p>
                            <button class="btn btn-primary" wire:click='transaksiBaru'>Transaksi Baru</button>
                        </div>
                    </div>
                @else
                    <!-- Tombol batal transaksi saat transaksi aktif -->
                    <button class="btn btn-danger" wire:click='batalTransaksi'>Batalkan Transaksi</button>
                @endif

                <div wire:loading>
                    <div class="loading-overlay">
                        <div class="text-center">
                            <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <div class="mt-3 fw-bold text-primary">Mohon tunggu, sedang memproses...</div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        @if ($transaksiAktif)
            <!-- Bagian transaksi aktif yang sudah ada -->
            <div class="row mt-2">
                <div class="col-8">
                    <div class="card border-primary">
                        <div class="card-body">
                            <h4 class="card-title">No Invoice : {{ $transaksiAktif->kode }}</h4>
                            <input type="string" class="form-control" placeholder="Masukkan kode invoice"
                                wire:model.live='kode'>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Barang</th>
                                        <th>Harga</th>
                                        <th>Qty</th>
                                        <th>Total</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($semuaProduk as $produk)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $produk->produk->kode }}</td>
                                            <td>{{ $produk->produk->nama }}</td>
                                            <td>{{ number_format($produk->produk->harga, 2, '.', ',') }}</td>
                                            <td>
                                                {{ $produk->jumlah }}
                                            </td>
                                            <td>{{ number_format($produk->produk->harga * $produk->jumlah, 2, '.', ',') }}
                                            </td>
                                            <td>
                                                <button wire:click="hapusProduk({{ $produk->id }})"
                                                    class="btn btn-danger">Hapus</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="card border-primary">
                        <div class="card-body">
                            <h4 class="card-title">Total Biaya</h4>
                            <div class="d-flex justify-content-between">
                                <span>Rp.</span>
                                <span>{{ number_format($totalSemuaBelanja, 2, '.', ',') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="card border-primary mt-2">
                        <div class="card-body">
                            <h4 class="card-title">Bayar</h4>
                            <input type="number" class="form-control" placeholder="Masukkan jumlah bayar"
                                wire:model.live='bayar'>
                        </div>
                    </div>
                    <div class="card border-primary mt-2">
                        <div class="card-body">
                            <h4 class="card-title">Kembalian</h4>
                            <div class="d-flex justify-content-between">
                                <span>Rp.</span>
                                <span>{{ number_format($kembalian, 2, '.', ',') }}</span>
                            </div>
                        </div>
                    </div>
                    @if (session()->has('error'))
                        <div class="alert alert-danger mt-2">
                            {{ session('error') }}
                        </div>
                    @endif
                    @if ($bayar)
                        @if ($kembalian < 0)
                            <div class="alert alert-danger mt-2">
                                Jumlah bayar kurang
                            </div>
                        @elseif ($kembalian >= 0)
                            <button class="btn btn-success w-100 mt-2"
                                wire:click='prosesTransaksiSelesai'>Bayar</button>
                        @endif
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>

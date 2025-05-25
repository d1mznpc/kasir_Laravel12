<div>
    <div class="container">
        <div class="row my-2">
            <div class="col-12">
                @if (!$transaksiAktif)
                    <button class="btn btn-primary" wire:click='transaksiBaru'>Transaksi Baru</button>
                @else
                    <button class="btn btn-danger" wire:click='batalTransaksi'>Batalkan Transaksi</button>
                @endif
                <button class="btn btn-info" wire:loading>Loading ...</button>
            </div>
        </div>
        @if ($transaksiAktif)
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
                            <div class="flex justify-content-betweeen">
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
                            <div class="flex justify-content-betweeen">
                                <span>Rp.</span>
                                <span>{{ number_format($kembalian, 2, '.', ',') }}</span>
                            </div>
                        </div>
                    </div>
                    @if ($bayar)
                        @if ($kembalian < 0)
                            <div class="alert alert-danger mt-2">
                                Jumlah bayar kurang
                            </div>
                        @elseif ($kembalian >= 0)
                            <button class="btn btn-success w-100 mt-2" wire:click='transaksiSelesai'>Bayar</button>
                        @endif
                    @endif

                </div>
            </div>
        @endif
    </div>
</div>
<div>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card border-primary">
                    <div class="card-body">
                        <h4 class="card-title">Laporan Transaksi</h4>
                        <a href="{{ route('cetak') }}" target="_blank">CETAK</a>

                        <table class="table table-bordered table-striped table-hover" id="laporan">
                            <thead>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>No Inv.</th>
                                <th>total</th>
                            </thead>
                            <tbody>
                                @foreach ($semuaTransaksi as $transaksi)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $transaksi->created_at->format('d-m-Y') }}</td>
                                    <td>{{ $transaksi->kode }}</td>
                                    <td>Rp. {{ number_format($transaksi->total, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
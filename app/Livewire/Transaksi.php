<?php

namespace App\Livewire;

use App\Models\DetilTransaksi;
use App\Models\Produk;
use App\Models\Transaksi as ModelTransaksi;
use Livewire\Component;

class Transaksi extends Component
{
    public $kode, $total, $status, $kembalian, $totalSemuaBelanja;
    public $bayar = 0;
    public $transaksiAktif;
    public $transaksiSudahSelesai = false; // <-- perbaikan nama properti

    public function transaksiBaru()
    {
        $this->resetExcept(['transaksiSudahSelesai']);
        $this->transaksiSudahSelesai = false;

        $this->transaksiAktif = new ModelTransaksi();
        $this->transaksiAktif->kode = 'INV/' . date('YmdHis');
        $this->transaksiAktif->total = 0;
        $this->transaksiAktif->status = 'pending';
        $this->transaksiAktif->save();
    }

    public function batalTransaksi()
    {
        if ($this->transaksiAktif) {
            $detilTransaksi = DetilTransaksi::where('transaksi_id', $this->transaksiAktif->id)->get();
            foreach ($detilTransaksi as $detil) {
                $produk = Produk::find($detil->produk_id);
                if ($produk) {
                    $produk->stok += $detil->jumlah;
                    $produk->save();
                }
                $detil->delete();
            }
            $this->transaksiAktif->delete();
        }

        $this->resetExcept(['transaksiSudahSelesai']);
        $this->transaksiSudahSelesai = false;
    }

    public function updatedKode()
    {
        $produk = Produk::where('kode', $this->kode)->first();
        if ($produk && $produk->stok > 0) {
            $detil = DetilTransaksi::firstOrNew(
                ['transaksi_id' => $this->transaksiAktif->id, 'produk_id' => $produk->id],
                ['jumlah' => 0]
            );
            $detil->jumlah += 1;
            $detil->save();

            $produk->stok -= 1;
            $produk->save();

            $this->reset('kode');
        }
    }

    public function updatedBayar()
    {
        if ($this->bayar >= 0 && $this->totalSemuaBelanja > 0) {
            $this->kembalian = $this->bayar - $this->totalSemuaBelanja;
        } else {
            $this->kembalian = 0;
        }
    }

    public function hapusProduk($id)
    {
        $detil = DetilTransaksi::find($id);
        if ($detil) {
            $produk = Produk::find($detil->produk_id);
            if ($produk) {
                $produk->stok += $detil->jumlah;
                $produk->save();
            }
            $detil->delete();
        }
    }

    public function prosesTransaksiSelesai()
    {
        if (!$this->transaksiAktif) return;

        if ($this->bayar < $this->totalSemuaBelanja) {
            session()->flash('error', 'Pembayaran tidak mencukupi!');
            return;
        }

        $this->transaksiAktif->total = $this->totalSemuaBelanja;
        $this->transaksiAktif->status = 'selesai';
        $this->transaksiAktif->save();

        $this->transaksiSudahSelesai = true;

        $this->transaksiAktif = null;
        $this->bayar = 0;
        $this->kembalian = 0;
        $this->totalSemuaBelanja = 0;
    }

    public function render()
    {
        $semuaProduk = [];

        if ($this->transaksiAktif) {
            $semuaProduk = DetilTransaksi::with('produk')
                ->where('transaksi_id', $this->transaksiAktif->id)
                ->get();

            $this->totalSemuaBelanja = $semuaProduk->sum(function ($detil) {
                return optional($detil->produk)->harga * $detil->jumlah;
            });
        } else {
            $this->totalSemuaBelanja = 0;
        }

        return view('livewire.transaksi')->with([
            'semuaProduk' => $semuaProduk,
            'transaksiSudahSelesai' => $this->transaksiSudahSelesai
        ]);
    }
}

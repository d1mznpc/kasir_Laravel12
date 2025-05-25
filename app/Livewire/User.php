<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User as ModelUser;

class User extends Component
{
    public $pilihanMenu = "lihat";
    public $nama;
    public $email;
    public $password;
    public $peran;
    public $penggunaTerpilih;

    public function mount()
    {
        if (auth()->user()->peran != "admin") {
            abort(403);
        }
    }

    public function pilihEdit($id)
    {
        $this->penggunaTerpilih = ModelUser::findOrFail($id);
        $this->nama = $this->penggunaTerpilih->name;
        $this->email = $this->penggunaTerpilih->email;
        $this->peran = $this->penggunaTerpilih->peran;
        $this->pilihanMenu = "edit";
    }
    public function simpanEdit()
    {
        $this->validate([
            'nama' => 'required',
            'email' => ['required','email','unique:users,email,'.$this->penggunaTerpilih->id],
            'peran' => 'required',
            'password' => 'nullable|min:6'
        ],[
            'nama.required' => 'Nama tidak boleh kosong',
            'email.required' => 'Email tidak boleh kosong',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'peran.required' => 'Peran tidak boleh kosong',
            'password.min' => 'Password minimal 6 karakter'
        ]);
        $simpan = $this->penggunaTerpilih;
        $simpan->name = $this->nama;
        $simpan->email = $this->email;
        if ($this->password) {
            $simpan->password = bcrypt($this->password);
        }
        $simpan->peran = $this->peran;
        $simpan->save();

        $this->reset(['nama', 'email', 'password', 'peran', 'penggunaTerpilih']);
        $this->pilihanMenu = "lihat";
        session()->flash('pesan', 'Data berhasil disimpan');
    }
    public function pilihHapus($id)
    {
        $this->penggunaTerpilih = ModelUser::findOrFail($id);
        $this->pilihanMenu = "hapus";
    }
    public function batal()
    {
        $this->reset();
    }
    public function hapus()
    {
        $this->penggunaTerpilih->delete();
        $this->pilihanMenu = "lihat";
    }

    public function simpan()
    {
        $this->validate([
            'nama' => 'required',
            'email' => ['required','email','unique:users,email'],
            'peran' => 'required',
            'password' => 'required|min:6'
        ],[
            'nama.required' => 'Nama tidak boleh kosong',
            'email.required' => 'Email tidak boleh kosong',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'peran.required' => 'Peran tidak boleh kosong',
            'password.required' => 'Password tidak boleh kosong',
            'password.min' => 'Password minimal 6 karakter'
        ]);

        $simpan = new ModelUser();
        $simpan->name = $this->nama;
        $simpan->email = $this->email;
        $simpan->password = bcrypt($this->password);
        $simpan->peran = $this->peran;
        $simpan->save();

        $this->reset(['nama', 'email', 'password', 'peran']);
        $this->pilihanMenu = "lihat";
        session()->flash('pesan', 'Data berhasil disimpan');
    }
    public function pilihMenu($menu)
    {
        $this->pilihanMenu = $menu;
    }
    public function render()
    {
        return view('livewire.user')->with(['semuaPengguna' => ModelUser::all()]);
    }
}
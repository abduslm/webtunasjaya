<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DaftarbarangController extends Controller
{
    public function index()
    {
        // Nantinya data barang dari database akan diambil di sini
        // $barangs = Barang::all();

        return view('admin.absensi.daftarbarang');
    }
}
<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

Route::get('/admin', function () {
    return view('admin.dashboard');})->name('admin.dashboard');
Route::get('/admin/beranda', function () {
    return view('admin.front_pages.beranda');})->name('admin.beranda');
Route::get('/admin/layanan', function () {
    return view('admin.front_pages.layanan');})->name('admin.layanan');
Route::get('/admin/portofolio', function () {
    return view('admin.front_pages.portofolio');})->name('admin.portofolio');
Route::get('/admin/tentang-kami', function () {
    return view('admin.front_pages.tentangKami');})->name('admin.tentang-kami');
Route::get('/admin/hubungi-kami', function () {
    return view('admin.front_pages.hubungiKami');})->name('admin.hubungi-kami');
Route::get('/admin/dokumentasi', function () {
    return view('admin.front_pages.dokumentasi');})->name('admin.dokumentasi');

Route::get('/admin/persetujuan-cuti', function () {
    return view('admin.absensi.persetujuanCuti');})->name('admin.persetujuan-cuti');
Route::get('/admin/lokasi-absensi', function () {
    return view('admin.absensi.lokasiAbsensi');})->name('admin.lokasi-absensi');
Route::get('/admin/koreksi-absensi', function () {
    return view('admin.absensi.koreksiAbsensi');})->name('admin.koreksi-absensi');
Route::get('/admin/kelola-user', function () {
    return view('admin.absensi.kelolaUser');})->name('admin.kelola-user');
Route::get('/admin/daftar-absensi', function () {
    return view('admin.absensi.daftarAbsensi');})->name('admin.daftar-absensi');
Route::get('/admin/logout', function () {
    return view('logout');})->name('admin.logout');

Route::get('/login', function () {
    return view('admin.absensi.login');})->name('login');
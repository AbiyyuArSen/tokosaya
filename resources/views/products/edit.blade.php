@extends('layouts.app')

@section('title', 'Edit Produk')

@section('nav-title', 'Manajemen Produk')

@section('content')

    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 18px;">
        <h1 style="font-size:22px; font-weight:700; margin:0;">✏️ Edit Produk</h1>
        <a href="{{ route('products.index') }}" class="btn" style="text-decoration:none;color:var(--accent);font-weight:600;">← Kembali ke Daftar</a>
    </div>

    <form action="{{ route('products.update', $product->id) }}" method="POST" style="max-width:700px;margin:0 auto;">
        @csrf
        @method('PUT')

        {{-- Tambahkan field store_name --}}
        <label>Nama Toko</label>
        <input type="text" name="store_name" value="{{ old('store_name', $product->store_name) }}" required placeholder="Nama Toko">

        {{-- Menggunakan $product->name yang akan bekerja karena Accessor di Model --}}
        <label>Nama Produk</label>
        <input type="text" name="name" value="{{ old('name', $product->name) }}" required placeholder="Nama Produk">

        <label>Harga (IDR)</label>
        <input type="text" id="price" name="price" value="{{ old('price', $product->price) }}" required placeholder="Harga Produk" inputmode="decimal" pattern="[0-9.,]*">

        <script>
            document.querySelector('form').addEventListener('submit', function (e) {
                const p = document.getElementById('price');
                if (p && p.value) {
                    let v = p.value.replace(/\s+/g, '');
                    if (v.indexOf(',') !== -1 && v.indexOf('.') !== -1) {
                        v = v.replace(/\./g, '');
                        v = v.replace(/,/g, '.');
                    } else if (v.indexOf(',') !== -1) {
                        v = v.replace(/,/g, '.');
                    }
                    v = v.replace(/[^0-9.\-]/g, '');
                    p.value = v;
                }
            });
        </script>

        <label>Deskripsi</label>
        <textarea name="description" rows="5" placeholder="Deskripsi produk">{{ old('description', $product->description) }}</textarea>

        <button type="submit" class="btn-blue" style="width:100%;">Perbarui Produk</button>
    </form>

@endsection
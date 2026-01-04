<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        // Preprocess input: normalisasi format harga (terima '120000,00' atau '120.000,00')
        $input = $request->all();
        $input['price'] = $this->normalizePrice($input['price'] ?? null);

        // 1. Validasi data (menggunakan 'name' dan 'store_name' dari form)
        $data = \Illuminate\Support\Facades\Validator::make($input, [
            'store_name' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'description' => 'nullable|string',
        ])->validate();

        // 2. SINKRONISASI: Mengubah 'name' (input) menjadi 'nama' (kolom DB)
        $data['nama'] = $data['name']; 
        unset($data['name']); // Hapus kunci 'name' yang tidak dikenali DB

        Product::create($data); // Baris ini sekarang aman

        return redirect()->route('products.index')->with('success', 'Product created.');
    }

    public function update(Request $request, Product $product)
    {
        // Preprocess input: normalisasi harga
        $input = $request->all();
        $input['price'] = $this->normalizePrice($input['price'] ?? null);

        // 1. Validasi data
        $data = \Illuminate\Support\Facades\Validator::make($input, [
            'store_name' => 'required|string|max:255', // Asumsi ada di form edit
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'description' => 'nullable|string',
        ])->validate();

        // 2. SINKRONISASI: Mengubah 'name' (input) menjadi 'nama' (kolom DB)
        $data['nama'] = $data['name'];
        unset($data['name']);

        $product->update($data);

        return redirect()->route('products.index')->with('success', 'Product updated.');
    }

    /**
     * Normalizes price input to a dot-decimal numeric string.
     * Accepts formats like "120000,00", "120.000,00", "120000.00", etc.
     */
    private function normalizePrice($value)
    {
        if ($value === null) return null;
        $value = trim((string) $value);

        // Jika ada kedua separator '.' dan ',', asumsikan '.' ribuan dan ',' desimal (contoh: 1.234,56)
        if (strpos($value, ',') !== false && strpos($value, '.') !== false) {
            $value = str_replace('.', '', $value);
            $value = str_replace(',', '.', $value);
        } else {
            // Jika hanya ada koma, gunakan sebagai decimal separator
            if (strpos($value, ',') !== false) {
                $value = str_replace(',', '.', $value);
            }
            // Jika hanya titik, biarkan (titik adalah decimal separator)
        }

        // Hapus karakter selain angka, titik, atau minus
        $value = preg_replace('/[^0-9\.\-]/', '', $value);

        return $value;
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }


    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted.');
    }
}

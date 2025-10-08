<?php

namespace App\Http\Controllers;

use App\Models\ProductKantin;
use GuzzleHttp\Promise\Create;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductKantinController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $kategori = $request->get('kategori');
        if($kategori){
            $produk_kantin = ProductKantin::where('kategori', $kategori)->get();
        }
        else{
            $produk_kantin = ProductKantin::all();
        }

        return view('dashboard', compact('produk_kantin'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|max:50',
            'harga' => 'required|numeric',
            'stok' => 'required|integer|min:0',
            'kategori' => 'nullable|in:makanan,minuman',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048' // ← Tambah ini
        ]);

        // Upload gambar jika ada
        $gambarPath = null;
        if ($request->hasFile('gambar')) {
            $gambarPath = $request->file('gambar')->store('foto', 'public');
        }

        ProductKantin::create([
            'nama' => $request->nama,
            'harga' => $request->harga,
            'stok' => $request->stok,
            'kategori' => $request->kategori,
            'gambar' => $gambarPath // ← Simpan path
        ]);

        return redirect()->back()->with('success', 'Menu baru berhasil dibuat');
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductKantin $productKantin)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductKantin $productKantin)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductKantin $produk)
    {
        $request->validate([
            'nama' => 'required|max:50',
            'harga' => 'nullable|numeric',
            'stok' => 'nullable|integer|min:0',
            'kategori' => 'nullable|in:makanan,minuman',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048' // ← Tambah ini
        ]);

        // Upload gambar baru jika ada
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($produk->gambar) {
                Storage::disk('public')->delete($produk->gambar);
            }
            
            // Upload gambar baru
            $gambarPath = $request->file('gambar')->store('product-images', 'public');
            $produk->gambar = $gambarPath;
        }

        // Update data lainnya
        $produk->nama = $request->nama;
        $produk->harga = $request->harga;
        $produk->stok = $request->stok;
        $produk->kategori = $request->kategori;
        $produk->save();

        return redirect()->back()->with('success', 'Produk berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductKantin $produk)
    {
        // Hapus gambar jika ada
        if ($produk->gambar) {
            Storage::disk('public')->delete($produk->gambar);
        }

        $produk->delete();
        
        return redirect()->route('dashboard')->with('success', 'Menu telah dihapus');
    }

    public function beli(Request $request, ProductKantin $produk)
    {
        $request->validate([
            'nominal' => 'required|numeric|min:0'
        ]);

        $nominal = $request->nominal;
        $harga = $produk->harga;

        // cek apakah stok tersedia
        if($produk->stok <= 0 ){
            return redirect()->back()->with('error', 'stok habis!');
        }

        // cek nominal cukup atau tidak
        if($nominal < $harga){
            $kurang = $harga - $nominal;
            return redirect()->back()->with('error', "Uang kurang RP" . number_format($kurang, 0, ',','.'));
        }

        // menghitung kembalian
        $kembalian = $nominal - $harga;

        // kurangi stok ketika pembelian berhasil
        $produk->decrement('stok');

        if($kembalian > 0){
            return redirect()->back()->with('success', "Berhasil membeli produk! Kembalian: RP " . number_format($kembalian, 0, ',', '.'));
        }else{
            return redirect()->back()->with('success', "Pembelian berhasil, Uang anda pas.");
        }
    }
}

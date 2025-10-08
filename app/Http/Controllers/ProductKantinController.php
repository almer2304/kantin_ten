<?php

namespace App\Http\Controllers;

use App\Models\ProductKantin;
use GuzzleHttp\Promise\Create;
use Illuminate\Http\Request;

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
            'kategori' => 'nullable|in:makanan, minuman'
        ]);

        ProductKantin::create([
            'nama' => $request->nama,
            'harga' => $request->harga,
            'kategori' => $request->kategori
        ]);

        return redirect()->back()->with('status','Menu baru berhasil dibuat');
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
        $request -> validate([
            'nama' => 'required|max:50',
            'harga' => 'nullable|numeric',
            'kategori' => 'nullable|in:makanan,minuman'
        ]);

        $produk->update([
            'nama' => $request->nama,
            'harga' => $request->harga,
            'kategori' => $request->kategori
        ]);

        return redirect()->back()->with('status','Produk berhasil di update');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductKantin $produk)
    {
        // dd([
        //     'masuk' => 'Controller destroy dipanggil',
        //     'data' => $productKantin
        // ]);

        $produk->delete();
        return redirect()->route('dashboard')->with('status','Menu telah dihapus');
    }
}

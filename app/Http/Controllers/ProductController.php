<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator; //panggil fungsi untuk validasi inputan
use Illuminate\Http\Request;
use App\Models\Product; //panggil model

class ProductController extends Controller
{
    public function store(Request $request)
    {
        // melakukan validasi inputan
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:100',
            'price' => 'required|numeric',
            'type' => 'required|in:makanan,minuman,makeup',
            'expired_at' => 'required|date'
        ]);

        // kondisi inputan salah
        if ($validator->fails()) {
            return response()->json($validator->messages())->setStatusCode(422);
        }

        // menampung inputan sudah benar
        $validated = $validator->validated();

        // masukan ke tabel products
        Product::create([
            'name' => $validated['name'],
            'price' => $validated['price'],
            'type' => $validated['type'],
            'expired_at' => $validated['expired_at'],
        ]);

        // notifikasi inputan berhasil
        return response()->json([
            'messages' => 'Data Berhasil Disimpan'
        ])->setStatusCode(201);
    }

    public function show()
    {
        $products = Product::all();

        return response()->json($products)->setStatusCode(200);
    }

    public function update(Request $request, $id)
    {
        // melakukan validasi inputan
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:100',
            'price' => 'required|numeric',
            'type' => 'required|in:makanan,minuman,makeup',
            'expired_at' => 'required|date'
        ]);

        // kondisi inputan salah
        if ($validator->fails()) {
            return response()->json($validator->messages())->setStatusCode(422);
        }

        // menampung inputan sudah benar
        $validated = $validator->validated();

        $checkData = Product::find($id);

        // dd($checkData);

        if ($checkData) {
            Product::where('id', $id)
                ->update([
                    'name' => $validated['name'],
                    'price' => $validated['price'],
                    'type' => $validated['type'],
                    'expired_at' => $validated['expired_at'],
                ]);

            // notifikasi inputan berhasil
            return response()->json([
                'messages' => 'Data Berhasil Disunting'
            ])->setStatusCode(201);
        }

        // notifikasi inputan gagal
        return response()->json([
            'messages' => 'Data Produk tidak ditemukan'
        ])->setStatusCode(404);
    }

    public function destroy($id)
    {
        $checkData = Product::find($id);

        if ($checkData) {
            Product::destroy($id);

            // notifikasi inputan gagal
            return response()->json([
                'messages' => 'Data berhasil dihapus'
            ])->setStatusCode(200);
        }

        // notifikasi inputan gagal
        return response()->json([
            'messages' => 'Data Produk tidak ditemukan'
        ])->setStatusCode(404);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dokumen;

class DokumenMasukController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $itemsPerPage = request('items') ?? 10;

        $dokumen = Dokumen::with(['detailDokumen'])
                    ->latest()
                    ->filter(request(['search']))
                    ->where('status', '=', 'Terverifikasi')
                    ->paginate($itemsPerPage)
                    ->withQueryString();

        return view("pages/dokumen-masuk/index", [
            "title" => "Data Arsip",
            "dokumen" => $dokumen
        ]);
    }
}

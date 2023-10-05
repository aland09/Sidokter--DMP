<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dokumen;
use Spatie\Activitylog\Models\Activity;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $itemsPerPage = request('items') ?? 10;
        $total_dokumen = Dokumen::count();
        $dokumen_terverifikasi = Dokumen::where('status', '=', 'Terverifikasi')->count();
        $dokumen_keluar = Dokumen::where('status', '=', 'Dipinjam')->count();
        $logs = Activity::with('causer')->latest()->take(15)->get();

        return view("pages.dashboard.index", [
            "title"             => "Data Arsip",
            "total_dokumen" => $total_dokumen,
            "dokumen_terverifikasi" => $dokumen_terverifikasi,
            "dokumen_keluar" => $dokumen_keluar,
            "logs" => $logs,
        ]);
    }
}

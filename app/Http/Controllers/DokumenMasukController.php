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

       // $noBox = $this->generateNoBox(2023);
        $itemsPerPage = request('items') ?? 10;

        $dokumen = Dokumen::with(['detailDokumen'])
                    ->latest()
                    ->filter(request(['search']))
                    ->where('status', '=', 'Terverifikasi')
                    ->paginate($itemsPerPage)
                    ->withQueryString();

        return view("pages/dokumen-masuk/index", [
            "title" => "Data Arsip",
            "dokumen" => $dokumen,
            "no_box_tmp" => $this->generate_no_box(2023)
        ]);
    }

    public function generate_no_box($year) {
        $counter = Dokumen::whereNull('no_box')->where('kurun_waktu', '=', $year)->count();
        $short_year = substr($year,2);
        $current_number = sprintf("%05d", $counter+1);
        $no_box = $current_number."/".$year."/P.".$short_year."/SBPKDJP";
        return $no_box;
    }

    public function get_no_box($year) {
        return response()->json($this->generate_no_box($year));
    }
}

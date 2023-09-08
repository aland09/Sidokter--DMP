<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dokumen;
use App\Models\DetailDokumen;

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
                    ->orderBy('no_box', 'ASC')
                    ->paginate($itemsPerPage)
                    ->withQueryString();

        return view("pages/dokumen-masuk/index", [
            "title" => "Data Arsip",
            "dokumen" => $dokumen,
            "no_box_tmp" => $this->generate_no_box(2023)
        ]);
    }

    public function generate_no_box($year) {
        $counter = Dokumen::whereNotNull('no_box')->where('kurun_waktu', '=', $year)->distinct()->count('no_box');
        $short_year = substr($year,2);
        $current_number = sprintf("%05d", $counter+1);
        $no_box = $current_number."/".$year."/P.".$short_year."/SBPKDJP";
        return $no_box;
    }

    public function get_no_box($year) {
        return response()->json($this->generate_no_box($year));
    }

    public function update_no_box(Request $request) {
        $ids = $request['id'];
        $id = explode(",",$ids[0]);
        $kurun_waktu = $request['kurun_waktu'];
        $no_box = $this->generate_no_box($kurun_waktu);
        $data['no_box'] = $no_box;
        
        foreach($id as $item) {
            
            Dokumen::where('id', $item)->update($data);
             DetailDokumen::where('dokumen_id', $item)->update($data);
        }

        return redirect()->route('dokumen-masuk.index')->with('message','No. Box telah berhasil diperbaharui');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Dokumen $dokumen_masuk)
    {
        $dokumen = Dokumen::with([
                    'detailDokumen' => function($query) {
                        $query->orderBy('id', 'ASC');
                    },
                    'akunJenis' => function($query) {
                        $query->select('id', 'kode_akun','nama_akun');
                    },
                ])
                ->where('id', $dokumen_masuk->id)
                ->first();

        return view("pages/dokumen-masuk/show", [
            "title"             => "Detail Data Dokumen Masuk",
            "dokumen"           => $dokumen
        ]);
    }

    public function detail_box($no_box)
    {
        $no_box_convert = str_replace("_","/",$no_box);
        $berkas_dokumen = Dokumen::with([
                    'detailDokumen' => function($query) {
                        $query->orderBy('id', 'ASC');
                    },
                    'akunJenis' => function($query) {
                        $query->select('id', 'kode_akun','nama_akun');
                    },
                ])
                ->where('no_box', $no_box_convert)
                ->get();



        return view("pages/dokumen-masuk/detail-box", [
            "title"             => "Detail Data Dokumen Masuk",
            "no_box"            => $no_box,
            "berkas_dokumen"    => $berkas_dokumen
        ]);

    }
}

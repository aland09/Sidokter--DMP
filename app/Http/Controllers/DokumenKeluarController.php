<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dokumen;
use App\Models\DokumenKeluar;

class DokumenKeluarController extends Controller
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

        $dokumen = DokumenKeluar::with(['dokumen'])
                    ->latest()
                    ->filter(request(['search']))
                    ->paginate($itemsPerPage)
                    ->withQueryString();

        return view("pages/dokumen-keluar/index", [
            "title" => "Daftar Dokumen Keluar",
            "dokumen" => $dokumen
        ]);
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create()
    {
        $listDokumen = Dokumen::select('id','no_sp2d')->where('status','=','Terverifikasi')->get();
        return view('pages/dokumen-keluar/create', [
            'listDokumen' => $listDokumen,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data['dokumen_id']         = $request['dokumen_id'];
        $data['nama_peminjam']      = $request['nama_peminjam'];
        $data['tanggal_peminjaman'] = $request['tanggal_peminjaman'];
        $data['instansi']           = $request['instansi'];
        $data['tujuan']             = $request['tujuan'];
        
        $dokumen_keluar = DokumenKeluar::create($data);


        $dokumens   = Dokumen::find($request['dokumen_id']);
        if($dokumens) {
            $dokumens->status = 'Dipinjam';
            $dokumens->update();
        }

        return redirect()->route('dokumen-keluar.index')->with('message','Dokumen keluar berhasil ditambahkan.');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Dokumen $dokumen_keluar)
    {
        $dokumen_keluar = DokumenKeluar::with(['dokumen'])
                ->where('id', $dokumen_keluar->id)
                ->first();

        return view("pages/dokumen-keluar/show", [
            "title"                 => "Detail Data Dokumen Keluar",
            "dokumen_keluar"        => $dokumen_keluar
        ]);
    }
}

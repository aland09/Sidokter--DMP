<?php

namespace App\Http\Controllers;

use App\Models\Regulasi;
use Illuminate\Http\Request;
use App\Http\Requests\StoreRegulasiRequest;
use App\Http\Requests\UpdateRegulasiRequest;
use Illuminate\Support\Facades\Storage;

class RegulasiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $regulasi = Regulasi::all();

        return view("pages/regulasi/index", [
            "title" => "Regulasi",
            "regulasi" => $regulasi
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages/regulasi/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRegulasiRequest $request)
    {
        $data = $request->except(['_token']);

        // if ($request->hasFile('file_regulasi')){
        $filenameWithExt = $request->file('file_regulasi')->getClientOriginalName();
        $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
        $extension = $request->file('file_regulasi')->getClientOriginalExtension();
        $filenameSimpan = $filename.'_'.time().'.'.$extension;
        $path = $request->file('file_regulasi')->storeAs('public/dokumen_regulasi', $filenameSimpan);
        //     $data['file_regulasi'] = $path;
        // }

        $data['file_regulasi'] = 'dokumen_regulasi/' . $filenameSimpan;
        // dd($data);

        Regulasi::create($data);
        return redirect()->route('regulasi.index')->with('message','Data Regulasi Berhasil Dibuat');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Regulasi  $regulasi
     * @return \Illuminate\Http\Response
     */
    public function show(Regulasi $regulasi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Regulasi  $regulasi
     * @return \Illuminate\Http\Response
     */
    public function edit(Regulasi $regulasi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateRegulasiRequest  $request
     * @param  \App\Models\Regulasi  $regulasi
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRegulasiRequest $request, Regulasi $regulasi)
    {
        $data = $request->except(['_token','_method']);

        if ($request->hasFile('file_regulasi')){
            $filenameWithExt = $request->file('file_regulasi')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('file_regulasi')->getClientOriginalExtension();
            $filenameSimpan = $filename.'_'.time().'.'.$extension;
            $path = $request->file('file_regulasi')->storeAs('public/dokumen_regulasi', $filenameSimpan);
            $data['file_regulasi'] = 'dokumen_regulasi/'.$filenameSimpan;
        }

        Regulasi::where('id', $regulasi->id)->update($data);

        return redirect()->route('regulasi.index')->with('message','Data regulasi berhasil diperbaharui');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Regulasi  $regulasi
     * @return \Illuminate\Http\Response
     */
    public function destroy(Regulasi $regulasi)
    {
        Regulasi::destroy($regulasi->id);
        return redirect()->route('regulasi.index')->with('message', 'Data regulasi berhasil dihapus');
    }
}

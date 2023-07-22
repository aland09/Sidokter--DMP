<?php

namespace App\Http\Controllers;

use App\Models\JenisBelanja;
use Illuminate\Http\Request;

class JenisBelanjaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$JenisPerPage = request('jenis_belanja') ?? 10;

        $jenis = JenisBelanja::with(['detail_dokumens'])
                    ->latest();

        return view("pages/jenis-belanja/index", [
            "title" => "Jenis Belanja",
            "jenis" => $jenis
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //$belanjaList = JenisBelanja::select('id','name')->get();

        return view('pages/users/create', [
            //'rolesList' => $rolesList
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
        $validatedData = $request->validate([
            'name' => 'required|max:250',
            'status' => 'required',
        ]);
 
        
        JenisBelanja::create($validatedData);
        return redirect('jenis-belanja.index')->with('success', 'Jenis Belanja Berhasil Ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\JenisBelanja  $jenisBelanja
     * @return \Illuminate\Http\Response
     */
    public function show(JenisBelanja $jenisBelanja)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\JenisBelanja  $jenisBelanja
     * @return \Illuminate\Http\Response
     */
    public function edit(JenisBelanja $jenisBelanja)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\JenisBelanja  $jenisBelanja
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, JenisBelanja $jenisBelanja)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\JenisBelanja  $jenisBelanja
     * @return \Illuminate\Http\Response
     */
    public function destroy(JenisBelanja $jenisBelanja)
    {
        //
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\DetailDokumen;
use Illuminate\Http\Request;

class DetailDokumenController extends Controller
{
    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\DetailDokumen  $detail_data_arsip
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, DetailDokumen $detail_data_arsip)
    {
        $rules = [
            'dokumen_id' => 'required',
            'kode_klasifikasi' => 'required',
            'uraian' => 'required',
            'tanggal_validasi' => 'required',
            'jumlah_satuan_item' => 'required',
            'keterangan' => 'required',
            'no_spm' => 'required',
            'no_sp2d' => 'required',
            'nominal' => 'required',
            'skpd' => 'required',
            'pejabat_penandatangan' => 'required',
            'unit_pengolah' => 'required',
            'kurun_waktu' => 'required',
            'jumlah_satuan_berkas' => 'required',
            'tkt_perkemb' => 'required',
            'no_box' => 'required',
        ];

        $validatedData = $request->validate($rules);
         
        DetailDokumen::where('id', $detail_data_arsip->id)->update($validatedData);

        return redirect()->route('data-arsip.index')->with('message','Data arsip berhasil diperbaharui');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\DetailDokumen  $penempatan_murid
     * @return \Illuminate\Http\Response
     */
    public function destroy(DetailDokumen $detail_data_arsip)
    {
        DetailDokumen::destroy($detail_data_arsip->id);
        return redirect()->route('data-arsip.index')->with('message', 'Data arsip berhasil dihapus');
    }
}

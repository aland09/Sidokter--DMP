<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use App\Imports\DokumenImport;
use App\Imports\DetailDokumenImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;


class DokumenController extends Controller
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
                    ->paginate($itemsPerPage)
                    ->withQueryString();

        return view("pages/data-arsip/index", [
            "title" => "Data Arsip",
            "dokumen" => $dokumen
        ]);
    }

    public function import_excel(Request $request) 
	{
		$this->validate($request, [
			'file' => 'required|mimes:csv,xls,xlsx'
		]);

		$file = $request->file('file');

		$nama_file = rand().$file->getClientOriginalName();

		$file->move('file_dokumen',$nama_file);

		Excel::import(new DokumenImport, public_path('/file_dokumen/'.$nama_file));
		Excel::import(new DetailDokumenImport, public_path('/file_dokumen/'.$nama_file));

        return redirect()->route('data-arsip.index')->with('message','Data arsip berhasil diimport');

	}
}

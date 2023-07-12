<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use App\Models\DetailDokumen;
use App\Imports\DokumenImport;
use App\Imports\DetailDokumenImport;
use App\Imports\DokumenSp2dImport;
use App\Imports\DokumenSpmImport;
use App\Imports\DokumenSppImport;
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
                    ->where('status', '=', 'Menunggu Verifikasi')
                    ->paginate($itemsPerPage)
                    ->withQueryString();

        return view("pages/data-arsip/index", [
            "title" => "Data Arsip",
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
        $listArsip = [
            [
                "id"      => 1,
                "no_sp2d" => "00000001/SPP/10503000/VI/2023"
            ],
            [
                "id"      => 2,
                "no_sp2d" => "00000002/SPP/10503000/VI/2023"
            ],
            [
                "id"      => 3,
                "no_sp2d" => "00000003/SPP/10503000/VI/2023"
            ]
        ];

        return view('pages/data-arsip/create', [
            'listArsip' => $listArsip,
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
        $no_sp2d_dokumen = $request['no_sp2d_dokumen'];

        $sp2d_dokumen = Dokumen::where('no_sp2d', $no_sp2d_dokumen)->first();

        if ($sp2d_dokumen === null) {

            $data['no_sp2d'] = $request['no_sp2d_dokumen'];
            $data['status'] = 'Menunggu Verifikasi';

            $dokumen_id = Dokumen::create($data)->id;

            $nilai_count = $request['nilai_count'];

            if(!empty($request['nilai_count'])) {
        
                foreach($nilai_count as $key=>$value){
    
                    $arsipData['dokumen_id'] = $dokumen_id;
                    $arsipData['kode_klasifikasi'] = $request['kode_klasifikasi'][$key];
                    $arsipData['uraian'] = $request['uraian'][$key];
                    $arsipData['tanggal_validasi'] = $request['tanggal_validasi'][$key];
                    $arsipData['jumlah_satuan_item'] = $request['jumlah_satuan_item'][$key];
                    $arsipData['keterangan'] = $request['keterangan'][$key];
                    $arsipData['no_spm'] = $request['no_spm'][$key];
                    $arsipData['no_sp2d'] = $request['no_sp2d'][$key];
                    $arsipData['nominal'] = $request['nominal'][$key];
                    $arsipData['skpd'] = $request['skpd'][$key];
                    $arsipData['pejabat_penandatangan'] = $request['pejabat_penandatangan'][$key];
                    $arsipData['unit_pengolah'] = $request['unit_pengolah'][$key];
                    $arsipData['kurun_waktu'] = $request['kurun_waktu'][$key];
                    $arsipData['jumlah_satuan_berkas'] = $request['jumlah_satuan_berkas'][$key];
                    $arsipData['tkt_perkemb'] = $request['tkt_perkemb'][$key];
                    $arsipData['no_box'] = $request['no_box'][$key];
                    
                    DetailDokumen::create($arsipData);
                }
            }

            return redirect()->route('data-arsip.index')->with('message', 'Data arsip berhasil ditambahkan.');


        } else {
            return redirect()->route('data-arsip.create')->with('message','Data arsip dengan No. SP2D '.$no_sp2d_dokumen.' sudah terdapat pada sistem');
        }

    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Dokumen  $data_arsip
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, Dokumen $data_arsip)
    {
        $data = $request->except(['_token','_method']);

        Dokumen::where('id', $data_arsip->id)->update($data);

        return redirect()->route('data-arsip.index')->with('message','Data arsip berhasil diperbaharui');
    }

    public function verification_document(Request $request) 
    {
        $id         = $request['id'];
        $status     = $request['status'];

        $dokumens   = Dokumen::find($id);
        if($dokumens) {
            $dokumens->status = $status;
            $dokumens->update();
            return redirect()->route('data-arsip.index')->with('message','Data arsip berhasil diverifikasi');
        }
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
	    // Excel::import(new DetailDokumenImport, public_path('/file_dokumen/'.$nama_file));
        Excel::import(new DokumenSp2dImport, public_path('/file_dokumen/'.$nama_file));
        Excel::import(new DokumenSpmImport, public_path('/file_dokumen/'.$nama_file));
        Excel::import(new DokumenSppImport, public_path('/file_dokumen/'.$nama_file));

        return redirect()->route('data-arsip.index')->with('message','Data arsip berhasil diimport');

	}

    // Get Arsip Budle
    public function getBerkasArsip($id) {
        $list = [
            [
                "dokumen_id" => 1,
                "kode_klasifikasi" => "UD.02.02",
                "uraian" => "Uraian 00000001",
                "tanggal_validasi" => "2023-02-02 00:00:00",
                "jumlah_satuan_item" => "",
                "keterangan" => "Keterangan 00000001",
                "no_spm" => "00000001",
                "no_sp2d" => "00000001/SPP/10503000/VI/2023",
                "nominal" => "1000000",
                "skpd" => "SKPD 00000001",
                "pejabat_penandatangan" => "",
                "unit_pengolah" => "",
                "kurun_waktu" => "2023",
                "jumlah_satuan_berkas" => "",
                "tkt_perkemb" => "",
                "no_box" => "",
                "file_dokumen" => "",
            ],
            [
                "dokumen_id" => 1,
                "kode_klasifikasi" => "UD.02.02",
                "uraian" => "Uraian 00000004",
                "tanggal_validasi" => "2023-02-02 00:00:00",
                "jumlah_satuan_item" => "",
                "keterangan" => "Keterangan 00000004",
                "no_spm" => "00000004",
                "no_sp2d" => "00000001/SPP/10503000/VI/2023",
                "nominal" => "1000000",
                "skpd" => "SKPD 00000004",
                "pejabat_penandatangan" => "",
                "unit_pengolah" => "",
                "kurun_waktu" => "2023",
                "jumlah_satuan_berkas" => "",
                "tkt_perkemb" => "",
                "no_box" => "",
                "file_dokumen" => "",
            ],
            [
                "dokumen_id" => 2,
                "kode_klasifikasi" => "UD.02.02",
                "uraian" => "Uraian 00000002",
                "tanggal_validasi" => "2023-02-02 00:00:00",
                "jumlah_satuan_item" => "",
                "keterangan" => "Keterangan 00000002",
                "no_spm" => "00000002",
                "no_sp2d" => "00000002/SPP/10503000/VI/2023",
                "nominal" => "1000000",
                "skpd" => "SKPD 00000002",
                "pejabat_penandatangan" => "",
                "unit_pengolah" => "",
                "kurun_waktu" => "2023",
                "jumlah_satuan_berkas" => "",
                "tkt_perkemb" => "",
                "no_box" => "",
                "file_dokumen" => "",
            ],
        ];


        $filtered = array_filter($list, function ($obj) use ($id) {
            return $obj['dokumen_id'] == $id;
        });

        return response()->json($filtered);
    }
}

<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Dokumen;
use App\Models\DetailDokumen;
use App\Models\AkunJenis;
use App\Imports\DokumenImport;
use App\Imports\DokumenSp2dImport;
use App\Imports\DokumenSpmImport;
use App\Imports\DokumenSppImport;
use App\Exports\DokumenExport;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;

class DokumenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): View
    {
        $akunJenisOptions       = AkunJenis::select('kode_akun', 'nama_akun')->get();

        $itemsPerPage           = request('items') ?? 10;

        $start_date_validate = $request->input('start_date_validate');
        $end_date_validate = $request->input('end_date_validate');

        $dokumen = Dokumen::with([
            'detailDokumen' => function ($query) {
                $query->orderBy('id', 'ASC');
            },
            'akunJenis' => function ($query) {
                $query->select('id', 'kode_akun', 'nama_akun');
            },
        ])
            ->filter(request(['search']))
            ->orderBy('tanggal_validasi', 'DESC')
            ->where('status', '=', 'Menunggu Verifikasi')
            ->when($start_date_validate, function ($query) use ($start_date_validate) {
                $start_date_validate = date('Y-m-d', strtotime($start_date_validate));
                $query->whereDate('tanggal_validasi', '>=', $start_date_validate);
            })
            ->when($end_date_validate, function ($query) use ($end_date_validate) {
                $end_date_validate = date('Y-m-d', strtotime($end_date_validate));
                $query->whereDate('tanggal_validasi', '<=', $end_date_validate);
            })
            ->sortable()
            ->paginate($itemsPerPage)
            ->onEachSide(0)
            ->withQueryString();

        if ($request->ajax()) {
            return view('pages/data-arsip/data', compact('dokumen'));
        }

        return view("pages/data-arsip/index", [
            "title"             => "Data Arsip",
            "monthsOptions"     => $this->getMonths(),
            "yearsOptions"      => $this->getYears(),
            "akunJenisOptions"  => $akunJenisOptions,
            "dokumen"           => $dokumen,
            "no_box_tmp"        => $this->generate_no_box(2023)
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

            if (!empty($request['nilai_count'])) {

                foreach ($nilai_count as $key => $value) {

                    $arsipData['dokumen_id'] = $dokumen_id;
                    $arsipData['kode_klasifikasi'] = $request['kode_klasifikasi'][$key];
                    $arsipData['uraian'] = $request['uraian'][$key];
                    $arsipData['tanggal_validasi'] = $request['tanggal_validasi'][$key];
                    $arsipData['jumlah_satuan_item'] = $request['jumlah_satuan_item'][$key];
                    $arsipData['keterangan'] = $request['keterangan'][$key];
                    $arsipData['no_spm'] = $request['no_spm'][$key];
                    $arsipData['no_sp2d'] = $request['no_sp2d'][$key];
                    $arsipData['no_surat'] = $request['no_surat'][$key];
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
            return redirect()->route('data-arsip.create')->with('message', 'Data arsip dengan No. SP2D ' . $no_sp2d_dokumen . ' sudah terdapat pada sistem');
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
        $url = '/data-arsip/' . $data_arsip->id;

        $data = $request->except(['_token', '_method', $url]);

        Dokumen::where('id', $data_arsip->id)->update($data);


        $dataDetail['keterangan'] = $request['keterangan'];
        $dataDetail['unit_pengolah'] = $request['unit_pengolah'];


        DetailDokumen::where('dokumen_id', $data_arsip->id)->update($dataDetail);

        return redirect()->route('data-arsip.index')->with('message', 'Data arsip berhasil diperbaharui');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Dokumen $data_arsip)
    {
        $dokumen = Dokumen::with([
            'detailDokumen' => function ($query) {
                $query->orderBy('id', 'ASC');
            },
            'akunJenis' => function ($query) {
                $query->select('id', 'kode_akun', 'nama_akun');
            },
        ])
            ->where('id', $data_arsip->id)
            ->first();

        return view("pages/data-arsip/show", [
            "title"             => "Detail Data Arsip",
            "dokumen"           => $dokumen
        ]);
    }

    public function verification_document(Request $request)
    {
        $id         = $request['id'];
        $status     = $request['status'];

        $dokumens   = Dokumen::find($id);
        if ($dokumens) {
            $dokumens->status = $status;
            $dokumens->update();
            return redirect()->route('data-arsip.index')->with('message', 'Data arsip berhasil diverifikasi');
        }
    }

    public function export_excel($ext)
    {
        return Excel::download(new DokumenExport, 'daftar-berkas.' . $ext);
    }

    public function import_excel(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);

        $file = $request->file('file');

        $nama_file = rand() . $file->getClientOriginalName();

        $file->move('file_dokumen', $nama_file);

        Excel::import(new DokumenImport, public_path('/file_dokumen/' . $nama_file));
        // Excel::import(new DetailDokumenImport, public_path('/file_dokumen/'.$nama_file));
        Excel::import(new DokumenSp2dImport, public_path('/file_dokumen/' . $nama_file));
        Excel::import(new DokumenSpmImport, public_path('/file_dokumen/' . $nama_file));
        Excel::import(new DokumenSppImport, public_path('/file_dokumen/' . $nama_file));



        return redirect()->route('data-arsip.index')->with('message', 'Data arsip berhasil diimport');
    }

    public function import_monitoring(Request $request)
    {

        $kode_wilayah = Auth::user()->kode_wilayah;

        $success = 0;

        $method     = $request['method_type'];
        $akun_jenis = $request['akun_jenis'];

        $query_main = "";
        $query_akun = "";
        $query_wilayah = "";

        // Constructing query for akun_jenis
        if ($akun_jenis > 0) {
            $query_akun .= " AND (";
            foreach ($akun_jenis as $key => $element) {
                if ($key === array_key_first($akun_jenis)) {
                    $query_akun .= "kode_akun_jenis = '" . $element . "'";
                } else {
                    $query_akun .= " OR kode_akun_jenis = '" . $element . "'";
                }
            }
            $query_akun .= ") ";
        }

        // Constructing main query based on method type
        if ($method === 'periode') {
            $tahun = $request['tahun'];
            $bulan = $request['bulan'];
            $hari = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
            $start = '1.' . $bulan . '.' . $tahun;
            $end = $hari . '.' . $bulan . '.' . $tahun;

            $query_main = "SELECT * FROM newsipkd.VW_MONITORING_SP2D_AKUN_JENIS@newsipkd WHERE tgl_sp2d >= to_date('" . $start . "', 'DD.MM.YYYY') and tgl_sp2d <= to_date('" . $end . "', 'DD.MM.YYYY')";
        } else {
            $tanggal = $request['tanggal'];
            $date = Carbon::createFromFormat('Y-m-d', $tanggal)->format('j.n.Y');

            $query_main = "SELECT * FROM newsipkd.VW_MONITORING_SP2D_AKUN_JENIS@newsipkd WHERE TRUNC(tgl_sp2d) = to_date('" . $date . "', 'DD.MM.YYYY')";
        }

        // Adding KODE_WILAYAH condition if kode_wilayah is not null or empty
        if (!empty($kode_wilayah)) {
            $query_wilayah = " AND KODE_WILAYAH = '" . $kode_wilayah . "'";
        }

        // Constructing the final query
        $final_query = $query_main . $query_akun . $query_wilayah;

        $sp2d_monitoring = DB::connection('oraclelink')->select($final_query);

        if ($sp2d_monitoring) {
            foreach ($sp2d_monitoring as $value) {
                $uraian = $value->uraian;
                $no_spm = $value->no_spm;
                $no_sp2d = $value->no_sp2d_full;
                $no_spp = $value->no_spp;
                $nominal = $value->nilai_sp2d;
                $kurun_waktu = $value->tahun;
                $nwp = $value->nama_wp;
                $skpd = $value->nama_opd;
                $kode_akun_jenis = $value->kode_akun_jenis;
                $akun_jenis_id = AkunJenis::select('id')->where('kode_akun', '=', $kode_akun_jenis)->first()->id;

                $dokumens = Dokumen::where('no_sp2d', $no_sp2d)->first();
                if ($dokumens === null) {
                    $data['no_sp2d'] =  $no_sp2d;
                    $data['akun_jenis_id'] =  $akun_jenis_id;
                    $data['kode_klasifikasi'] = 'UD.02.02';
                    $data['uraian'] =  $uraian;
                    $data['tanggal_validasi'] =  $value->tgl_sp2d;
                    $data['jumlah_satuan_item'] =  1;
                    $data['keterangan'] =  '';
                    $data['nominal'] =  $nominal;
                    $data['skpd'] =  $skpd;
                    $data['nwp'] =  $nwp;
                    $data['kurun_waktu'] =  $kurun_waktu;
                    $data['jumlah_satuan_berkas'] =  1;
                    $data['tkt_perkemb'] = 'Tembusan';
                    $data['status'] = 'Menunggu Verifikasi';
                    $dokumen_id = Dokumen::create($data)->id;

                    $dataSpp['dokumen_id'] = $dokumen_id;
                    $dataSpp['kode_klasifikasi'] = 'UD.02.02';
                    $dataSpp['uraian'] = $value->uraian;
                    $dataSpp['tanggal_surat'] = $value->tgl_spp;
                    $dataSpp['pejabat_penandatangan'] = 'Bendahara/PPTK';
                    $dataSpp['unit_pengolah'] = $skpd;
                    $dataSpp['jumlah_satuan'] = 1;
                    $dataSpp['no_surat'] = $value->no_spp;
                    $dataSpp['kurun_waktu'] = $value->tahun;
                    $dataSpp['tkt_perk'] = 'Asli';
                    DetailDokumen::create($dataSpp);

                    $dataSpm['dokumen_id'] = $dokumen_id;
                    $dataSpm['kode_klasifikasi'] = 'UD.02.02';
                    $dataSpm['uraian'] = $value->uraian;
                    $dataSpm['tanggal_surat'] = $value->tgl_spm;
                    $dataSpm['pejabat_penandatangan'] = 'PA/KPA';
                    $dataSpm['unit_pengolah'] = $skpd;
                    $dataSpm['jumlah_satuan'] = 1;
                    $dataSpm['no_surat'] = $value->no_spm;
                    $dataSpm['kurun_waktu'] = $value->tahun;
                    $dataSpm['tkt_perk'] = 'Asli';
                    DetailDokumen::create($dataSpm);

                    $dataSptjm['dokumen_id'] = $dokumen_id;
                    $dataSptjm['kode_klasifikasi'] = 'UD.02.02';
                    $dataSptjm['uraian'] = '';
                    $dataSptjm['tanggal_surat'] = NULL;
                    $dataSptjm['pejabat_penandatangan'] = 'PA/KPA';
                    $dataSptjm['unit_pengolah'] = $skpd;
                    $dataSptjm['jumlah_satuan'] = 1;
                    $dataSptjm['no_surat'] = '';
                    $dataSptjm['kurun_waktu'] = $value->tahun;
                    $dataSptjm['tkt_perk'] = 'Asli';
                    DetailDokumen::create($dataSptjm);

                    $success = $success + 1;
                }
            }

            $dokumenMonitoring = Dokumen::find(1);

            // if($dokumenMonitoring) {
            //     activity()
            //     ->performedOn($dokumenMonitoring)
            //     ->event('created')
            //     ->log('telah melakukan <strong>tarik data monitoring</strong> pada sistem');
            // }
            return redirect()->route('data-arsip.index')->with('message', number_format($success, 0, ",", ".") . ' Data arsip berhasil di import.');
        } else {
            return redirect()->route('data-arsip.index')->with('error', 'Tidak ada data yang dapat ditarik pada periode tersebut');
        }
    }

    // Get Arsip Budle
    public function getBerkasArsip($id)
    {
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
                "no_surat" => "00000001/SPP/10503000/VI/2023",
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
                "no_surat" => "00000001/SPP/10503000/VI/2023",
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
                "no_surat" => "00000002/SPP/10503000/VI/2023",
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

    public function getMonths()
    {
        $months = [];
        for ($i = 1; $i <= 12; $i++) {
            //indonesian month names using carbon
            $months[] = [
                'id'   => $i,
                'name' => Carbon::createFromFormat('!m', $i)->locale('id_ID')->isoFormat('MMMM'),
            ];
        }

        return $months;
    }

    public function getYears()
    {
        $currentYear = Carbon::now()->format('Y');
        $years       = [];
        for ($i = 0; $i <= 10; $i++) {
            //indonesian month names using carbon
            $years[] = [
                'id'   => $currentYear - $i,
                'name' => $currentYear - $i,
            ];
        }

        return $years;
    }

    public function generate_no_box($year)
    {
        $counter = Dokumen::whereNotNull('no_box')->where('kurun_waktu', '=', $year)->distinct()->count('no_box');
        $short_year = substr($year, 2);
        $current_number = sprintf("%05d", $counter + 1);
        $no_box = $current_number . "/" . $year . "/P." . $short_year . "/SBPKDJP";
        return $no_box;
    }

    public function get_no_box($year)
    {
        return response()->json($this->generate_no_box($year));
    }

    public function update_no_box(Request $request)
    {
        $ids = $request['id'];
        $id = explode(",", $ids[0]);
        $kurun_waktu = $request['kurun_waktu'];
        $no_box = $this->generate_no_box($kurun_waktu);
        $data['no_box'] = $no_box;

        foreach ($id as $item) {

            Dokumen::where('id', $item)->update($data);
            DetailDokumen::where('dokumen_id', $item)->update($data);
        }

        return redirect()->route('data-arsip.index')->with('message', 'No. Box telah berhasil diperbaharui');
    }
}

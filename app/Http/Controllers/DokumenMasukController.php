<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dokumen;
use App\Models\DetailDokumen;
use Milon\Barcode\DNS1D;
use Milon\Barcode\DNS2D;

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
            ->sortable()
            ->paginate($itemsPerPage)
            ->onEachSide(0)
            ->withQueryString();

        return view("pages/dokumen-masuk/index", [
            "title" => "Data Arsip",
            "dokumen" => $dokumen
        ]);
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
            'detailDokumen' => function ($query) {
                $query->orderBy('id', 'ASC');
            },
            'akunJenis' => function ($query) {
                $query->select('id', 'kode_akun', 'nama_akun');
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
        $no_box_convert = str_replace("_", "/", $no_box);
        $berkas_dokumen = Dokumen::with([
            'detailDokumen' => function ($query) {
                $query->orderBy('id', 'ASC');
            },
            'akunJenis' => function ($query) {
                $query->select('id', 'kode_akun', 'nama_akun');
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

    public function generate_barcode(Request $request)
    {
        // $barcodeType = $request->input('type'); // Get the barcode type (e.g., 'qrcode', 'code128', 'code39')
        // $data = $request->input('data'); // Get the data for the barcode

        // if ($barcodeType === 'qrcode') {
        //     // Generate a QR code
        //     $barcode = new DNS2D();
        //     $storagePath = public_path('barcodes'); // Set the public path
        //     $barcode->setStorPath($storagePath);
        //     $barcode->getBarcodePNG($data, $barcodeType);
        // } else {
        //     // Generate other barcode types like Code128 or Code39
        //     $barcode = new DNS1D();
        //     $storagePath = public_path('barcodes'); // Set the public path
        //     $barcode->setStorPath($storagePath);
        //     $barcode->getBarcodePNG($data, $barcodeType);
        // }

        // $barcodeImagePath = public_path('barcodes/' . $barcodeType . '.png');

        // if (file_exists($barcodeImagePath)) {
        //     return response()->file($barcodeImagePath);
        // } else {
        //     return response('Barcode not found', 404);
        // }
        return \DNS2D::getBarcodePNGPath('AYAM', 'QRCODE');
    }
}

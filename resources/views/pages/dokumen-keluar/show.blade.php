@php
    $html_tag_data = ['override' => '{ "attributes" : { "placement" : "vertical", "layout":"fluid" }, "showSettings" : false }'];
    $title = 'Detail Data';
    $description = 'Halaman Detail Data Dokumen Keluar';
    $breadcrumbs = [route('dashboard.index') => 'Beranda', route('dokumen-keluar.index') => 'Data Dokumen Keluar',  route('dokumen-keluar.index') . '/' . $dokumen_keluar->id => 'Detail Data'];
@endphp
@extends('layout', ['html_tag_data' => $html_tag_data, 'title' => $title, 'description' => $description])

@section('css')
@endsection

@section('js_vendor')
    <script src="{{ asset('js/cs/scrollspy.js') }}"></script>
@endsection

@section('js_page')
@endsection


@section('content')
    <div class="container">
        <!-- Title and Top Buttons Start -->
        <div class="page-title-container">
            <div class="row">
                <!-- Title Start -->
                <div class="col-12 col-md-7">
                    <h1 class="mb-0 pb-0 display-4" id="title">{{ $title }}</h1>
                    @include('_layout.breadcrumb', ['breadcrumbs' => $breadcrumbs])
                </div>
                <!-- Title End -->
            </div>
        </div>
        <!-- Title and Top Buttons End -->

        <!-- Content Start -->
        <section class="scroll-section">

            <div class="card mb-5">
                <div class="card-body">
                    <h5 class="card-title text-primary">{{ $title }}</h5>
                    <p class="card-text mb-5">
                        {{ $description }}
                    </p>


                    <div class="mb-n2" id="accordionCardsExample">
                        <hr>
                        <p class="mb-1"><strong>Nama Peminjam</strong></p>
                        <p class="mb-4">
                            {{ $dokumen_keluar->nama_peminjam ? $dokumen_keluar->nama_peminjam : '-' }}
                        </p>

                        <p class="mb-1"><strong>Hari/ Tanggal</strong></p>
                        <p class="mb-4">
                            {{ $dokumen_keluar->tanggal_peminjaman ? $dokumen_keluar->tanggal_peminjaman : '-' }}
                        </p>

                        <p class="mb-1"><strong>Instansi</strong></p>
                        <p class="mb-4">
                            {{ $dokumen_keluar->instansi ? $dokumen_keluar->instansi : '-' }}
                        </p>

                        <p class="mb-1"><strong>Tujuan</strong></p>
                        <p class="mb-4">
                            {{ $dokumen_keluar->tujuan ? $dokumen_keluar->tujuan : '-' }}
                        </p>

                        <hr>

                        <p class="mb-1"><strong>Kode Klasifikasi</strong></p>
                        <p class="mb-4">
                            {{ $dokumen_keluar->dokumen->kode_klasifikasi ? $dokumen_keluar->dokumen->kode_klasifikasi : '-' }}
                        </p>

                        <p class="mb-1"><strong>Uraian</strong></p>
                        <p class="mb-4">
                            {{ $dokumen_keluar->dokumen->uraian ? $dokumen_keluar->dokumen->uraian : '-' }}
                        </p>

                        <p class="mb-1"><strong>Tanggal Validasi</strong></p>
                        <p class="mb-4">
                            {{ $dokumen_keluar->dokumen->tanggal_validasi ? $dokumen_keluar->dokumen->tanggal_validasi : '-' }}
                        </p>

                        <p class="mb-1"><strong>Jumlah Satuan Item</strong></p>
                        <p class="mb-4">
                            {{ $dokumen_keluar->dokumen->jumlah_satuan_item ? $dokumen_keluar->dokumen->jumlah_satuan_item : '-' }}
                        </p>

                        <p class="mb-1"><strong>Keterangan</strong></p>
                        <p class="mb-4">
                            {{ $dokumen_keluar->dokumen->keterangan ? $dokumen_keluar->dokumen->keterangan : '-' }}
                        </p>

                        <p class="mb-1"><strong>No. SP2D</strong></p>
                        <p class="mb-4">
                            {{ $dokumen_keluar->dokumen->no_sp2d ? $dokumen_keluar->dokumen->no_sp2d : '-' }}
                        </p>

                        <p class="mb-1"><strong>Nominal</strong></p>
                        <p class="mb-4">
                            Rp. {{ number_format($dokumen_keluar->dokumen->nominal, 0, ',', '.') }},-
                        </p>

                        <p class="mb-1"><strong>Kode Akun Jenis</strong></p>
                        <p class="mb-4">
                            {{ $dokumen_keluar->dokumen->akunJenis ? $dokumen_keluar->dokumen->akunJenis->kode_akun : '-' }}
                        </p>

                        <p class="mb-1"><strong>Nama Akun Jenis</strong></p>
                        <p class="mb-4">
                            {{ $dokumen_keluar->dokumen->akunJenis ? $dokumen_keluar->dokumen->akunJenis->nama_akun : '-' }}
                        </p>

                        <p class="mb-1"><strong>SKPD/Unit SKPD</strong></p>
                        <p class="mb-4">
                            {{ $dokumen_keluar->dokumen->skpd ? $dokumen_keluar->dokumen->skpd : '-' }}
                        </p>

                        <p class="mb-1"><strong>NWP</strong></p>
                        <p class="mb-4">
                            {{ $dokumen_keluar->dokumen->nwp ? $dokumen_keluar->dokumen->nwp : '-' }}
                        </p>

                        <p class="mb-1"><strong>Pejabat Penandatangan</strong></p>
                        <p class="mb-4">
                            {{ $dokumen_keluar->dokumen->pejabat_penandatangan ? $dokumen_keluar->dokumen->pejabat_penandatangan : '-' }}
                        </p>

                        <p class="mb-1"><strong>Unit Pengolah</strong></p>
                        <p class="mb-4">
                            {{ $dokumen_keluar->dokumen->unit_pengolah ? $dokumen_keluar->dokumen->unit_pengolah : '-' }}
                        </p>

                        <p class="mb-1"><strong>Kurun Waktu</strong></p>
                        <p class="mb-4">
                            {{ $dokumen_keluar->dokumen->kurun_waktu ? $dokumen_keluar->dokumen->kurun_waktu : '-' }}
                        </p>

                        <p class="mb-1"><strong>Jumlah Satuan Berkas</strong></p>
                        <p class="mb-4">
                            {{ $dokumen_keluar->dokumen->jumlah_satuan_berkas ? $dokumen_keluar->dokumen->jumlah_satuan_berkas : '-' }}
                            Berkas
                        </p>

                        <p class="mb-1"><strong>Tingkat Perkembangan</strong></p>
                        <p class="mb-4">
                            {{ $dokumen_keluar->dokumen->tkt_perkemb ? $dokumen_keluar->dokumen->tkt_perkemb : '-' }}
                        </p>

                        <p class="mb-1"><strong>No. Box</strong></p>
                        <p class="mb-4">
                            {{ $dokumen_keluar->dokumen->no_box ? $dokumen_keluar->dokumen->no_box : '-' }}
                        </p>

                        <p class="mb-1"><strong>Status</strong></p>
                        <p class="mb-4">
                            {{ $dokumen_keluar->dokumen->status ? $dokumen_keluar->dokumen->status : '-' }}
                        </p>
                    </div>

                </div>
            </div>
        </section>
    </div>
@endsection

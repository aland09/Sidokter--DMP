@php
    $html_tag_data = ['override' => '{ "attributes" : { "placement" : "vertical", "layout":"fluid" }, "showSettings" : false }'];
    $title = 'Detail Data';
    $description = 'Halaman Detail Data Pemberkasan';
    $breadcrumbs = ['/' => 'Beranda', '/data-arsip' => 'Data Pemberkasan', '/data-arsip/' . $dokumen->id => 'Detail Data'];
@endphp
@extends('layout', ['html_tag_data' => $html_tag_data, 'title' => $title, 'description' => $description])

@section('css')
@endsection

@section('js_vendor')
    <script src="/js/cs/scrollspy.js"></script>
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
                        <div class="card border border-primary d-flex mb-2">
                            <div class="d-flex flex-grow-1" role="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseOneCards" aria-expanded="true" aria-controls="collapseOneCards">
                                <div class="card-body py-4">
                                    <div class="btn btn-link list-item-heading p-0 text-primary">
                                        {{ $dokumen->no_sp2d ? $dokumen->no_sp2d : '-' }}</div>
                                </div>
                            </div>
                            <div id="collapseOneCards" class="collapse" data-bs-parent="#accordionCardsExample">
                                <div class="card-body accordion-content pt-0">
                                    <p class="mb-1"><strong>Kode Klasifikasi</strong></p>
                                    <p class="mb-4">
                                        {{ $dokumen->kode_klasifikasi ? $dokumen->kode_klasifikasi : '-' }}
                                    </p>

                                    <p class="mb-1"><strong>Uraian</strong></p>
                                    <p class="mb-4">
                                        {{ $dokumen->uraian ? $dokumen->uraian : '-' }}
                                    </p>

                                    <p class="mb-1"><strong>Tanggal Validasi</strong></p>
                                    <p class="mb-4">
                                        {{ $dokumen->tanggal_validasi ? $dokumen->tanggal_validasi : '-' }}
                                    </p>

                                    <p class="mb-1"><strong>Jumlah Satuan Item</strong></p>
                                    <p class="mb-4">
                                        {{ $dokumen->jumlah_satuan_item ? $dokumen->jumlah_satuan_item : '-' }}
                                    </p>

                                    <p class="mb-1"><strong>Keterangan</strong></p>
                                    <p class="mb-4">
                                        {{ $dokumen->keterangan ? $dokumen->keterangan : '-' }}
                                    </p>

                                    <p class="mb-1"><strong>No. SP2D</strong></p>
                                    <p class="mb-4">
                                        {{ $dokumen->no_sp2d ? $dokumen->no_sp2d : '-' }}
                                    </p>

                                    <p class="mb-1"><strong>Nominal</strong></p>
                                    <p class="mb-4">
                                        Rp. {{ number_format($dokumen->nominal, 0, ',', '.') }},-
                                    </p>

                                    <p class="mb-1"><strong>Kode Akun Jenis</strong></p>
                                    <p class="mb-4">
                                        {{ $dokumen->akunJenis ? $dokumen->akunJenis->kode_akun : '-' }}
                                    </p>

                                    <p class="mb-1"><strong>Nama Akun Jenis</strong></p>
                                    <p class="mb-4">
                                        {{ $dokumen->akunJenis ? $dokumen->akunJenis->nama_akun : '-' }}
                                    </p>

                                    <p class="mb-1"><strong>SKPD/Unit SKPD</strong></p>
                                    <p class="mb-4">
                                        {{ $dokumen->skpd ? $dokumen->skpd : '-' }}
                                    </p>

                                    <p class="mb-1"><strong>NWP</strong></p>
                                    <p class="mb-4">
                                        {{ $dokumen->nwp ? $dokumen->nwp : '-' }}
                                    </p>

                                    <p class="mb-1"><strong>Pejabat Penandatangan</strong></p>
                                    <p class="mb-4">
                                        {{ $dokumen->pejabat_penandatangan ? $dokumen->pejabat_penandatangan : '-' }}
                                    </p>

                                    <p class="mb-1"><strong>Unit Pengolah</strong></p>
                                    <p class="mb-4">
                                        {{ $dokumen->unit_pengolah ? $dokumen->unit_pengolah : '-' }}
                                    </p>

                                    <p class="mb-1"><strong>Kurun Waktu</strong></p>
                                    <p class="mb-4">
                                        {{ $dokumen->kurun_waktu ? $dokumen->kurun_waktu : '-' }}
                                    </p>

                                    <p class="mb-1"><strong>Jumlah Satuan Berkas</strong></p>
                                    <p class="mb-4">
                                        {{ $dokumen->jumlah_satuan_berkas ? $dokumen->jumlah_satuan_berkas : '-' }} Berkas
                                    </p>

                                    <p class="mb-1"><strong>Tingkat Perkembangan</strong></p>
                                    <p class="mb-4">
                                        {{ $dokumen->tkt_perkemb ? $dokumen->tkt_perkemb : '-' }}
                                    </p>

                                    <p class="mb-1"><strong>No. Box</strong></p>
                                    <p class="mb-4">
                                        {{ $dokumen->no_box ? $dokumen->no_box : '-' }}
                                    </p>

                                    <p class="mb-1"><strong>Status</strong></p>
                                    <p class="mb-4">
                                        {{ $dokumen->status ? $dokumen->status : '-' }}
                                    </p>

                                </div>
                            </div>


                        </div>

                        @foreach ($dokumen->detailDokumen ?? [] as $subitem)
                            <div class="card border d-flex mb-2">
                                <div class="d-flex flex-grow-1" role="button" data-bs-toggle="collapse"
                                    data-bs-target="#card_{{ $subitem->id }}" aria-expanded="true"
                                    aria-controls="card_{{ $subitem->id }}">
                                    <div class="card-body py-4">
                                        <div class="btn btn-link list-item-heading p-0 text-primary">
                                            {{ $subitem->no_surat ? $subitem->no_surat : 'Tidak Ada No. Surat' }}</div>
                                    </div>
                                </div>
                                <div id="card_{{ $subitem->id }}" class="collapse"
                                    data-bs-parent="#accordionCardsExample">
                                    <div class="card-body accordion-content pt-0">
                                        <p class="mb-1"><strong>Kode Klasifikasi</strong></p>
                                        <p class="mb-4">
                                            {{ $subitem->kode_klasifikasi ? $subitem->kode_klasifikasi : '-' }}
                                        </p>

                                        <p class="mb-1"><strong>Uraian</strong></p>
                                        <p class="mb-4">
                                            {{ $subitem->uraian ? $subitem->uraian : '-' }}
                                        </p>

                                        <p class="mb-1"><strong>Tanggal Surat</strong></p>
                                        <p class="mb-4">
                                            {{ $subitem->tanggal_surat ? $subitem->tanggal_surat : '-' }}
                                        </p>

                                        <p class="mb-1"><strong>Jumlah Satuan</strong></p>
                                        <p class="mb-4">
                                            {{ $subitem->jumlah_satuan ? $subitem->jumlah_satuan : '-' }}
                                        </p>

                                        <p class="mb-1"><strong>Keterangan</strong></p>
                                        <p class="mb-4">
                                            {{ $subitem->keterangan ? $subitem->keterangan : '-' }}
                                        </p>

                                        <p class="mb-1"><strong>Jenis Naskah Dinas</strong></p>
                                        <p class="mb-4">
                                            {{ $subitem->jenis_naskah_dinas ? $subitem->jenis_naskah_dinas : '-' }}
                                        </p>

                                        <p class="mb-1"><strong>No. Surat</strong></p>
                                        <p class="mb-4">
                                            {{ $subitem->no_surat ? $subitem->no_surat : '-' }}
                                        </p>

                                        <p class="mb-1"><strong>Pejabat Penandatangan</strong></p>
                                        <p class="mb-4">
                                            {{ $subitem->pejabat_penandatangan ? $subitem->pejabat_penandatangan : '-' }}
                                        </p>

                                        <p class="mb-1"><strong>Unit Pengolah</strong></p>
                                        <p class="mb-4">
                                            {{ $subitem->unit_pengolah ? $subitem->unit_pengolah : '-' }}
                                        </p>

                                        <p class="mb-1"><strong>Kurun Waktu</strong></p>
                                        <p class="mb-4">
                                            {{ $subitem->kurun_waktu ? $subitem->kurun_waktu : '-' }}
                                        </p>

                                        <p class="mb-1"><strong>No. Box</strong></p>
                                        <p class="mb-4">
                                            {{ $subitem->no_box ? $subitem->no_box : '-' }}
                                        </p>

                                        <p class="mb-1"><strong>Tingkat
                                            Perkembangan</strong></p>
                                        <p class="mb-4">
                                            {{ $subitem->tkt_perk ? $subitem->tkt_perk : '-' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                </div>
            </div>
        </section>
    </div>
@endsection

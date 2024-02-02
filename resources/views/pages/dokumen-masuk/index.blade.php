@php
    $html_tag_data = ['override' => '{ "attributes" : { "placement" : "vertical", "layout":"fluid" }, "showSettings" : false }'];
    $title = 'Dokumen Masuk';
    $description = 'Halaman Dokumen Masuk';
    $breadcrumbs = [route('dashboard.index') => 'Beranda', route('dokumen-masuk.index') => 'Dokumen Masuk'];
@endphp
@extends('layout', ['html_tag_data' => $html_tag_data, 'title' => $title, 'description' => $description])
@section('css')
    <link rel="stylesheet" href="{{ asset('css/vendor/datatables.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/vendor/bootstrap-datepicker3.standalone.min.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        .text-sortable a,
        .text-sortable a:hover {
            color: #afafaf !important;
        }

        .text-sticky a,
        .text-sticky a:hover {
            color: #ffffff !important;
        }
    </style>
@endsection

@section('js_vendor')
    <script src="{{ asset('js/vendor/bootstrap-submenu.js') }}"></script>
    <script src="{{ asset('js/vendor/datatables.min.js') }}"></script>
    <script src="{{ asset('js/vendor/mousetrap.min.js') }}"></script>
    <script src="{{ asset('js/cs/scrollspy.js') }}"></script>
    <script src="{{ asset('js/vendor/jquery.validate/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('js/vendor/datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('js/vendor/datepicker/locales/bootstrap-datepicker.es.min.js') }}"></script>
@endsection

@section('js_page')
    <script src="{{ asset('js/base/pagination.js') }}"></script>
    <script src="{{ asset('js/forms/validation.js') }}"></script>
    <script src="{{ asset('js/forms/controls.datepicker.js') }}"></script>
    <script src="{{ asset('js/base/sticky-table.js') }}"></script>
    <script>
        $(document).ready(function() {
            $(".sticky-header").floatThead({
                scrollingTop: -20
            });

            $(window).scroll(function() {
                if ($(this).scrollTop() > 50) {
                    $('#thead-header-sticky tr th').removeClass('text-sortable');
                    $('#thead-header-sticky tr th').addClass('bg-primary');
                    $('#thead-header-sticky tr th').addClass('text-sticky');

                } else {
                    $('#thead-header-sticky tr th').removeClass('bg-primary');
                    $('#thead-header-sticky tr th').removeClass('text-sticky');
                    $('#thead-header-sticky tr th').addClass('text-sortable');
                }
            });
        });
    </script>
@endsection

@section('content')

    @if (session()->has('message'))
        <div class="position-fixed top-0 end-0 p-3" style="z-index: 5">
            <div class="toast bg-success fade show" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header py-2">
                    <strong class="me-auto text-white">Informasi</strong>
                    <button type="button" class="btn-close text-white" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body text-white"> {{ session()->get('message') }}</div>
            </div>
        </div>
    @endif

    <div class="container">
        <div class="row">
            <div class="col">
                <!-- Title and Top Buttons Start -->
                <div class="page-title-container">
                    <div class="row">
                        <!-- Title Start -->
                        <div class="col-12 col-md-12">
                            <h1 class="mb-0 pb-0 display-4" id="title">{{ $title }}</h1>
                            @include('_layout.breadcrumb', ['breadcrumbs' => $breadcrumbs])
                        </div>
                        <!-- Title End -->
                    </div>
                </div>
                <!-- Title and Top Buttons End -->
                <!-- Content Start -->
                <div class="data-table-rows slim">
                    <!-- Controls Start -->
                    <div class="row">
                        <!-- Search Start -->
                        <div class="col-sm-12 col-md-6 col-lg-6 col-xxl-4 mb-3">
                            <form name="seach-form" id="search-form" action="{{ route('dokumen-masuk.index') }}">
                                <div
                                    class="d-inline-block float-md-start me-1 mb-1 search-input-container w-100 shadow bg-foreground">
                                    <input class="form-control datatable-search" name="search" placeholder="Pencarian..."
                                        value="{{ request('search') }}" />
                                    <span class="search-magnifier-icon" onClick="document.forms['search-form'].submit();">
                                        <i data-acorn-icon="search"></i>
                                    </span>
                                </div>
                            </form>
                        </div>
                        <!-- Search End -->
                        <div class="col-sm-12 col-md-6 col-lg-6 col-xxl-8 text-end mb-3">
                            <div class="d-inline-block">
                                <!-- Print Button Start -->
                                <button class="btn btn-icon btn-icon-only btn-foreground-alternate shadow datatable-print"
                                    data-bs-delay="0" data-datatable="#datatableRowsServerSide" data-bs-toggle="tooltip"
                                    data-bs-placement="top" title="Print" type="button">
                                    <i data-acorn-icon="print"></i>
                                </button>
                                <!-- Print Button End -->
                                <!-- Export Dropdown Start -->
                                <div class="d-inline-block datatable-export" data-datatable="#datatableRowsServerSide">
                                    <button class="btn p-0" data-bs-toggle="dropdown" type="button" data-bs-offset="0,3">
                                        <span class="btn btn-icon btn-icon-only btn-foreground-alternate shadow dropdown"
                                            data-bs-delay="0" data-bs-placement="top" data-bs-toggle="tooltip"
                                            title="Export">
                                            <i data-acorn-icon="download"></i>
                                        </span>
                                    </button>
                                    <div class="dropdown-menu shadow dropdown-menu-end">
                                        <button class="dropdown-item export-copy" type="button">Copy</button>
                                        <button class="dropdown-item export-excel" type="button">Excel</button>
                                        <button class="dropdown-item export-cvs" type="button">Cvs</button>
                                    </div>
                                </div>
                                <!-- Export Dropdown End -->
                                <!-- Length Start -->
                                <div class="dropdown-as-select d-inline-block datatable-length"
                                    data-datatable="#datatableRowsServerSide" data-childSelector="span">
                                    <button class="btn p-0 shadow" type="button" data-bs-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false" data-bs-offset="0,3">
                                        <span class="btn btn-foreground-alternate dropdown-toggle" data-bs-toggle="tooltip"
                                            data-bs-placement="top" data-bs-delay="0" title="Jumlah Data Per Halaman">
                                            10 Data
                                        </span>
                                    </button>
                                    <div class="dropdown-menu shadow dropdown-menu-end">
                                        <a class="dropdown-item paginate-item" data-items="5" href="#">5 Data</a>
                                        <a class="dropdown-item paginate-item active" data-items="10" href="#">10
                                            Data</a>
                                        <a class="dropdown-item paginate-item" data-items="20" href="#">20 Data</a>
                                    </div>
                                </div>
                                <!-- Length End -->
                            </div>
                        </div>
                    </div>
                    <!-- Controls End -->
                    <!-- Table Start -->
                    @if ($dokumen->count() > 0)
                        <div class="data-table-responsive-wrapper overflow-auto">
                            <table class="data-table hover dataTable no-footer sticky-header">
                                <thead id="thead-header-sticky">
                                    <tr>
                                        <th class="text-muted text-small text-uppercase text-sortable rounded-start">
                                            @sortablelink('id', 'No.')</th>
                                        <th class="text-muted text-small text-uppercase text-sortable">
                                            @sortablelink('kode_klasifikasi', 'Kode Klasifikasi')</th>
                                        <th class="text-muted text-small text-uppercase text-sortable">
                                            Uraian</th>
                                        <th class="text-muted text-small text-uppercase text-sortable">
                                            @sortablelink('tanggal_validasi', 'Tanggal Validasi')</th>
                                        <th class="text-muted text-small text-uppercase text-sortable">
                                            @sortablelink('jumlah_satuan_item', 'Jumlah Satuan Item')</th>
                                        <th class="text-muted text-small text-uppercase text-sortable">
                                            @sortablelink('keterangan', 'Keterangan')</th>
                                        <th style="width: 300px !important"
                                            class="text-muted text-small text-uppercase text-sortable">
                                            @sortablelink('no_sp2d', 'No. SP2D')</th>
                                        <th class="text-muted text-small text-uppercase text-sortable">@sortablelink('nominal', 'Nominal')
                                        </th>
                                        <th class="text-muted text-small text-uppercase text-sortable">@sortablelink('akun_jenis_id', 'Kode Akun Jenis')
                                        </th>
                                        <th class="text-muted text-small text-uppercase text-sortable">@sortablelink('akun_jenis_id', 'Nama Akun Jenis')
                                        </th>
                                        <th class="text-muted text-small text-uppercase text-sortable">@sortablelink('skpd', 'SKPD/Unit SKPD')
                                        </th>
                                        <th class="text-muted text-small text-uppercase text-sortable">@sortablelink('nwp', 'NWP')
                                        </th>
                                        <th class="text-muted text-small text-uppercase text-sortable">@sortablelink('pejabat_penandatangan', 'Pejabat Penandatangan')
                                        </th>
                                        <th class="text-muted text-small text-uppercase text-sortable">@sortablelink('unit_pengolah', 'Unit Pengolah')
                                        </th>
                                        <th class="text-muted text-small text-uppercase text-sortable">@sortablelink('kurun_waktu', 'Kurun Waktu')
                                        </th>
                                        <th class="text-muted text-small text-uppercase text-sortable">@sortablelink('jumlah_satuan_berkas', 'Jumlah Satuan Berkas')
                                        </th>
                                        <th class="text-muted text-small text-uppercase text-sortable">@sortablelink('tkt_perkemb', 'Tingkat Perkembangan')
                                        </th>
                                        <th class="text-muted text-small text-uppercase text-sortable">@sortablelink('no_box', 'No. Box')
                                        </th>
                                        <th class="text-muted text-small text-uppercase text-sortable">@sortablelink('status', 'Status')
                                        </th>
                                        <th width="10%" class="empty rounded-end">&nbsp;</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dokumen ?? [] as $item)
                                        <tr>
                                            <td style="height: 42px !important" class="cursor-pointer py-2"
                                                data-bs-toggle="collapse" data-bs-target="#collapse{{ $item->id }}">
                                                {{ $loop->index + 1 }}.
                                            </td>
                                            <td style="height: 42px !important" class="cursor-pointer py-2"
                                                data-bs-toggle="collapse" data-bs-target="#collapse{{ $item->id }}">
                                                {{ $item->kode_klasifikasi ? $item->kode_klasifikasi : '-' }}
                                            </td>
                                            <td style="height: 42px !important" class="cursor-pointer py-2"
                                                data-bs-toggle="collapse" data-bs-target="#collapse{{ $item->id }}">
                                                {{ $item->uraian ? $item->uraian : '-' }}

                                            </td>
                                            <td style="height: 42px !important" class="cursor-pointer py-2"
                                                data-bs-toggle="collapse" data-bs-target="#collapse{{ $item->id }}">
                                                {{ $item->tanggal_validasi ? $item->tanggal_validasi : '-' }}
                                            </td>
                                            <td style="height: 42px !important" class="cursor-pointer py-2"
                                                data-bs-toggle="collapse" data-bs-target="#collapse{{ $item->id }}">
                                                {{ $item->jumlah_satuan_item ? $item->jumlah_satuan_item : '-' }}
                                            </td>
                                            <td style="height: 42px !important" class="cursor-pointer py-2"
                                                data-bs-toggle="collapse" data-bs-target="#collapse{{ $item->id }}">
                                                {{ $item->keterangan ? $item->keterangan : '-' }}
                                            </td>
                                            <td style="height: 42px !important;" class="cursor-pointer py-2"
                                                data-bs-toggle="collapse" data-bs-target="#collapse{{ $item->id }}">
                                                {{ $item->no_sp2d ? $item->no_sp2d : '-' }}
                                            </td>
                                            <td style="height: 42px !important" class="cursor-pointer py-2"
                                                data-bs-toggle="collapse" data-bs-target="#collapse{{ $item->id }}">
                                                Rp.<span
                                                    class="text-white">__</span>{{ number_format($item->nominal, 0, ',', '.') }},-
                                            </td>
                                            <td style="height: 42px !important;" class="cursor-pointer py-2"
                                                data-bs-toggle="collapse" data-bs-target="#collapse{{ $item->id }}">
                                                {{ $item->akunJenis ? $item->akunJenis->kode_akun : '-' }}
                                            </td>
                                            <td style="height: 42px !important;" class="cursor-pointer py-2"
                                                data-bs-toggle="collapse" data-bs-target="#collapse{{ $item->id }}">
                                                {{ $item->akunJenis ? $item->akunJenis->nama_akun : '-' }}
                                            </td>
                                            <td style="height: 42px !important" class="cursor-pointer py-2"
                                                data-bs-toggle="collapse" data-bs-target="#collapse{{ $item->id }}">
                                                {{ $item->skpd ? $item->skpd : '-' }}
                                            </td>
                                            <td style="height: 42px !important" class="cursor-pointer py-2"
                                                data-bs-toggle="collapse" data-bs-target="#collapse{{ $item->id }}">
                                                {{ $item->nwp ? $item->nwp : '-' }}
                                            </td>
                                            <td style="height: 42px !important" class="cursor-pointer py-2"
                                                data-bs-toggle="collapse" data-bs-target="#collapse{{ $item->id }}">
                                                {{ $item->pejabat_penandatangan ? $item->pejabat_penandatangan : '-' }}
                                            </td>
                                            <td style="height: 42px !important" class="cursor-pointer py-2"
                                                data-bs-toggle="collapse" data-bs-target="#collapse{{ $item->id }}">
                                                {{ $item->unit_pengolah ? $item->unit_pengolah : '-' }}
                                            </td>
                                            <td style="height: 42px !important" class="cursor-pointer py-2"
                                                data-bs-toggle="collapse" data-bs-target="#collapse{{ $item->id }}">
                                                {{ $item->kurun_waktu ? $item->kurun_waktu : '-' }}
                                            </td>
                                            <td style="height: 42px !important" class="cursor-pointer py-2"
                                                data-bs-toggle="collapse" data-bs-target="#collapse{{ $item->id }}">
                                                {{ $item->jumlah_satuan_berkas ? $item->jumlah_satuan_berkas : '-' }}
                                                Berkas
                                            </td>
                                            <td style="height: 42px !important" class="cursor-pointer py-2"
                                                data-bs-toggle="collapse" data-bs-target="#collapse{{ $item->id }}">
                                                {{ $item->tkt_perkemb ? $item->tkt_perkemb : '-' }}
                                            </td>
                                            <td style="height: 42px !important" class="cursor-pointer py-2"
                                                data-bs-toggle="collapse" data-bs-target="#collapse{{ $item->id }}">
                                                {{ $item->no_box ? $item->no_box : '-' }}
                                            </td>
                                            <td style="height: 42px !important" class="cursor-pointer py-2"
                                                data-bs-toggle="collapse" data-bs-target="#collapse{{ $item->id }}">
                                                {{ $item->status ? $item->status : '-' }}
                                            </td>
                                            <td style="height: 42px !important" class="cursor-pointer py-2">
                                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">

                                                    @if ($item->no_box)
                                                        <a download="{{ $item->no_box }}.png"
                                                            href="data:image/png;base64,{{ DNS2D::getBarcodePNG(url('/detail-box', Str::replace('/', '_', $item->no_box)), 'QRCODE', 200, 200) }}"
                                                            target="_blank">
                                                            <div class="bg-white p-1 rounded-sm" style="height: 30px">
                                                                {!! '<img class="mb-3" src="data:image/png;base64,' .
                                                                    DNS2D::getBarcodePNG($item->no_box, 'QRCODE', 1, 1) .
                                                                    '" alt="' .
                                                                    $item->no_box .
                                                                    '"   />' !!}
                                                            </div>
                                                        </a>
                                                    @endif

                                                    <a href="{{ route('dokumen-masuk.show', $item->id) }}"
                                                        class="btn btn-icon btn-icon-only btn-sm btn-secondary"
                                                        type="button">
                                                        <i class="fa fa-info" aria-hidden="true"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="collapse" id="collapse{{ $item->id }}">
                                            <td class="border" colspan="21">
                                                <div class="data-table-responsive-wrapper overflow-auto">
                                                    <table class="data-table dataTable no-footer">
                                                        <tr>
                                                            <td class="bg-primary text-white text-small text-uppercase">No.
                                                            </td>
                                                            <td class="bg-primary text-white text-small text-uppercase">
                                                                Kode
                                                                Klasifikasi</td>
                                                            <td class="bg-primary text-white text-small text-uppercase">
                                                                Uraian</td>
                                                            <td class="bg-primary text-white text-small text-uppercase">
                                                                Tanggal Surat
                                                            </td>
                                                            <td class="bg-primary text-white text-small text-uppercase">
                                                                Jumlah Satuan
                                                            </td>
                                                            <td class="bg-primary text-white text-small text-uppercase">
                                                                Keterangan
                                                            </td>
                                                            <td class="bg-primary text-white text-small text-uppercase">
                                                                Jenis Naskah
                                                                Dinas</td>
                                                            <td style="widtd: 300px !important"
                                                                class="bg-primary text-white text-small text-uppercase">
                                                                No. Surat</td>
                                                            <td class="bg-primary text-white text-small text-uppercase">
                                                                Pejabat
                                                                Penandatangan</td>
                                                            <td class="bg-primary text-white text-small text-uppercase">
                                                                Unit Pengolah
                                                            </td>
                                                            <td class="bg-primary text-white text-small text-uppercase">
                                                                Kurun Waktu
                                                            </td>
                                                            <td class="bg-primary text-white text-small text-uppercase">No.
                                                                Box</td>
                                                            <td colspan="7"
                                                                class="bg-primary text-white text-small text-uppercase">
                                                                Tingkat
                                                                Perkembangan</td>
                                                            <td class="bg-primary empty">&nbsp;</td>
                                                        </tr>
                                                        @foreach ($item->detailDokumen ?? [] as $subitem)
                                                            <tr class="border">
                                                                <td style="height: 42px !important"
                                                                    class="empty py-2 border-start border-top border-bottom">
                                                                    {{ $loop->index + 1 }}.</td>
                                                                <td style="height: 42px !important"
                                                                    class="py-2 border-top border-bottom">
                                                                    {{ $subitem->kode_klasifikasi ? $subitem->kode_klasifikasi : '-' }}
                                                                </td>
                                                                <td style="height: 42px !important"
                                                                    class="py-2 border-top border-bottom">
                                                                    {{ $subitem->uraian ? $subitem->uraian : '-' }}
                                                                </td>
                                                                <td style="height: 42px !important"
                                                                    class="py-2 border-top border-bottom">
                                                                    {{ $subitem->tanggal_surat ? $subitem->tanggal_surat : '-' }}
                                                                </td>
                                                                <td style="height: 42px !important"
                                                                    class="py-2 border-top border-bottom">
                                                                    {{ $subitem->jumlah_satuan ? $subitem->jumlah_satuan : '-' }}
                                                                </td>
                                                                <td style="height: 42px !important"
                                                                    class="py-2 border-top border-bottom">
                                                                    {{ $subitem->keterangan ? $subitem->keterangan : '-' }}
                                                                </td>
                                                                <td style="height: 42px !important"
                                                                    class="py-2 border-top border-bottom">
                                                                    {{ $subitem->jenis_naskah_dinas ? $subitem->jenis_naskah_dinas : '-' }}
                                                                </td>
                                                                <td style="height: 42px !important;"
                                                                    class="py-2 border-top border-bottom">
                                                                    {{ $subitem->no_surat ? $subitem->no_surat : '-' }}
                                                                </td>
                                                                <td style="height: 42px !important"
                                                                    class="py-2 border-top border-bottom">
                                                                    {{ $subitem->pejabat_penandatangan ? $subitem->pejabat_penandatangan : '-' }}
                                                                </td>
                                                                <td style="height: 42px !important"
                                                                    class="py-2 border-top border-bottom">
                                                                    {{ $subitem->unit_pengolah ? $subitem->unit_pengolah : '-' }}
                                                                </td>
                                                                <td style="height: 42px !important"
                                                                    class="py-2 border-top border-bottom">
                                                                    {{ $subitem->kurun_waktu ? $subitem->kurun_waktu : '-' }}
                                                                </td>
                                                                <td style="height: 42px !important"
                                                                    class="py-2 border-top border-bottom">
                                                                    {{ $subitem->no_box ? $subitem->no_box : '-' }}
                                                                </td>
                                                                <td colspan="6" style="height: 42px !important"
                                                                    class="py-2 border-top border-bottom">
                                                                    {{ $subitem->tkt_perk ? $subitem->tkt_perk : '-' }}
                                                                </td>
                                                                <td colspan="5" style="height: 42px !important"
                                                                    class="py-2">
                                                                    <div
                                                                        class="d-grid gap-2 d-md-flex justify-content-md-end">
                                                                        {{-- <a href="#"
                                                                            class="btn btn-icon btn-icon-only btn-sm btn-outline-info"
                                                                            type="button">
                                                                            <i data-acorn-icon="eye"></i>
                                                                        </a> --}}
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- Table End -->

                        <div class="d-flex flex-row justify-content-center mt-5">
                            {{ $dokumen->links() }}
                        </div>
                    @else
                        <div class="d-flex align-items-center justify-content-center mt-5" style="height: 60vh">
                            <div class="alert alert-warning w-75 text-center" role="alert">
                                Data Tidak Ditemukan
                            </div>
                        </div>
                    @endif
                </div>
                <!-- Content End -->
            </div>
        </div>
    </div>

@endsection

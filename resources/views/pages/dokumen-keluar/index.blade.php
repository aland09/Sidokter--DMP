@php
    $html_tag_data = ['override' => '{ "attributes" : { "placement" : "vertical", "layout":"fluid" }, "showSettings" : false }'];
    $title = 'Dokumen Keluar';
    $description = 'Halaman Dokumen Keluar';
    $breadcrumbs = ['/' => 'Beranda', '/dokumen-keluar' => 'Dokumen Keluar'];
@endphp
@extends('layout', ['html_tag_data' => $html_tag_data, 'title' => $title, 'description' => $description])
@section('css')
    <link rel="stylesheet" href="/css/vendor/datatables.min.css" />
    <link rel="stylesheet" href="/css/vendor/bootstrap-datepicker3.standalone.min.css" />
@endsection
@section('js_vendor')
    <script src="/js/vendor/bootstrap-submenu.js"></script>
    <script src="/js/vendor/datatables.min.js"></script>
    <script src="/js/vendor/mousetrap.min.js"></script>
    <script src="/js/cs/scrollspy.js"></script>
    <script src="/js/vendor/jquery.validate/jquery.validate.min.js"></script>
    <script src="/js/vendor/datepicker/bootstrap-datepicker.min.js"></script>
    <script src="/js/vendor/datepicker/locales/bootstrap-datepicker.es.min.js"></script>
@endsection
@section('js_page')
    <script src="/js/base/pagination.js"></script>
    <script src="/js/forms/validation.js"></script>
    <script src="/js/forms/controls.datepicker.js"></script>
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
                        <div class="col-12 col-md-7">
                            <h1 class="mb-0 pb-0 display-4" id="title">{{ $title }}</h1>
                            @include('_layout.breadcrumb', ['breadcrumbs' => $breadcrumbs])
                        </div>
                        <!-- Title End -->
                        <!-- Top Buttons Start -->
                        <div class="col-12 col-md-5 d-flex align-items-start justify-content-end gap-3">
                            <a href="{{ route('dokumen-keluar.create') }}"
                                class="btn btn-primary btn-icon btn-icon-start w-100 w-md-auto mt-3 mt-sm-0">
                                <i data-acorn-icon="plus"></i>
                                <span>Tambah Data</span>
                            </a>
                            <!-- Add New Button End -->
                        </div>
                        <!-- Top Buttons End -->
                    </div>
                </div>
                <!-- Title and Top Buttons End -->
                <!-- Content Start -->
                <div class="data-table-rows slim">
                    <!-- Controls Start -->
                    <div class="row">
                        <!-- Search Start -->
                        <div class="col-sm-12 col-md-6 col-lg-6 col-xxl-4 mb-3">
                            <form name="seach-form" id="search-form" action="/dokumen-keluar">
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
                        <div class="data-table-responsive-wrapper">
                            <table class="data-table hover dataTable no-footer">
                                <thead>
                                    <tr>
                                        <th class="text-muted text-small text-uppercase text-center" rowspan="2">No</th>
                                        <th class="text-muted text-small text-uppercase text-center" rowspan="2">Nama Peminjam</th>
                                        <th class="text-muted text-small text-uppercase text-center" rowspan="2">Hari/ Tanggal</td>
                                        <th class="text-muted text-small text-uppercase text-center" rowspan="2">Instansi</th>
                                        <th class="text-muted text-small text-uppercase text-center" rowspan="2">Tujuan</th>
                                        <th class="text-muted text-small text-uppercase text-center" colspan="3">
                                            Dokumen</th>
                                        <th width="10%" class="empty" rowspan="2"></th>
                                    </tr>
                                    <tr>
                                        <th class="text-muted text-small text-uppercase text-center">Tahun</th>
                                        <th class="text-muted text-small text-uppercase text-center">No. SP2D</th>
                                        <th class="text-muted text-small text-uppercase text-center">Uraian</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dokumen ?? [] as $item)
                                        <tr>
                                            <td style="height: 42px !important" class="py-2">{{ $loop->index + 1 }}.
                                            </td>
                                            <td style="height: 42px !important" class="py-2">
                                                {{ $item->nama_peminjam ? $item->nama_peminjam : '-' }}</td>
                                            <td style="height: 42px !important" class="py-2">
                                                {{ $item->tanggal_peminjaman ? $item->tanggal_peminjaman : '-' }}</td>
                                            <td style="height: 42px !important" class="py-2">
                                                {{ $item->instansi ? $item->instansi : '-' }}</td>
                                            <td style="height: 42px !important" class="py-2">
                                                {{ $item->tujuan ? $item->tujuan : '-' }}</td>
                                            <td style="height: 42px !important" class="py-2">
                                                {{ $item->dokumen->kurun_waktu ? $item->dokumen->kurun_waktu : '-' }}</td>
                                            <td style="height: 42px !important" class="py-2">
                                                {{ $item->dokumen->no_sp2d ? $item->dokumen->no_sp2d : '-' }}</td>
                                            <td style="height: 42px !important" class="py-2">
                                                {{ $item->dokumen->uraian ? $item->dokumen->uraian : '-' }}</td>
                                            <td style="height: 42px !important" class="py-2">
                                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                                    <a href="{{ route('dokumen-keluar.show', $item->id) }}"
                                                        class="btn btn-icon btn-icon-only btn-sm btn-info"
                                                        type="button">
                                                        <i data-acorn-icon="info-circle"></i>
                                                    </a>
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

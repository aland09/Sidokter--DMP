@php
    $html_tag_data = ['override' => '{ "attributes" : { "placement" : "vertical", "layout":"fluid" }, "showSettings" : false }'];
    $title = 'Daftar Regulasi';
    $description = 'Halaman Daftar Regulasi';
    $breadcrumbs = ['/' => 'Beranda', '/regulasi' => 'Daftar Regulasi'];
@endphp
@extends('layout', ['html_tag_data' => $html_tag_data, 'title' => $title, 'description' => $description])
@section('css')
    <link rel="stylesheet" href="/css/vendor/datatables.min.css" />
@endsection
@section('js_vendor')
    <script src="/js/vendor/bootstrap-submenu.js"></script>
    <script src="/js/vendor/datatables.min.js"></script>
    <script src="/js/vendor/mousetrap.min.js"></script>
@endsection
@section('js_page')
    <script src="/js/base/pagination.js"></script>
    <script>
        const submitBtn = document.getElementById('submitBtn');
        $(submitBtn).click(function() {
            $('#modalDialog').modal('hide');
            $('#delete-form').submit();
        });

        const item = $(this).data('item');
        const file_dokumen = item['file_regulasi'];
        $(document).ready(function(){
            if (file_dokumen) {
                $('#file_dokumen_append').html(`<a href="${file_dokumen}"
                            target="_blank"
                            class="btn btn-outline-primary mb-3">Lihat File</a>
                `);
            }
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
                        <div class="col-12 col-md-7">
                            <h1 class="mb-0 pb-0 display-4" id="title">{{ $title }}</h1>
                            @include('_layout.breadcrumb', ['breadcrumbs' => $breadcrumbs])
                        </div>
                        <!-- Title End -->
                        <!-- Top Buttons Start -->
                        <div class="col-12 col-md-5 d-flex align-items-start justify-content-end">
                            <!-- Add New Button Start -->
                            <a href="{{ route('regulasi.create') }}"
                                class="btn btn-outline-primary btn-icon btn-icon-start w-100 w-md-auto mt-3 mt-sm-0">
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
                            <form name="seach-form" id="search-form" action="/regulasi">
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
                    @if ($regulasi->count() > 0)
                        <div class="data-table-responsive-wrapper">
                            <table class="data-table hover dataTable no-footer">
                                <thead>
                                    <tr>
                                        <th class="text-muted text-small text-uppercase">No.</th>
                                        <th width="45%" class="text-muted text-small text-uppercase">Regulasi</th>
                                        <th width="45%" class="text-muted text-small text-uppercase">File</th>
                                        <th width="10%" class="empty">&nbsp;</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($regulasi ?? [] as $item)
                                        <tr>
                                            <td style="height: 42px !important" class="py-2">{{ $loop->index + 1 }}.</td>
                                            <td style="height: 42px !important" class="py-2">{{ $item->name }}</td>
                                            @if($item->file_regulasi)
                                            <td style="height: 42px !important" id="file_dokumen_append" class="py-2">  <!-- {{ $item->file_regulasi }} -->    <a href="{{  asset('storage/' . $item->file_regulasi) }}" target="_blank"
                                            class="btn btn-secondary">Lihat Dokumen</a> </td>
                                            @endif
                                            <td style="height: 42px !important" class="py-2">
                                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                                    <!--<a href="{{ route('regulasi.edit', $item->id) }}"
                                                        class="btn btn-icon btn-icon-only btn-sm btn-outline-warning"
                                                        type="button">
                                                        <i data-acorn-icon="edit"></i>
                                                    </a>-->
                                                    <form id="delete-form" action="/regulasi/{{ $item->id }}" method="POST"
                                                        class="d-inline">
                                                        @method('delete')
                                                        @csrf
                                                        <button type="button" id="confirmBtn" class="btn btn-icon btn-icon-only btn-sm btn-outline-danger"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#modalDialog"><i
                                                            data-acorn-icon="bin"></i></button>

                                                        {{-- <button
                                                            class="btn btn-icon btn-icon-only btn-sm btn-outline-danger"
                                                            onclick="return confirm('are you sure?')"><i
                                                                data-acorn-icon="bin"></i></button> --}}
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- Table End -->

                        <div class="d-flex flex-row justify-content-center mt-5">
                            {{-- $regulasi->links() --}}
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

    <!-- Modal -->
    <div class="modal fade" id="modalDialog" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header pt-4 pb-3" style="border-bottom: none !important">
                    <h5 class="modal-title" id="staticBackdropLabel">Hapus Data?</h5>
                </div>
                <div class="modal-footer pt-0 pb-4" style="border-top: none !important">
                    <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" id="submitBtn" class="btn btn-primary">Ya</button>
                </div>
            </div>
        </div>
    </div>

@endsection

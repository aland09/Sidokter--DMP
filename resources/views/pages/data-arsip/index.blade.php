@php
    $html_tag_data = ['override' => '{ "attributes" : { "placement" : "vertical", "layout":"fluid" }, "showSettings" : false }'];
    $title = 'Data Arsip';
    $description = 'Halaman Data Arsip';
    $breadcrumbs = ['/' => 'Beranda', '/data-arsip' => 'Data Arsip'];
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
    <script>
        $(document).on("click", ".modal-edit", function() {
            var form_edit_id = $(this).data('id');
            $(".modal-body #form_edit_id").val(form_edit_id);
        });

        const submitBtnEdit = document.getElementById('submitBtnEdit');
        $(submitBtnEdit).click(function() {
            const formId = '#' + $(".modal-body #form_edit_id").val();
            $('#modalEdit').modal('hide');
            $(formId).submit();
        });

        $(document).on("click", ".modal-hapus", function() {
            var form_hapus_id = $(this).data('id');
            $(".modal-body #form_hapus_id").val(form_hapus_id);
        });

        const submitBtnHapus = document.getElementById('submitBtnHapus');
        $(submitBtnHapus).click(function() {
            const formId = '#' + $(".modal-body #form_hapus_id").val();
            $('#modalHapus').modal('hide');
            $(formId).submit();
        });

        $(document).on('click', '.btn-edit', function() {
            const item = $(this).data('item');
            const subitem = $(this).data('subitem');
            const form = '#form_' + item + '_' + subitem;
            const tabel = '#tabel_' + item + '_' + subitem;

            $(tabel).fadeOut("slow", function() {
                $(this).addClass("d-none");
            });

            $(form).fadeIn("slow", function() {
                $(this).removeClass("d-none");
                $('div.data-table-responsive-wrapper').scrollLeft(0);
            });


        });

        $(document).on('click', '.btn-cancel', function() {
            const item = $(this).data('item');
            const subitem = $(this).data('subitem');
            const form = '#form_' + item + '_' + subitem;
            const tabel = '#tabel_' + item + '_' + subitem;

            $(tabel).fadeIn("slow", function() {
                $(this).removeClass("d-none");
            });

            $(form).fadeOut("slow", function() {
                $(this).addClass("d-none");
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

    {{-- notifikasi form validasi --}}
    @if ($errors->has('file'))
        <span class="invalid-feedback" role="alert">
            <strong>{{ $errors->first('file') }}</strong>
        </span>
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
                            <!-- Add New Button Start -->
                            <button type="button" data-bs-toggle="modal" data-bs-target="#modalImport"
                                class="btn btn-outline-primary btn-icon btn-icon-start w-100 w-md-auto mt-3 mt-sm-0">
                                <i data-acorn-icon="cloud-upload"></i>
                                <span>Import Data</span>
                            </button>

                            <a href="{{ route('data-arsip.create') }}" class="btn btn-primary btn-icon btn-icon-start w-100 w-md-auto mt-3 mt-sm-0">
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
                            <form name="seach-form" id="search-form" action="/data-arsip">
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
                            <table class="data-table hover dataTable no-footer">
                                <thead>
                                    <tr>
                                        <th class="text-muted text-small text-uppercase">No.</th>
                                        <th class="text-muted text-small text-uppercase">Kode Klasifikasi</th>
                                        <th class="text-muted text-small text-uppercase">Uraian</th>
                                        <th class="text-muted text-small text-uppercase">Tanggal Validasi</th>
                                        <th class="text-muted text-small text-uppercase">Jumlah Satuan Item</th>
                                        <th class="text-muted text-small text-uppercase">Keterangan</th>
                                        <th class="text-muted text-small text-uppercase">No. SPM</th>
                                        <th style="width: 300px !important" class="text-muted text-small text-uppercase">
                                            No. SP2D</th>
                                        <th class="text-muted text-small text-uppercase">Nominal</th>
                                        <th class="text-muted text-small text-uppercase">SKPD</th>
                                        <th class="text-muted text-small text-uppercase">Pejabat Penandatangan</th>
                                        <th class="text-muted text-small text-uppercase">Unit Pengolah</th>
                                        <th class="text-muted text-small text-uppercase">Kurun Waktu</th>
                                        <th class="text-muted text-small text-uppercase">Jumlah Satuan Berkas</th>
                                        <th class="text-muted text-small text-uppercase">Tkt. Perkemb</th>
                                        <th class="text-muted text-small text-uppercase">No. Box</th>
                                        <th width="10%" class="empty">&nbsp;</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dokumen ?? [] as $item)
                                        <tr>
                                            <td style="height: 42px !important" class="py-2 bg-primary text-white">
                                                {{ $loop->index + 1 }}.
                                            </td>
                                            <td colspan="15" style="height: 42px !important"
                                                class="py-2 bg-primary text-white">
                                                {{ $item->no_sp2d }} ({{ $item->status }})
                                            </td>
                                            <td style="height: 42px !important" class="py-2 bg-primary text-white">
                                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                                    <a href="#"
                                                        class="btn btn-icon btn-icon-only btn-sm btn-outline-white"
                                                        type="button">
                                                        <i data-acorn-icon="check"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        @foreach ($item->detailDokumen ?? [] as $subitem)
                                            <tr id="tabel_{{ $item->id }}_{{ $subitem->id }}">
                                                <td style="height: 42px !important" class="empty py-2">&nbsp;</td>
                                                <td style="height: 42px !important" class="py-2">
                                                    {{ $subitem->kode_klasifikasi }}
                                                </td>
                                                <td style="height: 42px !important" class="py-2">
                                                    {{ $subitem->uraian }}
                                                </td>
                                                <td style="height: 42px !important" class="py-2">
                                                    {{ $subitem->tanggal_validasi }}
                                                </td>
                                                <td style="height: 42px !important" class="py-2">
                                                    {{ $subitem->jumlah_satuan_item }}
                                                </td>
                                                <td style="height: 42px !important" class="py-2">
                                                    {{ $subitem->keterangan }}
                                                </td>
                                                <td style="height: 42px !important" class="py-2">
                                                    {{ $subitem->no_spm }}
                                                </td>
                                                <td style="height: 42px !important;" class="py-2">
                                                    {{ $subitem->no_sp2d }}
                                                </td>
                                                <td style="height: 42px !important" class="py-2">
                                                    {{ $subitem->nominal }}
                                                </td>
                                                <td style="height: 42px !important" class="py-2">
                                                    {{ $subitem->skpd }}
                                                </td>
                                                <td style="height: 42px !important" class="py-2">
                                                    {{ $subitem->pejabat_penandatangan }}
                                                </td>
                                                <td style="height: 42px !important" class="py-2">
                                                    {{ $subitem->unit_pengolah }}
                                                </td>
                                                <td style="height: 42px !important" class="py-2">
                                                    {{ $subitem->kurun_waktu }}
                                                </td>
                                                <td style="height: 42px !important" class="py-2">
                                                    {{ $subitem->jumlah_satuan_berkas }}
                                                </td>
                                                <td style="height: 42px !important" class="py-2">
                                                    {{ $subitem->tkt_perkemb }}
                                                </td>
                                                <td style="height: 42px !important" class="py-2">
                                                    {{ $subitem->no_box }}
                                                </td>
                                                <td style="height: 42px !important" class="py-2">
                                                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                                        <a href="#"
                                                            class="btn btn-icon btn-icon-only btn-sm btn-outline-info"
                                                            type="button">
                                                            <i data-acorn-icon="eye"></i>
                                                        </a>
                                                        <button type="button" data-item="{{ $item->id }}"
                                                            data-subitem="{{ $subitem->id }}"
                                                            class="btn btn-icon btn-icon-only btn-sm btn-outline-warning btn-edit">
                                                            <i data-acorn-icon="edit"></i>
                                                        </button>
                                                        <form id="delete_{{ $item->id }}_{{ $subitem->id }}"
                                                            action="/detail-data-arsip/{{ $subitem->id }}"
                                                            method="POST" class="d-inline">
                                                            @method('delete')
                                                            @csrf
                                                            <button type="button"
                                                                class="btn btn-icon btn-icon-only btn-sm btn-outline-danger modal-hapus"
                                                                data-id="delete_{{ $item->id }}_{{ $subitem->id }}"
                                                                data-bs-toggle="modal" data-bs-target="#modalHapus"><i
                                                                    data-acorn-icon="bin"></i></button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>

                                            <tr class="d-none" id="form_{{ $item->id }}_{{ $subitem->id }}">
                                                <td colspan="17">
                                                    <div class="my-5 ps-3">
                                                        <form id="edit_{{ $item->id }}_{{ $subitem->id }}"
                                                            action="/detail-data-arsip/{{ $subitem->id }}"
                                                            class="tooltip-label-end edit-form" novalidate method="POST"
                                                            enctype="multipart/form-data">
                                                            @method('put')
                                                            @csrf

                                                            <input type="hidden" name="dokumen_id"
                                                                value="{{ $item->id }}">

                                                            <div class="mb-3 position-relative form-group">
                                                                <label class="form-label text-primary fw-bold">Kode
                                                                    Klasifikasi</label>
                                                                <input type="text" class="form-control"
                                                                    name="kode_klasifikasi"
                                                                    value="{{ $subitem->kode_klasifikasi }}" required />
                                                            </div>

                                                            <div class="mb-3 position-relative form-group">
                                                                <label
                                                                    class="form-label text-primary fw-bold">Uraian</label>
                                                                <textarea class="form-control" name="uraian" required>{{ $subitem->uraian }}</textarea>
                                                            </div>

                                                            <div class="mb-3 position-relative form-group">
                                                                <label class="form-label text-primary fw-bold">Tanggal
                                                                    Validasi</label>
                                                                <input type="text" class="form-control datepicker"
                                                                    name="tanggal_validasi"
                                                                    value="{{ $subitem->tanggal_validasi }}" required />
                                                            </div>

                                                            <div class="mb-3 position-relative form-group">
                                                                <label class="form-label text-primary fw-bold">Jumlah
                                                                    Satuan Item</label>
                                                                <input type="text" class="form-control"
                                                                    name="jumlah_satuan_item"
                                                                    value="{{ $subitem->jumlah_satuan_item }}" required />
                                                            </div>

                                                            <div class="mb-3 position-relative form-group">
                                                                <label
                                                                    class="form-label text-primary fw-bold">Keterangan</label>
                                                                <input type="text" class="form-control"
                                                                    name="keterangan" value="{{ $subitem->keterangan }}"
                                                                    required />
                                                            </div>

                                                            <div class="mb-3 position-relative form-group">
                                                                <label class="form-label text-primary fw-bold">No.
                                                                    SPM</label>
                                                                <input type="text" class="form-control" name="no_spm"
                                                                    value="{{ $subitem->no_spm }}" required />
                                                            </div>

                                                            <div class="mb-3 position-relative form-group">
                                                                <label class="form-label text-primary fw-bold">No.
                                                                    SP2D</label>
                                                                <input type="text" class="form-control" name="no_sp2d"
                                                                    value="{{ $subitem->no_sp2d }}" required />
                                                            </div>

                                                            <div class="mb-3 position-relative form-group">
                                                                <label
                                                                    class="form-label text-primary fw-bold">Nominal</label>
                                                                <input type="number" class="form-control" name="nominal"
                                                                    value="{{ $subitem->nominal }}" required />
                                                            </div>

                                                            <div class="mb-3 position-relative form-group">
                                                                <label class="form-label text-primary fw-bold">SKPD</label>
                                                                <input type="text" class="form-control" name="skpd"
                                                                    value="{{ $subitem->skpd }}" required />
                                                            </div>

                                                            <div class="mb-3 position-relative form-group">
                                                                <label class="form-label text-primary fw-bold">Pejabat
                                                                    Penandatangan</label>
                                                                <input type="text" class="form-control"
                                                                    name="pejabat_penandatangan"
                                                                    value="{{ $subitem->pejabat_penandatangan }}"
                                                                    required />
                                                            </div>

                                                            <div class="mb-3 position-relative form-group">
                                                                <label class="form-label text-primary fw-bold">Unit
                                                                    Pengolah</label>
                                                                <select name="unit_pengolah" class="form-select" required>
                                                                    <option value="{{ $subitem->unit_pengolah }}">
                                                                        {{ $subitem->unit_pengolah }}</option>
                                                                    <option value="SBPK-JP">SBPK-JP</option>
                                                                    <option value="SBPK-JU">SBPK-JU</option>
                                                                    <option value="SBPK-JB">SBPK-JB</option>
                                                                    <option value="SBPK-JS">SBPK-JS</option>
                                                                    <option value="SBPK-JT">SBPK-JT</option>
                                                                </select>
                                                            </div>

                                                            <div class="mb-3 position-relative form-group">
                                                                <label class="form-label text-primary fw-bold">Kurun
                                                                    Waktu</label>
                                                                <input type="number" class="form-control"
                                                                    name="kurun_waktu"
                                                                    value="{{ $subitem->kurun_waktu }}" required />
                                                            </div>

                                                            <div class="mb-3 position-relative form-group">
                                                                <label class="form-label text-primary fw-bold">Jumlah
                                                                    Satuan Berkas</label>
                                                                <input type="text" class="form-control"
                                                                    name="jumlah_satuan_berkas"
                                                                    value="{{ $subitem->jumlah_satuan_berkas }}"
                                                                    required />
                                                            </div>

                                                            <div class="mb-3 position-relative form-group">
                                                                <label class="form-label text-primary fw-bold">Tkt.
                                                                    Perkemb</label>
                                                                <input type="text" class="form-control"
                                                                    name="tkt_perkemb"
                                                                    value="{{ $subitem->tkt_perkemb }}" required />
                                                            </div>

                                                            <div class="mb-3 position-relative form-group">
                                                                <label class="form-label text-primary fw-bold">No.
                                                                    Box</label>
                                                                <input type="text" class="form-control" name="no_box"
                                                                    value="{{ $subitem->no_box }}" required />
                                                            </div>

                                                            <div class="mb-3 position-relative form-group">
                                                                <label class="form-label text-primary fw-bold">File
                                                                    Dokumen</label>
                                                                @if ($subitem->file_dokumen)
                                                                <br>
                                                                    <a href="{{ $subitem->file_dokumen }}"
                                                                        target="_blank"
                                                                        class="btn btn-outline-primary mb-3">Lihat File</a>
                                                                @endif
                                                                <input class="form-control" type="file"
                                                                    name="file_dokumen" />
                                                            </div>

                                                            <div class="d-grid gap-2 d-md-flex justify-content-start mt-5">
                                                                <button type="button" data-item="{{ $item->id }}"
                                                                    data-subitem="{{ $subitem->id }}"
                                                                    class="btn btn-outline-primary btn-cancel">
                                                                    Batal
                                                                </button>

                                                                <button type="button" class="btn btn-primary modal-edit"
                                                                    data-id="edit_{{ $item->id }}_{{ $subitem->id }}"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#modalEdit">Simpan</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
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

    <!-- Modal Import -->
    <div class="modal fade" id="modalImport" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="/data-arsip/import_excel" enctype="multipart/form-data">
                <div class="modal-content">
                    <div class="modal-header py-3">
                        <h5 class="modal-title" id="exampleModalLabelDefault">Import Data</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body py-3">
                        {{ csrf_field() }}
                        <div class="mb-3 position-relative form-group">
                            <label class="form-label text-primary fw-bold">File Import</label>
                            <input class="form-control" type="file" name="file" required="required" />
                        </div>
                    </div>
                    <div class="modal-footer pt-0 pb-4" style="border-top: none !important">
                        <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Import</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit-->
    <div class="modal fade" id="modalEdit" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header pt-4 pb-3" style="border-bottom: none !important">
                    <h5 class="modal-title" id="staticBackdropLabel">Simpan Data?</h5>
                </div>
                <div class="modal-body d-none">
                    <input type="hidden" name="form_edit_id" id="form_edit_id" value="" />
                </div>
                <div class="modal-footer pt-0 pb-4" style="border-top: none !important">
                    <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" id="submitBtnEdit" class="btn btn-primary">Ya</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Hapus -->
    <div class="modal fade" id="modalHapus" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header pt-4 pb-3" style="border-bottom: none !important">
                    <h5 class="modal-title" id="staticBackdropLabel">Hapus Data?</h5>
                </div>
                <div class="modal-body d-none">
                    <input type="hidden" name="form_hapus_id" id="form_hapus_id" value="" />
                </div>
                <div class="modal-footer pt-0 pb-4" style="border-top: none !important">
                    <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" id="submitBtnHapus" class="btn btn-primary">Ya</button>
                </div>
            </div>
        </div>
    </div>

@endsection

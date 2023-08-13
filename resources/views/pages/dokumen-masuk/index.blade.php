@php
    $html_tag_data = ['override' => '{ "attributes" : { "placement" : "vertical", "layout":"fluid" }, "showSettings" : false }'];
    $title = 'Dokumen Masuk';
    $description = 'Halaman Dokumen Masuk';
    $breadcrumbs = ['/' => 'Beranda', '/dokumen-masuk' => 'Dokumen Masuk'];
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
        var ids = [];
        $('.no-box-check').change(function() {
            const id = $(this).data('id');
            // $('.no-box-check:not(#check-parent_' + id + ')').prop("checked", false);
            // $('.check-child').prop("checked", false);
            if ($(this).is(":checked")) {
                $('.no-box-check_' + id).prop("checked", true);
                $('#btn-barcode').prop('disabled', false);
                year = $(this).val();
                $('#kurun_waktu').val(year);
                $('#dokumen_id').val(id);
                ids.push(id);
            } else {
                $('#kurun_waktu').val('');
                $('#dokumen_id').val('');
                $('.no-box-check_' + id).prop("checked", false);
                $('#btn-barcode').prop('disabled', true);
                const index = ids.indexOf(id);
                if (index > -1) { // only splice array when item is found
                    ids.splice(index, 1); // 2nd parameter means remove one item only
                }
            }

            $('#dokumen_id').val(ids);
            console.log('ids', ids);
        });

        $('#btn-barcode').click(function() {
            $('#modalFormBarcode').modal('show');
            const year = $('#kurun_waktu').val();
            if (year) {
                $.ajax({
                    url: '/get-no-box/' + year,
                    type: "GET",
                    data: {
                        "_token": "{{ csrf_token() }}"
                    },
                    dataType: "json",
                    success: function(data) {
                        if (data) {
                            $('#no_box_display').html(data);
                            $('#no_box').val(data);
                        } else {
                            $('#no_box_display').html("Gagal Membuat QR Code");
                            $('#no_box').val('');
                        }
                    }
                });
            } else {
                $('#no_box_display').html("Gagal Membuat QR Code");
                $('#no_box').val('');
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
                        <div class="col-12 col-md-5 d-flex align-items-start justify-content-end gap-3">
                            <button type="button"
                                class="btn btn-primary btn-icon btn-icon-start w-100 w-md-auto mt-3 mt-sm-0"
                                id="btn-barcode" disabled>
                                <i data-acorn-icon="plus"></i>
                                <span>Buat QR Code No. Box</span>
                            </button>
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
                            <form name="seach-form" id="search-form" action="/dokumen-masuk">
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
                                        <th colspan="2" class="text-muted text-small text-uppercase">Kode Klasifikasi
                                        </th>
                                        <th class="text-muted text-small text-uppercase">Uraian</th>
                                        <th class="text-muted text-small text-uppercase">Tanggal Validasi</th>
                                        <th class="text-muted text-small text-uppercase">Jumlah Satuan Item</th>
                                        <th class="text-muted text-small text-uppercase">Keterangan</th>
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
                                        <th class="text-muted text-small text-uppercase">Status</th>
                                        <th width="10%" class="empty">&nbsp;</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dokumen ?? [] as $item)
                                        <tr>
                                            <td style="height: 42px !important" class="py-2 bg-primary text-white">
                                                {{ $loop->index + 1 }}.
                                            </td>
                                            <td style="height: 42px !important" class="py-2 bg-primary text-white">
                                                <div class="mb-1 ms-3"><input type="checkbox"
                                                        class="form-check-input no-box-check"
                                                        value="{{ $item->kurun_waktu }}"
                                                        id="check-parent_{{ $item->id }}"
                                                        data-id="{{ $item->id }}"
                                                        {{ $item->no_box !== null ? ' disabled' : '' }}>
                                                </div>
                                            </td>
                                            <td style="height: 42px !important" class="py-2 bg-primary text-white">
                                                {{ $item->kode_klasifikasi }}
                                            </td>
                                            <td style="height: 42px !important" class="py-2 bg-primary text-white">
                                                {{ $item->uraian }}
                                            </td>
                                            <td style="height: 42px !important" class="py-2 bg-primary text-white">
                                                {{ $item->tanggal_validasi }}
                                            </td>
                                            <td style="height: 42px !important" class="py-2 bg-primary text-white">
                                                {{ $item->jumlah_satuan_item }}
                                            </td>
                                            <td style="height: 42px !important" class="py-2 bg-primary text-white">
                                                {{ $item->keterangan }}
                                            </td>
                                            <td style="height: 42px !important;" class="py-2 bg-primary text-white">
                                                {{ $item->no_sp2d }}
                                            </td>
                                            <td style="height: 42px !important" class="py-2 bg-primary text-white">
                                                {{ $item->nominal }}
                                            </td>
                                            <td style="height: 42px !important" class="py-2 bg-primary text-white">
                                                {{ $item->skpd }}
                                            </td>
                                            <td style="height: 42px !important" class="py-2 bg-primary text-white">
                                                {{ $item->pejabat_penandatangan }}
                                            </td>
                                            <td style="height: 42px !important" class="py-2 bg-primary text-white">
                                                {{ $item->unit_pengolah }}
                                            </td>
                                            <td style="height: 42px !important" class="py-2 bg-primary text-white">
                                                {{ $item->kurun_waktu }}
                                            </td>
                                            <td style="height: 42px !important" class="py-2 bg-primary text-white">
                                                {{ $item->jumlah_satuan_berkas }}
                                            </td>
                                            <td style="height: 42px !important" class="py-2 bg-primary text-white">
                                                {{ $item->tkt_perkemb }}
                                            </td>
                                            <td style="height: 42px !important" class="py-2 bg-primary text-white">
                                                {{ $item->no_box }}
                                            </td>
                                            <td style="height: 42px !important" class="py-2 bg-primary text-white">
                                                {{ $item->status }}
                                            </td>
                                            <td style="height: 42px !important" class="py-2 bg-primary text-white">
                                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">

                                                    {{-- @if ($item->no_box)
                                                        <div class="bg-white p-1 rounded-sm" style="height: 30px">
                                                            {!! '<img class="mb-3" src="data:image/png;base64,' .
                                                                DNS2D::getBarcodePNG($item->no_box, 'QRCODE', 1, 1) .
                                                                '" alt="' .
                                                                $item->no_box .
                                                                '"   />' !!}
                                                        </div>
                                                    @endif --}}



                                                    <a href="#"
                                                        class="btn btn-icon btn-icon-only btn-sm btn-outline-info"
                                                        type="button">
                                                        <i data-acorn-icon="eye"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-muted text-small text-uppercase">No.</th>
                                            <th colspan="2" class="text-muted text-small text-uppercase">Kode
                                                Klasifikasi</th>
                                            <th class="text-muted text-small text-uppercase">Uraian</th>
                                            <th class="text-muted text-small text-uppercase">Tanggal Surat</th>
                                            <th class="text-muted text-small text-uppercase">Jumlah Satuan</th>
                                            <th class="text-muted text-small text-uppercase">Keterangan</th>
                                            <th class="text-muted text-small text-uppercase">Jenis Naskah Dinas</th>
                                            <th style="width: 300px !important"
                                                class="text-muted text-small text-uppercase">
                                                No. Surat</th>
                                            <th class="text-muted text-small text-uppercase">Pejabat Penandatangan</th>
                                            <th class="text-muted text-small text-uppercase">Unit Pengolah</th>
                                            <th class="text-muted text-small text-uppercase">Kurun Waktu</th>
                                            <th class="text-muted text-small text-uppercase">No. Box</th>
                                            <th class="text-muted text-small text-uppercase">Tkt. Perk.</th>
                                            <th colspan="5" width="10%" class="empty">&nbsp;</th>
                                        </tr>
                                        @foreach ($item->detailDokumen ?? [] as $subitem)
                                            <tr id="tabel_{{ $item->id }}_{{ $subitem->id }}">
                                                <td style="height: 42px !important" class="empty py-2">
                                                    {{ $loop->index + 1 }}.</td>
                                                <td style="height: 42px !important" class="py-2">
                                                    <div class="mb-1 ms-3"><input type="checkbox" disabled
                                                            class="form-check-input check-child no-box-check_{{ $item->id }}">
                                                    </div>
                                                </td>
                                                <td style="height: 42px !important" class="py-2">
                                                    {{ $subitem->kode_klasifikasi }}
                                                </td>
                                                <td style="height: 42px !important" class="py-2">
                                                    {{ $subitem->uraian }}
                                                </td>
                                                <td style="height: 42px !important" class="py-2">
                                                    {{ $subitem->tanggal_surat }}
                                                </td>
                                                <td style="height: 42px !important" class="py-2">
                                                    {{ $subitem->jumlah_satuan }}
                                                </td>
                                                <td style="height: 42px !important" class="py-2">
                                                    {{ $subitem->keterangan }}
                                                </td>
                                                <td style="height: 42px !important" class="py-2">
                                                    {{ $subitem->jenis_naskah_dinas }}
                                                </td>
                                                <td style="height: 42px !important;" class="py-2">
                                                    {{ $subitem->no_surat }}
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
                                                    {{ $subitem->no_box }}
                                                </td>
                                                <td style="height: 42px !important" class="py-2">
                                                    {{ $subitem->tkt_perk }}
                                                </td>
                                                <td colspan="5" style="height: 42px !important" class="py-2">
                                                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                                        <a href="#"
                                                            class="btn btn-icon btn-icon-only btn-sm btn-outline-info"
                                                            type="button">
                                                            <i data-acorn-icon="eye"></i>
                                                        </a>
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
    <div class="modal fade" id="modalFormBarcode" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="/data-arsip-no-box" enctype="multipart/form-data">
                <div class="modal-content">
                    <div class="modal-header py-3">
                        <h5 class="modal-title" id="exampleModalLabelDefault">Buat QR Code No. Box</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body d-flex flex-column align-items-center justify-content-center text-center py-3">
                        {{ csrf_field() }}
                        {!! '<img class="mb-3" src="data:image/png;base64,' .
                            DNS2D::getBarcodePNG($no_box_tmp, 'QRCODE', 12, 12) .
                            '" alt="' .
                            $no_box_tmp .
                            '"   />' !!}
                        <div class="form-label text-primary fw-bold" id="no_box_display">Mohon Tunggu...</div>
                        <input type="hidden" name="id[]" id="dokumen_id">
                        <input type="hidden" name="kurun_waktu" id="kurun_waktu">

                    </div>
                    <div class="modal-footer pt-3 pb-3">
                        <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection

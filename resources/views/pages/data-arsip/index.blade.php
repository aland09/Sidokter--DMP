@php
    $html_tag_data = ['override' => '{ "attributes" : { "placement" : "vertical", "layout":"fluid" }, "showSettings" : false }'];
    $title = 'Pemberkasan';
    $description = 'Halaman Pemberkasan';
    $breadcrumbs = ['/' => 'Beranda', '/data-arsip' => 'Pemberkasan'];
@endphp
@extends('layout', ['html_tag_data' => $html_tag_data, 'title' => $title, 'description' => $description])
@section('css')
    <link rel="stylesheet" href="/css/vendor/datatables.min.css" />
    <link rel="stylesheet" href="/css/vendor/bootstrap-datepicker3.standalone.min.css" />
    <link rel="stylesheet" href="/css/vendor/select2.min.css" />
    <link rel="stylesheet" href="/css/vendor/select2-bootstrap4.min.css" />
@endsection
@section('js_vendor')
    <script src="/js/vendor/bootstrap-submenu.js"></script>
    <script src="/js/vendor/datatables.min.js"></script>
    <script src="/js/vendor/mousetrap.min.js"></script>
    <script src="/js/cs/scrollspy.js"></script>
    <script src="/js/vendor/jquery.validate/jquery.validate.min.js"></script>
    <script src="/js/vendor/datepicker/bootstrap-datepicker.min.js"></script>
    <script src="/js/vendor/datepicker/locales/bootstrap-datepicker.es.min.js"></script>
    <script src="/js/vendor/select2.full.min.js"></script>
@endsection
@section('js_page')
    <script src="/js/base/pagination.js"></script>
    <script src="/js/forms/validation.js"></script>
    <script src="/js/forms/controls.datepicker.js"></script>
    <script src="/js/forms/controls.select2.js"></script>
    <script>
        const submitBtnEditChild = document.getElementById('submitBtnEditChild');
        $(submitBtnEditChild).click(function() {
            $('#modalEditChild').modal('hide');
            $('#form_edit_child').submit();
        });

        const submitBtnEditParent = document.getElementById('submitBtnEditParent');
        $(submitBtnEditParent).click(function() {
            $('#modalEditParent').modal('hide');
            $('#form_edit_parent').submit();
        });

        const submitBtnAddParent = document.getElementById('submitBtnAddParent');
        $(submitBtnAddParent).click(function() {
            $('#modalAddParent').modal('hide');
            $('#form_add_parent').submit();
        });

        $(document).on("click", ".modal-hapus", function() {
            var form_hapus_id = $(this).data('id');
            $(".modal-body #form_hapus_id").val(form_hapus_id);
        });

        $(document).on("click", ".modal-verifikasi", function() {
            var form_verifikasi_id = $(this).data('id');
            var form_verifikasi_status = $(this).data('status');
            $(".modal-body #form_verifikasi_id").val(form_verifikasi_id);
            $(".modal-body #form_verifikasi_status").val(form_verifikasi_status);
        });

        const submitBtnHapus = document.getElementById('submitBtnHapus');
        $(submitBtnHapus).click(function() {
            const formId = '#' + $(".modal-body #form_hapus_id").val();
            $('#modalHapus').modal('hide');
            $(formId).submit();
        });

        $(document).on('click', '.btn-edit-child', function() {
            const item = $(this).data('item');
            const subitem = $(this).data('subitem');
            const index = $(this).data('index');
            var disable = true;



            if (index > 3) {
                disable = false;
            }
            // TRIGER PROP INPUT
            $(".modal-body #child_kode_klasifikasi").prop("disabled", disable);
            $(".modal-body #child_jumlah_satuan").prop("disabled", disable);
            $(".modal-body #child_keterangan").prop("disabled", disable);
            $(".modal-body #child_jenis_naskah_dinas").prop("disabled", disable);
            $(".modal-body #child_pejabat_penandatangan").prop("disabled", disable);
            $(".modal-body #child_unit_pengolah").prop("disabled", disable);
            $(".modal-body #child_kurun_waktu").prop("disabled", disable);
            $(".modal-body #child_no_box").prop("disabled", disable);

            // UUPDATE VALUE INPUT
            $('#form_edit_child').attr('action', '/detail-data-arsip/' + subitem['id']);
            $(".modal-body #child_dokumen_id").val(subitem['dokumen_id']);
            $(".modal-body #child_kode_klasifikasi").val(subitem['kode_klasifikasi']);
            $(".modal-body #child_uraian").val(subitem['uraian']);
            $(".modal-body #child_tanggal_surat").val(subitem['tanggal_surat']);
            $(".modal-body #child_jumlah_satuan").val(subitem['jumlah_satuan']);
            $(".modal-body #child_keterangan").val(subitem['keterangan']);
            $(".modal-body #child_jenis_naskah_dinas").val(subitem['jenis_naskah_dinas']);
            $(".modal-body #child_no_surat").val(subitem['no_surat']);
            $(".modal-body #child_pejabat_penandatangan").val(subitem['pejabat_penandatangan']);
            $(".modal-body #child_unit_pengolah").val(subitem['unit_pengolah']);
            $(".modal-body #child_kurun_waktu").val(subitem['kurun_waktu']);
            $(".modal-body #child_no_box").val(subitem['no_box']);
            $(".modal-body #child_tkt_perk").val(subitem['tkt_perk']);

            const file_dokumen = subitem['file_dokumen'];
            if (file_dokumen) {
                $('#file_dokumen_append').html(`<a href="storage/${file_dokumen}"
                            target="_blank"
                            class="btn btn-outline-primary mb-3">Lihat File</a>
                `);
            }

            $('#modalSideEditChild').modal('show');
        });

        $(document).on('click', '.btn-edit-parent', function() {
            const item = $(this).data('item');
            $('#form_edit_parent').attr('action', '/data-arsip/' + item['id']);

            $(".modal-body #parent_kode_klasifikasi").val(item['kode_klasifikasi']);
            $(".modal-body #parent_uraian").val(item['uraian']);
            $(".modal-body #parent_tanggal_validasi").val(item['tanggal_validasi']);
            $(".modal-body #parent_jumlah_satuan_item").val(item['jumlah_satuan_item']);
            $(".modal-body #parent_keterangan").val(item['keterangan']);
            $(".modal-body #parent_no_spm").val(item['no_spm']);
            $(".modal-body #parent_no_sp2d").val(item['no_sp2d']);
            $(".modal-body #parent_no_spp").val(item['no_surat']);
            $(".modal-body #parent_nominal").val(item['nominal']);
            $(".modal-body #parent_skpd").val(item['skpd']);
            $(".modal-body #parent_nwp").val(item['nwp']);
            $(".modal-body #parent_pejabat_penandatangan").val(item['pejabat_penandatangan']);
            $(".modal-body #parent_unit_pengolah").val(item['unit_pengolah']);
            $(".modal-body #parent_kurun_waktu").val(item['kurun_waktu']);
            $(".modal-body #parent_jumlah_satuan_berkas").val(item['jumlah_satuan_berkas']);
            $(".modal-body #parent_tkt_perkemb").val(item['tkt_perkemb']);
            $(".modal-body #parent_no_box").val(item['no_box']);
            $('#modalSideEditParent').modal('show');
        });

        $(document).on('click', '.btn-add-parent', function() {
            const id = $(this).data('id');
            const subitem = $(this).data('subitem');
            console.log('subitem', subitem);
            $(".modal-body #parent_add_kode_klasifikasi").val(subitem['kode_klasifikasi']);
            $(".modal-body #parent_add_tanggal_surat").val(subitem['tanggal_surat']);
            $(".modal-body #parent_add_jumlah_satuan").val(subitem['jumlah_satuan']);
            $(".modal-body #parent_add_keterangan").val(subitem['keterangan']);
            $(".modal-body #parent_add_jenis_naskah_dinas").val(subitem['jenis_naskah_dinas']);
            $(".modal-body #parent_add_pejabat_penandatangan").val('PA/KPA');
            $(".modal-body #parent_add_unit_pengolah").val(subitem['unit_pengolah']);
            $(".modal-body #parent_add_kurun_waktu").val(subitem['kurun_waktu']);
            $(".modal-body #parent_add_no_box").val(subitem['no_box']);
            $(".modal-body #parent_add_tkt_perk").val(subitem['tkt_perk']);
            $(".modal-body #parent_add_dokumen_id").val(id);
            $('#modalSideAddParent').modal('show');
        });

        $('#addSection').click(function() {

            let section = `<div class="mb-3 position-relative form-group">
                            <label class="form-label text-primary fw-bold">Uraian</label>
                            <input type="text" class="form-control" name="uraian"
                                id="parent_uraian" required />
                            <label class="form-label text-primary fw-bold">No. Surat</label>
                            <input type="text" class="form-control" name="no_surat"
                                id="parent_no_surat" required />
                            <label class="form-label text-primary fw-bold">Tanggal Surat</label>
                            <input type="text" class="form-control" name="tanggal_surat"
                                id="parent_tanggal_surat" required />
                            </div>`

            $('#box').append(section);
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

    @if (session()->has('error'))
        <div class="position-fixed top-0 end-0 p-3" style="z-index: 5">
            <div class="toast bg-danger fade show" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header py-2">
                    <strong class="me-auto text-white">Informasi</strong>
                    <button type="button" class="btn-close text-white" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body text-white"> {{ session()->get('error') }}</div>
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
        @foreach ($test ?? [] as $tr)
            {{ $tr }}
        @endforeach

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
                            <button class="btn btn-outline-primary btn-icon btn-icon-start w-100 w-md-auto mt-3 mt-sm-0"
                                data-bs-toggle="dropdown" type="button" data-bs-offset="0,3">
                                <i data-acorn-icon="cloud-download"></i>
                                <span>Import Data</span>
                            </button>
                            <div class="dropdown-menu shadow dropdown-menu-end">
                                <button class="dropdown-item" type="button" data-bs-toggle="modal"
                                    data-bs-target="#modalImport">Import Excel</button>
                                <button class="dropdown-item" type="button" data-bs-toggle="modal"
                                    data-bs-target="#modalTarikData">Tarik Data Monitoring</button>
                            </div>
                            <a href="{{ route('data-arsip.create') }}"
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
                                        <a target="_blank" href="data-arsip/export-excel/xlsx"
                                            class="dropdown-item export-excel">Export Daftar Arsip</a>
                                        <a target="_blank" href="detail-data-arsip/export-excel/xlsx"
                                            class="dropdown-item export-excel">Export Daftar Isi Berkas</a>
                                    </div>
                                </div>
                                <!-- Export Dropdown End -->
                                <!-- Length Start -->
                                <div class="dropdown-as-select d-inline-block datatable-length"
                                    data-datatable="#datatableRowsServerSide" data-childSelector="span">
                                    <button class="btn p-0 shadow" type="button" data-bs-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false" data-bs-offset="0,3">
                                        <span class="btn btn-foreground-alternate dropdown-toggle"
                                            data-bs-toggle="tooltip" data-bs-placement="top" data-bs-delay="0"
                                            title="Jumlah Data Per Halaman">
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
                                <thead style="position: sticky;top: 0">
                                    <tr>
                                        <th class="text-muted text-small text-uppercase" style="position: sticky;top: 0">
                                            No.</th>
                                        <th class="text-muted text-small text-uppercase" style="position: sticky;top: 0">
                                            Kode Klasifikasi</th>
                                        <th class="text-muted text-small text-uppercase" style="position: sticky;top: 0">
                                            Uraian</th>
                                        <th class="text-muted text-small text-uppercase" style="position: sticky;top: 0">
                                            Tanggal Validasi</th>
                                        <th class="text-muted text-small text-uppercase" style="position: sticky;top: 0">
                                            Jumlah Satuan Item</th>
                                        <th class="text-muted text-small text-uppercase" style="position: sticky;top: 0">
                                            Keterangan</th>
                                        <th style="width: 300px !important" class="text-muted text-small text-uppercase">
                                            No. SP2D</th>
                                        <th class="text-muted text-small text-uppercase">Nominal</th>
                                        <th class="text-muted text-small text-uppercase">Kode Akun Jenis</th>
                                        <th class="text-muted text-small text-uppercase">Nama Akun Jenis</th>
                                        <th class="text-muted text-small text-uppercase">SKPD/Unit SKPD</th>
                                        <th class="text-muted text-small text-uppercase">NWP</th>
                                        <th class="text-muted text-small text-uppercase">Pejabat Penandatangan</th>
                                        <th class="text-muted text-small text-uppercase">Unit Pengolah</th>
                                        <th class="text-muted text-small text-uppercase">Kurun Waktu</th>
                                        <th class="text-muted text-small text-uppercase">Jumlah Satuan Berkas</th>
                                        <th class="text-muted text-small text-uppercase">Tingkat Perkembangan</th>
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
                                                {{ $item->kode_klasifikasi ? $item->kode_klasifikasi : '-' }}
                                            </td>
                                            <td style="height: 42px !important" class="py-2 bg-primary text-white">
                                                {{ $item->uraian ? $item->uraian : '-' }}

                                            </td>
                                            <td style="height: 42px !important" class="py-2 bg-primary text-white">
                                                {{ $item->tanggal_validasi ? $item->tanggal_validasi : '-' }}
                                            </td>
                                            <td style="height: 42px !important" class="py-2 bg-primary text-white">
                                                {{ $item->jumlah_satuan_item ? $item->jumlah_satuan_item : '-' }}
                                            </td>
                                            <td style="height: 42px !important" class="py-2 bg-primary text-white">
                                                {{ $item->keterangan ? $item->keterangan : '-' }}
                                            </td>
                                            <td style="height: 42px !important;" class="py-2 bg-primary text-white">
                                                {{ $item->no_sp2d ? $item->no_sp2d : '-' }}
                                            </td>
                                            <td style="height: 42px !important" class="py-2 bg-primary text-white">
                                                Rp.<span
                                                    class="text-primary">__</span>{{ number_format($item->nominal, 0, ',', '.') }},-
                                            </td>
                                            <td style="height: 42px !important;" class="py-2 bg-primary text-white">
                                                {{ $item->akunJenis ? $item->akunJenis->kode_akun : '-' }}
                                            </td>
                                            <td style="height: 42px !important;" class="py-2 bg-primary text-white">
                                                {{ $item->akunJenis ? $item->akunJenis->nama_akun : '-' }}
                                            </td>
                                            <td style="height: 42px !important" class="py-2 bg-primary text-white">
                                                {{ $item->skpd ? $item->skpd : '-' }}
                                            </td>
                                            <td style="height: 42px !important" class="py-2 bg-primary text-white">
                                                {{ $item->nwp ? $item->nwp : '-' }}
                                            </td>
                                            <td style="height: 42px !important" class="py-2 bg-primary text-white">
                                                {{ $item->pejabat_penandatangan ? $item->pejabat_penandatangan : '-' }}
                                            </td>
                                            <td style="height: 42px !important" class="py-2 bg-primary text-white">
                                                {{ $item->unit_pengolah ? $item->unit_pengolah : '-' }}
                                            </td>
                                            <td style="height: 42px !important" class="py-2 bg-primary text-white">
                                                {{ $item->kurun_waktu ? $item->kurun_waktu : '-' }}
                                            </td>
                                            <td style="height: 42px !important" class="py-2 bg-primary text-white">
                                                {{ $item->jumlah_satuan_berkas ? $item->jumlah_satuan_berkas : '-' }}
                                                Berkas
                                            </td>
                                            <td style="height: 42px !important" class="py-2 bg-primary text-white">
                                                {{ $item->tkt_perkemb ? $item->tkt_perkemb : '-' }}
                                            </td>
                                            <td style="height: 42px !important" class="py-2 bg-primary text-white">
                                                {{ $item->no_box ? $item->no_box : '-' }}
                                            </td>
                                            <td style="height: 42px !important" class="py-2 bg-primary text-white">
                                                {{ $item->status ? $item->status : '-' }}
                                            </td>
                                            <td style="height: 42px !important" class="py-2 bg-primary text-white">
                                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">

                                                    <a href="{{ route('data-arsip.show', $item->id) }}"
                                                        class="btn btn-icon btn-icon-only btn-sm btn-outline-secondary"
                                                        type="button">
                                                        <i data-acorn-icon="info-circle"></i>
                                                    </a>

                                                    <button type="button" data-item="{{ $item }}"
                                                        class="btn btn-icon btn-icon-only btn-sm btn-warning btn-edit-parent">
                                                        <i data-acorn-icon="edit"></i>
                                                    </button>

                                                    <button type="button" data-id="{{ $item->id }}"
                                                        data-subitem="{{ $item->detailDokumen[0] ?? null }}"
                                                        class="btn btn-icon btn-icon-only btn-sm btn-info btn-add-parent">
                                                        <i data-acorn-icon="plus"></i>
                                                    </button>

                                                    <button
                                                        class="btn btn-icon btn-icon-only btn-sm btn-white modal-verifikasi"
                                                        type="button" data-id="{{ $item->id }}"
                                                        data-status="Terverifikasi" data-bs-toggle="modal"
                                                        data-bs-target="#modalVerifikasi">
                                                        <i data-acorn-icon="check"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-muted text-small text-uppercase">No.</th>
                                            <th class="text-muted text-small text-uppercase">Kode Klasifikasi</th>
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
                                            <th colspan="7" class="text-muted text-small text-uppercase">Tingkat
                                                Perkembangan</th>
                                            <th width="10%" class="empty">&nbsp;</th>
                                        </tr>

                                        <tr>
                                            @foreach ($item->detailDokumen ?? [] as $subitem)
                                                <td style="height: 42px !important" class="empty py-2">
                                                    {{ $loop->index + 1 }}.</td>
                                                <td style="height: 42px !important" class="py-2">
                                                    {{ $subitem->kode_klasifikasi ? $subitem->kode_klasifikasi : '-' }}
                                                </td>
                                                <td style="height: 42px !important" class="py-2">
                                                    {{ $subitem->uraian ? $subitem->uraian : '-' }}
                                                </td>
                                                <td style="height: 42px !important" class="py-2">
                                                    {{ $subitem->tanggal_surat ? $subitem->tanggal_surat : '-' }}
                                                </td>
                                                <td style="height: 42px !important" class="py-2">
                                                    {{ $subitem->jumlah_satuan ? $subitem->jumlah_satuan : '-' }}
                                                </td>
                                                <td style="height: 42px !important" class="py-2">
                                                    {{ $subitem->keterangan ? $subitem->keterangan : '-' }}
                                                </td>
                                                <td style="height: 42px !important" class="py-2">
                                                    {{ $subitem->jenis_naskah_dinas ? $subitem->jenis_naskah_dinas : '-' }}
                                                </td>
                                                <td style="height: 42px !important;" class="py-2">
                                                    {{ $subitem->no_surat ? $subitem->no_surat : '-' }}
                                                </td>
                                                <td style="height: 42px !important" class="py-2">
                                                    {{ $subitem->pejabat_penandatangan ? $subitem->pejabat_penandatangan : '-' }}
                                                </td>
                                                <td style="height: 42px !important" class="py-2">
                                                    {{ $subitem->unit_pengolah ? $subitem->unit_pengolah : '-' }}
                                                </td>
                                                <td style="height: 42px !important" class="py-2">
                                                    {{ $subitem->kurun_waktu ? $subitem->kurun_waktu : '-' }}
                                                </td>
                                                <td style="height: 42px !important" class="py-2">
                                                    {{ $subitem->no_box ? $subitem->no_box : '-' }}
                                                </td>
                                                <td colspan="7" style="height: 42px !important" class="py-2">
                                                    {{ $subitem->tkt_perk ? $subitem->tkt_perk : '-' }}
                                                </td>
                                                <td style="height: 42px !important" class="py-2">
                                                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                                        <button type="button" data-item="{{ $item }}"
                                                            data-subitem="{{ $subitem }}"
                                                            data-index="{{ $loop->index + 1 }}"
                                                            class="btn btn-icon btn-icon-only btn-sm btn-outline-warning btn-edit-child">
                                                            <i data-acorn-icon="edit"></i>
                                                        </button>


                                                        {{-- <form id="delete_{{ $item->id }}_{{ $subitem->id }}"
                                                            action="/detail-data-arsip/{{ $subitem->id }}"
                                                            method="POST" class="d-inline">
                                                            @method('delete')
                                                            @csrf
                                                            <button type="button"
                                                                class="btn btn-icon btn-icon-only btn-sm btn-outline-danger modal-hapus"
                                                                data-id="delete_{{ $item->id }}_{{ $subitem->id }}"
                                                                data-bs-toggle="modal" data-bs-target="#modalHapus"><i
                                                                    data-acorn-icon="bin"></i></button>
                                                        </form> --}}

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
            <form method="POST" action="/data-arsip/import-excel" enctype="multipart/form-data">
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

    <!-- Modal Tarik Data -->
    <div class="modal fade" id="modalTarikData" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="/import-monitoring" enctype="multipart/form-data">
                <div class="modal-content">
                    <div class="modal-header py-3">
                        <h5 class="modal-title" id="exampleModalLabelDefault">Tarik Data Monitoring</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body py-3">
                        {{ csrf_field() }}
                        <div class="mb-3 position-relative form-group">
                            <label class="form-label text-primary fw-bold">Tahun</label>
                            <select name="tahun" class="form-select select2" required>
                                <option value="">Pilih Tahun</option>
                                @foreach ($yearsOptions ?? [] as $item)
                                    <option value="{{ $item['id'] }}">{{ $item['name'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3 position-relative form-group">
                            <label class="form-label text-primary fw-bold">Bulan</label>
                            <select name="bulan" class="form-select select2" required>
                                <option value="">Pilih Bulan</option>
                                @foreach ($monthsOptions ?? [] as $item)
                                    <option value="{{ $item['id'] }}">{{ $item['name'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3 position-relative form-group">
                            <label class="form-label text-primary fw-bold">Akun Jenis</label>
                            <select multiple="multiple" name="akun_jenis[]" class="form-select select2"
                                placeholder="Pilih Akun Jenis">
                                @foreach ($akunJenisOptions ?? [] as $item)
                                    <option value="{{ $item['kode_akun'] }}">{{ $item['kode_akun'] }} -
                                        {{ $item['nama_akun'] }}</option>
                                @endforeach
                            </select>
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

    <!-- Modal Side Edit Child -->
    <div class="modal modal-right fade" id="modalSideEditChild" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <form id="form_edit_child" class="tooltip-label-end edit-form" novalidate method="POST"
            enctype="multipart/form-data">
            @method('put')
            @csrf
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header pt-4 pb-3">
                        <h5 class="modal-title" id="staticBackdropLabel">Edit Data</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="child_dokumen_id" name="dokumen_id" />

                        <div class="mb-3 position-relative form-group">
                            <label class="form-label text-primary fw-bold">Kode Klasifikasi</label>
                            <input type="text" class="form-control" name="kode_klasifikasi"
                                id="child_kode_klasifikasi" required disabled />
                        </div>

                        <div class="mb-3 position-relative form-group">
                            <label class="form-label text-primary fw-bold">Uraian</label>
                            <textarea class="form-control" name="uraian" id="child_uraian" required></textarea>
                        </div>

                        <div class="mb-3 position-relative form-group">
                            <label class="form-label text-primary fw-bold">Tanggal Surat</label>
                            <input type="text" class="form-control datepicker" name="tanggal_surat"
                                id="child_tanggal_surat" required />
                        </div>

                        <div class="mb-3 position-relative form-group">
                            <label class="form-label text-primary fw-bold">Jumlah Satuan</label>
                            <input type="text" class="form-control" name="jumlah_satuan" id="child_jumlah_satuan"
                                required disabled />
                        </div>

                        <div class="mb-3 position-relative form-group">
                            <label class="form-label text-primary fw-bold">Keterangan</label>
                            <select name="keterangan" id="child_keterangan" class="form-select" disabled required>
                                <option value="Tekstual">Tekstual</option>
                                <option value="Digital">Digital</option>
                            </select>
                        </div>

                        <div class="mb-3 position-relative form-group">
                            <label class="form-label text-primary fw-bold">Jenis Naskah Dinas</label>
                            <input type="text" class="form-control" name="jenis_naskah_dinas"
                                id="child_jenis_naskah_dinas" disabled required />
                        </div>

                        <div class="mb-3 position-relative form-group">
                            <label class="form-label text-primary fw-bold">No. Surat</label>
                            <input type="text" class="form-control" name="no_surat" id="child_no_surat" required />
                        </div>

                        <div class="mb-3 position-relative form-group">
                            <label class="form-label text-primary fw-bold">Pejabat Penandatangan</label>
                            <input type="text" class="form-control" name="pejabat_penandatangan"
                                id="child_pejabat_penandatangan" disabled required />
                        </div>

                        <div class="mb-3 position-relative form-group">
                            <label class="form-label text-primary fw-bold">Unit Pengolah</label>
                            <input type="text" class="form-control" name="unit_pengolah" id="child_unit_pengolah"
                                required disabled />
                        </div>

                        <div class="mb-3 position-relative form-group">
                            <label class="form-label text-primary fw-bold">Kurun Waktu</label>
                            <input type="number" class="form-control" name="kurun_waktu" id="child_kurun_waktu"
                                required disabled />
                        </div>

                        <div class="mb-3 position-relative form-group">
                            <label class="form-label text-primary fw-bold">No. Box</label>
                            <input type="text" class="form-control" name="no_box" id="child_no_box" disabled
                                required />
                        </div>

                        <div class="mb-3 position-relative form-group">
                            <label class="form-label text-primary fw-bold">Tingkat Perkembangan</label>
                            <select name="tkt_perk" id="child_tkt_perk" class="form-select" required>
                                <option value="Asli">Asli</option>
                                <option value="Tembusan">Tembusan</option>
                            </select>
                        </div>

                        <div class="mb-3 position-relative form-group">
                            <label class="form-label text-primary fw-bold">File Dokumen</label>
                            <div id="file_dokumen_append"></div>

                            <input class="form-control" type="file" name="file_dokumen" />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#modalEditChild">Simpan</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Modal Edit Child Confirm -->
    <div class="modal fade" id="modalEditChild" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header pt-4 pb-3" style="border-bottom: none !important">
                    <h5 class="modal-title" id="staticBackdropLabel">Simpan Data?</h5>
                </div>
                <div class="modal-footer pt-0 pb-4" style="border-top: none !important">
                    <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" id="submitBtnEditChild" class="btn btn-primary">Ya</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Side Edit Parent -->
    <div class="modal modal-right fade" id="modalSideEditParent" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <form id="form_edit_parent" class="tooltip-label-end edit-form" novalidate method="POST"
            enctype="multipart/form-data">
            @method('put')
            @csrf
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header pt-4 pb-3">
                        <h5 class="modal-title" id="staticBackdropLabel">Edit Data</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3 position-relative form-group">
                            <label class="form-label text-primary fw-bold">Kode
                                Klasifikasi</label>
                            <input type="text" class="form-control" name="kode_klasifikasi"
                                id="parent_kode_klasifikasi" required disabled />
                        </div>

                        <div class="mb-3 position-relative form-group">

                            <label class="form-label text-primary fw-bold">Uraian</label>
                            <textarea class="form-control" name="uraian" id="parent_uraian" rows="6" disabled required></textarea>
                        </div>

                        <div class="mb-3 position-relative form-group">
                            <label class="form-label text-primary fw-bold">Tanggal
                                Validasi</label>
                            <input type="text" class="form-control datepicker" name="tanggal_validasi"
                                id="parent_tanggal_validasi" required disabled />
                        </div>

                        <div class="mb-3 position-relative form-group">
                            <label class="form-label text-primary fw-bold">Jumlah
                                Satuan Item</label>
                            <input type="text" class="form-control" name="jumlah_satuan_item"
                                id="parent_jumlah_satuan_item" required />
                        </div>

                        <div class="mb-3 position-relative form-group">
                            <label class="form-label text-primary fw-bold">Keterangan</label>
                            <select name="keterangan" id="parent_keterangan" class="form-select" required>
                                <option value="Tekstual">Tekstual</option>
                                <option value="Digital">Digital</option>
                            </select>
                        </div>

                        <div class="mb-3 position-relative form-group">
                            <label class="form-label text-primary fw-bold">No.
                                SP2D</label>
                            <input type="text" class="form-control" name="no_sp2d" id="parent_no_sp2d" required
                                disabled />
                        </div>

                        <div class="mb-3 position-relative form-group">
                            <label class="form-label text-primary fw-bold">Nominal</label>
                            <input type="number" class="form-control" name="nominal" id="parent_nominal" required
                                disabled />
                        </div>

                        <div class="mb-3 position-relative form-group">
                            <label class="form-label text-primary fw-bold">SKPD</label>
                            <input type="text" class="form-control" name="skpd" id="parent_skpd" required
                                disabled />
                        </div>

                        <div class="mb-3 position-relative form-group">
                            <label class="form-label text-primary fw-bold">NWP</label>
                            <input type="text" class="form-control" name="nwp" id="parent_nwp" required
                                disabled />
                        </div>

                        <div class="mb-3 position-relative form-group">
                            <label class="form-label text-primary fw-bold">Pejabat
                                Penandatangan</label>
                            <select name="pejabat_penandatangan" id="parent_pejabat_penandatangan" class="form-select"
                                required>
                                <option value="Kuasa BUD">Kuasa BUD</option>
                                <option value="Plt. Kuasa BUD">Plt. Kuasa BUD</option>
                                <option value="Plh. Kuasa BUD">Plh. Kuasa BUD</option>
                            </select>
                        </div>

                        <div class="mb-3 position-relative form-group">
                            <label class="form-label text-primary fw-bold">Unit
                                Pengolah</label>
                            <select name="unit_pengolah" id="parent_unit_pengolah" class="form-select" required>
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
                            <input type="number" class="form-control" name="kurun_waktu" id="parent_kurun_waktu"
                                required disabled />
                        </div>

                        <div class="mb-3 position-relative form-group">
                            <label class="form-label text-primary fw-bold">Jumlah Satuan
                                Berkas</label>
                            <input type="number" class="form-control" name="jumlah_satuan_berkas"
                                id="parent_jumlah_satuan_berkas" required />
                        </div>

                        <div class="mb-3 position-relative form-group">
                            <label class="form-label text-primary fw-bold">Tingkat Perkembangan</label>
                            <select name="tkt_perkemb" id="parent_tkt_perkemb" class="form-select" required>
                                <option value="Asli">Asli</option>
                                <option value="Tembusan">Tembusan</option>
                            </select>
                        </div>

                        <div class="mb-3 position-relative form-group">
                            <label class="form-label text-primary fw-bold">No.
                                Box</label>
                            <input type="text" class="form-control" name="no_box" id="parent_no_box" disabled
                                required />
                        </div>
                        <div id="box">

                        </div>

                        <!--<div class="col text-end">
                                            <button id="addSection" class="btn btn-secondary me-3" type="button">Tambah
                                                Kegiatan</button>

                                        </div>-->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#modalEditParent">Simpan</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Modal Edit Parent Confirm -->
    <div class="modal fade" id="modalEditParent" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header pt-4 pb-3" style="border-bottom: none !important">
                    <h5 class="modal-title" id="staticBackdropLabel">Simpan Data?</h5>
                </div>
                <div class="modal-body d-none">
                    <input type="hidden" name="form_edit_parent_id" id="form_edit_parent_id" value="" />
                </div>
                <div class="modal-footer pt-0 pb-4" style="border-top: none !important">
                    <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" id="submitBtnEditParent" class="btn btn-primary">Ya</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Side Add Parent -->
    <div class="modal modal-right fade" id="modalSideAddParent" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <form id="form_add_parent" action="/detail-data-arsip" class="tooltip-label-end edit-form" novalidate
            method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header pt-4 pb-3">
                        <h5 class="modal-title" id="staticBackdropLabel">Tambah Data</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="parent_add_dokumen_id" name="dokumen_id">

                        <div class="mb-3 position-relative form-group">
                            <label class="form-label text-primary fw-bold">Kode
                                Klasifikasi</label>
                            <input type="text" class="form-control" id="parent_add_kode_klasifikasi"
                                name="kode_klasifikasi" required />
                        </div>

                        <div class="mb-3 position-relative form-group">
                            <label class="form-label text-primary fw-bold">Uraian</label>
                            <textarea class="form-control" id="parent_add_uraian" name="uraian" rows="6" required></textarea>
                        </div>

                        <div class="mb-3 position-relative form-group">
                            <label class="form-label text-primary fw-bold">Tanggal
                                Surat</label>
                            <input type="text" class="form-control datepicker" id="parent_add_tanggal_surat"
                                name="tanggal_surat" required />
                        </div>

                        <div class="mb-3 position-relative form-group">
                            <label class="form-label text-primary fw-bold">Jumlah
                                Satuan</label>
                            <input type="text" class="form-control" id="parent_add_jumlah_satuan"
                                name="jumlah_satuan" required />
                        </div>

                        <div class="mb-3 position-relative form-group">
                            <label class="form-label text-primary fw-bold">Keterangan</label>
                            <select id="parent_add_keterangan" name="keterangan" class="form-select" required>
                                <option value="Tekstual">Tekstual</option>
                                <option value="Digital">Digital</option>
                            </select>
                        </div>

                        <div class="mb-3 position-relative form-group">
                            <label class="form-label text-primary fw-bold">Jenis Naskah
                                Dinas</label>
                            <input type="text" class="form-control" id="parent_add_jenis_naskah_dinas"
                                name="jenis_naskah_dinas" required />
                        </div>

                        <div class="mb-3 position-relative form-group">
                            <label class="form-label text-primary fw-bold">No.
                                Surat</label>
                            <input type="text" class="form-control" id="parent_add_no_surat" name="no_surat"
                                required />
                        </div>

                        <div class="mb-3 position-relative form-group">
                            <label class="form-label text-primary fw-bold">Pejabat
                                Penandatangan</label>
                            <!-- <select id="parent_add_pejabat_penandatangan" name="pejabat_penandatangan"
                                            class="form-select" required>
                                            <option selected value="PA/KPA">PA/KPA</option>
                                        </select> -->
                            <input type="text" class="form-control" id="parent_add_pejabat_penandatangan"
                                name="pejabat_penandatangan" required />
                        </div>

                        <div class="mb-3 position-relative form-group">
                            <label class="form-label text-primary fw-bold">Unit
                                Pengolah</label>
                            <input type="text" class="form-control" id="parent_add_unit_pengolah"
                                name="unit_pengolah" required />
                        </div>

                        <div class="mb-3 position-relative form-group">
                            <label class="form-label text-primary fw-bold">Kurun
                                Waktu</label>
                            <input type="number" class="form-control" id="parent_add_kurun_waktu" name="kurun_waktu"
                                required />
                        </div>

                        <div class="mb-3 position-relative form-group">
                            <label class="form-label text-primary fw-bold">No.
                                Box</label>
                            <input type="text" class="form-control" id="parent_add_no_box" name="no_box" disabled
                                required />
                        </div>

                        <div class="mb-3 position-relative form-group">
                            <label class="form-label text-primary fw-bold">Tingkat Perkembangan</label>
                            <select id="parent_add_tkt_perk" name="tkt_perk" class="form-select" required>
                                <option value="Asli">Asli</option>
                                <option value="Tembusan">Tembusan</option>
                            </select>
                        </div>

                        <div class="mb-3 position-relative form-group">
                            <label class="form-label text-primary fw-bold">File
                                Dokumen</label>
                            <input class="form-control" type="file" name="file_dokumen" />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#modalAddParent">Simpan</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Modal Add Parent Confirm-->
    <div class="modal fade" id="modalAddParent" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header pt-4 pb-3" style="border-bottom: none !important">
                    <h5 class="modal-title" id="staticBackdropLabel">Simpan Data?</h5>
                </div>
                <div class="modal-footer pt-0 pb-4" style="border-top: none !important">
                    <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" id="submitBtnAddParent" class="btn btn-primary">Ya</button>
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

    <!-- Modal Verifikasi -->
    <div class="modal fade" id="modalVerifikasi" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="/data-arsip/verifikasi_dokumen">
                @csrf
                <div class="modal-content">
                    <div class="modal-header pt-4 pb-3" style="border-bottom: none !important">
                        <h5 class="modal-title" id="staticBackdropLabel">Verifikasi Data?</h5>
                    </div>
                    <div class="modal-body d-none">
                        <input type="hidden" name="id" id="form_verifikasi_id" value="" />
                        <input type="hidden" name="status" id="form_verifikasi_status" value="" />
                    </div>
                    <div class="modal-footer pt-0 pb-4" style="border-top: none !important">
                        <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Ya</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection

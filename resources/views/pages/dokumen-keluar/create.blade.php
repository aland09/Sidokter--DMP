@php
    $html_tag_data = ['override' => '{ "attributes" : { "placement" : "vertical", "layout":"fluid" }, "showSettings" : false }'];
    $title = 'Tambah Data';
    $description = 'Halaman Tambah Data Dokumen Keluar';
    $breadcrumbs = ['/' => 'Beranda', '/dokumen-keluar' => 'Daftar Dokumen Keluar', '/dokumen-keluar/create' => 'Tambah Data'];
@endphp
@extends('layout', ['html_tag_data' => $html_tag_data, 'title' => $title, 'description' => $description])

@section('css')
    <link rel="stylesheet" href="/css/vendor/select2.min.css" />
    <link rel="stylesheet" href="/css/vendor/select2-bootstrap4.min.css" />
@endsection

@section('js_vendor')
    <script src="/js/cs/scrollspy.js"></script>
    <script src="/js/vendor/jquery.validate/jquery.validate.min.js"></script>
    <script src="/js/vendor/select2.full.min.js"></script>
    <script src="/js/vendor/datepicker/bootstrap-datepicker.min.js"></script>
    <script src="/js/vendor/datepicker/locales/bootstrap-datepicker.es.min.js"></script>
@endsection

@section('js_page')
    <script src="/js/forms/validation.js"></script>
    <script src="/js/forms/controls.select2.js"></script>
    <script src="/js/forms/controls.datepicker.js"></script>
    <script>
        const submitBtn = document.getElementById('submitBtn');
        $(submitBtn).click(function() {
            $('#modalDialog').modal('hide');
            $('#form').submit();
        });
    </script>
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
                    <!-- tooltip-label-end inputs should be wrapped in form-group class -->
                    <form id="form" class="tooltip-label-end" novalidate action="/dokumen-keluar" method="POST">
                        @csrf
                        <div class="mb-3 position-relative form-group">
                            <label class="form-label text-primary fw-bold">Berkas</label>
                            <select name="dokumen_id" class="form-select select2" required>
                                <option value="">Pilih Berkas</option>
                                @foreach ($listDokumen ?? [] as $item)
                                    <option value="{{ $item->id }}">{{ $item->no_sp2d }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3 position-relative form-group">
                            <label class="form-label text-primary fw-bold">Nama Peminjam</label>
                            <input type="text" class="form-control" name="nama_peminjam" required />
                        </div>

                        <div class="mb-3 position-relative form-group">
                            <label class="form-label text-primary fw-bold">Tanggal</label>
                            <input type="text" class="form-control datepicker" name="tanggal_peminjaman" required />
                        </div>

                        <div class="mb-3 position-relative form-group">
                            <label class="form-label text-primary fw-bold">Instansi</label>
                            <input type="text" class="form-control" name="instansi" required />
                        </div>

                        <div class="mb-3 position-relative form-group">
                            <label class="form-label text-primary fw-bold">Tujuan</label>
                            <textarea class="form-control" name="tujuan" rows="5" required></textarea>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-5">
                            <a href="{{ route('dokumen-keluar.index') }}" class="btn btn-outline-primary me-md-2">Batal</a>
                            <button type="button" id="confirmBtn" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#modalDialog">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
        <!-- Content End -->
    </div>


    <!-- Modal -->
    <div class="modal fade" id="modalDialog" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header pt-4 pb-3" style="border-bottom: none !important">
                    <h5 class="modal-title" id="staticBackdropLabel">Simpan Data?</h5>
                </div>
                <div class="modal-footer pt-0 pb-4" style="border-top: none !important">
                    <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" id="submitBtn" class="btn btn-primary">Ya</button>
                </div>
            </div>
        </div>
    </div>
@endsection

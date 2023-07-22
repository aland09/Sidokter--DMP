@php
    $html_tag_data = ['override' => '{ "attributes" : { "placement" : "vertical", "layout":"fluid" }, "showSettings" : false }'];
    $title = 'Tambah Data';
    $description = 'Halaman Tambah Data Jenis Belanja';
    $breadcrumbs = ['/' => 'Beranda', '/regulasi' => 'Daftar Regulasi', '/regulasi/create' => 'Tambah Data'];
@endphp
@extends('layout', ['html_tag_data' => $html_tag_data, 'title' => $title, 'description' => $description])

@section('css')
@endsection

@section('js_vendor')
    <script src="/js/cs/scrollspy.js"></script>
    <script src="/js/vendor/jquery.validate/jquery.validate.min.js"></script>
@endsection

@section('js_page')
    <script src="/js/forms/validation.js"></script>
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
                    <form id="form" class="tooltip-label-end" novalidate action="/regulasi" method="POST">
                        @csrf
                        <div class="mb-3 position-relative form-group">
                            <label class="form-label text-primary fw-bold">Nama Regulasi</label>
                            <input type="text" class="form-control" name="name" required />
                        </div>

                        <div class="mb-3 position-relative form-group">
                            <label class="form-label text-primary fw-bold">Deskripsi</label>
                            <textarea type="text" class="form-control" name="deskripsi" required ></textarea>
                        </div>

                        <div class="mb-3 position-relative form-group">
                            <label class="form-label text-primary fw-bold">Upload File Regulasi</label>
                            <input type="file" class="form-control" name="file_regulasi" required />
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-5">
                            <a href="{{ route('regulasi.index') }}" class="btn btn-outline-primary me-md-2">Batal</a>
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

@php
    $html_tag_data = ['override' => '{ "attributes" : { "placement" : "vertical", "layout":"fluid" }, "showSettings" : false }'];
    $title = 'Edit Data';
    $description = 'Halaman Edit Peran';
    $breadcrumbs = [route('dashboard.index') => 'Beranda', route('roles.index') => 'Daftar Data Pengguna',  route('roles.index') . '/' . $roles->id => 'Edit Data'];
@endphp
@extends('layout', ['html_tag_data' => $html_tag_data, 'title' => $title, 'description' => $description])

@section('css')
@endsection

@section('js_vendor')
    <script src="{{ asset('js/cs/scrollspy.js') }}"></script>
    <script src="{{ asset('js/vendor/jquery.validate/jquery.validate.min.js') }}"></script>
@endsection


@section('js_page')
    <script src="{{ asset('js/forms/validation.js') }}"></script>
    <script>
        $(document).ready(function() {
            const submitBtn = document.getElementById('submitBtn');
            $(submitBtn).click(function() {
                $('#modalDialog').modal('hide');
                $('#form').submit();
            });

            $('[name="all_permission"]').on('click', function() {

                if ($(this).is(':checked')) {
                    $.each($('.permission'), function() {
                        $(this).prop('checked', true);
                    });
                } else {
                    $.each($('.permission'), function() {
                        $(this).prop('checked', false);
                    });
                }
            });
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
                    <form id="form" class="tooltip-label-end" novalidate action="{{ route('roles.update', ['role' => $roles->id]) }}"
                        method="POST">
                        @method('put')
                        @csrf
                        <div class="mb-3 position-relative form-group mb-5">
                            <label class="form-label text-primary fw-bold">Nama</label>
                            <input type="text" class="form-control" name="name" value="{{ $roles->name }}"
                                required />
                        </div>

                        <div class="d-flex flex-row justify-content-between form-label text-primary fw-bold">
                            <span>Tetapkan Hak Akses</span>
                            <div>
                                <span class="me-2">Semua Akses</span>
                                <input name="all_permission" type="checkbox" class="form-check-input" />
                            </div>
                        </div>
                        <div class="row row-cols-1 g-2">
                            @foreach ($permissionsList as $permission)
                                <div class="col">
                                    <div class="card border rounded shadow-none">
                                        <div class="card-body pt-3 pb-3">
                                            <label class="form-check custom-icon mb-0 checked-opacity-100">
                                                <input type="checkbox" class="form-check-input permission"
                                                    name="permission[{{ $permission->name }}]"
                                                    value="{{ $permission->name }}"
                                                    {{ in_array($permission->name, $rolePermissions) ? 'checked' : '' }} />
                                                <span class="form-check-label">
                                                    <span class="content opacity-50">
                                                        <span class="heading mb-1 lh-1-25">{{ $permission->name }}</span>
                                                        <span class="d-block text-small text-muted">Guard:
                                                            {{ $permission->guard_name }}</span>
                                                    </span>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-5">
                            <a href="{{ route('roles.index') }}" class="btn btn-outline-primary me-md-2">Batal</a>
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

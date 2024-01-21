@php
    $html_tag_data = ['override' => '{ "attributes" : { "placement" : "vertical", "layout":"fluid" }, "showSettings" : false }'];
    $title = 'Tambah Data';
    $description = 'Halaman Tambah Data Pemberkasan';
    $breadcrumbs = [route('dashboard.index') => 'Beranda',  route('data-arsip.index') => 'Data Pemberkasan', route('data-arsip.create') => 'Tambah Data'];
@endphp
@extends('layout', ['html_tag_data' => $html_tag_data, 'title' => $title, 'description' => $description])

@section('css')
@endsection

@section('js_vendor')
    <script src="/js/cs/scrollspy.js"></script>
    <script src="/js/vendor/jquery.validate/jquery.validate.min.js"></script>
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

            $('#berkas_arsip').on('change', function() {
                const dokumenId = $(this).val();
                const sp2d = $( "#berkas_arsip option:selected" ).text();

                $("#no_sp2d_dokumen").val(sp2d);

                let html = '';
                if (dokumenId) {
                    $.ajax({
                        url: '/get-berkas-arsip/' + dokumenId,
                        type: "GET",
                        data: {
                            "_token": "{{ csrf_token() }}"
                        },
                        dataType: "json",
                        success: function(data) {
                            if (data) {
                                let index = 1;
                                $.each(data, function(key, items) {
                                    index++;
                                    html +=
                                        `
                                        <div class="card mb-5">
                                            <div class="card-body">
                                                <input type="hidden" name="nilai_count[]" value="${index}" />

                                                    <div class="mb-3 position-relative form-group">
                                                        <label class="form-label text-primary fw-bold">Kode
                                                            Klasifikasi</label>
                                                        <input type="text" class="form-control"
                                                            name="kode_klasifikasi[]"
                                                            value="${items.kode_klasifikasi}" required />
                                                    </div>

                                                    <div class="mb-3 position-relative form-group">
                                                        <label
                                                            class="form-label text-primary fw-bold">Uraian</label>
                                                        <textarea class="form-control" name="uraian[]" required>${items.uraian}</textarea>
                                                    </div>

                                                    <div class="mb-3 position-relative form-group">
                                                        <label class="form-label text-primary fw-bold">Tanggal
                                                            Validasi</label>
                                                        <input type="text" class="form-control datepicker"
                                                            name="tanggal_validasi[]"
                                                            value="${items.tanggal_validasi}" required />
                                                    </div>

                                                    <div class="mb-3 position-relative form-group">
                                                        <label class="form-label text-primary fw-bold">Jumlah
                                                            Satuan Item</label>
                                                        <input type="text" class="form-control"
                                                            name="jumlah_satuan_item[]"
                                                            value="${items.jumlah_satuan_item}" required />
                                                    </div>

                                                    <div class="mb-3 position-relative form-group">
                                                        <label
                                                            class="form-label text-primary fw-bold">Keterangan</label>
                                                        <input type="text" class="form-control"
                                                            name="keterangan[]" value="${items.keterangan}"
                                                            required />
                                                    </div>

                                                    <div class="mb-3 position-relative form-group">
                                                        <label class="form-label text-primary fw-bold">No.
                                                            SPM</label>
                                                        <input type="text" class="form-control" name="no_spm[]"
                                                            value="${items.no_spm}" required />
                                                    </div>

                                                    <div class="mb-3 position-relative form-group">
                                                        <label class="form-label text-primary fw-bold">No.
                                                            SP2D</label>
                                                        <input type="text" class="form-control" name="no_sp2d[]"
                                                            value="${items.no_sp2d}" required />
                                                    </div>

                                                    <div class="mb-3 position-relative form-group">
                                                        <label
                                                            class="form-label text-primary fw-bold">Nominal</label>
                                                        <input type="number" class="form-control" name="nominal[]"
                                                            value="${items.nominal}" required />
                                                    </div>

                                                    <div class="mb-3 position-relative form-group">
                                                        <label class="form-label text-primary fw-bold">SKPD</label>
                                                        <input type="text" class="form-control" name="skpd[]"
                                                            value="${items.skpd}" required />
                                                    </div>

                                                    <div class="mb-3 position-relative form-group">
                                                        <label class="form-label text-primary fw-bold">Pejabat
                                                            Penandatangan</label>
                                                        <input type="text" class="form-control"
                                                            name="pejabat_penandatangan[]"
                                                            value="${items.pejabat_penandatangan}"
                                                            required />
                                                    </div>

                                                    <div class="mb-3 position-relative form-group">
                                                        <label class="form-label text-primary fw-bold">Unit
                                                            Pengolah</label>
                                                        <select name="unit_pengolah[]" class="form-select" required>
                                                            <option value="${items.unit_pengolah}">
                                                                </option>
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
                                                            name="kurun_waktu[]"
                                                            value="${items.kurun_waktu}" required />
                                                    </div>

                                                    <div class="mb-3 position-relative form-group">
                                                        <label class="form-label text-primary fw-bold">Jumlah
                                                            Satuan Berkas</label>
                                                        <input type="text" class="form-control"
                                                            name="jumlah_satuan_berkas[]"
                                                            value="${items.jumlah_satuan_berkas}"
                                                            required />
                                                    </div>

                                                    <div class="mb-3 position-relative form-group">
                                                        <label class="form-label text-primary fw-bold">Tingkat Perkembangan</label>
                                                        <input type="text" class="form-control"
                                                            name="tkt_perkemb[]"
                                                            value="${items.tkt_perkemb}" required />
                                                    </div>

                                                    <div class="mb-3 position-relative form-group">
                                                        <label class="form-label text-primary fw-bold">No.
                                                            Box</label>
                                                        <input type="text" class="form-control" name="no_box[]"
                                                            value="${items.no_box}" required />
                                                    </div>
                                                </div>
                                            </div>
                                    `;
                                });

                                $('#formArsip').empty().append(html);
                            } else {
                                html += `
                                    <div class="card">
                                        <div class="card-body">
                                            <p class="text-center py-5 my-5">Data Tidak Ditemukan</p>
                                        </div>
                                    </div>
                                `;
                                $('#formArsip').empty().append(html);
                            }
                        }
                    });
                } else {
                    html += `
                        <div class="card">
                            <div class="card-body">
                                <p class="text-center py-5 my-5">Data Tidak Ditemukan</p>
                            </div>
                        </div>
                    `;
                    $('#formArsip').empty().append(html);
                }
            });
        });
    </script>
@endsection


@section('content')
    @if (session()->has('message'))
        <div class="position-fixed top-0 end-0 p-3" style="z-index: 5">
            <div class="toast bg-danger fade show" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header py-2">
                    <strong class="me-auto text-white">Informasi</strong>
                    <button type="button" class="btn-close text-white" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body text-white"> {{ session()->get('message') }}</div>
            </div>
        </div>
    @endif

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
            <form id="form" class="tooltip-label-end" novalidate action="/data-arsip" method="POST">
                <div class="card mb-5">
                    <div class="card-body">
                        <h5 class="card-title text-primary">{{ $title }}</h5>
                        <p class="card-text mb-5">
                            {{ $description }}
                        </p>
                        <!-- tooltip-label-end inputs should be wrapped in form-group class -->

                        @csrf
                        <div class="mb-3 position-relative form-group">
                            <label class="form-label text-primary fw-bold">Berkas Arsip</label>
                            <input type="hidden" id="no_sp2d_dokumen" name="no_sp2d_dokumen">
                            <select id="berkas_arsip" class="form-select" required>
                                <option value="">Pilih Berkas Arsip</option>
                                @foreach ($listArsip ?? [] as $item)
                                    <option value="{{ $item['id'] }}">{{ $item['no_sp2d'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div id="formArsip"></div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-5">
                    <a href="{{ route('data-arsip.index') }}" class="btn btn-outline-primary me-md-2">Batal</a>
                    <button type="button" id="confirmBtn" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#modalDialog">Simpan</button>
                </div>
            </form>
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

@php
    $html_tag_data = ['override' => '{ "attributes" : { "placement" : "vertical", "layout":"fluid" }, "showSettings" : false }'];
    $title = 'Pemberkasan';
    $description = 'Halaman Pemberkasan';
    $breadcrumbs = [route('dashboard.index') => 'Beranda', route('data-arsip.index') => 'Pemberkasan'];
@endphp
@extends('layout', ['html_tag_data' => $html_tag_data, 'title' => $title, 'description' => $description])
@section('css')
    <link rel="stylesheet" href="{{ asset('css/vendor/datatables.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/vendor/bootstrap-datepicker3.standalone.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/vendor/select2.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/vendor/select2-bootstrap4.min.css') }}" />
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
    <script src="{{ asset('js/vendor/select2.full.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.worker.min.js"></script>
@endsection
@section('js_page')
    <script src="{{ asset('js/forms/validation.js') }}"></script>
    <script src="{{ asset('js/forms/controls.datepicker.js') }}"></script>
    <script src="{{ asset('js/forms/controls.select2.js') }}"></script>
    <script src="{{ asset('js/base/sticky-table.js') }}"></script>
    <script>
        var pages = 0;
        var keyword = '';
        var start_date_validate = '';
        var end_date_validate = '';
        var items = 10;


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
            });

            $('#btn-barcode').click(function() {
                $('#modalFormBarcode').modal('show');
                const year = $('#kurun_waktu').val();
                if (year) {
                    $.ajax({
                        url: '{{ url('get-no-box') }}/' + year,
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

            const submitBtnEditChild = document.getElementById('submitBtnEditChild');
            $(submitBtnEditChild).click(function() {
                $('#modalEditChild').modal('hide');
                submitEditChild();
            });

            const submitBtnEditParent = document.getElementById('submitBtnEditParent');
            $(submitBtnEditParent).click(function() {
                $('#modalEditParent').modal('hide');
                submitEditParent();
            });

            const submitBtnAddParent = document.getElementById('submitBtnAddParent');
            $(submitBtnAddParent).click(function() {
                $('#modalAddParent').modal('hide');
                submitAddParent();
            });

            const submitBtnVerifikasi = document.getElementById('submitBtnVerifikasi');
            $(submitBtnVerifikasi).click(function() {
                console.log('kulik submitBtnVerifikasi');
                submitVerifikasi();
            });



            $('#parent_add_pejabat_penandatangan_select').on('change', function(e) {
                let optionSelected = $("option:selected", this);
                let valueSelected = this.value;

                if (valueSelected === 'Lainnya') {
                    $('#parent_add_pejabat_penandatangan_select').addClass('d-none');
                    $('#parent_add_pejabat_penandatangan_input').removeClass('d-none');
                    $('#parent_add_pejabat_penandatangan_select').prop('disabled', true);
                    $('#parent_add_pejabat_penandatangan_input').prop('disabled', false);
                } else {
                    $('#parent_add_pejabat_penandatangan_select').removeClass('d-none');
                    $('#parent_add_pejabat_penandatangan_input').addClass('d-none');
                    $('#parent_add_pejabat_penandatangan_select').prop('disabled', false);
                    $('#parent_add_pejabat_penandatangan_input').prop('disabled', true);
                }
            });

            $('#parent_add_jenis_naskah_dinas_select').on('change', function(e) {
                let optionSelected = $("option:selected", this);
                let valueSelected = this.value;

                if (valueSelected === 'Lainnya') {
                    $('#parent_add_jenis_naskah_dinas_select').addClass('d-none');
                    $('#parent_add_jenis_naskah_dinas_input').removeClass('d-none');
                    $('#parent_add_jenis_naskah_dinas_select').prop('disabled', true);
                    $('#parent_add_jenis_naskah_dinas_input').prop('disabled', false);
                } else {
                    $('#parent_add_jenis_naskah_dinas_select').removeClass('d-none');
                    $('#parent_add_jenis_naskah_dinas_input').addClass('d-none');
                    $('#parent_add_jenis_naskah_dinas_select').prop('disabled', false);
                    $('#parent_add_jenis_naskah_dinas_input').prop('disabled', true);
                }
            });

            $('#child_pejabat_penandatangan_select').on('change', function(e) {
                let optionSelected = $("option:selected", this);
                let valueSelected = this.value

                if (valueSelected === 'Lainnya') {
                    $('#child_pejabat_penandatangan_select').addClass('d-none');
                    $('#child_pejabat_penandatangan_input').removeClass('d-none');
                    $('#child_pejabat_penandatangan_select').prop('disabled', true);
                    $('#child_pejabat_penandatangan_input').prop('disabled', false);
                } else {
                    $('#child_pejabat_penandatangan_select').removeClass('d-none');
                    $('#child_pejabat_penandatangan_input').addClass('d-none');
                    $('#child_pejabat_penandatangan_select').prop('disabled', false);
                    $('#child_pejabat_penandatangan_input').prop('disabled', true);
                }
            });

            $('#child_jenis_naskah_dinas_select').on('change', function(e) {
                let optionSelected = $("option:selected", this);
                let valueSelected = this.value

                if (valueSelected === 'Lainnya') {
                    $('#child_jenis_naskah_dinas_select').addClass('d-none');
                    $('#child_jenis_naskah_dinas_input').removeClass('d-none');
                    $('#child_jenis_naskah_dinas_select').prop('disabled', true);
                    $('#child_jenis_naskah_dinas_input').prop('disabled', false);
                } else {
                    $('#child_jenis_naskah_dinas_select').removeClass('d-none');
                    $('#child_jenis_naskah_dinas_input').addClass('d-none');
                    $('#child_jenis_naskah_dinas_select').prop('disabled', false);
                    $('#child_jenis_naskah_dinas_input').prop('disabled', true);
                }
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

            $("input[name='method_type']:radio").click(function() {
                const val = $(this).val();
                if (val === 'harian') {
                    $("#harianContainer").addClass("d-block");
                    $("#periodeContainer").addClass("d-none");
                    $("#harianContainer").removeClass("d-none");
                    $('#tahunImport').prop('required', false);
                    $('#bulanImport').prop('required', false);
                    $('#tanggalImport').prop('required', true);
                } else {
                    $("#periodeContainer").addClass("d-block");
                    $("#harianContainer").addClass("d-none");
                    $("#periodeContainer").removeClass("d-none");
                    $('#tahunImport').prop('required', true);
                    $('#bulanImport').prop('required', true);
                    $('#tanggalImport').prop('required', false);
                }
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

                    // NASKAH DINAS
                    $('.modal-body #child_jenis_naskah_dinas_select').empty();
                    if (subitem['jenis_naskah_dinas']) {
                        $('.modal-body #child_jenis_naskah_dinas_select').append(
                            '<option selected value="' +
                            subitem['jenis_naskah_dinas'] + '">' + subitem['jenis_naskah_dinas'] +
                            '</option>');
                    }
                    $('.modal-body #child_jenis_naskah_dinas_select').append(
                        '<option value="SPTJM,Ceklis SPM">SPTJM,Ceklis SPM</option>');
                    $('.modal-body #child_jenis_naskah_dinas_select').append(
                        '<option value="Pernyataan Verifikasi">Pernyataan Verifikasi</option>');
                    $('.modal-body #child_jenis_naskah_dinas_select').append(
                        '<option value="Ringkasan Kontrak">Ringkasan Kontrak</option>');
                    $('.modal-body #child_jenis_naskah_dinas_select').append(
                        '<option value="Lainnya">Lainnya</option>');

                    // PEJABAT PENANDATANGANAN
                    $('.modal-body #child_pejabat_penandatangan_select').empty();
                    if (subitem['pejabat_penandatangan']) {
                        $('.modal-body #child_pejabat_penandatangan_select').append(
                            '<option selected value="' +
                            subitem['pejabat_penandatangan'] + '">' + subitem['pejabat_penandatangan'] +
                            '</option>');
                    }
                    $('.modal-body #child_pejabat_penandatangan_select').append(
                        '<option value="PA/KPA">PA/KPA</option>');
                    $('.modal-body #child_pejabat_penandatangan_select').append(
                        '<option value="PPK">PPK</option>');
                    $('.modal-body #child_pejabat_penandatangan_select').append(
                        '<option value="Bendahara">Bendahara</option>');
                    $('.modal-body #child_pejabat_penandatangan_select').append(
                        '<option value="Lainnya">Lainnya</option>');
                } else {
                    if (subitem['jenis_naskah_dinas']) {
                        $('.modal-body #child_jenis_naskah_dinas_select').append(
                            '<option selected value="' +
                            subitem['jenis_naskah_dinas'] + '">' + subitem['jenis_naskah_dinas'] +
                            '</option>');
                    }

                    if (subitem['pejabat_penandatangan']) {
                        $('.modal-body #child_pejabat_penandatangan_select').append(
                            '<option selected value="' +
                            subitem['pejabat_penandatangan'] + '">' + subitem['pejabat_penandatangan'] +
                            '</option>');
                    }
                }

                // TRIGER PROP INPUT
                $(".modal-body #child_kode_klasifikasi").prop("disabled", disable);
                $(".modal-body #child_jumlah_satuan").prop("disabled", disable);
                $(".modal-body #child_keterangan").prop("disabled", disable);
                $(".modal-body #child_jenis_naskah_dinas_select").prop("disabled", disable);
                $(".modal-body #child_pejabat_penandatangan_select").prop("disabled", disable);
                $(".modal-body #child_unit_pengolah").prop("disabled", disable);
                $(".modal-body #child_kurun_waktu").prop("disabled", disable);
                $(".modal-body #child_no_box").prop("disabled", disable);

                // TRIGER INIT JENIS NASKAH DINAS
                $(".modal-body #child_jenis_naskah_dinas_select").removeClass('d-none');
                $(".modal-body #child_jenis_naskah_dinas_input").prop('disabled', true);
                $(".modal-body #child_jenis_naskah_dinas_input").addClass('d-none');

                // TRIGER INIT PEJABAT PENANDATANGANAN
                $(".modal-body #child_pejabat_penandatangan_select").removeClass('d-none');
                $(".modal-body #child_pejabat_penandatangan_input").prop('disabled', true);
                $(".modal-body #child_pejabat_penandatangan_input").addClass('d-none');

                // UUPDATE VALUE INPUT
                $(".modal-body #child_id").val(subitem['id']);
                $(".modal-body #child_dokumen_id").val(subitem['dokumen_id']);
                $(".modal-body #child_kode_klasifikasi").val(subitem['kode_klasifikasi']);
                $(".modal-body #child_uraian").val(subitem['uraian']);
                $(".modal-body #child_tanggal_surat").val(subitem['tanggal_surat']);
                $(".modal-body #child_jumlah_satuan").val(subitem['jumlah_satuan']);
                $(".modal-body #child_keterangan").val(subitem['keterangan']);
                $(".modal-body #child_no_surat").val(subitem['no_surat']);
                $(".modal-body #child_unit_pengolah").val(subitem['unit_pengolah']);
                $(".modal-body #child_kurun_waktu").val(subitem['kurun_waktu']);
                $(".modal-body #child_no_box").val(subitem['no_box']);
                $(".modal-body #child_tkt_perk").val(subitem['tkt_perk']);

                const file_dokumen = subitem['file_dokumen'];
                if (file_dokumen) {
                    $('#file_dokumen_append').html(`<a href="{{ asset('storage') }}/${file_dokumen}"
                            target="_blank"
                            class="btn btn-outline-primary mb-3">Lihat File</a>
                `);
                }

                $('#file_dokumen').val('');
                $('#pdf_preview_container').hide();
                $('#modalSideEditChild').modal('show');
            });

            function submitEditParent() {
                var formData = $('#form_edit_parent').serialize();
                var id = $(".modal-body #parent_id").val();
                $.ajax({
                    url: "{{ route('data-arsip.index') }}/" + id, // Replace with your route
                    type: "POST",
                    data: formData,
                    success: function(response) {
                        $('#modalSideEditParent').modal('hide');
                        $('#successToast').removeClass("hide");
                        $('#successToast').addClass("show");
                        $('#successMessage').html('Data arsip berhasil diperbaharui');
                        getData();
                    },
                    error: function(error) {
                        console.error(error);
                    }
                });
            }

            function submitAddParent() {
                var form = $('#form_add_parent')[0];
                var formData = new FormData(form);
                $.ajax({
                    url: "{{ route('detail-data-arsip.store') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        $('#modalSideAddParent').modal('hide');
                        $('#successToast').removeClass("hide");
                        $('#successToast').addClass("show");
                        $('#successMessage').html('Data isi arsip berhasil ditambahkan');
                        getData();
                    },
                    error: function(error) {
                        console.error(error);
                    }
                });
            }

            function submitEditChild() {
                var form = $('#form_edit_child')[0];
                var formData = new FormData(form);
                var id = $(".modal-body #child_id").val();
                $.ajax({
                    url: "{{ route('detail-data-arsip.index') }}/" + id, // Replace with your route
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        $('#modalSideEditChild').modal('hide');
                        $('#successToast').removeClass("hide");
                        $('#successToast').addClass("show");
                        $('#successMessage').html('Data isi arsip berhasil diperbaharui');
                        getData();
                    },
                    error: function(error) {
                        console.error(error);
                    }
                });
            }

            function submitVerifikasi() {
                console.log('submitBtnVerifikasi');
                var formData = $('#form_verifikasi').serialize();
                $.ajax({
                    url: "{{ route('data-arsip.verifikasi_dokumen') }}", // Replace with your route
                    type: "POST",
                    data: formData,
                    success: function(response) {
                        $('#modalVerifikasi').modal('hide');
                        $('#successToast').removeClass("hide");
                        $('#successToast').addClass("show");
                        $('#successMessage').html('Data arsip berhasil diverifikasi');
                        getData();
                    },
                    error: function(error) {
                        console.error(error);
                    }
                });
            }


        $(document).on('click', '.btn-edit-parent', function() {
            const item = $(this).data('item');
            $(".modal-body #parent_id").val(item['id']);
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
            $('#parent_add_pejabat_penandatangan_select').removeClass('d-none');
            $('#parent_add_pejabat_penandatangan_input').addClass('d-none');
            $(".modal-body #parent_add_pejabat_penandatangan_select").val('PA/KPA');
            $(".modal-body #parent_add_pejabat_penandatangan_select").removeClass('d-none');
            $(".modal-body #parent_add_pejabat_penandatangan_select").prop('disabled', false);
            $(".modal-body #parent_add_pejabat_penandatangan_input").prop('disabled', true);
            $(".modal-body #parent_add_pejabat_penandatangan_input").addClass('d-none');
            $(".modal-body #parent_add_kode_klasifikasi").val(subitem['kode_klasifikasi']);
            $(".modal-body #parent_add_tanggal_surat").val(subitem['tanggal_surat']);
            $(".modal-body #parent_add_jumlah_satuan").val(subitem['jumlah_satuan']);
            $(".modal-body #parent_add_keterangan").val(subitem['keterangan']);
            $(".modal-body #parent_add_jenis_naskah_dinas_select").val('SPTJM,Ceklis SPM');
            $(".modal-body #parent_add_jenis_naskah_dinas_select").removeClass('d-none');
            $(".modal-body #parent_add_jenis_naskah_dinas_select").prop('disabled', false);
            $(".modal-body #parent_add_jenis_naskah_dinas_input").prop('disabled', true);
            $(".modal-body #parent_add_jenis_naskah_dinas_input").addClass('d-none');
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

        $(document).on('click', '.pagination a', function(event) {
            $('li').removeClass('active');
            $(this).parent('li').addClass('active');
            event.preventDefault();
            var myurl = $(this).attr('href');
            var page = $(this).attr('href').split('page=')[1];
            pages = page;
            getData();
        });

        $('#search').on('input', function(e) {
            let val = $(this).val();
            keyword = val;
            clearTimeout($(this).data('timeout'));
            $(this).data('timeout', setTimeout(function() {
                getData();
            }, 500));
        });

        $(".paginate-item").on('click', function(event) {
            const item = $(this).data('items');
            items = item;
            getData();
        });

        $(".btn-reset-filter").on('click', function(event) {
            start_date_validate = '';
            end_date_validate = '';
            $("input[name=start_date_validate]").val('');
            $("input[name=end_date_validate]").val('');
        });

        $(".btn-filter").on('click', function(event) {
            start_date_validate = $("input[name=start_date_validate]").val();
            end_date_validate = $("input[name=end_date_validate]").val();
            getData();
            $('#modalFilter').modal('hide');
        });
        });

        function getData() {

            let requestData = {};
            if (pages !== 0) {
                requestData.page = pages;
            }

            if (keyword !== '') {
                requestData.search = keyword;
            }

            if (start_date_validate !== '') {
                requestData.start_date_validate = start_date_validate;
            }

            if (end_date_validate !== '') {
                requestData.end_date_validate = end_date_validate;
            }

            requestData.items = items;

            $.ajax({
                    url: "{{ route('data-arsip.index') }}",
                    type: "get",
                    data: requestData,
                    dataType: "html",
                    beforeSend: function() {
                        $('#loader').show();
                    }
                })
                .done(function(data) {
                    $("#item-lists").empty().html(data);
                    // backToTop();
                })
                .fail(function(jqXHR, ajaxOptions, thrownError) {
                    alert('No response from server');
                })
                .always(function() {
                    $('#loader').hide();
                });

        }

        function backToTop() {
            document.body.scrollTop = 0;
            document.documentElement.scrollTop = 0;
        }

        document.getElementById('file_dokumen').addEventListener('change', function(event) {
            var file = event.target.files[0];
            var previewContainer = document.getElementById('pdf_preview_container');
            var previewFrame = document.getElementById('pdf_preview');

            if (file && file.type === "application/pdf") {
                var fileReader = new FileReader();
                fileReader.onload = function() {
                    var typedarray = new Uint8Array(this.result);

                    pdfjsLib.getDocument(typedarray).promise.then(function(pdf) {
                        pdf.getPage(1).then(function(page) {
                            var scale = 1.5;
                            var viewport = page.getViewport({ scale: scale });

                            var canvas = document.createElement('canvas');
                            var context = canvas.getContext('2d');
                            canvas.height = viewport.height;
                            canvas.width = viewport.width;

                            var renderContext = {
                                canvasContext: context,
                                viewport: viewport
                            };
                            page.render(renderContext).promise.then(function() {
                                previewFrame.src = canvas.toDataURL();
                                previewContainer.style.display = 'block';  // Show the preview
                            });
                        });
                    });
                };
                fileReader.readAsArrayBuffer(file);
            } else {
                alert('Please upload a PDF file.');
                previewContainer.style.display = 'none';  // Hide the preview if the file is not a PDF
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

    <div class="position-fixed top-0 end-0 p-3" style="z-index: 5">
        <div id="successToast" class="toast bg-success fade hide" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header py-2">
                <strong class="me-auto text-white">Informasi</strong>
                <button type="button" class="btn-close text-white" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body text-white" id="successMessage"></div>
        </div>
    </div>

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

                            <button type="button"
                                class="btn btn-primary btn-icon btn-icon-start w-100 w-md-auto mt-3 mt-sm-0"
                                id="btn-barcode" disabled>
                                <i data-acorn-icon="plus"></i>
                                <span>Buat QR Code No. Box</span>
                            </button>
                            {{-- <a href="{{ route('data-arsip.create') }}"
                                class="btn btn-primary btn-icon btn-icon-start w-100 w-md-auto mt-3 mt-sm-0">
                                <i data-acorn-icon="plus"></i>
                                <span>Tambah Data</span>
                            </a> --}}
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
                            <div
                                class="d-inline-block float-md-start me-1 mb-1 search-input-container w-100 shadow bg-foreground">
                                <input class="form-control datatable-search" name="search" id="search"
                                    placeholder="Pencarian..." />
                                <span class="search-magnifier-icon" onClick="getData()">
                                    <i data-acorn-icon="search"></i>
                                </span>
                            </div>
                        </div>
                        <!-- Search End -->
                        <div class="col-sm-12 col-md-6 col-lg-6 col-xxl-8 text-end mb-3">
                            <div class="d-inline-block">
                                <!-- Print Button Start -->
                                <button class="btn btn-icon btn-icon-only btn-primary shadow datatable-print" type="button"
                                    data-bs-toggle="modal" data-bs-target="#modalFilter">
                                    <i data-acorn-icon="filter"></i>
                                </button>
                                <!-- Print Button End -->
                                <!-- Export Dropdown Start -->
                                <div class="d-inline-block datatable-export" data-datatable="#datatableRowsServerSide">
                                    <button class="btn p-0" data-bs-toggle="dropdown" type="button"
                                        data-bs-offset="0,3">
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
                                        <a class="dropdown-item paginate-item" data-items="5" href="javascript:void(0)">5
                                            Data</a>
                                        <a class="dropdown-item paginate-item active" data-items="10"
                                            href="javascript:void(0">10
                                            Data</a>
                                        <a class="dropdown-item paginate-item" data-items="20"
                                            href="javascript:void(0">20 Data</a>
                                    </div>
                                </div>
                                <!-- Length End -->
                            </div>
                        </div>
                    </div>
                    <!-- Controls End -->
                    <!-- Table Start -->
                    <div id="item-lists">
                        @include('pages/data-arsip/data')
                    </div>
                </div>
                <!-- Content End -->
            </div>
        </div>
    </div>

    <!-- Modal Import -->
    <div class="modal fade" id="modalImport" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('data-arsip.import-excel') }}" enctype="multipart/form-data">
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
            <form method="POST" action="{{ route('import-monitoring') }}" enctype="multipart/form-data">
                <div class="modal-content">
                    <div class="modal-header py-3">
                        <h5 class="modal-title" id="exampleModalLabelDefault">Tarik Data Monitoring</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body py-3">
                        {{ csrf_field() }}

                        <div class="mb-3">
                            <div>
                                <label class="form-label d-block text-primary fw-bold">Metode Tarik Data</label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="method_type" id="methodRadio1"
                                        value="periode" checked />
                                    <label class="form-check-label" for="methodRadio1">Periode</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="method_type" id="methodRadio2"
                                        value="harian" />
                                    <label class="form-check-label" for="methodRadio2">Harian</label>
                                </div>
                            </div>
                        </div>

                        <div id="periodeContainer">
                            <div class="mb-3 position-relative form-group">
                                <label class="form-label text-primary fw-bold">Tahun</label>
                                <select name="tahun" id="tahunImport" class="form-select select2" required>
                                    <option value="">Pilih Tahun</option>
                                    @foreach ($yearsOptions ?? [] as $item)
                                        <option value="{{ $item['id'] }}">{{ $item['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3 position-relative form-group">
                                <label class="form-label text-primary fw-bold">Bulan</label>
                                <select name="bulan" id="bulanImport" class="form-select select2" required>
                                    <option value="">Pilih Bulan</option>
                                    @foreach ($monthsOptions ?? [] as $item)
                                        <option value="{{ $item['id'] }}">{{ $item['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="d-none" id="harianContainer">
                            <div class="mb-3 position-relative form-group">
                                <label class="form-label text-primary fw-bold">Tanggal Tarik Data</label>
                                <input type="text" id="tanggalImport" class="form-control datepicker"
                                    autocomplete="off" name="tanggal" />
                            </div>
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
        <form id="form_edit_child" class="tooltip-label-end" novalidate method="POST" enctype="multipart/form-data">
            @method('put')
            @csrf
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header pt-4 pb-3">
                        <h5 class="modal-title" id="staticBackdropLabel">Edit Data</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="child_id" />
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
                            <select id="child_jenis_naskah_dinas_select" name="jenis_naskah_dinas" class="form-select"
                                required></select>
                            <input type="text" class="form-control d-none" id="child_jenis_naskah_dinas_input"
                                name="jenis_naskah_dinas" required />
                        </div>

                        <div class="mb-3 position-relative form-group">
                            <label class="form-label text-primary fw-bold">No. Surat</label>
                            <input type="text" class="form-control" name="no_surat" id="child_no_surat" required />
                        </div>

                        <div class="mb-3 position-relative form-group">
                            <label class="form-label text-primary fw-bold">Pejabat Penandatangan</label>
                            <select id="child_pejabat_penandatangan_select" name="pejabat_penandatangan"
                                class="form-select" required></select>
                            <input type="text" class="form-control d-none" id="child_pejabat_penandatangan_input"
                                name="pejabat_penandatangan" required />
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
                            <input class="form-control" type="file" name="file_dokumen" id="file_dokumen" accept="application/pdf"/>
                        </div>

                        <div class="mb-3 position-relative form-group" id="pdf_preview_container" style="display: none;">
                            <label class="form-label text-primary fw-bold">Preview Dokumen</label>
                            <iframe class="border" id="pdf_preview" style="width: 100%; height: 500px;" frameborder="0"></iframe>
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
        <form id="form_edit_parent" class="tooltip-label-end" novalidate method="POST" enctype="multipart/form-data">
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

                        <input type="hidden" class="form-control" name="id" id="parent_id" required />

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
                            <label class="form-label text-primary fw-bold">Pejabat Penandatangan</label>
                            <select name="pejabat_penandatangan" id="parent_pejabat_penandatangan" class="form-select"
                                required>
                                <option value="Kuasa BUD">Kuasa BUD</option>
                                <option value="Plt. Kuasa BUD">Plt. Kuasa BUD</option>
                                <option value="Plh. Kuasa BUD">Plh. Kuasa BUD</option>
                            </select>
                        </div>

                        <div class="mb-3 position-relative form-group">
                            <label class="form-label text-primary fw-bold">Unit Pengolah</label>
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
        <form id="form_add_parent" class="tooltip-label-end" novalidate method="POST" enctype="multipart/form-data">
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
                            <select id="parent_add_jenis_naskah_dinas_select" name="jenis_naskah_dinas"
                                class="form-select" required>
                                <option selected value="SPTJM,Ceklis SPM">SPTJM,Ceklis SPM</option>
                                <option value="Pernyataan Verifikasi">Pernyataan Verifikasi</option>
                                <option value="Ringkasan Kontrak">Ringkasan Kontrak</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                            <input type="text" class="form-control d-none" id="parent_add_jenis_naskah_dinas_input"
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
                            <select id="parent_add_pejabat_penandatangan_select" name="pejabat_penandatangan"
                                class="form-select" required>
                                <option selected value="PA/KPA">PA/KPA</option>
                                <option value="PPK">PPK</option>
                                <option value="Bendahara">Bendahara</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                            <input type="text" class="form-control d-none" id="parent_add_pejabat_penandatangan_input"
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
                            <input type="text" class="form-control bg-muted" id="parent_add_no_box" name="no_box"
                                readonly required />
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
            <form id="form_verifikasi" method="POST">
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
                        <button type="button" id="submitBtnVerifikasi" class="btn btn-primary">Ya</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal No. Box -->
    <div class="modal fade" id="modalFormBarcode" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('data-arsip-no-box') }}" enctype="multipart/form-data">
                <div class="modal-content">
                    <div class="modal-header py-3">
                        <h5 class="modal-title" id="exampleModalLabelDefault">Buat QR Code No. Box</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body d-flex flex-column align-items-center justify-content-center text-center py-3">
                        {{ csrf_field() }}
                        {!! '<img class="mb-3" src="data:image/png;base64,' .
                            DNS2D::getBarcodePNG(url('/detail-box', Str::replace('/', '_', $no_box_tmp)), 'QRCODE', 12, 12) .
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

    <!-- Modal Filter -->
    <div class="modal fade" id="modalFilter" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form>
                <div class="modal-content">
                    <div class="modal-header py-3">
                        <h5 class="modal-title" id="exampleModalLabelDefault">Filter Data</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body py-3">
                        {{ csrf_field() }}

                        <div class="mb-3 position-relative form-group">
                            <label class="form-label text-primary fw-bold">Tanggal Validasi</label>
                            <div class="input-daterange input-group" id="datePickerRange">
                                <input type="text" class="form-control" name="start_date_validate"
                                    placeholder="Awal Periode" />
                                <span class="mx-2"></span>
                                <input type="text" class="form-control" name="end_date_validate"
                                    placeholder="Akhir Periode" />
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer pt-0 pb-4" style="border-top: none !important">
                        <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-outline-primary btn-reset-filter">Reset</button>
                        <button type="button" class="btn btn-primary btn-filter">Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

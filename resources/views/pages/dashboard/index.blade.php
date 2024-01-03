@php
    $html_tag_data = ['override' => '{ "attributes" : { "placement" : "vertical", "layout":"fluid" }, "showSettings" : false }'];
    $title = 'Dashboard';
    $description = 'Selamat Datang, Dalam Layanan Sistem Dokumen Terpadu (SiDOKTER)';
    $breadcrumbs = ['/' => 'Dashboard'];
@endphp
@extends('layout', ['html_tag_data' => $html_tag_data, 'title' => $title, 'description' => $description])

@section('css')
    <link rel="stylesheet" href="{{ asset('css/vendor/glide.core.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/vendor/introjs.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/vendor/select2.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/vendor/select2-bootstrap4.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/vendor/plyr.css') }}" />
@endsection

@section('js_vendor')
    <script src="{{ asset('js/vendor/Chart.bundle.min.js') }}"></script>
    <script src="{{ asset('js/vendor/chartjs-plugin-datalabels.js') }}"></script>
    <script src="{{ asset('js/vendor/chartjs-plugin-rounded-bar.min.js') }}"></script>
    <script src="{{ asset('js/vendor/glide.min.js') }}"></script>
    <script src="{{ asset('js/vendor/intro.min.js') }}"></script>
    <script src="{{ asset('js/vendor/select2.full.min.js') }}"></script>
    <script src="{{ asset('js/vendor/plyr.min.js') }}"></script>
@endsection


@section('js_page')
    <script src="{{ asset('js/cs/glide.custom.js') }}"></script>
    <script src="{{ asset('js/cs/charts.extend.js') }}"></script>
    <script src="{{ asset('js/pages/dashboard.default.js') }}"></script>
@endsection


@section('content')
    <div class="container">
        <!-- Title and Top Buttons Start -->
        <div class="page-title-container">
            <div class="row">
                <!-- Title Start -->
                <div class="col-12 col-sm-6">
                    <h1 class="mb-0 pb-0 display-4" id="title">{{ $title }}</h1>
                </div>
                <!-- Title End -->
            </div>
        </div>
        <!-- Title and Top Buttons End -->



        <div class="row">
            <div class="col-12">

                <!-- Stats Start -->
                <h2 class="small-title">Statisitk</h2>
                <div class="row">
                    <div class="col-4">
                        <div class="card mb-5 sh-20 hover-border-primary">
                            <div
                                class="h-100 p-4 text-center align-items-center d-flex flex-column justify-content-between">
                                <div
                                    class="d-flex flex-column justify-content-center align-items-center sh-5 sw-5 rounded-xl bg-gradient-light mb-2">
                                    <i data-acorn-icon="shrink-diagonal-2" class="text-white"></i>
                                </div>
                                <p class="mb-0 lh-1">Surat Masuk</p>
                                <p class="cta-3 mb-0 text-primary">{{ number_format($total_dokumen, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="card mb-5 sh-20 hover-border-primary">
                            <div
                                class="h-100 p-4 text-center align-items-center d-flex flex-column justify-content-between">
                                <div
                                    class="d-flex flex-column justify-content-center align-items-center sh-5 sw-5 rounded-xl bg-gradient-light mb-2">
                                    <i data-acorn-icon="send" class="text-white"></i>
                                </div>
                                <p class="mb-0 lh-1">Surat Keluar</p>
                                <p class="cta-3 mb-0 text-primary">{{ number_format($dokumen_keluar, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="card mb-5 sh-20 hover-border-primary">
                            <div
                                class="h-100 p-4 text-center align-items-center d-flex flex-column justify-content-between">
                                <div
                                    class="d-flex flex-column justify-content-center align-items-center sh-5 sw-5 rounded-xl bg-gradient-light mb-2">
                                    <i data-acorn-icon="check-circle" class="text-white"></i>
                                </div>
                                <p class="mb-0 lh-1">Data Terverifikasi</p>
                                <p class="cta-3 mb-0 text-primary">{{ number_format($dokumen_terverifikasi, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Stats Start -->
            </div>
        </div>

        <div class="row">
            <!-- Logs Start -->
            <div class="col-lg-12 mb-5 sh-45">
                <h2 class="small-title">Logs</h2>
                <div class="card sh-40 h-lg-100-card">

                    @if ($logs->count() > 0)
                        <div class="card-body mb-n2 scroll-out h-100">
                            <div class="scroll h-100">
                                @foreach ($logs ?? [] as $item)
                                    <div class="row g-0 mb-2">
                                        <div class="col-auto">
                                            <div
                                                class="sw-3 d-inline-block d-flex justify-content-start align-items-center h-100">
                                                <div class="sh-3">
                                                    <i data-acorn-icon="circle" class="text-primary align-top"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div
                                                class="card-body d-flex flex-column pt-0 pb-0 ps-3 pe-4 h-100 justify-content-center">
                                                <div class="d-flex flex-column">
                                                    <div class="text-alternate mt-n1 lh-1-25"><strong>{{ $item->causer->name ?? '' }}</strong> {!! $item->description !!}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <div class="d-inline-block d-flex justify-content-end align-items-center h-100">
                                                <div class="text-muted ms-2 mt-n1 lh-1-25">{{ $item->created_at->format('j F Y, H:i:s') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-center mt-5">
                                <div class="alert alert-warning w-75 text-center" role="alert">
                                    Belum Ada Catatan Kegiatan
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
            <!-- Logs End -->
        </div>

    </div>
@endsection

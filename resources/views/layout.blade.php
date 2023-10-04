<!DOCTYPE html>
<html lang="en" data-url-prefix="/" data-footer="true"
    @isset($html_tag_data)
    @foreach ($html_tag_data as $key => $value) data-{{ $key }}='{{ $value }}' @endforeach
@endisset>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>SiDOKTER | {{ $title }}</title>
    <meta name="description" content="{{ $description }}" />
    @include('_layout.head')
    <style>
        /* Loader styles */
        .loader-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100vh;
            background-color: rgba(255, 255, 255, 0.7);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }
    </style>
</head>

<body>
    <div class="loader-container" id="loader" style="display: none">
        <div class="overlay-spinner"></div>
    </div>


    <div id="root">
        <div id="nav" class="nav-container d-flex"
            @isset($custom_nav_data) @foreach ($custom_nav_data as $key => $value)
    data-{{ $key }}="{{ $value }}"
        @endforeach
        @endisset>
            @include('_layout.nav')
        </div>
        <main>
            {{-- <div id="loader" class="loader" style="display: none;"></div> --}}
            @yield('content')
        </main>
        @include('_layout.footer')
    </div>
    @include('_layout.modal_settings')
    @include('_layout.modal_search')
    @include('_layout.scripts')
</body>

</html>

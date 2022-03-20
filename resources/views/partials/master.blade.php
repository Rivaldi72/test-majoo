<!DOCTYPE html>
<html lang="en">

@include('partials.style')

<body class="animsition">
    <div class="page-wrapper">
        @include('partials.header')
        @include('partials.sidebar')

        <div class="page-container mt-5">

            @yield('content')

        </div>
    </div>
    @yield('modal')
    <div class="modal fade" id="modal-box" role="dialog" aria-labelledby="staticModalLabel"
        aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog" role="document">
            <div class="modal-content" id="modal-content">
            </div>
        </div>
    </div>
    @include('partials.script')
</body>
@yield('javascript')
</html>

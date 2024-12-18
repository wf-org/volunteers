<!DOCTYPE html>
<html>
    <head>
        <title>Volunteer Database</title>
        <link rel="shortcut icon" href="/img/favicon.ico"/>

        <!-- Bootstrap styles -->
        <link rel="stylesheet" href="/css/bootstrap.min.css">
        <link rel="stylesheet" href="/css/bootstrap-theme.min.css">

        <!-- Custom styles -->
        <link rel="stylesheet" href="/css/main.css">

        <!-- Custom scripts -->
        <script src="/js/bundle.js"></script>

        <!-- Mobile friendly viewport -->
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body>
        @include('partials/header')

        <section class="content container-fluid">
            @if(Session::has('success'))
                <?php $success = Session::get('success'); ?>

                <div class="general-alert alert alert-success" role="alert">
                    @if(is_array($success))
                        <b>{{ $success['title'] or 'Success!'}}</b> {{ $success['message'] or '' }}
                    @else
                        <b>Success!</b> {{ $success }}
                    @endif
                </div>
            @endif

            @if(Session::has('warning'))
                <div class="general-alert alert alert-warning" role="alert">
                    @if(isset(Session::get('warning')['layout']))
                        <b>Warning!</b> @include('partials.warning.'.Session::get('warning')['layout'], Session::get('warning'))
                    @else
                        <b>Warning!</b> {{ Session::get('warning') }}
                    @endif
                </div>
            @endif

            @if(Session::has('error'))
                <div class="general-alert alert alert-danger" role="alert">
                    <b>Error!</b> {{ Session::get('error') }}
                </div>
            @endif

            @if(Session::has('warning'))
                <div class="general-alert alert alert-warning" role="alert">
                    <b>Warning:</b> {{ Session::get('warning')}}
                </div>
            @endif

            @yield('content')
        </section>

        @include('partials/footer')

        <!-- Media query elements for js -->
        <div class="mobile hidden-md hidden-lg"></div>
        <div class="desktop hidden-xs hidden-sm"></div>
    </body>
</html>

<!doctype html>
<html lang="en">

    <head>

        <meta charset="utf-8" />
        <title>{{ $data['tab_title'] }}</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="{{ config('app.app_desc') }}" name="description" />
        <meta content="{{ config('app.app_author') }}" name="author" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png')}}">

        <!-- Bootstrap Css -->
        <link href="{{ asset('assets/css/bootstrap.min.css')}}" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="{{ asset('assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('mine/google.font.css') }}" rel="stylesheet" type="text/css" />

        <!-- App Css-->
        <link href="{{ asset('assets/css/app.min.css')}}" id="app-style" rel="stylesheet" type="text/css" />
        <link href="{{ asset('mine/style.css') }}" rel="stylesheet" type="text/css" />


    </head>

    <body class="auth-body-bg">
        <div class="loading"><div class="loader"></div></div>

        <div>
            <div class="container-fluid p-0">
                <div class="row no-gutters">
                    <div class="col-lg-4">
                        <div class="authentication-page-content p-4 d-flex align-items-center min-vh-100">
                            <div class="w-100">
                                <div class="row justify-content-center">
                                    <div class="col-lg-9">
                                        <div>
                                            <div class="text-center">
                                                <div>
                                                    <a href="index.html" class="logo"><img src="{{ asset('assets/images/logo-dark.png') }}" height="50" alt="logo"></a>
                                                </div>

                                                <h4 class="font-size-18 mt-4">{{ config('app.name') }}</h4>
                                                <p class="text-muted">
                                                    {{ $data['page_desc'] }} <br>
                                                    <span>Akses terbatas hanya untuk pengawas.</span>
                                                </p>
                                            </div>

                                            <div class="p-2 mt-5">
                                                @yield('content')
                                            </div>

                                            <div class="mt-5 text-center">
                                                {{-- <p>Don't have an account ? <a href="auth-register.html" class="font-weight-medium text-primary"> Register </a> </p> --}}
                                                <p>© 2025 Integrated Terminal. Property of Patra Niaga Dumai</p>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="authentication-bg">
                            <div class="bg-overlay"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <!-- JAVASCRIPT -->
        <script src="{{ asset('assets/libs/jquery/jquery.min.js')}}"></script>
        <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
        <script src="{{ asset('assets/libs/metismenu/metisMenu.min.js')}}"></script>
        <script src="{{ asset('assets/libs/simplebar/simplebar.min.js')}}"></script>
        <script src="{{ asset('assets/libs/node-waves/waves.min.js')}}"></script>

        <script src="{{ asset('assets/js/app.js')}}"></script>
        <script src="{{ asset('mine/script.js') }}"></script>


    </body>
</html>

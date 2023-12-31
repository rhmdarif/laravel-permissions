<!doctype html>
<html lang="en">
<head>

        <meta charset="utf-8" />
        <title>{{ $title ?? 'Koperasi Simpan Pinjam' }}</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- App favicon -->
        <link rel="shortcut icon" href="/assets/images/favicon.ico">

        <!-- Layout Js -->
        <script src="/assets/js/layout.js"></script>
        <!-- Bootstrap Css -->
        <link href="/assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="/assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />

        {{ $css ?? "" }}

    </head>

    <body data-sidebar="colored">

    <!-- <body data-layout="horizontal" data-topbar="dark"> -->

        <!-- Begin page -->
        <div id="layout-wrapper">


            @include('components.templating.admin.header')
            @include('components.templating.admin.leftsidebar')



            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            <div class="main-content">

                <div class="page-content">
                    <div class="container-fluid pt-2">

                        {{ $slot }}

                    </div> <!-- container-fluid -->
                </div>
                <!-- End Page-content -->

                @include('components.templating.admin.footer')

            </div>
            <!-- end main content-->

            {{ $modal ?? "" }}

        </div>
        <!-- END layout-wrapper -->

        <!-- JAVASCRIPT -->
        <script src="/assets/libs/jquery/jquery.min.js"></script>
        <script src="/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="/assets/libs/metismenu/metisMenu.min.js"></script>
        <script src="/assets/libs/simplebar/simplebar.min.js"></script>
        <script src="/assets/libs/node-waves/waves.min.js"></script>

        <!-- Icon -->
        <script src="/assets/vendors/unicons.iconscout.com/release/v2.0.1/script/monochrome/bundle.js"></script>

        <script src="/assets/js/app.js"></script>

        {{ $js ?? "" }}

    </body>

<!-- Mirrored from themesdesign.in/tocly/layouts/5.3.1/pages-starter.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 14 Oct 2023 05:26:17 GMT -->
</html>

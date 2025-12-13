<!--<!DOCTYPE html>
<html lang="en">
<head>
    < ?= $this->renderSection('head') ?>
</head>

<body>
    < ?= $this->renderSection('content') ?>
</body>

</html>-->


<!--
=========================================================
* Material Dashboard 2 PRO - v3.1.0
=========================================================

* Product Page:  https://www.creative-tim.com/product/material-dashboard-pro
* Copyright 2024 Creative Tim (https://www.creative-tim.com)
* Coded by Creative Tim

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
-->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="../../../assets/img/favicon.png">
    <link rel="icon" type="image/png" href="../../../assets/propis/img/favicon.png">
    <title>LSMusic - <?= $this->renderSection('headName') ?> </title>
    <!--     Fonts and icons     -->
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900" />
    <!-- Nucleo Icons -->
    <link href="../../../assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="../../../assets/css/nucleo-svg.css" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <!-- Material Icons -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
    <!-- CSS Files -->
    <link id="pagestyle" href="../../../assets/css/material-dashboard.css?v=3.1.0" rel="stylesheet" />
    <!-- CSS Files -->
    <link rel="stylesheet" href="../../../assets/css/styles.css">
    <link rel="stylesheet" href="<?= base_url('assets/propis/styles.css') ?>">
</head>

<body class="bg-gray-200">
    <!-- Navbar -->
    <header>
        <nav class="navbar navbar-expand-lg position-absolute top-0 z-index-3 w-100 shadow-none my-3 navbar-transparent mt-4">
            <div class="containerCentrat">
                <a href="<?= base_url(route_to('landing_view')) ?>" title="Go to Home Page" class="navbar-brand text-center text-white text-decoration-none logo-big">
                    LSMusic
                </a>
            </div>
    </header>
    <!-- End Navbar -->
    <main class="main-content  mt-0">
        <div class="page-header align-items-start min-vh-100" style="background-image: url('https://images.unsplash.com/photo-1497294815431-9365093b7331?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1950&q=80');">
            <span class="mask bg-gradient-dark opacity-6"></span>
            <div class="container my-auto">
                <div class="row">
                    <?= $this->renderSection('container') ?>
                </div>
            </div>
        </div>
    </main>
</body>

<footer class="footer position-absolute bottom-2 py-2 w-100">
    <div class="container">
        <div class="row2 align-items-center justify-content-lg-evenly">
            <div class="col-12 col-md-6 my-auto">
                <div class="copyright text-center text-sm text-white">
                    © <script>
                        document.write(new Date().getFullYear())
                    </script>
                    <?= lang('App.copyright') ?> <i class="fa fa-heart" aria-hidden="true"></i>
                </div>
            </div>
        </div>
    </div>
</footer>

<!--   Core JS Files   -->
<script src="../../../assets/js/core/popper.min.js"></script>
<script src="../../../assets/js/core/bootstrap.min.js"></script>
<script src="../../../assets/js/plugins/perfect-scrollbar.min.js"></script>
<script src="../../../assets/js/plugins/smooth-scrollbar.min.js"></script>
<!-- Kanban scripts -->
<script src="../../../assets/js/plugins/dragula/dragula.min.js"></script>
<script src="../../../assets/js/plugins/jkanban/jkanban.min.js"></script>
<script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
        var options = {
            damping: '0.5'
        }
        Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
</script>
<!-- Github buttons -->
<script async defer src="https://buttons.github.io/buttons.js"></script>
<!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
<script src="../../../assets/js/material-dashboard.min.js?v=3.1.0"></script>
<script src="<?= site_url('/assets/js/commons.js') ?>"></script>
<?= $this->renderSection('scripts') ?>


</html>
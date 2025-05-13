<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?= $this->renderSection('title') ?>

    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900" />
    <link href="<?= site_url('/assets/css/nucleo-icons.css') ?>" rel="stylesheet" />
    <link href="<?= site_url('/assets/css/nucleo-svg.css') ?>" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link id="pagestyle" href="<?= site_url('/assets/css/material-dashboard.css?v=3.1.0') ?>" rel="stylesheet" />
    <link rel="stylesheet" href="<?= site_url('/assets/css/spoty.css') ?>">
</head>

<body class="bg-dark">
    <nav class="navbar navbar-expand-lg navbar-dark bg-black position-sticky top-0" style="z-index: 1000;">
        <div class="container">
            <a class="navbar-brand text-success fw-bold fs-4" style="margin: 0px !important;" href="/home">LSpoty</a>

            <div class="d-flex align-items-center ms-auto gap-2">
                <a href="/my-playlists" title="my-playlists-page" class="d-flex align-items-center justify-content-center btn btn-link btn-just-icon text-white me-2" style="margin: 0 !important;">
                    <i class="fa fa-music"></i>
                </a>
                <?php if(session()->getFlashdata('notProfilePage')): ?>
                    <a href="/profile" title="profile-page" class="d-flex align-items-center justify-content-center btn btn-link btn-just-icon text-white me-2" style="margin: 0 5px 0 5px !important;">
                        <i class="fa fa-user-circle"></i>
                    </a>
                <?php endif; ?>
                <form action="/sign-out" method="GET" class="d-inline" style="margin: 0 !important;">
                    <button type="submit" title="sign-out" class="d-flex align-items-center justify-content-center btn btn-link btn-just-icon text-white" style="margin: 0 !important;">
                        <i class="fa fa-sign-out-alt"></i>
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container py-5">
        <?= $this->renderSection('content') ?>
    </div>

</body>

<!-- Footer -->
<footer class="bg-black text-center text-light py-3 mt-auto">
    <div class="copyright text-center text-sm text-white">
        © <script>
            document.write(new Date().getFullYear())
        </script>
        Made by Joan Enric, Pol and Roger with <i class="fa fa-heart" aria-hidden="true"></i>
    </div>
</footer>

<!-- Core JS Files -->
<script src="<?= site_url('/assets/js/core/popper.min.js') ?>"></script>
<script src="<?= site_url('/assets/js/core/bootstrap.min.js') ?>"></script>
<script src="<?= site_url('/assets/js/plugins/perfect-scrollbar.min.js') ?>"></script>
<!-- Swiper CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>

<?= $this->renderSection('javascript') ?>

</html>
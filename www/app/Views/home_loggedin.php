<?= $this->extend('defaultForm') ?>

<?= $this->section('headName') ?>
Home
<?= $this->endSection() ?>

<?= $this->section('sectionName') ?>
Home
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="text-center p-4">
    <h1 class="fw-bold text-primary-emphasis">Welcome
        <?php
        $session = session();
        if(!$session->has('user')){
            return redirect()->to('');
        }
        $user = $session->get('user');
        $username = explode("@", $user['email'])[0];
        echo $username;
        ?>
        ! Let's start shopping.</h1>
    </br>
</div>

<div class="d-flex justify-content-center gap-4 mt-4">
    <button type="button" class="btn bg-primary text-white" onclick="window.location.href='/search'">Search Page</button>
    <button type="button" class="btn bg-primary text-white" onclick="window.location.href='/sign-out'">Sign-out</button>
</div>

<?= $this->endSection() ?>

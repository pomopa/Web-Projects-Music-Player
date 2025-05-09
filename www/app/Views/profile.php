<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LSpoty - Your Music Companion</title>

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
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card bg-dark border border-success">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">Edit Profile</h4>
                </div>
                <div class="card-body">
                    <?php if(session()->getFlashdata('success')): ?>
                        <div class="alert alert-success">
                            <?= session()->getFlashdata('success') ?>
                        </div>
                    <?php endif; ?>

                    <?php if(session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger">
                            <?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?= site_url('/profile') ?>" method="POST" enctype="multipart/form-data" class="row g-3">

                        <!-- Profile Picture -->
                        <div class="col-12 text-center mb-4">
                            <div class="mx-auto position-relative" style="width: 150px; height: 150px;">
                                <?php if(!empty($user->profile_picture) && file_exists(FCPATH . 'uploads/profile/' . $user->profile_picture)): ?>
                                    <img src="<?= base_url('uploads/profile/' . $user->profile_picture) ?>" class="rounded-circle img-fluid border border-success" style="width: 150px; height: 150px; object-fit: cover;" alt="Profile Picture">
                                <?php else: ?>
                                    <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center border border-success" style="width: 150px; height: 150px;">
                                        <i class="fa fa-user fa-4x text-light"></i>
                                    </div>
                                <?php endif; ?>

                                <label for="profile_picture" class="position-absolute bottom-0 end-0 btn btn-sm btn-success rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; padding: 0;">
                                    <i class="fa fa-camera"></i>
                                </label>
                                <input type="file" id="profile_picture" name="profile_picture" class="d-none" accept="image/*">
                            </div>
                            <small class="text-light mt-2 d-block">Click the camera icon to change your profile picture</small>
                        </div>

                        <!-- Username -->
                        <div class="col-md-6">
                            <div class="input-group input-group-outline my-3">
                                <label class="form-label" for="username">Username</label>
                                <input type="text" id="username" name="username" class="form-control text-white" value="<?= $user->username ?>" required>
                            </div>
                        </div>

                        <!-- Age -->
                        <div class="col-md-6">
                            <div class="input-group input-group-outline my-3">
                                <label class="form-label" for="age">Age</label>
                                <input type="number" id="age" name="age" class="form-control text-white" value="<?= $user->age ?? '' ?>" min="1" max="120">
                            </div>
                        </div>

                        <!-- Email (Read-only) -->
                        <div class="col-12">
                            <div class="input-group input-group-outline my-3">
                                <label class="form-label" for="email">Email (Cannot be changed)</label>
                                <input type="email" id="email" class="form-control text-white" value="<?= $user->email ?>" readonly disabled>
                            </div>
                        </div>

                        <!-- Current Password -->
                        <div class="col-12">
                            <div class="input-group input-group-outline my-3">
                                <label class="form-label" for="current_password">Current Password (required to save changes)</label>
                                <input type="password" id="current_password" name="current_password" class="form-control text-white" required>
                            </div>
                        </div>

                        <!-- New Password -->
                        <div class="col-md-6">
                            <div class="input-group input-group-outline my-3">
                                <label class="form-label" for="new_password">New Password (leave blank to keep current)</label>
                                <input type="password" id="new_password" name="new_password" class="form-control text-white">
                            </div>
                        </div>

                        <!-- Confirm New Password -->
                        <div class="col-md-6">
                            <div class="input-group input-group-outline my-3">
                                <label class="form-label" for="confirm_password">Confirm New Password</label>
                                <input type="password" id="confirm_password" name="confirm_password" class="form-control text-white">
                            </div>
                        </div>

                        <!-- Save Button -->
                        <div class="col-12 d-flex justify-content-between align-items-center mt-4">
                            <button type="submit" class="btn btn-success">Save Changes</button>

                            <!-- Delete Account Button (will use JavaScript to show confirmation modal) -->
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                                Delete Account
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Account Modal -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content bg-dark text-white">
            <div class="modal-header border-bottom border-danger">
                <h5 class="modal-title text-danger" id="deleteAccountModalLabel">Confirm Account Deletion</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-warning mb-4"><i class="fa fa-exclamation-triangle"></i> Warning: This action cannot be undone!</p>

                <p>Deleting your account will:</p>
                <ul>
                    <li>Remove all your data from our database</li>
                    <li>Log you out immediately</li>
                    <li>Redirect you to the landing page</li>
                </ul>

                <form action="<?= site_url('profile/delete') ?>" method="POST" id="deleteAccountForm">
                    <div class="mb-3">
                        <label for="delete_password" class="form-label">Enter your password to confirm deletion:</label>
                        <input type="password" class="form-control bg-dark text-white" id="delete_password" name="password" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-top border-danger">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" onclick="document.getElementById('deleteAccountForm').submit();">
                    Delete My Account
                </button>
            </div>
        </div>
    </div>
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

<script>
    // Preview profile image before upload
    document.getElementById('profile_picture').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const profileContainer = document.querySelector('.rounded-circle');

                // If it's an img tag, update the src
                if (profileContainer.tagName === 'IMG') {
                    profileContainer.src = e.target.result;
                }
                // If it's the placeholder div, replace it with an img
                else {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.classList = 'rounded-circle img-fluid border border-success';
                    img.style = 'width: 150px; height: 150px; object-fit: cover;';
                    img.alt = 'Profile Picture';

                    profileContainer.parentNode.replaceChild(img, profileContainer);
                }
            }
            reader.readAsDataURL(file);
        }
    });
</script>

</html>
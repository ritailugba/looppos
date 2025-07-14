<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-lg">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <h2 class="fw-bold">LoopPOS</h2>
                        <p class="text-muted">Sign in to your account</p>
                    </div>

                    <?= form_open('/auth/login', ['class' => 'needs-validation', 'novalidate' => true]) ?>
                        <div class="mb-3">
                            <label for="username" class="form-label">Username or Email</label>
                            <input type="text" class="form-control" id="username" name="username" 
                                   value="<?= old('username') ?>" required>
                            <div class="invalid-feedback">
                                Please provide a valid username or email.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                            <div class="invalid-feedback">
                                Please provide a password.
                            </div>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">
                                Remember me
                            </label>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">Sign In</button>
                        </div>
                    <?= form_close() ?>

                    <div class="text-center mt-3">
                        <a href="<?= base_url('/auth/forgot-password') ?>" class="text-decoration-none">
                            Forgot your password?
                        </a>
                    </div>

                    <hr class="my-4">

                    <div class="text-center">
                        <p class="text-muted">Don't have an account?</p>
                        <a href="<?= base_url('/auth/register') ?>" class="btn btn-outline-secondary">
                            Create Account
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Bootstrap form validation
(function() {
    'use strict';
    window.addEventListener('load', function() {
        var forms = document.getElementsByClassName('needs-validation');
        var validation = Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }, false);
})();
</script>
<?= $this->endSection() ?>
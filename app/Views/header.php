<!-- In your view file -->
<?php if (!session()->get('logged_in')): ?>
    <a href="<?= base_url('login') ?>" class="btn btn-outline-light">Login</a>
    <a href="<?= base_url('register') ?>" class="btn btn-light">Register</a>
<?php else: ?>
    <a href="<?= base_url('logout') ?>" class="btn btn-danger">Logout</a>
<?php endif; ?>
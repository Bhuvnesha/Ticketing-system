<!-- Similar to login.php, but with just an email field -->
<form action="<?= base_url('forgot-password') ?>" method="post">
    <input type="email" name="email" placeholder="Your email" required>
    <button type="submit">Send Reset Link</button>
</form>
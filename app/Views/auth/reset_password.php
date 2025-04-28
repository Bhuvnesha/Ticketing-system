<form action="<?= base_url("reset-password/{$token}") ?>" method="post">
    <input type="password" name="password" placeholder="New password" required>
    <input type="password" name="confirm_password" placeholder="Repeat password" required>
    <button type="submit">Update Password</button>
</form>
<form action="<?= base_url('profile') ?>" method="post">
    <input type="text" name="username" value="<?= esc($user['username']) ?>" required>
    <input type="email" name="email" value="<?= esc($user['email']) ?>" required>
    <input type="password" name="password" placeholder="Leave blank to keep current">
    <button type="submit">Update Profile</button>
</form>
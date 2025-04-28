<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Ticket | Jeera</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h3>Edit Ticket #<?= $ticket['id'] ?></h3>
                    </div>
                    <div class="card-body">
                        <form action="<?= base_url("tickets/update/{$ticket['id']}") ?>" method="post">
                            <?= csrf_field() ?>
                            <div class="mb-3">
                                <label class="form-label">Title</label>
                                <input type="text" name="title" class="form-control" value="<?= esc($ticket['title']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control" rows="5" required><?= esc($ticket['description']) ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Priority</label>
                                <select name="priority" class="form-select" required>
                                <option value="Low" <?= ($ticket['priority'] == 'Low') ? 'selected' : '' ?>>Low</option>
                                    <option value="Medium" <?= ($ticket['priority'] == 'Medium') ? 'selected' : '' ?>>Medium</option>
                                    <option value="High" <?= ($ticket['priority'] == 'High') ? 'selected' : '' ?>>High</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select" required>
                                <option value="Open" <?= ($ticket['status'] == 'Open') ? 'selected' : '' ?>>Open</option>
                                    <option value="In Progress" <?= ($ticket['status'] == 'In Progress') ? 'selected' : '' ?>>In Progress</option>
                                    <option value="Closed" <?= ($ticket['status'] == 'Closed') ? 'selected' : '' ?>>Closed</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Update</button>
                            <a href="<?= base_url('tickets') ?>" class="btn btn-secondary">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tickets | Jeera Ticketing</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    <div class="container mt-4">
        <!-- Success Message (e.g., after ticket creation) -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>


        <!-- app/Views/tickets/list.php -->
        <div class="mb-3">
            <form action="<?= base_url('tickets') ?>" method="get">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search by title/status" value="<?= esc($search) ?>">
                    <button type="submit" class="btn btn-primary">Search</button>
                    <a href="<?= base_url('tickets') ?>" class="btn btn-secondary">Reset</a>
                </div>
            </form>
        </div>

        <div class="card shadow">
            <div class="card-header bg-primary text-white d-flex justify-content-between">
                <h3 class="card-title">Tickets</h3>
                <a href="<?= base_url('tickets/create') ?>" class="btn btn-light">
                    <i class="bi bi-plus-circle"></i> New Ticket
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Priority</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tickets as $ticket): ?>
                                <tr data-id="<?= $ticket['id'] ?>">
                                    <td><?= $ticket['id'] ?></td>
                                    <td><?= esc($ticket['title']) ?></td>
                                    <td>
                                        <span class="badge bg-<?= 
                                            ($ticket['priority'] == 'High') ? 'danger' : 
                                            (($ticket['priority'] == 'Medium') ? 'warning' : 'success') 
                                        ?>">
                                            <?= $ticket['priority'] ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?= 
                                            ($ticket['status'] == 'Open') ? 'primary' : 
                                            (($ticket['status'] == 'In Progress') ? 'info' : 'secondary') 
                                        ?>">
                                            <?= $ticket['status'] ?>
                                        </span>
                                    </td>
                                    <td><?= date('M d, Y h:i A', strtotime($ticket['created_at'])) ?></td>
                                    <td>
                                        <a href="<?= base_url('tickets/' . $ticket['id']) ?>" class="btn btn-sm btn-info">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <?php if (session('user_id') == $ticket['created_by']): ?>
                                            <a href="<?= base_url('tickets/edit/' . $ticket['id']) ?>" class="btn btn-sm btn-warning">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        <?php endif; ?>

                                        <!-- Inside the actions column of the table -->
                                        <?php if (session('user_id') == $ticket['created_by'] || session('role') == 'admin'): ?>
                                            <a href="<?= base_url('tickets/delete/' . $ticket['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <!-- Add this after the table -->
                    <div class="d-flex justify-content-center mt-4">
                        <?= $pager->links() ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Add this before </body> -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>
<script>
    // Make ticket rows draggable
    new Sortable(document.querySelector('tbody'), {
        animation: 150,
        onEnd: function(evt) {
            const ticketId = evt.item.getAttribute('data-id');
            const newStatus = evt.to.parentNode.previousElementSibling.textContent.trim(); // Get status from column
            
            fetch(`/tickets/update-status/${ticketId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ status: newStatus })
            }).then(response => {
                if (!response.ok) {
                    alert('Update failed!');
                    location.reload(); // Revert UI on failure
                }
            });
        }
    });
</script>


</body>
</html>
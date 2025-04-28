<!DOCTYPE html>
<html>
<head>
    <title>Ticket #<?= $ticket['id'] ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3>Ticket #<?= $ticket['id'] ?>: <?= esc($ticket['title']) ?></h3>
            </div>
            <div class="card-body">
                <p><strong>Priority:</strong> <span class="badge bg-<?= 
                    ($ticket['priority'] == 'High') ? 'danger' : 
                    (($ticket['priority'] == 'Medium') ? 'warning' : 'success')
                ?>"><?= $ticket['priority'] ?></span></p>
                <p><strong>Status:</strong> <?= $ticket['status'] ?></p>
                <p><strong>Description:</strong></p>
                <p><?= nl2br(esc($ticket['description'])) ?></p>
                <a href="<?= base_url('tickets') ?>" class="btn btn-secondary">Back</a>
                <hr>
                <!-- comments here -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5>Comments</h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($comments)) : ?>
                            <?php foreach ($comments as $comment) : ?>
                                <div class="mb-3 border-bottom pb-3">
                                    <div class="fw-bold"><?= $comment['username'] ?></div>
                                    <small class="text-muted"><?= date('M j, Y g:i a', strtotime($comment['created_at'])) ?></small>
                                    <p><?= nl2br(esc($comment['comment_text'])) ?></p>
                                </div>
                            <?php endforeach ?>
                        <?php else : ?>
                            <p>No comments yet.</p>
                        <?php endif ?>
                    </div>
                </div>


            </div>
        </div>


        <br><br>
        <div class="card">
         <div class="card-header">
        <h5>Add Comment</h5>
    </div>
    <div class="card-body">
        <?php if (session()->has('errors')) : ?>
            <div class="alert alert-danger">
                <?php foreach (session('errors') as $error) : ?>
                    <?= $error ?><br>
                <?php endforeach ?>
            </div>
        <?php endif ?>

        <?php if (session()->has('success')) : ?>
            <div class="alert alert-success">
                <?= session('success') ?>
            </div>
        <?php endif ?>

        <form action="<?= base_url('comment/save') ?>" method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="ticket_id" value="<?= $ticket['id'] ?>">
            <input type="hidden" name="user_id" value="<?= session()->get('user_id') ?>" readonly>
            
            <div class="mb-3">
                <textarea name="comment_text" class="form-control" rows="3" placeholder="Enter your comment"></textarea>
            </div>
            
            <button type="submit" class="btn btn-primary">Submit Comment</button>
        </form>
    </div>
    </div>
</div>

    <div class="card mt-4">

</div>

</body>
</html>


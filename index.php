<?php
include 'includes/db.php';
include 'includes/header.php';

$sql = "SELECT topics.id, topics.title, topics.created_at, users.username 
        FROM topics 
        JOIN users ON topics.user_id = users.id 
        ORDER BY topics.created_at DESC";
$result = $conn->query($sql);
?>

<div class="d-flex align-items-center justify-content-between mb-3">
    <h2 class="h4 mb-0">Recent Topics</h2>
    <a class="btn btn-primary btn-sm" href="create_topic.php"><i class="bi bi-plus-lg"></i> New Topic</a>
    
</div>

<?php if ($result && $result->num_rows > 0): ?>
    <div class="list-group shadow-sm">
        <?php while ($row = $result->fetch_assoc()): ?>
            <a href="topic.php?id=<?= $row['id'] ?>" class="list-group-item list-group-item-action py-3">
                <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1"><?= htmlspecialchars($row['title']) ?></h5>
                    <small class="text-muted"><?= $row['created_at'] ?></small>
                </div>
                <small class="text-secondary">by <?= htmlspecialchars($row['username']) ?></small>
            </a>
        <?php endwhile; ?>
    </div>
<?php else: ?>
    <div class="alert alert-info">No topics yet. Be the first to create one!</div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>

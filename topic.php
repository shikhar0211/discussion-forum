<?php
include 'includes/db.php';
include 'includes/header.php';

if (!isset($_GET['id'])) {
    echo "<div class=\"alert alert-danger\">Topic not found!</div>";
    include 'includes/footer.php';
    exit;
}

$topic_id = intval($_GET['id']);

// Fetch topic details
$stmt = $conn->prepare("SELECT t.title, t.description, t.created_at, u.username 
                        FROM topics t 
                        JOIN users u ON t.user_id = u.id 
                        WHERE t.id = ?");
$stmt->bind_param("i", $topic_id);
$stmt->execute();
$topic = $stmt->get_result()->fetch_assoc();

if (!$topic) {
    echo "<div class=\"alert alert-danger\">Topic not found!</div>";
    include 'includes/footer.php';
    exit;
}
?>

<div class="card card-forum mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h2 class="h5 mb-0"><?= htmlspecialchars($topic['title']) ?></h2>
        <small class="opacity-75">By <?= htmlspecialchars($topic['username']) ?> • <?= $topic['created_at'] ?></small>
    </div>
    <div class="card-body">
        <p class="mb-0"><?= nl2br(htmlspecialchars($topic['description'])) ?></p>
    </div>
</div>

<h3 class="h5">Replies</h3>
<?php
// Fetch replies with like counts
$stmt = $conn->prepare("SELECT p.id, p.content, p.created_at, u.username,
                        (SELECT COUNT(*) FROM likes WHERE post_id = p.id) AS like_count,
                        (SELECT COUNT(*) FROM likes WHERE post_id = p.id AND user_id = ?) AS user_liked
                        FROM posts p 
                        JOIN users u ON p.user_id = u.id 
                        WHERE p.topic_id = ? 
                        ORDER BY p.created_at ASC");
$viewer_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 0;
$topic_id = intval($_GET['id']); 
$stmt->bind_param("ii", $viewer_id, $topic_id);
$stmt->execute();
$replies = $stmt->get_result();

if ($replies->num_rows > 0):
    while ($row = $replies->fetch_assoc()):
?>
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-1">
                    <strong><?= htmlspecialchars($row['username']) ?></strong>
                    <small class="text-muted"><?= $row['created_at'] ?></small>
                </div>
                <div><?= nl2br(htmlspecialchars($row['content'])) ?></div>
            </div>
            <div class="card-footer bg-white border-0 pt-0">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <form method="post" action="like.php" class="d-inline">
                        <input type="hidden" name="post_id" value="<?= $row['id'] ?>">
                        <input type="hidden" name="topic_id" value="<?= $topic_id ?>">
                        <button class="btn btn-sm btn-outline-primary" type="submit">
                            <?= $row['user_liked'] ? "Unlike" : "Like" ?> (<?= $row['like_count'] ?>)
                        </button>
                    </form>
                <?php else: ?>
                    <small><a href="login.php">Login</a> to like</small>
                <?php endif; ?>
            </div>
        </div>

        <?php if (isset($_SESSION['user_id'])): ?>
            <form method="post" action="like.php" style="display:inline;">
                <input type="hidden" name="post_id" value="<?= $row['id'] ?>">
                <input type="hidden" name="topic_id" value="<?= $topic_id ?>">
                <button type="submit">
                    <?= $row['user_liked'] ? "Unlike" : "Like" ?> (<?= $row['like_count'] ?>)
                </button>
            </form>
        <?php else: ?>
            <small><a href="login.php">Login</a> to like</small>
        <?php endif; ?>
        <hr>
<?php 
    endwhile;
else:
    echo "<div class=\"alert alert-info\">No replies yet. Be the first to reply!</div>";
endif;
?>

<?php if (isset($_SESSION['user_id'])): ?>
    <div class="card border-0 shadow-sm mt-4">
        <div class="card-body">
            <h3 class="h6">Post a Reply</h3>
            <form method="post" action="post_reply.php">
                <input type="hidden" name="topic_id" value="<?= $topic_id ?>">
                <textarea class="form-control mb-3" name="content" rows="4" required></textarea>
                <button class="btn btn-primary" type="submit">Reply</button>
            </form>
        </div>
    </div>
<?php else: ?>
    <div class="alert alert-warning mt-3"><a href="login.php">Login</a> to reply.</div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>

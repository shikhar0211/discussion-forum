<?php
include 'includes/db.php';
include 'includes/header.php';

if (!isset($_GET['id'])) {
    echo "<div class=\"alert alert-danger\">User not found!</div>";
    include 'includes/footer.php';
    exit;
}

$user_id = intval($_GET['id']);

// Fetch user details
$stmt = $conn->prepare("SELECT username, email, bio, role, created_at FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (!$user) {
    echo "<div class=\"alert alert-danger\">User not found!</div>";
    include 'includes/footer.php';
    exit;
}
?>

<div class="row">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <h2 class="h5 mb-0">Profile: <?= htmlspecialchars($user['username']) ?></h2>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
                        <p><strong>Role:</strong> <span class="badge bg-secondary"><?= $user['role'] ?></span></p>
                        <p><strong>Joined:</strong> <?= $user['created_at'] ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Bio:</strong></p>
                        <p><?= $user['bio'] ? nl2br(htmlspecialchars($user['bio'])) : "<em class=\"text-muted\">No bio yet.</em>" ?></p>
                    </div>
                </div>
                <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $user_id): ?>
                    <a href="edit_profile.php" class="btn btn-outline-primary">Edit Profile</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header">
                <h3 class="h6 mb-0">Topics by <?= htmlspecialchars($user['username']) ?></h3>
            </div>
            <div class="card-body">
                <?php
                $stmt = $conn->prepare("SELECT id, title, created_at FROM topics WHERE user_id = ? ORDER BY created_at DESC");
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $topics = $stmt->get_result();

                if ($topics->num_rows > 0) {
                    echo "<div class=\"list-group list-group-flush\">";
                    while ($row = $topics->fetch_assoc()) {
                        echo "<a href='topic.php?id={$row['id']}' class=\"list-group-item list-group-item-action\">";
                        echo "<div class=\"d-flex w-100 justify-content-between\">";
                        echo "<h6 class=\"mb-1\">" . htmlspecialchars($row['title']) . "</h6>";
                        echo "<small class=\"text-muted\">{$row['created_at']}</small>";
                        echo "</div></a>";
                    }
                    echo "</div>";
                } else {
                    echo "<p class=\"text-muted\">No topics created yet.</p>";
                }
                ?>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header">
                <h3 class="h6 mb-0">Replies by <?= htmlspecialchars($user['username']) ?></h3>
            </div>
            <div class="card-body">
                <?php
                $stmt = $conn->prepare("SELECT p.content, p.created_at, t.id AS topic_id, t.title
                                        FROM posts p 
                                        JOIN topics t ON p.topic_id = t.id
                                        WHERE p.user_id = ?
                                        ORDER BY p.created_at DESC");
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $posts = $stmt->get_result();

                if ($posts->num_rows > 0) {
                    echo "<div class=\"list-group list-group-flush\">";
                    while ($row = $posts->fetch_assoc()) {
                        echo "<div class=\"list-group-item\">";
                        echo "<div class=\"d-flex w-100 justify-content-between mb-1\">";
                        echo "<strong>In <a href='topic.php?id={$row['topic_id']}'>" . htmlspecialchars($row['title']) . "</a></strong>";
                        echo "<small class=\"text-muted\">{$row['created_at']}</small>";
                        echo "</div>";
                        echo "<p class=\"mb-0 small\">" . htmlspecialchars(substr($row['content'], 0, 80)) . "...</p>";
                        echo "</div>";
                    }
                    echo "</div>";
                } else {
                    echo "<p class=\"text-muted\">No replies yet.</p>";
                }
                ?>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

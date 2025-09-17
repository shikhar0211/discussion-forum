<?php
include 'includes/db.php';
include 'includes/header.php';

$query = isset($_GET['q']) ? trim($_GET['q']) : '';

?>

<div class="d-flex align-items-center justify-content-between mb-3">
    <h2 class="h4 mb-0">Search Results</h2>
    <?php if ($query !== ''): ?>
        <span class="badge bg-primary">Searching for: "<?= htmlspecialchars($query) ?>"</span>
    <?php endif; ?>
</div>

<?php
if ($query !== '') {
    $search = "%" . $query . "%";

    // Search in topics (title + description)
    $stmt = $conn->prepare("SELECT t.id, t.title, t.created_at, u.username
                            FROM topics t
                            JOIN users u ON t.user_id = u.id
                            WHERE t.title LIKE ? OR t.description LIKE ?
                            ORDER BY t.created_at DESC");
    $stmt->bind_param("ss", $search, $search);
    $stmt->execute();
    $topics = $stmt->get_result();

    if ($topics->num_rows > 0) {
        echo "<div class=\"card border-0 shadow-sm mb-4\">";
        echo "<div class=\"card-header\"><h3 class=\"h6 mb-0\">Topics</h3></div>";
        echo "<div class=\"card-body p-0\">";
        echo "<div class=\"list-group list-group-flush\">";
        while ($row = $topics->fetch_assoc()) {
            echo "<a href='topic.php?id=" . $row['id'] . "' class=\"list-group-item list-group-item-action\">";
            echo "<div class=\"d-flex w-100 justify-content-between\">";
            echo "<h6 class=\"mb-1\">" . htmlspecialchars($row['title']) . "</h6>";
            echo "<small class=\"text-muted\">" . $row['created_at'] . "</small>";
            echo "</div>";
            echo "<small class=\"text-secondary\">by " . htmlspecialchars($row['username']) . "</small>";
            echo "</a>";
        }
        echo "</div></div></div>";
    } else {
        echo "<div class=\"alert alert-info\">No topics found for <strong>" . htmlspecialchars($query) . "</strong>.</div>";
    }

    // Search in posts (optional, to match replies too)
    $stmt = $conn->prepare("SELECT p.content, p.created_at, u.username, t.id AS topic_id, t.title
                            FROM posts p
                            JOIN users u ON p.user_id = u.id
                            JOIN topics t ON p.topic_id = t.id
                            WHERE p.content LIKE ?
                            ORDER BY p.created_at DESC");
    $stmt->bind_param("s", $search);
    $stmt->execute();
    $posts = $stmt->get_result();

    if ($posts->num_rows > 0) {
        echo "<div class=\"card border-0 shadow-sm mb-4\">";
        echo "<div class=\"card-header\"><h3 class=\"h6 mb-0\">Replies</h3></div>";
        echo "<div class=\"card-body p-0\">";
        echo "<div class=\"list-group list-group-flush\">";
        while ($row = $posts->fetch_assoc()) {
            echo "<div class=\"list-group-item\">";
            echo "<div class=\"d-flex w-100 justify-content-between mb-1\">";
            echo "<strong>" . htmlspecialchars($row['username']) . "</strong> replied in ";
            echo "<a href='topic.php?id=" . $row['topic_id'] . "'>" . htmlspecialchars($row['title']) . "</a>";
            echo "<small class=\"text-muted\">" . $row['created_at'] . "</small>";
            echo "</div>";
            echo "<blockquote class=\"blockquote mb-0 small\">";
            echo "<p class=\"mb-0\">" . htmlspecialchars(substr($row['content'], 0, 100)) . "...</p>";
            echo "</blockquote>";
            echo "</div>";
        }
        echo "</div></div></div>";
    }
}
?>

<?php include 'includes/footer.php'; ?>

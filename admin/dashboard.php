<?php
include '../includes/db.php';
include '../includes/header.php';

// Check if logged in and admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo "<div class=\"alert alert-danger\">You are not authorized to access this page.</div>";
    include '../includes/footer.php';
    exit;
}
?>

<div class="d-flex align-items-center justify-content-between mb-4">
    <h2 class="h4 mb-0">Admin Dashboard</h2>
    <a href="../index.php" class="btn btn-outline-secondary btn-sm">← Back to Forum</a>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <nav class="nav nav-pills nav-fill">
            <a class="nav-link <?= ($section === 'users') ? 'active' : '' ?>" href="dashboard.php?section=users">Manage Users</a>
            <a class="nav-link <?= ($section === 'topics') ? 'active' : '' ?>" href="dashboard.php?section=topics">Manage Topics</a>
            <a class="nav-link <?= ($section === 'posts') ? 'active' : '' ?>" href="dashboard.php?section=posts">Manage Posts</a>
        </nav>
    </div>
</div>

<?php
$section = $_GET['section'] ?? 'users';

// Manage Users
if ($section === 'users') {
    $users = $conn->query("SELECT id, username, email, role, created_at FROM users ORDER BY created_at DESC");
    echo "<div class=\"card border-0 shadow-sm\">";
    echo "<div class=\"card-header\"><h3 class=\"h6 mb-0\">Users</h3></div>";
    echo "<div class=\"card-body p-0\">";
    echo "<div class=\"table-responsive\">";
    echo "<table class=\"table table-striped table-hover mb-0\">";
    echo "<thead class=\"table-light\">";
    echo "<tr><th>ID</th><th>Username</th><th>Email</th><th>Role</th><th>Joined</th><th>Action</th></tr>";
    echo "</thead><tbody>";
    while ($row = $users->fetch_assoc()) {
        echo "<tr>";
        echo "<td>{$row['id']}</td>";
        echo "<td><strong>{$row['username']}</strong></td>";
        echo "<td>{$row['email']}</td>";
        echo "<td><span class=\"badge bg-" . ($row['role'] === 'admin' ? 'danger' : 'secondary') . "\">{$row['role']}</span></td>";
        echo "<td><small class=\"text-muted\">{$row['created_at']}</small></td>";
        echo "<td><a href='delete_user.php?id={$row['id']}' class=\"btn btn-sm btn-outline-danger\" onclick=\"return confirm('Are you sure?')\">Delete</a></td>";
        echo "</tr>";
    }
    echo "</tbody></table></div></div></div>";
}

// Manage Topics
if ($section === 'topics') {
    $topics = $conn->query("SELECT t.id, t.title, u.username, t.created_at 
                             FROM topics t 
                             JOIN users u ON t.user_id = u.id 
                             ORDER BY t.created_at DESC");
    echo "<div class=\"card border-0 shadow-sm\">";
    echo "<div class=\"card-header\"><h3 class=\"h6 mb-0\">Topics</h3></div>";
    echo "<div class=\"card-body p-0\">";
    echo "<div class=\"table-responsive\">";
    echo "<table class=\"table table-striped table-hover mb-0\">";
    echo "<thead class=\"table-light\">";
    echo "<tr><th>ID</th><th>Title</th><th>Author</th><th>Created</th><th>Action</th></tr>";
    echo "</thead><tbody>";
    while ($row = $topics->fetch_assoc()) {
        echo "<tr>";
        echo "<td>{$row['id']}</td>";
        echo "<td><strong>{$row['title']}</strong></td>";
        echo "<td>{$row['username']}</td>";
        echo "<td><small class=\"text-muted\">{$row['created_at']}</small></td>";
        echo "<td><a href='delete_topic.php?id={$row['id']}' class=\"btn btn-sm btn-outline-danger\" onclick=\"return confirm('Are you sure?')\">Delete</a></td>";
        echo "</tr>";
    }
    echo "</tbody></table></div></div></div>";
}

// Manage Posts
if ($section === 'posts') {
    $posts = $conn->query("SELECT p.id, p.content, u.username, t.title, p.created_at 
                           FROM posts p 
                           JOIN users u ON p.user_id = u.id 
                           JOIN topics t ON p.topic_id = t.id 
                           ORDER BY p.created_at DESC");
    echo "<div class=\"card border-0 shadow-sm\">";
    echo "<div class=\"card-header\"><h3 class=\"h6 mb-0\">Posts</h3></div>";
    echo "<div class=\"card-body p-0\">";
    echo "<div class=\"table-responsive\">";
    echo "<table class=\"table table-striped table-hover mb-0\">";
    echo "<thead class=\"table-light\">";
    echo "<tr><th>ID</th><th>Content</th><th>User</th><th>Topic</th><th>Created</th><th>Action</th></tr>";
    echo "</thead><tbody>";
    while ($row = $posts->fetch_assoc()) {
        echo "<tr>";
        echo "<td>{$row['id']}</td>";
        echo "<td><small>" . htmlspecialchars(substr($row['content'], 0, 50)) . "...</small></td>";
        echo "<td><strong>{$row['username']}</strong></td>";
        echo "<td>{$row['title']}</td>";
        echo "<td><small class=\"text-muted\">{$row['created_at']}</small></td>";
        echo "<td><a href='delete_post.php?id={$row['id']}' class=\"btn btn-sm btn-outline-danger\" onclick=\"return confirm('Are you sure?')\">Delete</a></td>";
        echo "</tr>";
    }
    echo "</tbody></table></div></div></div>";
}
?>

<?php include '../includes/footer.php'; ?>

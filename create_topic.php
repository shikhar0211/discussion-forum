<?php
include 'includes/db.php';
include 'includes/auth.php'; // ensures user is logged in BEFORE any output
include 'includes/header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = htmlspecialchars($_POST['title']);
    $description = htmlspecialchars($_POST['description']);
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO topics (user_id, title, description) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $user_id, $title, $description);

    if ($stmt->execute()) {
        $new_topic_id = $stmt->insert_id;
        header("Location: topic.php?id=" . $new_topic_id);
        exit;
    } else {
        $error = "Error creating topic: " . $stmt->error;
    }
}
?>

<h2 class="h4 mb-3">Create New Topic</h2>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form method="post">
            <div class="mb-3">
                <label class="form-label">Title</label>
                <input class="form-control" type="text" name="title" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea class="form-control" name="description" rows="5" required></textarea>
            </div>
            <button class="btn btn-primary" type="submit">Create Topic</button>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

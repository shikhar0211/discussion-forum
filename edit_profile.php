<?php
include 'includes/db.php';
include 'includes/auth.php'; // must be logged in BEFORE any output
include 'includes/header.php';

$user_id = $_SESSION['user_id'];

// Fetch current bio
$stmt = $conn->prepare("SELECT bio FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $bio = htmlspecialchars($_POST['bio']);

    $stmt = $conn->prepare("UPDATE users SET bio = ? WHERE id = ?");
    $stmt->bind_param("si", $bio, $user_id);

    if ($stmt->execute()) {
        header("Location: profile.php?id=" . $user_id);
        exit;
    } else {
        $error = "Error updating profile: " . $stmt->error;
    }
}
?>

<h2 class="h4 mb-3">Edit Profile</h2>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form method="post">
            <div class="mb-3">
                <label class="form-label">Bio</label>
                <textarea class="form-control" name="bio" rows="5"><?= htmlspecialchars($user['bio']) ?></textarea>
            </div>
            <button class="btn btn-primary" type="submit">Update Profile</button>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

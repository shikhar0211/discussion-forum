<?php
include 'includes/db.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id'])) {
    $topic_id = intval($_POST['topic_id']);
    $content = htmlspecialchars($_POST['content']);
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO posts (topic_id, user_id, content) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $topic_id, $user_id, $content);

    if ($stmt->execute()) {
        header("Location: topic.php?id=" . $topic_id);
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
} else {
    header("Location: index.php");
    exit;
}

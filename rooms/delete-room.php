<?php
include '../db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM rooms WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: list.php");
        exit();
    } else {
        echo "❌ Failed to delete: " . $conn->error;
    }
} else {
    echo "❌ No room ID provided.";
}
?>


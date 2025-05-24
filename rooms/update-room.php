<?php
include '../db.php';

if (isset($_POST['update_room'])) {
    $id = $_POST['id'];
    $room_number = $_POST['room_number'];
    $type = $_POST['type'];
    $price = $_POST['price'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE rooms SET room_number = ?, type = ?, price = ?, status = ? WHERE id = ?");
    $stmt->bind_param("ssdsi", $room_number, $type, $price, $status, $id);

    if ($stmt->execute()) {
        header("Location: list.php"); // ✅ Go back to the room list after updating
        exit();
    } else {
        echo "❌ Update failed: " . $conn->error;
    }
} else {
    echo "❌ Invalid request.";
}
?>

<?php
include '../db.php';

if (isset($_GET['id'])) {
    $guest_id = $_GET['id'];

    // Get the room_id of this guest
    $guest = $conn->query("SELECT * FROM guests WHERE id = $guest_id")->fetch_assoc();
    $room_id = $guest['room_id'];

    // Update guest status
    $conn->query("UPDATE guests SET status = 'checked_out' WHERE id = $guest_id");

    // Set room as available again
    $conn->query("UPDATE rooms SET status = 'available' WHERE id = $room_id");

    header("Location: list.php"); // Redirect back to guest list
    exit();
}
?>
<?php include '../includes/footer.php'; ?>

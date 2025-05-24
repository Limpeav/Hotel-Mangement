<?php
session_start();
include '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['booking_id'])) {
    $bookingId = intval($_POST['booking_id']);

    // Step 1: Get room_id from the booking
    $stmt = $conn->prepare("SELECT room_id FROM bookings WHERE id = ?");
    $stmt->bind_param('i', $bookingId);
    $stmt->execute();
    $stmt->bind_result($roomId);
    $stmt->fetch();
    $stmt->close();

    // Step 2: Cancel the booking
    $stmt = $conn->prepare("UPDATE bookings SET status = 'canceled' WHERE id = ?");
    $stmt->bind_param('i', $bookingId);
    $stmt->execute();
    $stmt->close();

    // Step 3: Set the room status to 'available'
    if ($roomId) {
        $stmt = $conn->prepare("UPDATE rooms SET status = 'available' WHERE id = ?");
        $stmt->bind_param('i', $roomId);
        $stmt->execute();
        $stmt->close();
    }

    // Redirect with a message
    header('Location: list.php?message=Booking+Canceled+Successfully');
    exit;
} else {
    // Invalid request
    header('Location: list.php?error=Invalid+Request');
    exit;
}

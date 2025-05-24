<?php
session_start();
include '../db.php';
include '../includes/functions.php';

if (!isset($_SESSION['role'])) {
    header('Location: ../auth/login.php');
    exit;
}

$message = '';
$guest_name = '';
$phone = '';
$room_type = '';
$booking_date = '';

if (isset($_POST['add_booking'])) {
    $guest_name = $_POST['guest_name'];
    $phone = $_POST['phone'];
    $room_type = $_POST['room_type'];
    $booking_date = $_POST['booking_date'];

    $stmt = $conn->prepare("SELECT * FROM rooms WHERE type = ? AND status = 'available' LIMIT 1");
    $stmt->bind_param("s", $room_type);
    $stmt->execute();
    $room_result = $stmt->get_result();

    if ($room_result->num_rows > 0) {
        $room = $room_result->fetch_assoc();
        $room_id = $room['id'];

        $insert = $conn->prepare("INSERT INTO bookings (guest_name, phone, room_id, booking_date, status) VALUES (?, ?, ?, ?, 'booked')");
        $insert->bind_param("ssis", $guest_name, $phone, $room_id, $booking_date);

        if ($insert->execute()) {
            $conn->query("UPDATE rooms SET status = 'booked' WHERE id = $room_id");
            $message = "✅ Room booked successfully for $guest_name!";

            // Clear form values
            $guest_name = '';
            $phone = '';
            $room_type = '';
            $booking_date = '';
        } else {
            $message = "❌ Failed to book room.";
        }
    } else {
        $message = "❌ No available room for selected type.";
    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Add Booking</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f4f4;
        }

        .main-content {
            margin-left: 240px;
            /* same width as sidebar */
            padding: 40px;
        }

        h2 {
            margin-bottom: 20px;
        }

        form {
            background: white;
            padding: 30px;
            border-radius: 8px;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        input,
        select {
            width: 100%;
            padding: 10px;
            margin-top: 8px;
            margin-bottom: 20px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        button {
            background: #2c3e50;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background: #1a252f;
        }

        .message {
            margin-bottom: 20px;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <?php include '../includes/sidebar.php'; ?>

    <div class="main-content">
        <h2>Add Booking</h2>
        <?php if ($message): ?>
            <div class="message"><?= $message ?></div>
        <?php endif; ?>

        <form method="POST">
            <label>Guest Name:</label>
            <input type="text" name="guest_name" required value="<?= htmlspecialchars($guest_name) ?>">

            <label>Phone:</label>
            <input type="text" name="phone" required value="<?= htmlspecialchars($phone) ?>">

            <label>Room Type:</label>
            <select name="room_type" required>
                <option value="">Select Room Type</option>
                <option value="Single" <?= $room_type == 'Single' ? 'selected' : '' ?>>Single</option>
                <option value="Double" <?= $room_type == 'Double' ? 'selected' : '' ?>>Double</option>
                <option value="Suite" <?= $room_type == 'Suite' ? 'selected' : '' ?>>Suite</option>
            </select>

            <label>Booking Date:</label>
            <input type="date" name="booking_date" required value="<?= htmlspecialchars($booking_date) ?>">

            <button type="submit" name="add_booking">Book Room</button>
        </form>

    </div>

</body>

</html>
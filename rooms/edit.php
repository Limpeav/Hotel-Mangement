<?php
include '../db.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_GET['id'])) {
    die("❌ No room ID provided.");
}

$id = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM rooms WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    die("❌ Room not found.");
}

$room = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Room - Hotel Management System</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 40px;
        }

        .container {
            max-width: 500px;
            margin: auto;
            background: white;
            padding: 30px 40px;
            border-radius: 10px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.08);
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 500;
        }

        input[type="text"],
        input[type="number"],
        select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            color: white;
            border: none;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        .back-link {
            display: block;
            margin-top: 20px;
            text-align: center;
            color: #007bff;
            text-decoration: none;
            font-size: 14px;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Room</h2>
        <form method="POST" action="update-room.php">
            <input type="hidden" name="id" value="<?= $room['id'] ?>">

            <label>Room Number:</label>
            <input type="text" name="room_number" value="<?= htmlspecialchars($room['room_number']) ?>" required>

            <label>Type:</label>
            <select name="type" required>
                <option value="Single" <?= $room['type'] === 'Single' ? 'selected' : '' ?>>Single</option>
                <option value="Double" <?= $room['type'] === 'Double' ? 'selected' : '' ?>>Double</option>
                <option value="Suite" <?= $room['type'] === 'Suite' ? 'selected' : '' ?>>Suite</option>
            </select>

            <label>Price ($):</label>
            <input type="number" name="price" step="0.01" value="<?= $room['price'] ?>" required>

            <label>Status:</label>
            <select name="status">
                <option value="available" <?= $room['status'] === 'available' ? 'selected' : '' ?>>Available</option>
                <option value="booked" <?= $room['status'] === 'booked' ? 'selected' : '' ?>>Booked</option>
            </select>

            <button type="submit" name="update_room">Update Room</button>
        </form>
        <a class="back-link" href="list.php">← Back to Room List</a>
    </div>
</body>
</html>

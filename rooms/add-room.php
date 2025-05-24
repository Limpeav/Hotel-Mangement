<?php
session_start();
include '../db.php';

if (!isset($_SESSION['role'])) {
    header('Location: ../auth/login.php');
    exit;
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_room'])) {
    $room_number = $_POST['room_number'];
    $type = $_POST['type'];
    $price = $_POST['price'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("INSERT INTO rooms (room_number, type, price, status) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssds", $room_number, $type, $price, $status);

    if ($stmt->execute()) {
        $message = "✅ Room added successfully!";
    } else {
        $message = "❌ Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Add Room - Hotel System</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body {
            background: #f4f6f9;
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
        }

        .main-content {
            margin-left: 240px;
            padding: 40px 30px;
            min-height: 100vh;
        }

        .form-card {
            background: #fff;
            padding: 30px;
            max-width: 600px;
            margin: auto;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        h2 {
            margin-bottom: 25px;
            text-align: center;
            color: #2c3e50;
        }

        label {
            font-weight: bold;
            color: #333;
        }

        input,
        select {
            width: 100%;
            padding: 10px 12px;
            margin-top: 5px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            color: white;
            font-weight: bold;
            border: none;
            border-radius: 6px;
            font-size: 15px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        .message {
            padding: 14px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 14px;
            line-height: 1.5;
        }

        .success {
            background-color: #e6ffed;
            border-left: 5px solid #28a745;
            color: #155724;
        }

        .error {
            background-color: #f8d7da;
            border-left: 5px solid #dc3545;
            color: #721c24;
        }
    </style>
</head>

<body>

    <?php include '../includes/sidebar.php'; ?>

    <div class="main-content">
        <div class="form-card">
            <h2>Add New Room</h2>

            <?php if ($message): ?>
                <div class="message <?= str_starts_with($message, '✅') ? 'success' : 'error' ?>">
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <label>Room Number</label>
                <input type="text" name="room_number" required>

                <label>Room Type</label>
                <select name="type" required>
                    <option value="">-- Select Type --</option>
                    <option value="Single">Single</option>
                    <option value="Double">Double</option>
                    <option value="Suite">Suite</option>
                </select>

                <label>Price ($)</label>
                <input type="number" name="price" step="0.01" required>

                <label>Status</label>
                <select name="status" required>
                    <option value="available">Available</option>
                    <option value="booked">Booked</option>
                </select>

                <button type="submit" name="add_room">Add Room</button>
            </form>
        </div>
    </div>

</body>

</html>
<?php
session_start();
include '../db.php';

if (!isset($_SESSION['role'])) {
    header('Location: ../auth/login.php');
    exit;
}

$message = '';

if (isset($_POST['add_guest'])) {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $room_id = $_POST['room_id'];
    $check_in = $_POST['check_in'];
    $check_out = !empty($_POST['check_out']) ? $_POST['check_out'] : null;
    
    // ✅ Always set status to 'checked_in'
    $status = 'checked_in';

    $stmt = $conn->prepare("INSERT INTO guests (name, phone, address, room_id, check_in, check_out, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssisss", $name, $phone, $address, $room_id, $check_in, $check_out, $status);

    if ($stmt->execute()) {
        // Optional: set room to 'booked'
        $conn->query("UPDATE rooms SET status = 'booked' WHERE id = $room_id");
        $message = "✅ Guest $name added successfully!";
    } else {
        $message = "❌ Error: " . $conn->error;
    }
}

// Fetch available rooms
$rooms = [];
$result = $conn->query("SELECT id, room_number FROM rooms WHERE status = 'available'");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $rooms[] = $row;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Guest</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .main-content {
            margin-left: 240px;
            padding: 40px 30px;
            min-height: 100vh;
        }

        form {
            background: #fff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 0 8px rgba(0,0,0,0.1);
            max-width: 600px;
        }

        h2 {
            color: #2c3e50;
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
        }

        input, select, textarea {
            width: 100%;
            padding: 8px;
            margin-top: 4px;
            margin-bottom: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        textarea {
            resize: vertical;
            height: 80px;
        }

        button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        .message {
            background-color: #e9f7ef;
            border-left: 5px solid #28a745;
            padding: 15px;
            margin-bottom: 20px;
        }

        .error {
            background-color: #f8d7da;
            border-left: 5px solid #dc3545;
        }
    </style>

    <script>
        window.onload = () => {
            const current = window.location.pathname;
            if (current.includes('guests')) {
                document.getElementById('guestMenu')?.classList.add("show");
                document.getElementById('guestArrow')?.textContent = "▼";
            }
        };
    </script>
</head>
<body>

<?php include '../includes/sidebar.php'; ?>

<div class="main-content">
    <h2>Add Guest</h2>

    <?php if ($message): ?>
        <div class="message <?= str_starts_with($message, '❌') ? 'error' : '' ?>">
            <?= $message ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <label>Name:</label>
        <input type="text" name="name" required>

        <label>Phone:</label>
        <input type="text" name="phone" required>

        <label>Address:</label>
        <textarea name="address" required></textarea>

        <label>Assign Room:</label>
        <select name="room_id" required>
            <option value="">Select a room</option>
            <?php foreach ($rooms as $room): ?>
                <option value="<?= $room['id'] ?>">Room <?= htmlspecialchars($room['room_number']) ?></option>
            <?php endforeach; ?>
        </select>

        <label>Check-in Date:</label>
        <input type="date" name="check_in" required>

        <label>Check-out Date (optional):</label>
        <input type="date" name="check_out">

        <button type="submit" name="add_guest">Add Guest</button>
    </form>
</div>

<?php include '../includes/footer.php'; ?>

</body>
</html>

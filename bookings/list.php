<?php
session_start();
include '../db.php';

if (!isset($_SESSION['role'])) {
    header('Location: ../auth/login.php');
    exit;
}

$showSidebar = !(isset($_GET['from']) && $_GET['from'] === 'dashboard');
$search = $_GET['search'] ?? '';

$filterSql = "WHERE bookings.status != 'canceled'";

if (!empty($search)) {
    $searchTerm = "%$search%";
    $filterSql .= " AND (bookings.guest_name LIKE ? OR bookings.phone LIKE ?)";
    $usePrepared = true;
} else {
    $usePrepared = false;
}

if (isset($_GET['filter']) && $_GET['filter'] === 'today') {
    $todayDate = date('Y-m-d');
    $filterSql .= " AND DATE(bookings.booking_date) = '$todayDate'";
}

$sql = "SELECT bookings.*, rooms.room_number, rooms.type 
        FROM bookings 
        JOIN rooms ON bookings.room_id = rooms.id 
        $filterSql
        ORDER BY bookings.id DESC";

if ($usePrepared) {
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query($sql);
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Booking List</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }

        .main-content {
            background-color: #f4f4f4;
        }

        h2 {
            margin-bottom: 20px;
            color: #2c3e50;
        }

        .search-form {
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
        }

        .search-form input {
            padding: 10px 14px;
            min-width: 300px;
            max-width: 500px;
            flex: 1;
            border-radius: 4px;
            border: 1px solid #ccc;
            font-size: 16px;
        }


        .search-form button {
            padding: 8px 16px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .search-form button:hover {
            background-color: #0056b3;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            background-color: #fff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        th,
        td {
            padding: 12px 15px;
            text-align: center;
            border-bottom: 1px solid #eee;
        }

        th {
            background-color: #2c3e50;
            color: white;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .no-data {
            text-align: center;
            padding: 20px;
            color: #888;
        }
    </style>
</head>

<body>

    <?php if ($showSidebar): ?>
        <?php include '../includes/sidebar.php'; ?>
    <?php endif; ?>

    <div class="main-content" style="<?= $showSidebar ? 'margin-left: 240px;' : '' ?> padding: 40px;">
        <h2>📋 Booking List</h2>

        <form method="GET" class="search-form">
            <input type="text" name="search" placeholder="Search by guest name or phone number." value="<?= htmlspecialchars($search) ?>">
            <button type="submit">Search</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>Guest Name</th>
                    <th>Phone</th>
                    <th>Room Number</th>
                    <th>Room Type</th>
                    <th>Booking Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($booking = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($booking['guest_name'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($booking['phone'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($booking['room_number'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($booking['type'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($booking['booking_date'] ?? '-') ?></td>
                            <td><?= htmlspecialchars(ucfirst($booking['status'] ?? '-')) ?></td>
                            <td>
                                <?php if ($booking['status'] !== 'canceled'): ?>
                                    <form method="POST" action="cancel-booking.php" onsubmit="return confirm('Are you sure you want to cancel this booking?');">
                                        <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">
                                        <button type="submit" style="padding: 6px 12px; background-color: #e74c3c; color: white; border: none; border-radius: 4px; cursor: pointer;">Cancel</button>
                                    </form>
                                <?php else: ?>
                                    <span style="color: #999;">Canceled</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td class="no-data" colspan="7">No bookings found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</body>

</html>
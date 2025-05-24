<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit;
}

include_once '../db.php';
include_once '../includes/functions.php';

// Count dashboard metrics
$totalRooms = countTotal($conn, 'rooms');
$availableRooms = countAvailableRooms($conn);
$todayBookings = countTodayBookings($conn);
$totalGuests = countTotal($conn, 'guests');
?>
<!DOCTYPE html>
<html>

<head>
    <title>Admin Dashboard</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f4f4;
        }

        .main-content {
            margin-left: 240px;
            padding: 40px;
        }

        h2 {
            margin-bottom: 20px;
            color: #2c3e50;
        }

        .card-container {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .card {
            flex: 1 1 200px;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .card h3 {
            margin-bottom: 10px;
            color: #34495e;
        }

        .card p {
            font-size: 24px;
            color: #2ecc71;
            margin: 0;
        }

        a {
            color: inherit;
        }

        a:hover {
            opacity: 0.9;
        }
    </style>
</head>

<body>

    <?php include '../includes/sidebar.php'; ?>

    <div class="main-content">
        <h2>📊 Admin Dashboard</h2>
        <div class="card-container">
            <a href="../rooms/list.php?from=dashboard&filter=available" target="_blank" style="text-decoration: none;">
                <div class="card">
                    <h3>Total Rooms</h3>
                    <p><?= $totalRooms ?></p>
                </div>
            </a>

            <a href="../rooms/list.php?from=dashboard&filter=available" target="_blank" style="text-decoration: none;">
                <div class="card">
                    <h3>Available Rooms</h3>
                    <p><?= $availableRooms ?></p>
                </div>
            </a>


            <a href="../bookings/list.php?from=dashboard&filter=today" target="_blank" style="text-decoration: none;">
                <div class="card">
                    <h3>Today's Bookings</h3>
                    <p><?= $todayBookings ?></p>
                </div>
            </a>

            
            <a href="../guests/list.php?from=dashboard" target="_blank" style="text-decoration: none;">
                <div class="card">
                    <h3>Total Guests</h3>
                    <p><?= $totalGuests ?></p>
                </div>
            </a>

        </div>
    </div>

</body>

</html>
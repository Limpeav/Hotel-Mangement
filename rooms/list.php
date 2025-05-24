<?php
session_start();
include '../db.php';

$showSidebar = true;
$filter = '';

// Only hide sidebar if coming from dashboard AND viewing available rooms
if (
    isset($_GET['from'], $_GET['filter']) &&
    $_GET['from'] === 'dashboard' &&
    $_GET['filter'] === 'available'
) {
    $showSidebar = false;
}

// If filtering available rooms
if (isset($_GET['filter']) && $_GET['filter'] === 'available') {
    $filter = "WHERE status = 'available'";
}

$sql = "SELECT * FROM rooms $filter";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Room List - Hotel Management System</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 30px;
        }

        .container {
            max-width: 1100px;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            <?php if ($showSidebar): ?>margin-left: 240px;
            <?php endif; ?>
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            overflow: hidden;
            border-radius: 8px;
            table-layout: fixed;
        }

        th,
        td {
            padding: 18px 20px;
            text-align: left;
            word-wrap: break-word;
        }

        th {
            background-color: #f7f7f7;
            font-weight: 600;
            border-bottom: 1px solid #ddd;
        }

        tr:nth-child(even) {
            background-color: #fcfcfc;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        td a {
            color: #007bff;
            text-decoration: none;
            margin-right: 12px;
            font-weight: 500;
        }

        td a:hover {
            text-decoration: underline;
        }

        .actions {
            text-align: center;
            white-space: nowrap;
        }
    </style>
</head>

<body>

    <?php if ($showSidebar): ?>
        <?php include '../includes/sidebar.php'; ?>
    <?php endif; ?>

    <div class="container">
        <h2><?= ($filter ? 'Available Rooms' : 'Room List') ?></h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Room Number</th>
                    <th>Type</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th class="actions">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['room_number']) ?></td>
                        <td><?= htmlspecialchars($row['type']) ?></td>
                        <td>$<?= number_format($row['price'], 2) ?></td>
                        <td><?= ucfirst(htmlspecialchars($row['status'])) ?></td>
                        <td class="actions">
                            <a href="edit.php?id=<?= $row['id'] ?>">✏️ Edit</a>
                            <a href="delete-room.php?id=<?= $row['id'] ?>" onclick="return confirm('Are you sure?');">🗑️ Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</body>

</html>
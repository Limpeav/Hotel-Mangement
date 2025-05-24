<?php
session_start();
include '../db.php';

if (!isset($_SESSION['role'])) {
    header('Location: ../auth/login.php');
    exit;
}

$search = $_GET['search'] ?? '';
$showSidebar = !(isset($_GET['from']) && $_GET['from'] === 'dashboard');

$sql = "SELECT guests.*, rooms.room_number 
        FROM guests 
        JOIN rooms ON guests.room_id = rooms.id";

if (!empty($search)) {
    $searchTerm = "%$search%";
    $sql .= " WHERE guests.name LIKE ? OR guests.phone LIKE ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $sql .= " ORDER BY guests.id DESC";
    $result = $conn->query($sql);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Guest List</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8f9fa;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .main-content {
            <?php if ($showSidebar): ?>margin-left: 240px;
            <?php endif; ?>padding: 20px 30px;
        }

        header {
            margin-bottom: 10px;
        }

        header h1 {
            font-weight: 700;
            font-size: 2rem;
            color: #2c3e50;
            margin: 0;
        }

        .search-form {
            display: flex;
            gap: 10px;
            margin: 15px 0 25px;
        }

        .search-form input[type="text"] {
            padding: 10px;
            width: 250px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 14px;
        }

        .search-form button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 18px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }

        .search-form button:hover {
            background-color: #0056b3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        th,
        td {
            padding: 14px 18px;
            text-align: center;
            border-bottom: 1px solid #dee2e6;
            font-size: 0.95rem;
        }

        th {
            background-color: #007bff;
            color: white;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        tbody tr:hover {
            background-color: #f1f7ff;
        }

        .btn-checkout {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 7px 15px;
            border-radius: 5px;
            font-weight: 600;
            cursor: pointer;
        }

        .btn-checkout:hover {
            background-color: #1e7e34;
        }

        .checked-out {
            color: #6c757d;
            font-weight: 600;
            font-style: italic;
        }

        @media (max-width: 700px) {
            .main-content {
                margin-left: 0;
                padding: 15px;
            }

            .search-form {
                flex-direction: column;
                width: 100%;
            }

            .search-form input,
            .search-form button {
                width: 100%;
            }
        }
    </style>
</head>

<body>

    <?php if ($showSidebar): ?>
        <?php include '../includes/sidebar.php'; ?>
    <?php endif; ?>

    <div class="main-content">
        <header>
            <h1>Guest List</h1>
        </header>

        <form method="GET" class="search-form">
            <input type="text" name="search" placeholder="Search guest..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
            <button type="submit">Search</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Room</th>
                    <th>Check-in</th>
                    <th>Check-out</th>
                    <th colspan="3" style="text-align: center;">Action</th>

                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($guest = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($guest['name']) ?></td>
                            <td><?= htmlspecialchars($guest['phone']) ?></td>
                            <td><?= htmlspecialchars($guest['room_number']) ?></td>
                            <td><?= htmlspecialchars($guest['check_in']) ?></td>
                            <td><?= htmlspecialchars($guest['check_out']) ?></td>
                            <td>
                                <?php if ($guest['status'] === 'checked_in'): ?>
                                    <form action="checkout.php" method="GET" onsubmit="return confirm('Check out this guest?');">
                                        <input type="hidden" name="id" value="<?= $guest['id'] ?>">
                                        <button type="submit" class="btn-checkout">Check Out</button>
                                    </form>
                                <?php else: ?>
                                    <span class="checked-out">✅ Checked out</span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <?php if ($_SESSION['role'] === 'admin'): ?>
                                    <a href="edit-guest.php?id=<?= $guest['id'] ?>" style="padding: 6px 12px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px; font-weight: 600;">Edit</a>
                                <?php endif; ?>
                            </td>

                            <td>
                                <?php if ($_SESSION['role'] === 'admin'): ?>
                                    <a href="delete-guest.php?id=<?= $guest['id'] ?>"
                                        onclick="return confirm('Are you sure you want to delete this guest?');"
                                        style="padding: 6px 12px; background-color: #dc3545; color: #fff; text-decoration: none; border-radius: 5px; font-weight: 600;">
                                        Delete
                                    </a>
                                <?php endif; ?>
                            </td>



                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="padding: 20px; font-style: italic;">No guests found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <?php if ($showSidebar): ?>
            <?php include '../includes/footer.php'; ?>
        <?php endif; ?>
    </div>

</body>

</html>
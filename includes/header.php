<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <title>Hotel Management System</title>
    <link rel="stylesheet" href="../css/style.css" />
</head>
<body>
<header>
    <nav>
        <ul>
            <li><a href="../index.php">Home</a></li>
            <li><a href="../rooms/list.php">Rooms</a></li>
            <li><a href="../guests/list.php">Guests</a></li>
            <?php if (isset($_SESSION['user'])): ?>
                <li><a href="../auth/logout.php">Logout (<?= htmlspecialchars($_SESSION['user']) ?>)</a></li>
            <?php else: ?>
                <li><a href="../auth/login.php">Login</a></li>
                <li><a href="../auth/register.php">Register</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>
<main>

<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'staff') {
    header('Location: ../auth/login.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head><title>Staff Dashboard</title></head>
<body>
    <?php include '../includes/sidebar.php'; ?>
    <div class="main-content">
        <h2>Staff Dashboard</h2>
        <p>Welcome, <?= htmlspecialchars($_SESSION['username']) ?>. You can assist with guest management and bookings.</p>
    </div>
</body>
</html>

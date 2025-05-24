<?php
include '../db.php'; // Adjust path as needed

$message = '';

if (isset($_POST['register'])) {
    $name = trim($_POST['name']);
    $username = trim($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];
    $created_at = date('Y-m-d H:i:s');

    $stmt = $conn->prepare("INSERT INTO users (name, username, password, role, created_at) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $username, $password, $role, $created_at);

    if ($stmt->execute()) {
        $message = "✅ User registered successfully!";
    } else {
        $message = "❌ Error registering user: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        form { max-width: 400px; margin: auto; }
        input, select, button { width: 100%; padding: 10px; margin-bottom: 10px; }
        .message { text-align: center; color: green; }
    </style>
</head>
<body>

<h2>User Registration</h2>
<?php if ($message): ?>
    <p class="message"><?= $message ?></p>
<?php endif; ?>

<form method="POST">
    <label>Full Name:</label>
    <input type="text" name="name" required>

    <label>Username:</label>
    <input type="text" name="username" required>

    <label>Password:</label>
    <input type="password" name="password" required>

    <label>Role:</label>
    <select name="role" required>
        <option value="">Select Role</option>
        <option value="admin">Admin</option>
        <option value="staff">Staff</option>
    </select>

    <button type="submit" name="register">Register</button>
</form>

</body>
</html>

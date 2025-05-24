<?php
$role = $_SESSION['role'] ?? '';
$username = $_SESSION['username'] ?? 'Guest';
$current = basename($_SERVER['PHP_SELF']);
?>

<!-- Sidebar CSS -->
<style>
    body {
        margin: 0;
        font-family: 'Segoe UI', sans-serif;
        display: flex;
    }

    .sidebar {
        width: 240px;
        height: 100vh;
        background-color: #1e272e;
        color: white;
        padding: 20px;
        box-sizing: border-box;
        position: fixed;
        left: 0;
        top: 0;
        overflow-y: auto;
    }

    .sidebar h3 {
        font-size: 20px;
        margin-bottom: 5px;
    }

    .sidebar p {
        font-size: 13px;
        color: #d2dae2;
        margin-bottom: 25px;
    }

    .sidebar a,
    .toggle-btn {
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: #ecf0f1;
        padding: 10px 12px;
        margin-bottom: 5px;
        border-radius: 6px;
        text-decoration: none;
        font-size: 15px;
        transition: 0.3s ease;
        background-color: transparent;
    }

    .sidebar a:hover,
    .toggle-btn:hover {
        background-color: #485460;
    }

    .sidebar a.active {
        background-color: #2f3640;
        font-weight: bold;
        font-size: 16px;
        border-left: 4px solid #00a8ff;
    }

    .toggle-btn {
        cursor: pointer;
        background: none;
        border: none;
        font-size: 15px;
    }

    .submenu {
        display: none;
        flex-direction: column;
        margin-left: 10px;
        border-left: 2px solid #485460;
        padding-left: 10px;
        margin-top: 5px;
    }

    .submenu.show {
        display: flex;
    }

    .submenu a {
        font-size: 14px;
        margin: 3px 0;
    }

    hr {
        border: none;
        border-top: 1px solid #485460;
        margin: 20px 0;
    }
</style>

<!-- Sidebar HTML -->
<div class="sidebar">
    <h3><?= ucfirst($role) ?> Panel</h3>
    <p>Hello, <?= htmlspecialchars($username) ?></p>
    <hr>

    <?php if ($role === 'admin'): ?>
        <a href="../dashboard/admin.php" class="<?= $current == 'admin.php' ? 'active' : '' ?>">🏠 Dashboard</a>

        <!-- Manage Rooms -->
        <button class="toggle-btn" onclick="toggleMenu('roomMenu', 'roomArrow')">
            🛏️ Manage Rooms <span id="roomArrow">►</span>
        </button>
        <div id="roomMenu" class="submenu">
            <a href="../rooms/add-room.php" class="<?= $current == 'add-room.php' ? 'active' : '' ?>">➕ Add Room</a>
            <a href="../rooms/list.php" class="<?= $current == 'list.php' && strpos($_SERVER['PHP_SELF'], 'rooms') !== false ? 'active' : '' ?>">📄 List Rooms</a>
        </div>

        <!-- Manage Guests -->
        <button class="toggle-btn" onclick="toggleMenu('guestMenuAdmin', 'guestArrowAdmin')">
            👤 Manage Guests <span id="guestArrowAdmin">►</span>
        </button>
        <div id="guestMenuAdmin" class="submenu">
            <a href="../guests/add-guest.php" class="<?= $current == 'add-guest.php' ? 'active' : '' ?>">➕ Add Guest</a>
            <a href="../guests/list.php" class="<?= $current == 'list.php' && strpos($_SERVER['PHP_SELF'], 'guests') !== false ? 'active' : '' ?>">📄 Guest List</a>
        </div>

        <!-- Manage Bookings -->
        <button class="toggle-btn" onclick="toggleMenu('bookingMenuAdmin', 'bookingArrowAdmin')">
            📁 Manage Bookings <span id="bookingArrowAdmin">►</span>
        </button>
        <div id="bookingMenuAdmin" class="submenu">
            <a href="../bookings/add-booking.php" class="<?= $current == 'add-booking.php' ? 'active' : '' ?>">➕ Add Booking</a>
            <a href="../bookings/list.php" class="<?= $current == 'list.php' && strpos($_SERVER['PHP_SELF'], 'bookings') !== false ? 'active' : '' ?>">📄 List Bookings</a>
        </div>

        <a href="../reports/daily-report.php" class="<?= $current == 'daily-report.php' ? 'active' : '' ?>">📊 Daily Reports</a>

    <?php elseif ($role === 'staff'): ?>
        <!-- Staff Guests -->
        <button class="toggle-btn" onclick="toggleMenu('guestMenuStaff', 'guestArrowStaff')">
            👤 Guests <span id="guestArrowStaff">►</span>
        </button>
        <div id="guestMenuStaff" class="submenu">
            <a href="../guests/add-guest.php" class="<?= $current == 'add-guest.php' ? 'active' : '' ?>">➕ Add Guest</a>
            <a href="../guests/list.php" class="<?= $current == 'list.php' && strpos($_SERVER['PHP_SELF'], 'guests') !== false ? 'active' : '' ?>">📄 Guest List</a>
        </div>

        <!-- Staff Bookings -->
        <button class="toggle-btn" onclick="toggleMenu('bookingMenuStaff', 'bookingArrowStaff')">
            📁 Bookings <span id="bookingArrowStaff">►</span>
        </button>
        <div id="bookingMenuStaff" class="submenu">
            <a href="../bookings/add-booking.php" class="<?= $current == 'add-booking.php' ? 'active' : '' ?>">➕ Add Booking</a>
        </div>
    <?php endif; ?>

    <hr>
    <a href="../auth/logout.php">🚪 Logout</a>
</div>

<!-- Sidebar JavaScript (unchanged) -->
<script>
    function toggleMenu(id, iconId) {
        const menu = document.getElementById(id);
        const icon = document.getElementById(iconId);
        const isShown = menu.classList.toggle('show');
        icon.textContent = isShown ? '▼' : '►';

        const openMenus = JSON.parse(localStorage.getItem('openMenus') || '{}');
        openMenus[id] = isShown;
        localStorage.setItem('openMenus', JSON.stringify(openMenus));
    }

    window.onload = () => {
        const openMenus = JSON.parse(localStorage.getItem('openMenus') || '{}');
        for (const id in openMenus) {
            if (openMenus[id]) {
                const menu = document.getElementById(id);
                const icon = document.getElementById(id.replace('Menu', 'Arrow'));
                if (menu) menu.classList.add('show');
                if (icon) icon.textContent = '▼';
            }
        }
    };
</script>
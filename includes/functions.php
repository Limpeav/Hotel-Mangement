<?php
function countTotal($conn, $table)
{
    $sql = "SELECT COUNT(*) AS total FROM $table";
    $result = $conn->query($sql);
    return ($result && $result->num_rows > 0) ? $result->fetch_assoc()['total'] : 0;
}

function countAvailableRooms($conn)
{
    $sql = "SELECT COUNT(*) AS total FROM rooms WHERE status = 'available'";
    $result = $conn->query($sql);
    return ($result && $result->num_rows > 0) ? $result->fetch_assoc()['total'] : 0;
}

function countTodayBookings($conn)
{
    $today = date('Y-m-d');
    $sql = "SELECT COUNT(*) AS total FROM bookings WHERE booking_date = '$today' AND status != 'canceled'";
    $result = $conn->query($sql);
    return ($result && $result->num_rows > 0) ? $result->fetch_assoc()['total'] : 0;
}
?>
<?php
include '../dbc/db_connect.php';

function fetchData($table, $idColumn, $id) {
    global $conn;
    $sql = "SELECT * FROM $table WHERE $idColumn = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = null;
    
    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
    }

    $stmt->close();
    return $data;
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (!$id) {
    echo "Invalid ID.";
    exit();
}

if (strpos($_SERVER['PHP_SELF'], 'update_user.php') !== false) {
    $user = fetchData('users', 'id', $id);
    if (!$user) {
        echo "No user found.";
        exit();
    }
} elseif (strpos($_SERVER['PHP_SELF'], 'update_staff.php') !== false) {
    $staff = fetchData('Staff', 'StaffId', $id);
    if (!$staff) {
        echo "No staff member found.";
        exit();
    }
} elseif (strpos($_SERVER['PHP_SELF'], 'update_booking.php') !== false) {
    $booking = fetchData('table_bookings', 'id', $id);
    if (!$booking) {
        echo "No booking found.";
        exit();
    }
} else {
    echo "Invalid request.";
    exit();
}

// Do not close the connection here to reuse it in other scripts
// $conn->close();
?>

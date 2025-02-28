<?php
include '../dbc/db_connect.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id) {
    $sql = "DELETE FROM table_bookings WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "Booking deleted successfully.";
    } else {
        echo "Error deleting booking: " . $stmt->error;
    }

    $stmt->close();
} else {
    die("Invalid ID.");
}

$conn->close();
?>

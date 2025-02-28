<?php
include '../dbc/db_connect.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id) {
    // Prepare and execute the delete statement
    $sql = "DELETE FROM menu WHERE menu_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "Menu item deleted successfully!";
        // Redirect to manager dashboard or menu list
        header("Location: manager_dashboard.php");
        exit();
    } else {
        echo "Error deleting menu item: " . htmlspecialchars($stmt->error);
    }

    $stmt->close();
} else {
    echo "Invalid ID.";
}

$conn->close();
?>

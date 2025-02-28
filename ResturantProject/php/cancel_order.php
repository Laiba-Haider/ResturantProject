<?php
// Include database connection
include '../dbc/db_connect.php';

// Get order ID from URL parameter
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id) {
    $sql = "DELETE FROM orders WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<script>
                alert('Order canceled successfully.');
                window.location.href = 'Menu1.php'; // Redirect to Menu
              </script>";
    } else {
        echo "<p style='color:red;'>Error canceling order: " . $stmt->error . "</p>";
    }

    $stmt->close();
} else {
    echo "<script>
            alert('Invalid order ID.');
            window.location.href = 'Menu1.php';
          </script>";
}

$conn->close();
?>

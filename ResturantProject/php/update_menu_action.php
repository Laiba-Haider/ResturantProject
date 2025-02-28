<?php
include '../dbc/db_connect.php';

// Check if the request method is POST to process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Safely access POST variables
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $name = $_POST['name'] ?? '';
    $price = $_POST['price'] ?? '';
    $description = $_POST['description'] ?? '';

    // Validate input fields
    if (!$id) {
        echo "Invalid menu ID.";
        exit();
    }

    if (empty($name) || empty($price) || empty($description)) {
        echo "All fields are required.";
        exit();
    }

    // Prepare the SQL update statement
    $sql = "UPDATE menu SET name = ?, price = ?, description = ? WHERE menu_id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        echo "Error preparing statement: " . htmlspecialchars($conn->error);
        exit();
    }

    // Bind the parameters to the SQL statement
    $stmt->bind_param("sssi", $name, $price, $description, $id);

    // Execute the query
    if ($stmt->execute()) {
        echo "Menu item updated successfully!";
        // Redirect to the manager dashboard or menu list after updating
        header("Location: manager_dashboard.php");
        exit();
    } else {
        echo "Error updating menu item: " . htmlspecialchars($stmt->error);
    }

    // Close the statement
    $stmt->close();
} else {
    // If not a POST request, reject the request
    echo "Invalid request method.";
}

// Close the database connection
$conn->close();
?>

<?php
include '../dbc/db_connect.php';

// Check if the request method is POST to process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Safely access POST variables
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $name = $_POST['StaffName'] ?? '';
    $role = $_POST['StaffRole'] ?? ''; // Add this field if necessary

    // Validate input fields
    if (!$id) {
        echo "Invalid staff ID.";
        exit();
    }

    if (empty($name)) {
        echo "Name field is required.";
        exit();
    }

    // Prepare the SQL update statement
    $sql = "UPDATE Staff SET StaffName = ?, StaffRole = ? WHERE StaffId = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        echo "Error preparing statement: " . htmlspecialchars($conn->error);
        exit();
    }

    // Bind the parameters to the SQL statement
    $stmt->bind_param("ssi", $name, $role, $id);

    // Execute the query
    if ($stmt->execute()) {
        echo "Staff updated successfully!";
        // Redirect to a success page or the manager dashboard after updating
        header("Location: manager_dashboard.php"); // Adjust the location as needed
        exit();
    } else {
        echo "Error updating staff: " . htmlspecialchars($stmt->error);
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

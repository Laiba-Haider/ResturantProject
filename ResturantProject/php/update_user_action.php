<?php
include '../dbc/db_connect.php';

// Check if the request method is POST to process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Safely access POST variables
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';

    // Validate input fields
    if (!$id) {
        echo "Invalid user ID.";
        exit();
    }

    if (empty($username) || empty($email)) {
        echo "Username and email fields are required.";
        exit();
    }

    // Prepare the SQL update statement
    $sql = "UPDATE users SET username = ?, email = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        echo "Error preparing statement: " . htmlspecialchars($conn->error);
        exit();
    }

    // Bind the parameters to the SQL statement
    $stmt->bind_param("ssi", $username, $email, $id);

    // Execute the query
    if ($stmt->execute()) {
        echo "User updated successfully!";
        // Redirect to a success page or the manager dashboard after updating
        header("Location: manager_dashboard.php"); // Adjust the location as needed
        exit();
    } else {
        echo "Error updating user: " . htmlspecialchars($stmt->error);
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

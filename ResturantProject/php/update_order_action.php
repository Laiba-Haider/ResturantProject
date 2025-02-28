<?php
// Include the database connection
require '../dbc/db_connect.php';

// Initialize variables
$error = '';
$success = '';

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    // Retrieve data from the form
    $orderId = $_POST['order_id']; // Hidden input to capture the order ID
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $items = isset($_POST['items']) ? $_POST['items'] : [];
    $quantity = $_POST['quantity'];
    $comments = $_POST['comments'];

    // Check if items is an array, if not, convert the comma-separated string into an array
    if (!is_array($items)) {
        $items = explode(',', $items);
    }

    // Convert the items array to a comma-separated string for storing in the database
    $itemsString = implode(", ", $items);

    // Initialize the total price
    $total_price = 0.0;

    // Calculate total price based on the selected items and quantity
    foreach ($items as $item) {
        $stmt = $conn->prepare("SELECT price FROM menu WHERE name = ?");
        $stmt->bind_param("s", trim($item));
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $total_price += $row['price'] * $quantity;
        } else {
            $error = "Error: Item '$item' not found in the menu.";
            break;
        }
        $stmt->close();
    }

    // Proceed with the update if no errors occurred
    if (!$error) {
        // Prepare the update statement
        $stmt = $conn->prepare("UPDATE Orders SET name = ?, email = ?, phone = ?, items = ?, quantity = ?, price = ?, comments = ? WHERE id = ?");
        if ($stmt) {
            // Bind the parameters
            $stmt->bind_param('sssidsdi', $name, $email, $phone, $itemsString, $quantity, $total_price, $comments, $orderId);
            
            // Execute the statement
            if ($stmt->execute()) {
                $success = "Order updated successfully!";
            } else {
                $error = "Error updating order: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $error = "Failed to prepare the statement: " . $conn->error;
        }
    }

    // Close the database connection
    $conn->close();
} else {
    $error = "Invalid request.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Update Status</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .status-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 50%;
            text-align: center;
        }
        h1 {
            margin-bottom: 20px;
        }
        .message {
            font-weight: bold;
            margin-bottom: 10px;
        }
        .error {
            color: red;
        }
        .success {
            color: green;
        }
        .back-button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .back-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="status-container">
        <h1>Order Update Status</h1>
        <?php if ($error): ?>
            <div class="message error"><?php echo htmlspecialchars($error); ?></div>
        <?php elseif ($success): ?>
            <div class="message success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        <a href="update_order.php?id=<?php echo $orderId; ?>" class="back-button">Go Back to Update Order</a>
    </div>
</body>
</html>

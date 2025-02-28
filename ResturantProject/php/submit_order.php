<?php
// Database connection
require '../dbc/db_connect.php';

// Initialize order data
$orderData = [];
$orderSubmitted = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $items = isset($_POST['items']) ? $_POST['items'] : [];
    $quantity = $_POST['quantity'];
    $comments = $_POST['comments'];

    if (!is_array($items)) {
        $items = explode(',', $items);
    }

    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $customer_id = $row['id'];
    } else {
        echo "<p style='color:red;'>Error: User with the name '$name' not found.</p>";
        exit;
    }
    $stmt->close();

    $total_price = 0.0;

    foreach ($items as $item) {
        $stmt = $conn->prepare("SELECT price FROM menu WHERE name = ?");
        $stmt->bind_param("s", $item);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $total_price += $row['price'] * $quantity;
        } else {
            echo "<p style='color:red;'>Error: Item '$item' not found in the menu.</p>";
            exit;
        }
        $stmt->close();
    }

    $sql = "INSERT INTO orders (customer_id, name, email, phone, items, quantity, price, comments) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Statement preparation failed: " . $conn->error);
    }

    $itemsString = implode(", ", $items);

    $stmt->bind_param('issssids', $customer_id, $name, $email, $phone, $itemsString, $quantity, $total_price, $comments);

    if ($stmt->execute()) {
        $orderSubmitted = true;
        $order_id = $stmt->insert_id; // ✅ Fetch the last inserted order ID
        $orderData = [
            'id' => $order_id, // ✅ Store the ID for cancelation
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'items' => $itemsString,
            'quantity' => $quantity,
            'price' => $total_price,
            'comments' => $comments,
        ];
    } else {
        echo "<p style='color:red; text-align:center;'>Error: " . $stmt->error . "</p>";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Summary</title>
    <style>
        body {
            background: url(../image/e.AVIF) center/cover no-repeat;
            color: white;
            text-align: center;
        }
        .summary-container {
            max-width: 500px;
            margin: 20px auto;
            padding: 20px;
            background: rgba(0, 0, 0, 0.5);
            border-radius: 8px;
        }
        .update-button {
            display: block;
            padding: 10px;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
            color: white;
        }
    </style>
</head>
<body>
    <div class="summary-container">
        <?php if ($orderSubmitted): ?>
            <h1>Order Summary</h1>
            <p><strong>Name:</strong> <?php echo htmlspecialchars($orderData['name']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($orderData['email']); ?></p>
            <p><strong>Phone:</strong> <?php echo htmlspecialchars($orderData['phone']); ?></p>
            <p><strong>Items:</strong> <?php echo htmlspecialchars($orderData['items']); ?></p>
            <p><strong>Quantity:</strong> <?php echo htmlspecialchars($orderData['quantity']); ?></p>
            <p><strong>Total Price:</strong> $<?php echo htmlspecialchars(number_format($orderData['price'], 2)); ?></p>
            <p><strong>Comments:</strong> <?php echo htmlspecialchars($orderData['comments']); ?></p>

            <!-- ✅ Cancel Order Button with Correct ID -->
            <a href="cancel_order.php?id=<?php echo $orderData['id']; ?>" class="update-button" style="background-color: red;">Cancel Order</a>

        <?php else: ?>
            <p style="color: red;">Order submission failed. Please try again.</p>
        <?php endif; ?>
    </div>
</body>
</html>

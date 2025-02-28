<?php
// Include the database connection
require '../dbc/db_connect.php';

// Initialize variables
$orderId = $_GET['id'] ?? null;
$order = null;
$error = '';

// Fetch order details for the given ID
if ($orderId && filter_var($orderId, FILTER_VALIDATE_INT)) {
    $stmt = $conn->prepare("SELECT * FROM Orders WHERE id = ?");
    $stmt->bind_param('i', $orderId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $order = $result->fetch_assoc();
    } else {
        $error = "Order not found.";
    }
    $stmt->close();
} else {
    $error = "Invalid order ID.";
}

// Fetch menu items for the dropdown
$menuItems = [];
$stmt = $conn->prepare("SELECT name FROM menu");
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $menuItems[] = $row['name'];
}
$stmt->close();

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Order</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
        }
       nav {
    display: flex;
    justify-content: center;
    align-items: center;

   /* position: relative;*/
  /*  z-index: 1;*/ /* Ensure navigation is above the video */
    background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent background */
    padding: 20px 0;
}

nav h2 {
    color: white;
    font-family: Blackadder ITC;
    font-size: 35px;
    margin-right: auto; /* Pushes links to the right */
    padding-left: 8%;
}

nav a {
    color: white;
    margin-right: 20px;
    text-decoration: none;
    padding: 0 15px;
    transition: color 0.3s ease;
}

nav a:hover {
    color: #FFB6C1;
    font-weight: bold;
    /*text-decoration-style: underline;*/
    text-decoration-line: underline;
}
        .update-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 80%;
            max-width: 600px;
            margin: 20px auto;
        }
        h1 {
            margin-bottom: 20px;
            color: #333;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"], input[type="email"], input[type="tel"], input[type="number"], textarea, select {
            width: 100%;
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #ccc;
            background-color: #f9f9f9;
        }
        button {
            padding: 10px 20px;
            background-color: #28a745;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1em;
            margin-top: 10px;
        }
        button:hover {
            background-color: #218838;
        }
        .error {
            color: #d9534f;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav>
        <h2>Pakistani Zaika</h2>
        <a href="../html/Homepage.html">Home</a>
        <a href="../html/About.html">About Us</a>
         <a href="../html/Booktable.html">Book Table</a>
        <a href="Menu1.php">Menu</a>
       <!--  <a href="Order.html">Order now</a> -->
        <a href="../html/Login.html">Login</a>
        <a href="../html/staffform.html">Staff</a>
        <a href="../html/CreateAccount.html">Sign up</a>
        <a href="../html/Feedback.html">Feedback</a>

       
    </nav>

    <div class="update-container">
        <h1>Update Order</h1>
        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if ($order): ?>
            <form action="update_order_action.php" method="POST">
                <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order['id']); ?>">

                <div class="form-group">
                    <label for="name">Your Name:</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($order['name']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="email">Email Address:</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($order['email']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="phone">Phone Number:</label>
                    <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($order['phone']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="items">Select Items:</label>
                    <select id="items" name="items[]" multiple required>
                        <?php foreach ($menuItems as $item): ?>
                            <option value="<?php echo htmlspecialchars($item); ?>"
                                <?php if (in_array($item, explode(", ", $order['items']))): ?>
                                    selected
                                <?php endif; ?>>
                                <?php echo htmlspecialchars($item); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="quantity">Quantity:</label>
                    <input type="number" id="quantity" name="quantity" value="<?php echo htmlspecialchars($order['quantity']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="comments">Additional Comments:</label>
                    <textarea id="comments" name="comments" required><?php echo htmlspecialchars($order['comments']); ?></textarea>
                </div>

                <button type="submit" name="update">Update Order</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>

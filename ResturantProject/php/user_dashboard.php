<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="../css/styless1.css">
</head>
<body>
    <nav>
        <h2>Pakistani Zaika</h2>
        <a href="../html/Homepage.html">Home</a>
        <a href="../html/About.html">About Us</a>
        <a href="../html/Booktable.html">Book Table</a>
        <a href="Menu1.php">Menu</a>
        <a href="../html/Order.html">Order now</a>
        <a href="../html/Login.html">Login</a>
        <a href="../html/CreateAccount.html">Sign up</a>
    </nav>

    <div class="container">
        <?php
        session_start();

        // Check if the user is logged in
        if (!isset($_SESSION['username'])) {
            header("Location: ../html/Login.html");
            exit();
        }

        include '../dbc/db_connect.php';

        // Retrieve the username from the session
        $username = $_SESSION['username'];

        // Prepare and execute the SQL query
        $sql = "SELECT username, lastname, phone_number, email FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            echo "<p>Prepare failed: " . htmlspecialchars($conn->error) . "</p>";
            exit();
        }

        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result === false) {
            echo "<p>Execute failed: " . htmlspecialchars($stmt->error) . "</p>";
            exit();
        }

        if ($result->num_rows > 0) {
            // Fetch and display user data
            while ($row = $result->fetch_assoc()) {
                echo "<div class='user-info'>";
                echo "<p><strong>Username:</strong> " . htmlspecialchars($row['username']) . "</p>";
                echo "<p><strong>Last Name:</strong> " . htmlspecialchars($row['lastname']) . "</p>";
                echo "<p><strong>Phone Number:</strong> " . htmlspecialchars($row['phone_number']) . "</p>";
                echo "<p><strong>Email:</strong> " . htmlspecialchars($row['email']) . "</p>";
                echo "</div>";
            }
        } else {
            echo "<p>No user found with username: " . htmlspecialchars($username) . "</p>";
        }

        $stmt->close();
        $conn->close();
        ?>

        <!-- Buttons for additional actions -->
        <div class="buttons-container">
            <a href="order.php" class="action-button">Order</a>
            <a href="book_table.php" class="action-button">Booking</a>
            <a href="update_user1.php" class="action-button">Profile</a>
            <a href="feedback.php" class="action-button">Feedback</a>
        <a href="Logout.php" class="action-button">Logout</a>
            
        </div>

    </div>
</body>
</html>

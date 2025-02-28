<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: ../html/Login1.html");
    exit();
}

include '../dbc/db_connect.php';

// Retrieve the username from the session
$username = $_SESSION['username'];

// Fetch orders related to the logged-in user
$sql = "SELECT name, email, phone, items,price, comments, created_at 
        FROM orders 
        WHERE name = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Prepare failed: " . htmlspecialchars($conn->error));
}

$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Orders</title>
    <link rel="stylesheet" href="styless1.css">
    <style type="text/css">
        * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
    margin: 0;
    overflow-x: hidden; /* Prevent horizontal scroll */
}

.background-video {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -70%);
    z-index: -1;
}

.background-video video {
    min-width: 100%; 
    min-height: 100%;
    width: auto;
    height: auto;
    object-fit: cover; /* Ensure the video covers the entire background */
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
    </style>
</head>
<body>
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

    <h1>Your Orders</h1>
    <div class="orders-container">
        <?php if ($result->num_rows > 0): ?>
            <ul>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <li>
                        <p><strong>Name:</strong> <?php echo htmlspecialchars($row['name']); ?></p>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($row['email']); ?></p>
                        <p><strong>Phone:</strong> <?php echo htmlspecialchars($row['phone']); ?></p>
                        <p><strong>Items:</strong> <?php echo htmlspecialchars($row['items']); ?></p>
                        <p><strong>Bill is:</strong> <?php echo htmlspecialchars($row['price']); ?></p>
                        <p><strong>Comments:</strong> <?php echo htmlspecialchars($row['comments']); ?></p>
                        <p><strong>Order Date:</strong> <?php echo htmlspecialchars($row['created_at']); ?></p>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>No orders found.</p>
        <?php endif; ?>
    </div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>

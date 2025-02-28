<?php
include '../dbc/db_connect.php';





// Fetch the booking details based on the booking ID passed through the URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$booking = null;

if ($id) {
    $sql = "SELECT * FROM table_bookings WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $booking = $result->fetch_assoc();
    } else {
        die("No booking found.");
    }
    $stmt->close();
} else {
    die("Invalid ID.");
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Booking</title>
    <style>
        /* Add your styling here */

         body {
            background-color: #222222;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: black;
        }

        nav {
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: rgba(0, 0, 0, 0.5);
            padding: 20px 0;
        }

        nav h2 {
            color: white;
            font-family: Blackadder ITC;
            font-size: 35px;
            margin-right: auto;
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
            text-decoration-line: underline;
        }

         .container {
            width: 35%;
            height: 95%;
            margin: 20px auto;
            background-color: rgba(0, 0, 0, 0.5);
            padding: 20px;
            border-radius: 8px;
        }

        h2 {
            text-align: center;
            color: orange;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 8px;
            color: darkorange;
        }

        input, textarea, select {
            padding: 5px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        textarea {
            resize: vertical;
        }

        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 12px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin-top: 10px;
            cursor: pointer;
            border-radius: 4px;
        }

        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    

     <nav>
        <h2>Pakistani Zaika</h2>
        <a href="../html/Homepage.html">Home</a>
        <a href="../html/Booktable.html">Book Table</a>
        <a href="Menu1.php">Menu</a>
        <a href="../html/Feedback.html">Feedback</a>
    </nav>

     <div class="container">
           <h2>Update Booking</h2>
    <form action="update_booking_action.php" method="POST">

        <input type="hidden" name="id" value="<?php echo htmlspecialchars($booking['id']); ?>">

        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($booking['name']); ?>" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($booking['email']); ?>" required>

        <label for="phone">Phone:</label>
        <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($booking['phone']); ?>" required>

        <label for="booking_datetime">Date and Time:</label>
        <input type="datetime-local" id="booking_datetime" name="booking_datetime" value="<?php echo htmlspecialchars($booking['booking_datetime']); ?>" required>

        <label for="location">Location:</label>
        <select id="location" name="location" required>
            <option value="indoor" <?php echo $booking['location'] === 'indoor' ? 'selected' : ''; ?>>Indoor</option>
            <option value="outdoor" <?php echo $booking['location'] === 'outdoor' ? 'selected' : ''; ?>>Outdoor</option>
        </select>

        <label for="comments">Additional Comments:</label>
        <textarea id="comments" name="comments" rows="4"><?php echo htmlspecialchars($booking['comments']); ?></textarea>

        <button type="submit">Update Booking</button>
    </form>  
            
        </div>
   
</body>
</html>

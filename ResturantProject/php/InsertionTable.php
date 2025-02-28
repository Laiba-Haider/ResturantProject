<?php
include '../dbc/db_connect.php';

// Display the navigation bar
echo '
<nav>
    <h2>Pakistani Zaika</h2>
    <a href="../html/Homepage.html">Home</a>
    <a href="../html/About.html">About Us</a>
    <a href="../html/Booktable.html">Book Table</a>
    <a href="Menu1.php">Menu</a>
    <a href="../html/Login.html">Login</a>
    <a href="../html/CreateAccount.html">Sign up</a>
    <a href="../html/Feedback.html">Feedback</a>
</nav>';

// CSS Styling for the nav bar and table display
echo '
<style>
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
    .booking-table {
        margin: 20px auto;
        border-collapse: collapse;
        width: 80%;
    }
    .booking-table th, .booking-table td {
        padding: 12px;
        text-align: left;
        border: 1px solid #ddd;
    }
    .booking-table th {
        background-color: #f2f2f2;
    }
    .btn {
        color: white;
        background-color: #007BFF;
        padding: 5px 10px;
        text-decoration: none;
        border-radius: 4px;
        transition: background-color 0.3s;
    }
    .btn:hover {
        background-color: #0056b3;
    }
</style>
';

$user_id = null; // Initialize user_id variable

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $booking_datetime = $_POST['booking_datetime'];
    $location = $_POST['location'];
    $comments = $_POST['comments'];
    $capacity = $_POST['capacity']; // Get capacity input

    // Fetch user_id based on email
    $userQuery = "SELECT id FROM users WHERE email = ? LIMIT 1";
    $stmt = $conn->prepare($userQuery);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $user_id = $user['id']; // Found user ID

        // Insert booking data with the fetched user_id, setting status to reserved, and including capacity
        $insertSql = "INSERT INTO table_bookings (user_id, name, email, phone, booking_datetime, location, comments, status, capacity) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, 'reserved', ?)";
        $insertStmt = $conn->prepare($insertSql);
        $insertStmt->bind_param("issssssi", $user_id, $name, $email, $phone, $booking_datetime, $location, $comments, $capacity);

        if ($insertStmt->execute()) {
            echo "Booking successfully created!";
        } else {
            echo "Error creating booking: " . $insertStmt->error;
        }

        $insertStmt->close();
    } else {
        echo "User not found. Please ensure your email is correct.";
    }

    $stmt->close();
}

// Display bookings only for the current user
if ($user_id) {
    $query = "SELECT * FROM table_bookings WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<table class='booking-table'>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Booking Date & Time</th>
                    <th>Location</th>
                    <th>Comments</th>
                    <th>Status</th>
                    <th>Capacity</th>
                    <th>Action</th>
                </tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . htmlspecialchars($row['name']) . "</td>
                    <td>" . htmlspecialchars($row['email']) . "</td>
                    <td>" . htmlspecialchars($row['phone']) . "</td>
                    <td>" . htmlspecialchars($row['booking_datetime']) . "</td>
                    <td>" . htmlspecialchars($row['location']) . "</td>
                    <td>" . htmlspecialchars($row['comments']) . "</td>
                    <td>" . htmlspecialchars($row['status']) . "</td>
                    <td>" . htmlspecialchars($row['capacity']) . "</td>
                    <td><a href='update_booking.php?id=" . urlencode($row['id']) . "' class='btn'>Update</a>
                     <a href='delete_booking.php?id=" . urlencode($row['id']) . "' class='btn btn-danger'>Delete</a>
                    </td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "No bookings found for your account.";
    }

    $stmt->close();
} else {
    echo "No booking data available.";
}

// Close the connection at the end of the script
$conn->close();
?>

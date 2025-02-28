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
</style>
';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Safely access POST variables with fallback values
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $booking_datetime = $_POST['booking_datetime'] ?? '';
    $location = $_POST['location'] ?? '';
    $comments = $_POST['comments'] ?? '';

    // Check if required fields are present
    if (empty($id) || empty($name) || empty($email) || empty($phone) || empty($booking_datetime) || empty($location)) {
        echo "Please fill in all required fields.";
        exit;
    }

    // Fetch the user_id based on the provided email
    $userQuery = "SELECT id FROM users WHERE email = ? LIMIT 1";
    $userStmt = $conn->prepare($userQuery);
    if ($userStmt === false) {
        die("Error preparing statement: " . $conn->error);
    }
    $userStmt->bind_param("s", $email);
    $userStmt->execute();
    $userResult = $userStmt->get_result();

    // Check if a user was found
    if ($userResult->num_rows > 0) {
        $user = $userResult->fetch_assoc();
        $user_id = $user['id'];

        // Prepare the update query for booking details
        $sql = "UPDATE table_bookings SET user_id = ?, name = ?, email = ?, phone = ?, booking_datetime = ?, location = ?, comments = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die("Error preparing statement: " . $conn->error);
        }

        // Bind the parameters for the update query
        $stmt->bind_param("issssssi", $user_id, $name, $email, $phone, $booking_datetime, $location, $comments, $id);

        // Execute the update query
        if ($stmt->execute()) {
            echo "Booking updated successfully!";
            
            // Fetch and display only the updated booking details of the user
            $fetchUpdatedQuery = "SELECT * FROM table_bookings WHERE id = ? AND user_id = ?";
            $fetchStmt = $conn->prepare($fetchUpdatedQuery);
            $fetchStmt->bind_param("ii", $id, $user_id);
            $fetchStmt->execute();
            $fetchResult = $fetchStmt->get_result();

            if ($fetchResult->num_rows > 0) {
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
                        </tr>";

                while ($row = $fetchResult->fetch_assoc()) {
                    echo "<tr>
                            <td>" . htmlspecialchars($row['name']) . "</td>
                            <td>" . htmlspecialchars($row['email']) . "</td>
                            <td>" . htmlspecialchars($row['phone']) . "</td>
                            <td>" . htmlspecialchars($row['booking_datetime']) . "</td>
                            <td>" . htmlspecialchars($row['location']) . "</td>
                            <td>" . htmlspecialchars($row['comments']) . "</td>
                            <td>" . htmlspecialchars($row['status']) . "</td>
                            <td>" . htmlspecialchars($row['capacity']) . "</td>
                          </tr>";
                }
                echo "</table>";
            } else {
                echo "Error retrieving updated booking details.";
            }
            $fetchStmt->close();
        } else {
            echo "Error updating booking: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "User not found. Please ensure your email is correct.";
    }

    $userStmt->close();
    $conn->close();
}
?>

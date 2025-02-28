



<?php
// session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: ../html/Login.html");
    exit();
}

include '../dbc/db_connect.php'; // Include your database connection

// Fetch data from the table_bookings table
$sql_bookings = "SELECT * FROM table_bookings ORDER BY booking_datetime DESC";
$stmt_bookings = $conn->prepare($sql_bookings);
$stmt_bookings->execute();
$result_bookings = $stmt_bookings->get_result();

if (!$result_bookings) {
    echo "<p>Error fetching bookings: " . htmlspecialchars($conn->error) . "</p>";
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>

     <style>
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

        h1, h2 {
            text-align: center;
            color: black;
        }

        .container {
            max-width: 90%;
            margin: 20px auto;
            background-color: white;
            color: black;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #333;
            color: white;
        }

        .btn {
            padding: 5px 10px;
            margin: 5px;
            background-color: #d9534f;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #c9302c;
        }
    </style>
</head>
<body>

</body>
</html>

<div class="container">
    <h2>Your Bookings</h2>
    <table>
        <tr>
            <th>Booking ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Date & Time</th>
            <th>Location</th>
            <th>Comments</th>
            <th>Created At</th>
            <th>Actions</th>
        </tr>
        <?php
        if ($result_bookings->num_rows > 0) {
            while ($row = $result_bookings->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                echo "<td>" . htmlspecialchars($row['phone']) . "</td>";
                echo "<td>" . htmlspecialchars($row['booking_datetime']) . "</td>";
                echo "<td>" . htmlspecialchars($row['location']) . "</td>";
                echo "<td>" . htmlspecialchars($row['comments']) . "</td>";
                echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
                 echo "<td>
                        <a href='update_user.php?id=" . urlencode($row['id']) . "' class='btn'>Update</a>
                        <a href='delete_user.php?id=" . urlencode($row['id']) . "' class='btn'>Delete</a>
                    </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='8'>No bookings found.</td></tr>";
        }
        ?>
    </table>
</div>

<?php
// Start session


// Check if the user is logged in and has a manager role
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'manager') {
    header("Location: ../html/Login.html");
    exit();
}

// Include the database connection
include '../dbc/db_connect.php'; // Ensure this file does not call session_start() again

// Fetch data of the specific manager based on the logged-in username
$username = $_SESSION['username'];
$sql_staff = "SELECT * FROM Staff WHERE StaffName = ?";
$stmt = $conn->prepare($sql_staff);

if (!$stmt) {
    echo "<p>Error preparing statement: " . htmlspecialchars($conn->error) . "</p>";
    exit();
}

// Bind the username parameter and execute
$stmt->bind_param('s', $username);
$stmt->execute();
$result_staff = $stmt->get_result();

if ($result_staff->num_rows === 0) {
    echo "<p>No profile found for the logged-in manager.</p>";
    exit();
}

// Fetch the manager's profile information
$manager = $result_staff->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Section</title>
    <style>
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
    </style>
</head>
<body>

<div class="container">
    <h1>Profile Section</h1>

    <!-- Manager Profile Section -->
    <h2>Manager Profile</h2>
    <table>
        <tr>
            <th>Staff ID</th>
            <td><?php echo htmlspecialchars($manager['StaffId']); ?></td>
        </tr>
        <tr>
            <th>Name</th>
            <td><?php echo htmlspecialchars($manager['StaffName']); ?></td>
        </tr>
        <tr>
            <th>Phone</th>
            <td><?php echo htmlspecialchars($manager['StaffPhone']); ?></td>
        </tr>
        <tr>
            <th>Shift</th>
            <td><?php echo htmlspecialchars($manager['StaffShift']); ?></td>
        </tr>
        <tr>
            <th>Role</th>
            <td><?php echo htmlspecialchars($manager['StaffRole']); ?></td>
        </tr>
    </table>

    <!-- Actions -->
    <a href="update_staff.php?id=<?php echo urlencode($manager['StaffId']); ?>" class="btn">Update Profile</a>
</div>

</body>
</html>

<?php
// Close the statement and connection
$stmt->close();
$conn->close();
?>

<?php
include '../dbc/db_connect.php';  // Connect to your database

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
    .staff-table {
        margin: 20px auto;
        border-collapse: collapse;
        width: 80%;
    }
    .staff-table th, .staff-table td {
        padding: 12px;
        text-align: left;
        border: 1px solid #ddd;
    }
    .staff-table th {
        background-color: #f2f2f2;
    }
    .btn-update {
        background-color: #4CAF50;
        color: white;
        border: none;
        padding: 8px 16px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 14px;
        cursor: pointer;
        border-radius: 4px;
        transition: background-color 0.3s ease;
    }
    .btn-update:hover {
        background-color: #45a049;
    }
</style>
';

// Retrieve form data
$user_id = $_REQUEST['user_id'] ?? '';
$staff_name = $_REQUEST['staff_name'] ?? '';
$staff_phone = $_REQUEST['staff_phone'] ?? '';
$staff_shift = $_REQUEST['staff_shift'] ?? '';
$staff_role = $_REQUEST['staff_role'] ?? '';
$staff_password = $_REQUEST['staff_password'] ?? ''; // Capture password input

// Check if the User ID exists in the users table
$sql_check_user = "SELECT id FROM users WHERE id = ?";
$stmt_check_user = $conn->prepare($sql_check_user);
$stmt_check_user->bind_param("i", $user_id);
$stmt_check_user->execute();
$stmt_check_user->store_result();

if ($stmt_check_user->num_rows > 0) {
    // User exists, proceed with insertion
    if (!empty($staff_password)) {
        // Hash the password
        $hashedPassword = password_hash($staff_password, PASSWORD_BCRYPT);
        
        // Insert staff data into the database
        $sql_insert_staff = "INSERT INTO Staff (UserId, StaffName, StaffPhone, StaffShift, StaffRole, StaffPassword) 
                             VALUES (?, ?, ?, ?, ?, ?)";
        $stmt_insert_staff = $conn->prepare($sql_insert_staff);
        $stmt_insert_staff->bind_param("isssss", $user_id, $staff_name, $staff_phone, $staff_shift, $staff_role, $hashedPassword);

        if ($stmt_insert_staff->execute()) {
            echo "Staff member added successfully!";
            
            // Fetch and display the newly added staff member data
            $fetchStaffQuery = "SELECT * FROM Staff WHERE UserId = ? ORDER BY StaffId DESC LIMIT 1";
            $fetchStaffStmt = $conn->prepare($fetchStaffQuery);
            $fetchStaffStmt->bind_param("i", $user_id);
            $fetchStaffStmt->execute();
            $fetchStaffResult = $fetchStaffStmt->get_result();

            if ($fetchStaffResult->num_rows > 0) {
                echo "<table class='staff-table'>
                        <tr>
                            <th>Staff Name</th>
                            <th>Phone</th>
                            <th>Shift</th>
                            <th>Role</th>
                            <th>Action</th>
                        </tr>";

                while ($row = $fetchStaffResult->fetch_assoc()) {
                    $staff_id = htmlspecialchars($row['StaffId']);
                    echo "<tr>
                            <td>" . htmlspecialchars($row['StaffName']) . "</td>
                            <td>" . htmlspecialchars($row['StaffPhone']) . "</td>
                            <td>" . htmlspecialchars($row['StaffShift']) . "</td>
                            <td>" . htmlspecialchars($row['StaffRole']) . "</td>
                            <td><a href='update_staff.php?id=" . urlencode($staff_id) . "' class='btn-update'>Update</a></td>
                          </tr>";
                }
                echo "</table>";
            } else {
                echo "Error retrieving staff details.";
            }
            $fetchStaffStmt->close();
        } else {
            echo "Error: " . $stmt_insert_staff->error;
        }
        $stmt_insert_staff->close();
    } else {
        // Handle the case where password is empty
        echo "Error: Password cannot be empty.";
    }
} else {
    // User ID does not exist
    echo "Error: The User ID does not exist in the users table.";
}

// Close connections
$stmt_check_user->close();
$conn->close();
?>

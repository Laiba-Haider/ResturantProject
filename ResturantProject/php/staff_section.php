<?php
include '../dbc/db_connect.php'; // Ensure the database connection is included

// Fetch data from staff table
$sql_staff = "SELECT * FROM Staff";
$result_staff = $conn->query($sql_staff);
if (!$result_staff) {
    die("Error fetching staff: " . $conn->error);
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>
    <style type="text/css">
        
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

    <h2>Staff</h2>
<table>
    <tr>
        <th>Staff ID</th>
        <th>Name</th>
        <th>Role</th>
        <th>Actions</th>

    </tr>
    <?php
    if ($result_staff->num_rows > 0) {
        while ($row = $result_staff->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['StaffId']) . "</td>";
            echo "<td>" . htmlspecialchars($row['StaffName']) . "</td>";
            echo "<td>" . htmlspecialchars($row['StaffRole']) . "</td>";
           
             echo "<td>
                        <a href='update_staff.php?id=" . urlencode($row['StaffId']) . "' class='btn'>Update</a>
                        <a href='delete_staff.php?id=" . urlencode($row['StaffId']) . "' class='btn'>Delete</a>
                    </td>";
                    echo "</tr>";
        }
        
    } else {
        echo "<tr><td colspan='3'>No staff members found.</td></tr>";
    }
    ?>
</table>


</body>
</html>


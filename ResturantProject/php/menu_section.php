<?php
include '../dbc/db_connect.php'; // Ensure the database connection is included

// Fetch data from menu table
$sql_menu = "SELECT * FROM menu";
$result_menu = $conn->query($sql_menu);
if (!$result_menu) {
    die("Error fetching menu items: " . $conn->error);
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>
    <style type="text/css">
        
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
        /* Your CSS styles here */
    </style> 
    </style>
</head>
<body>

</body>
</html>

<h2>Menu</h2>
<table>
    <tr>
        <th>Menu ID</th>
        <th>Name</th>
        <th>Price</th>
        <th>Description</th>
        <th>Actions</th>
    </tr>
    <?php
    if ($result_menu->num_rows > 0) {
        while ($row = $result_menu->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['menu_id']) . "</td>";
            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['price']) . "</td>";
            echo "<td>" . htmlspecialchars($row['description']) . "</td>";
             echo "<td>
                        <a href='update_menu.php?id=" . urlencode($row['menu_id']) . "' class='btn'>Update</a>
                        <a href='delete_menu.php?id=" . urlencode($row['menu_id']) . "' class='btn'>Delete</a>
                    </td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='4'>No menu items found.</td></tr>";
    }
    ?>
</table>

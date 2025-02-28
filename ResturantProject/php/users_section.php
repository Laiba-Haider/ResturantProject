<?php
include 'db_connect.php';

// Fetch data from the users table
$sql_users = "SELECT * FROM users";
$result_users = $conn->query($sql_users);

if (!$result_users) {
    echo "<p>Error fetching users: " . htmlspecialchars($conn->error) . "</p>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Section</title>
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
            max-width: 1000px;
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

    

    <div class="container">
        <h1>User Section</h1>

        <h2>Users</h2>
        <table>
            <tr>
                <th>User ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
            <?php
            if ($result_users->num_rows > 0) {
                while ($row = $result_users->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                    echo "<td>
                        <a href='update_user.php?id=" . urlencode($row['id']) . "' class='btn'>Update</a>
                        <a href='delete_user.php?id=" . urlencode($row['id']) . "' class='btn'>Delete</a>
                    </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No users found.</td></tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>

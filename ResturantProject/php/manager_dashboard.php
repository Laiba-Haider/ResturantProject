<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'manager') {
    header("Location: ../html/Login.html");
    exit();
}

include '../dbc/db_connect.php';

// Function to include sections dynamically
function includeSection($section) {
    $file = $section . '_section.php';
    if (file_exists($file)) {
        include $file;
    } else {
        echo "<p>Section not found.</p>";
    }
}

$section = isset($_GET['section']) ? $_GET['section'] : 'profile';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager Dashboard</title>
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

        .container {
            max-width: 1000px;
            margin: 20px auto;
            background-color: white;
            color: black;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
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

        .nav-buttons {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .nav-buttons a {
            margin: 0 10px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .nav-buttons a:hover {
            background-color: #0056b3;
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
        <a href="Logout.php">Logout</a>
    </nav>

    <div class="container">
        <div class="nav-buttons">
            <a href="?section=profile">Profile</a>
            <a href="?section=users">Customers</a>
            <a href="?section=staff">Staff</a>
            <a href="?section=booking">Bookings</a>
            <a href="?section=menu">Menu</a>
        </div>

        <?php
        // Include the selected section
        includeSection($section);
        ?>
    </div>

</body>
</html>

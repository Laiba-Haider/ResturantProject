<?php
include '../dbc/db_connect.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$menu = null;

if ($id) {
    // Fetch current menu data
    $sql = "SELECT * FROM menu WHERE menu_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $menu = $result->fetch_assoc();
    } else {
        die("No menu item found.");
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
    <title>Update Menu</title>
    <style>
        /* Add styling here */
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
        <a href="../html/Login.html">Login</a>
        <a href="../html/Feedback.html">Feedback</a>
    </nav>

    <div class="container">
   <h2>Update Menu</h2>
<form action="update_menu_action.php" method="POST">
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($menu['menu_id']); ?>">
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($menu['name']); ?>" required>
    <label for="price">Price:</label>
    <input type="text" id="price" name="price" value="<?php echo htmlspecialchars($menu['price']); ?>" required>
    <label for="description">Description:</label>
    <textarea id="description" name="description" required><?php echo htmlspecialchars($menu['description']); ?></textarea>
    <button type="submit">Update Menu</button>
</form>
</div>
</body>
</html>

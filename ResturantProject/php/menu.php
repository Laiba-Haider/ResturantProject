<?php
include '../dbc/db_connect.php'; // Ensure this file contains your database connection

$sql = "SELECT name, description, price FROM menu"; 
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Menu</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            overflow-x: hidden;
        }

        .background-video {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -60%);
            z-index: -1;
        }

        .background-video video {
            min-width: 100%; 
            min-height: 100%;
            width: auto;
            height: auto;
            object-fit: cover;
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
            margin-right: 10px;
            text-decoration: none;
            padding: 0 15px;
            transition: color 0.3s ease;
        }

        nav a:hover {
            color: #FFB6C1;
            font-weight: bold;
            text-decoration-line: underline;
        }

        .menu-container {
            max-width: 800px;
            margin: 0 auto;
            margin-top: 1%;
            border-radius: 8px;
            background-color: rgba(0, 0, 0, 0.5);
            padding: 20px;
        }

        h1 {
            padding-top: 4%;
            color: yellow;
            text-align: center;
            margin-bottom: 20px;
        }

        .menu-item {
            display: flex;
            justify-content: space-between;
            border-bottom: 1px solid #ddd;
            padding: 10px 0;
        }

        .menu-item:last-child {
            border-bottom: none;
        }

        .menu-item-info {
            flex: 1;
        }

        .menu-item-info h2 {
            padding: 2%;
            width: 40%;
            background-color: darkgoldenrod;
            color: black;
            margin-bottom: 5px;
            font-size: 1.2em;
        }

        .menu-item-info p {
            color: beige;
            font-size: 0.9em;
        }

        .menu-item-price {
            font-size: 1.2em;
            font-weight: bold;
            color: beige;
        }

        .order-button {
            text-align: center;
            margin-top: 20px;
        }

        .order-button button {
            padding: 10px 20px;
            font-size: 1em;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .order-button button a {
            color: #fff;
            text-decoration: none;
        }

        .order-button button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="background-video">
        <video autoplay loop muted playsinline>
            <source src="Manu.mp4" type="video/mp4">
        </video>
    </div>

    <nav>
        <h2>Pakistani Zaika</h2>
        <a href="../html/Homepage.html">Home</a>
        <a href="../html/Order.html">Order now</a>
        <a href="../html/Login.html">Login</a>
        <a href="../html/CreateAccount.html">Sign up</a>
    </nav>

    <div class="menu-container">
        <h1>Menu</h1>

        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='menu-item'>
                        <div class='menu-item-info'>
                            <h2>{$row['name']}</h2>
                            <p>{$row['description']}</p>
                        </div>
                        <div class='menu-item-price'>Rs: {$row['price']}</div>
                    </div>";
            }
        } else {
            echo "<p>No menu items available</p>";
        }
        $conn->close();
        ?>
        
        <div class="order-button">
            <button><a href="../html/Order.html">Order</a></button>
        </div>
    </div>
</body>
</html>

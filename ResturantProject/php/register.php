<?php
include '../dbc/db_connect.php';

// Check if form fields exist before accessing them
$username = isset($_POST['username']) ? trim($_POST['username']) : null;
$lastname = isset($_POST['lastname']) ? trim($_POST['lastname']) : null;
$phone = isset($_POST['number']) ? trim($_POST['number']) : null;
$email = isset($_POST['email']) ? trim($_POST['email']) : null;
$password = isset($_POST['password']) ? password_hash($_POST['password'], PASSWORD_BCRYPT) : null;

if (!$username || !$lastname || !$phone || !$email || !$password) {
    $message = "❌ All fields are required. Please fill out the form completely.";
    $success = false;
} else {
    // Check if the username already exists
    $check_sql = "SELECT username FROM users WHERE username = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $username);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        $message = "❌ Username '$username' is already taken! Please choose a different username.";
        $success = false;
    } else {
        // Proceed with insertion
        $sql = "INSERT INTO users (username, lastname, phone_number, email, password) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            $message = "❌ Prepare failed: " . $conn->error;
            $success = false;
        } else {
            $stmt->bind_param("sssss", $username, $lastname, $phone, $email, $password);

            if ($stmt->execute()) {
                $message = "🎉 Registration successful! Welcome, $username!";
                $success = true;
            } else {
                $message = "❌ Error: " . $stmt->error;
                $success = false;
            }

            $stmt->close();
        }
    }

    $check_stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Status</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: url('../image/2.jpg') no-repeat center center/cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            width: 40%;
            padding: 30px;
            background: rgba(0, 0, 0, 0.8);
            border-radius: 10px;
            color: white;
            text-align: center;
            box-shadow: 0px 0px 15px rgba(255, 255, 255, 0.5);
        }
        h2 {
            font-size: 26px;
            color: <?php echo $success ? 'limegreen' : 'red'; ?>;
            margin-bottom: 10px;
        }
        p {
            font-size: 18px;
            margin-bottom: 20px;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #ffcc00;
            color: black;
            font-size: 16px;
            font-weight: bold;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s ease;
        }
        .btn:hover {
            background: #ff9900;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2><?php echo $success ? 'Success!' : 'Error!'; ?></h2>
        <p><?php echo $message; ?></p>
        <a href="../html/Homepage.html" class="btn">Go To Home Page</a>
    </div>
</body>
</html>

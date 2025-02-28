<?php
include '../dbc/db_connect.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Check in users table
    $sql_user = "SELECT id, password FROM users WHERE username = ?";
    $stmt_user = $conn->prepare($sql_user);
    $stmt_user->bind_param("s", $username);
    $stmt_user->execute();
    $stmt_user->store_result();

    if ($stmt_user->num_rows > 0) {
        $stmt_user->bind_result($user_id, $hashed_password);
        $stmt_user->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['username'] = $username;
            $_SESSION['user_id'] = $user_id;
            $_SESSION['role'] = 'user';
            header("Location: user_dashboard.php");
            exit();
        }
    }
    $stmt_user->close();

    // Check in staff table
    $sql_staff = "SELECT StaffId, StaffRole, StaffPassword FROM staff WHERE StaffName = ?";
    $stmt_staff = $conn->prepare($sql_staff);
    $stmt_staff->bind_param("s", $username);
    $stmt_staff->execute();
    $stmt_staff->store_result();

    if ($stmt_staff->num_rows > 0) {
        $stmt_staff->bind_result($staff_id, $staff_role, $staff_password);
        $stmt_staff->fetch();

        if (password_verify($password, $staff_password)) {
            $_SESSION['username'] = $username;
            $_SESSION['staff_id'] = $staff_id;
            $_SESSION['role'] = strtolower(trim($staff_role)); // Normalize role

            if ($_SESSION['role'] === 'manager') {
                header("Location: manager_dashboard.php");
            } else {
                header("Location: user_dashboard.php");
            }
            exit();
        }
    }
    $stmt_staff->close();
}

$conn->close();
echo "Invalid credentials! <a href='../html/Login.html'>Try again</a>";
?>

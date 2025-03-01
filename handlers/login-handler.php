<?php
session_start();
include '../database/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    try {
        $user = verifyUser($username, $password);
        if ($user) {
            $_SESSION['user_id'] = $user['EmployeeID'];
            $_SESSION['username'] = $user['Username'];

            // Generate a unique token for session security
            $_SESSION['access_token'] = bin2hex(random_bytes(32));

            header("Location: ../views/dashboard.php");
            exit;
        } else {
            $_SESSION['login_error'] = "Invalid username or password!";
            header("Location: ../index.php");
            exit;
        }
    } catch (Exception $e) {
        $_SESSION['login_error'] = "Login failed. Please try again.";
        header("Location: ../index.php");
        exit;
    }
}

function verifyUser($username, $password) {
    global $conn;
    $stmt = $conn->prepare("SELECT EmployeeID, Username, Password FROM employees WHERE Username = ?");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['Password'])) {
            return $row; // Valid credentials
        }
    }
    return false;
}
?>

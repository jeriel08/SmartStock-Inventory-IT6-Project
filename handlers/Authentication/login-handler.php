<?php
session_start();
include '../../database/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    try {
        $user = verifyUser($username, $password);
        if ($user) {
            $_SESSION['user_id'] = $user['EmployeeID'];
            $_SESSION['username'] = $user['Username'];
            $_SESSION['first_name'] = $user['FirstName']; // Add FirstName
            $_SESSION['last_name'] = $user['LastName'];   // Add LastName
            $_SESSION['role'] = $user['Role'];            // Add Role

            // Generate a unique token for session security
            $_SESSION['access_token'] = bin2hex(random_bytes(32));

            // Log the login action into audit_logs
            $stmt = $conn->prepare("INSERT INTO audit_logs (TableName, RecordID, ActionType, NewValue, AdminID, Timestamp) 
                                    VALUES ('Employees', ?, 'Login', ?, ?, NOW())");
            $stmt->bind_param('isi', $user['EmployeeID'], $user['Username'], $user['EmployeeID']);
            $stmt->execute();
            $stmt->close();

            header("Location: ../../views/dashboard.php");
            exit;
        } else {
            $_SESSION['login_error'] = "Invalid username or password!";
            header("Location: ../../index.php");
            exit;
        }
    } catch (Exception $e) {
        $_SESSION['login_error'] = "Login failed. Please try again.";
        header("Location: ../../index.php");
        exit;
    }
}

function verifyUser($username, $password)
{
    global $conn;
    $stmt = $conn->prepare("SELECT EmployeeID, Username, Password, FirstName, LastName, Role, Status FROM employees WHERE Username = ?");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if ($row['Status'] === 'Inactive') {
            $_SESSION['login_error'] = "Your account is inactive. Please contact the administrator.";
            return false;
        }
        if (password_verify($password, $row['Password'])) {
            return $row; // Valid credentials
        }
    }
    return false;
}

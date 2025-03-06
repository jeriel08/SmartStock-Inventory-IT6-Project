<?php
session_start();
include '../../database/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Get form data
        $firstName = $_POST['employeeFirstName'] ?? '';
        $lastName = $_POST['employeeLastName'] ?? '';
        $username = $_POST['employeeUsername'] ?? '';
        $newPassword = $_POST['employeePassword'] ?? '';
        $confirmPassword = $_POST['employeeConfirmPassword'] ?? '';
        $oldPassword = $_POST['employeeOldPassword'] ?? '';

        // Verify old password
        $stmt = $conn->prepare("SELECT Password FROM employees WHERE EmployeeID = ?");
        $stmt->bind_param('i', $_SESSION['user_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();

        if (!password_verify($oldPassword, $user['Password'])) {
            $_SESSION['update_account_error'] = "Incorrect old password.";
            header("Location: ../../views/account.php");
            exit;
        }

        // Check if password fields match (if provided)
        if (!empty($newPassword) && $newPassword !== $confirmPassword) {
            $_SESSION['update_account_error'] = "New password and confirmation do not match.";
            header("Location: ../../views/account.php");
            exit;
        }

        // Prepare update query
        $updateFields = [];
        $types = '';
        $params = [];

        if ($firstName !== $_SESSION['first_name']) {
            $updateFields[] = "FirstName = ?";
            $types .= 's';
            $params[] = $firstName;
        }
        if ($lastName !== $_SESSION['last_name']) {
            $updateFields[] = "LastName = ?";
            $types .= 's';
            $params[] = $lastName;
        }
        if ($username !== $_SESSION['username']) {
            $updateFields[] = "Username = ?";
            $types .= 's';
            $params[] = $username;
        }
        if (!empty($newPassword)) {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $updateFields[] = "Password = ?";
            $types .= 's';
            $params[] = $hashedPassword;
        }

        // Always update Updated_At and Updated_By
        $updateFields[] = "Updated_At = NOW()";
        $updateFields[] = "Updated_By = ?";
        $types .= 'i';
        $params[] = $_SESSION['user_id'];

        if (!empty($updateFields)) {
            $query = "UPDATE employees SET " . implode(', ', $updateFields) . " WHERE EmployeeID = ?";
            $types .= 'i';
            $params[] = $_SESSION['user_id'];

            $stmt = $conn->prepare($query);
            $stmt->bind_param($types, ...$params);

            if ($stmt->execute()) {
                // Update session with new values
                $_SESSION['first_name'] = $firstName;
                $_SESSION['last_name'] = $lastName;
                $_SESSION['username'] = $username;
                $_SESSION['update_account_success'] = "Account updated successfully!";
                header("Location: ../../views/account.php");
                exit;
            } else {
                $_SESSION['update_account_error'] = "Failed to update account: " . $stmt->error;
                header("Location: ../../views/account.php");
                exit;
            }
        } else {
            $_SESSION['update_account_success'] = "No changes made.";
            header("Location: ../../views/account.php");
            exit;
        }
    } catch (Exception $e) {
        $_SESSION['update_account_error'] = "Operation Failed. Please try again.";
        header("Location: ../../views/account.php");
        exit;
    }
}

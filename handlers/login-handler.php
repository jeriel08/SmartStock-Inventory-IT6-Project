<?php

try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $user = verifyUser($username, $password);
        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            header('Location: ../views/dashboard.php');
            exit;
        } else {
            $_SESSION['errors'] = "Invalid username or password!";
            header("Location: ../index.php");
            exit;
        }
    }

} catch (\Exception $e) {
    echo "Error: " . $e;
}




function verifyUser($username, $password) {
    global $conn;
    $stmt = $conn->prepare('SELECT id, username, password FROM users WHERE username = ?');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if(password_verify($password, $row['password'])) {
            return $row;
        }
    } else {    
        return false;
    }
}


?>
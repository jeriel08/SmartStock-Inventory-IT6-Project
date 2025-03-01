<?php
// Database connection (adjust if your database.php differs)
$conn = new mysqli('localhost', 'root', '', 'smartstock_inventory');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error . PHP_EOL);
}

// Function to get input from console
function getInput($prompt) {
    echo $prompt;
    return trim(fgets(STDIN));
}

// Collect user input
$firstName = getInput("Enter First Name: ");
$lastName = getInput("Enter Last Name: ");
$username = getInput("Enter Username: ");
$password = getInput("Enter Password: ");
$role = getInput("Enter Role (e.g., ADMIN, STAFF): ");

// Hash the password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

try {
    // Prepare and execute the insert statement
    $stmt = $conn->prepare(
        "INSERT INTO employees (FirstName, LastName, Username, Password, Role) 
         VALUES (?, ?, ?, ?, ?)"
    );
    $stmt->bind_param('sssss', $firstName, $lastName, $username, $hashedPassword, $role);
    
    if ($stmt->execute()) {
        echo "Account created successfully! EmployeeID: " . $conn->insert_id . PHP_EOL;
    } else {
        echo "Failed to create account: " . $conn->error . PHP_EOL;
    }
    
    $stmt->close();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . PHP_EOL;
}

// Close the connection
$conn->close();
?>
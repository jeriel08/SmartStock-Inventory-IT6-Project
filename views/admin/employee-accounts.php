<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}

include '../../database/database.php';

// Fetch employee records
$stmt = $conn->prepare("SELECT * FROM Employees ORDER BY Created_at DESC");
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
        rel="icon"
        type="image/x-icon"
        href="../../statics/images/app-logo.ico" />

    <link rel="stylesheet" href="../../statics/account-style.css" />
    <link rel="stylesheet" href="../../statics/style.css" />
    <link rel="stylesheet" href="../../statics/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../../statics/account-manager.css" />

    <!-- Google Font Icon Links -->
    <link
        href="https://fonts.googleapis.com/icon?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Round"
        rel="stylesheet" />

    <script src="../../statics/js/bootstrap.min.js"></script>

    <title>Account Manager | SmartStock Inventory</title>
</head>

<body class="main">
    <nav class="navbar bg-body-tertiary fixed-top shadow-sm">
        <div class="container-fluid">
            <!-- Left side: Button and Header -->
            <div class="d-flex align-items-center">
                <button
                    class="navbar-toggler mx-3 border-0 shadow-none"
                    type="button"
                    data-bs-toggle="offcanvas"
                    data-bs-target="#offcanvasNavbar"
                    aria-controls="offcanvasNavbar"
                    aria-label="Toggle navigation">
                    <span class="material-icons-outlined navbar-icon"> menu </span>
                </button>
                <a class="navbar-brand fw-semibold" href="../account.php">ACCOUNT</a>
                <span class="material-icons-outlined me-3">chevron_right</span>
                <a class="navbar-brand fw-semibold" href="audit-log.php">ACCOUNT MANAGER</a>
            </div>

            <!-- Right side: Account Section -->
            <div class="d-flex align-items-center me-5 ms-auto">
                <span class="material-icons-outlined me-2 fs-1">account_circle</span>
                <div>
                    <p class="fw-bold mb-0">
                        <?php echo htmlspecialchars($_SESSION['first_name'] . ' ' . $_SESSION['last_name']); ?>
                    </p>
                    <small class="mt-0">
                        <?php echo htmlspecialchars($_SESSION['role']); ?>
                    </small>
                </div>
            </div>

            <!-- Offcanvas Menu -->
            <div
                class="offcanvas offcanvas-start"
                tabindex="-1"
                id="offcanvasNavbar"
                aria-labelledby="offcanvasNavbarLabel">
                <div class="offcanvas-header d-flex align-items-center mt-4">
                    <div class="col-10">
                        <img
                            src="../../statics/images/logo-2.png"
                            alt="SmartStock Inventory Logo"
                            class="img-fluid" />
                    </div>
                </div>
                <div class="offcanvas-body">
                    <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                        <li class="nav-item">
                            <a
                                class="nav-link btn btn-outline-dark d-flex align-items-center gap-2 my-3 py-2 px-4"
                                href="../dashboard.php">
                                <span class="material-icons-outlined"> dashboard </span>
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a
                                class="nav-link btn btn-outline-dark d-flex align-items-center gap-2 my-3 py-2 px-4"
                                href="../products.php">
                                <span class="material-icons-outlined"> inventory_2 </span>
                                Products
                            </a>
                        </li>
                        <li class="nav-item">
                            <a
                                class="nav-link btn btn-outline-dark d-flex align-items-center gap-2 my-3 py-2 px-4"
                                href="../orders.php">
                                <span class="material-icons-outlined"> shopping_cart </span>
                                Customer Orders
                            </a>
                        </li>
                        <li class="nav-item">
                            <a
                                class="nav-link btn btn-outline-dark d-flex align-items-center gap-2 my-3 py-2 px-4"
                                href="../suppliers.php">
                                <span class="material-icons-outlined"> inventory </span>
                                Suppliers
                            </a>
                        </li>
                        <li class="nav-item">
                            <a
                                class="nav-link btn btn-outline-dark d-flex align-items-center gap-2 my-3 py-2 px-4"
                                href="../purchases.php">
                                <span class="material-icons-outlined"> local_shipping </span>
                                Supplier Orders
                            </a>
                        </li>
                        <li class="nav-item">
                            <a
                                class="nav-link btn btn-outline-dark d-flex align-items-center gap-2 my-3 py-2 px-4"
                                href="../returns.php">
                                <span class="material-icons-outlined">
                                    assignment_return
                                </span>
                                Returns
                            </a>
                        </li>
                        <li class="nav-item">
                            <a
                                class="nav-link btn btn-outline-dark d-flex align-items-center gap-2 my-3 py-2 px-4 active"
                                href="../account.php">
                                <span class="material-icons-outlined"> account_circle </span>
                                Account
                            </a>
                        </li>
                        <li class="nav-item">
                            <a
                                class="nav-link btn btn-outline-dark d-flex align-items-center gap-2 my-3 py-2 px-4"
                                href="../../handlers/Authentication/logout-handler.php">
                                <span class="material-icons-outlined"> logout </span>
                                Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="container pt-4 mb-0">

        <div class="card shadow-sm p-3 rounded-4 mt-3">
            <div class="card-title px-3">
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <h2 class="fw-semibold">Manage Employee Accounts</h2>
                    <!-- Add Account Button -->
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">
                        Add Account
                    </button>
                </div>
            </div>
            <?php if (isset($_SESSION['status_update_success'])): ?>
                <div class="alert alert-success">
                    <?php echo $_SESSION['status_update_success'];
                    unset($_SESSION['status_update_success']); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['status_update_error'])): ?>
                <div class="alert alert-danger">
                    <?php echo $_SESSION['status_update_error'];
                    unset($_SESSION['status_update_error']); ?>
                </div>
            <?php endif; ?>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th>Employee ID</th>
                                <th>Name</th>
                                <th>Username</th>
                                <th>Role</th>
                                <th class="align-middle text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="table-group-divider">
                            <?php if ($result->num_rows > 0): ?>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td class="align-middle"><?php echo $row['EmployeeID']; ?></td>
                                        <td class="align-middle"><?php echo $row['FirstName'] . " " . $row['LastName']; ?></td>
                                        <td class="align-middle"><?php echo $row['Username']; ?></td>
                                        <td class="align-middle"><?php echo $row['Role']; ?></td>
                                        <td class="align-middle">
                                            <form action="../../handlers/Employee/update-account-status.php" method="POST">
                                                <input type="hidden" name="employeeID" value="<?php echo $row['EmployeeID']; ?>">
                                                <select name="status" class="form-select" onchange="this.form.submit()">
                                                    <option value="Active" <?php echo ($row['Status'] == 'Active') ? 'selected' : ''; ?>>Active</option>
                                                    <option value="Inactive" <?php echo ($row['Status'] == 'Inactive') ? 'selected' : ''; ?>>Inactive</option>
                                                </select>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center">No employee records available.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Employee Modal -->
    <div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-labelledby="addEmployeeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-semibold" id="addEmployeeModalLabel">Add New Employee</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="../../handlers/Employee/add-employee-handler.php" method="POST">
                        <div class="mb-3">
                            <label for="employeeFirstName" class="form-label fw-semibold">First Name</label>
                            <input type="text" class="form-control" id="employeeFirstName" name="employeeFirstName" placeholder="Enter first name" required>
                        </div>
                        <div class="mb-3">
                            <label for="employeeLastName" class="form-label fw-semibold">Last Name</label>
                            <input type="text" class="form-control" id="employeeLastName" name="employeeLastName" placeholder="Enter last name" required>
                        </div>
                        <div class="mb-3">
                            <label for="employeeUsername" class="form-label fw-semibold">Username</label>
                            <input type="text" class="form-control" id="employeeUsername" name="employeeUsername" placeholder="Enter username" required>
                        </div>
                        <div class="mb-3">
                            <label for="employeePassword" class="form-label fw-semibold">Password</label>
                            <input type="password" class="form-control" id="employeePassword" name="employeePassword" placeholder="Enter password" required>
                        </div>
                        <div class="mb-3">
                            <label for="employeeRole" class="form-label fw-semibold">Role</label>
                            <select class="form-select" id="employeeRole" name="employeeRole" required>
                                <option selected disabled>Select Role</option>
                                <option value="Admin">Admin</option>
                                <option value="Employee">Employee</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="employeeStatus" class="form-label fw-semibold">Status</label>
                            <select class="form-select" id="employeeStatus" name="employeeStatus" required>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Add Employee</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
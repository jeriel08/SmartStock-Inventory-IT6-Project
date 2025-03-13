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

    <script src="../../statics/js/bootstrap.bundle.js"></script>

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
                <a class="navbar-brand fw-semibold" href="audit-log.php">ACCOUNT MANAGER</a>
            </div>

            <!-- Right side: Account Section with Dropdown Button -->
            <div class="d-flex align-items-center me-5 ms-auto">
                <span class="material-icons-outlined me-2 fs-1">account_circle</span>
                <div>
                    <p class="fw-bold mb-0"><?php echo htmlspecialchars($_SESSION['first_name'] . ' ' . $_SESSION['last_name']); ?></p>
                    <small class="mt-0"><?php echo htmlspecialchars($_SESSION['role']); ?></small>
                </div>
                <div class="d-flex align-items-center">
                    <button class="btn border-0 bg-transparent p-0 ms-2" type="button" id="accountDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="material-icons-outlined">arrow_drop_down</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end me-2" aria-labelledby="accountDropdown">
                        <li><a class="dropdown-item" href="../account.php">Account Settings</a></li>
                        <li><a class="dropdown-item" href="../../handlers/Authentication/logout-handler.php">Logout</a></li>
                    </ul>
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
                        <?php if (isset($_SESSION['role']) && strtoupper($_SESSION['role']) === 'ADMIN'): ?>
                            <hr>
                            <li class="nav-item">
                                <h6 class="text-muted mb-3 px-4 ">Admin Controls</h6>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link btn btn-outline-dark d-flex align-items-center gap-2 my-3 py-2 px-4" href="audit-log.php">
                                    <span class="material-icons-outlined">local_activity</span>
                                    Audit Log
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link btn btn-outline-dark d-flex align-items-center gap-2 my-3 py-2 px-4 active" href="employee-accounts.php">
                                    <span class="material-icons-outlined">manage_accounts</span>
                                    Manage Employee Accounts
                                </a>
                            </li>
                        <?php endif; ?>
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
                                            <!-- Edit Button to Trigger Modal -->
                                            <button type="button" class="btn btn-primary btn-sm"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editEmployeeModal"
                                                data-employee-id="<?php echo $row['EmployeeID']; ?>"
                                                onclick="loadEmployeeData(<?php echo $row['EmployeeID']; ?>)">
                                                Edit
                                            </button>
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

    <!-- Edit Employee Modal -->
    <div class="modal fade" id="editEmployeeModal" tabindex="-1" aria-labelledby="editEmployeeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editEmployeeModalLabel">Edit Employee</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editEmployeeForm" action="../../handlers/Employee/update-employee.php" method="POST">
                        <input type="hidden" name="employeeID" id="modalEmployeeID">
                        <div class="mb-3">
                            <label for="modalFirstName" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="modalFirstName" name="firstName">
                        </div>
                        <div class="mb-3">
                            <label for="modalLastName" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="modalLastName" name="lastName">
                        </div>
                        <div class="mb-3">
                            <label for="modalUsername" class="form-label">Username</label>
                            <input type="text" class="form-control" id="modalUsername" name="username">
                        </div>
                        <div class="mb-3">
                            <label for="modalRole" class="form-label">Role</label>
                            <input type="text" class="form-control" id="modalRole" name="role">
                        </div>
                        <div class="mb-3">
                            <label for="modalPassword" class="form-label">Password (Enter new to update)</label>
                            <input type="password" class="form-control" id="modalPassword" name="password" placeholder="••••••••">
                            <small class="form-text text-muted">Leave blank to keep current password.</small>
                        </div>
                        <div class="mb-3">
                            <label for="modalStatus" class="form-label">Status</label>
                            <select name="status" id="modalStatus" class="form-select">
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" form="editEmployeeForm">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript to Load Employee Data -->
    <script>
        function loadEmployeeData(employeeID) {
            fetch(`../../handlers/Employee/get-employee.php?employeeID=${employeeID}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('modalEmployeeID').value = data.EmployeeID;
                    document.getElementById('modalFirstName').value = data.FirstName;
                    document.getElementById('modalLastName').value = data.LastName;
                    document.getElementById('modalUsername').value = data.Username;
                    document.getElementById('modalRole').value = data.Role;
                    document.getElementById('modalPassword').value = ''; // Empty by default
                    document.getElementById('modalStatus').value = data.Status;
                })
                .catch(error => console.error('Error fetching employee data:', error));
        }
    </script>
</body>

</html>
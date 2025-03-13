<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}

include '../../database/database.php';

// Check if the user is an admin
if (!isset($_SESSION['role']) || strtoupper($_SESSION['role']) !== 'ADMIN') {
    header("Location: ../index.php");
    exit();
}

// Fetch audit logs from the database
$sql = "SELECT * FROM audit_logs ORDER BY Timestamp DESC";
$result = $conn->query($sql);
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

    <title>Audit Log | SmartStock Inventory</title>
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
                <a class="navbar-brand fw-semibold" href="audit-log.php">AUDIT LOGS</a>
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
                                <a class="nav-link btn btn-outline-dark d-flex align-items-center gap-2 my-3 py-2 px-4 active" href="audit-log.php">
                                    <span class="material-icons-outlined">local_activity</span>
                                    Audit Log
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link btn btn-outline-dark d-flex align-items-center gap-2 my-3 py-2 px-4" href="employee-accounts.php">
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
        <div class="card shadow-sm rounded-4 p-3">
            <div class="card-body">
                <?php if ($result->num_rows > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Timestamp</th>
                                    <th>EmployeeID</th>
                                    <th>Action</th>
                                    <th>Table</th>
                                    <th>Details</th>
                                </tr>
                            </thead>
                            <tbody class="table-group-divider">
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td class="py-2"><?php echo $row['Timestamp']; ?></td>
                                        <td class="py-2"><?php echo $row['AdminID']; ?></td>
                                        <td class="py-2"><?php echo $row['ActionType']; ?></td>
                                        <td class="py-2"><?php echo $row['TableName']; ?></td>
                                        <td class="py-2"><?php echo $row['NewValue']; ?></td> <!-- Shows only new values -->
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-center text-muted fw-semibold">No recorded transactions at this time.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>

</html>
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

// Get filter and pagination parameters
$start_time = $_GET['start_time'] ?? '';
$end_time = $_GET['end_time'] ?? '';
$records_per_page = 10;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $records_per_page;

// Build the SQL query with optional time filter
$sql = "SELECT * FROM audit_logs WHERE 1=1";
$count_sql = "SELECT COUNT(*) as total FROM audit_logs WHERE 1=1";
$params = [];
$types = "";

if (!empty($start_time)) {
    $sql .= " AND Timestamp >= ?";
    $count_sql .= " AND Timestamp >= ?";
    $params[] = $start_time;
    $types .= "s";
}
if (!empty($end_time)) {
    $sql .= " AND Timestamp <= ?";
    $count_sql .= " AND Timestamp <= ?";
    $params[] = $end_time;
    $types .= "s";
}

// Add ordering and pagination to main query
$sql .= " ORDER BY Timestamp DESC LIMIT ? OFFSET ?";
$params[] = $records_per_page;
$params[] = $offset;
$types .= "ii";

// Get the total number of filtered audit logs
$total_stmt = $conn->prepare($count_sql);
if (!empty($start_time) && !empty($end_time)) {
    $total_stmt->bind_param("ss", $start_time, $end_time);
} elseif (!empty($start_time)) {
    $total_stmt->bind_param("s", $start_time);
} elseif (!empty($end_time)) {
    $total_stmt->bind_param("s", $end_time);
}
$total_stmt->execute();
$total_result = $total_stmt->get_result();
$total_row = $total_result->fetch_assoc();
$total_records = $total_row['total'];
$total_pages = ceil($total_records / $records_per_page);

// Fetch audit logs for the current page
$stmt = $conn->prepare($sql);
if (!empty($start_time) && !empty($end_time)) {
    $stmt->bind_param("ssii", $start_time, $end_time, $records_per_page, $offset);
} elseif (!empty($start_time)) {
    $stmt->bind_param("sii", $start_time, $records_per_page, $offset);
} elseif (!empty($end_time)) {
    $stmt->bind_param("sii", $end_time, $records_per_page, $offset);
} else {
    $stmt->bind_param("ii", $records_per_page, $offset);
}
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
    <link rel="stylesheet" href="../../statics/products-style.css">

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

    <div class="container mt-4 pt-5 pb-5">
        <div class="row align-items-center justify-content-between mb-3">
            <div class="col-md-5 d-flex">
                <!-- Optional: Add a search bar later if needed -->
            </div>
            <div class="col-md-auto d-flex gap-2">
                <button type="button" class="btn btn-outline-secondary d-flex align-items-center gap-2 py-2 rounded-4"
                    data-bs-toggle="modal" data-bs-target="#filterModal">
                    <span class="material-icons-outlined">tune</span>
                    <span>Filter</span>
                </button>
            </div>
        </div>

        <div class="container-fluid rounded-4 shadow">
            <div class="table-responsive mb-3">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th style="white-space: nowrap;">Timestamp</th>
                            <th style="white-space: nowrap;">EmployeeID</th>
                            <th style="white-space: nowrap;">Action</th>
                            <th style="white-space: nowrap;">Table</th>
                            <th style="max-width: 300px;">Details</th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider">
                        <?php if ($result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td class="py-2"><?php echo $row['Timestamp']; ?></td>
                                    <td class="py-2"><?php echo $row['AdminID']; ?></td>
                                    <td class="py-2"><?php echo $row['ActionType']; ?></td>
                                    <td class="py-2"><?php echo $row['TableName']; ?></td>
                                    <td class="py-2"
                                        style="max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"
                                        title="<?php echo htmlspecialchars($row['NewValue']); ?>">
                                        <?php echo htmlspecialchars($row['NewValue']); ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center">No recorded transactions at this time.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <!-- Pagination Controls -->
                <nav aria-label="Audit Logs Pagination">
                    <ul class="pagination justify-content-center">
                        <?php if ($page > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?start_time=<?= urlencode($start_time) ?>&end_time=<?= urlencode($end_time) ?>&page=<?= $page - 1 ?>">&laquo;</a>
                            </li>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="page-item <?= ($i === $page) ? 'active' : '' ?>">
                                <a class="page-link" href="?start_time=<?= urlencode($start_time) ?>&end_time=<?= urlencode($end_time) ?>&page=<?= $i ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>

                        <?php if ($page < $total_pages): ?>
                            <li class="page-item">
                                <a class="page-link" href="?start_time=<?= urlencode($start_time) ?>&end_time=<?= urlencode($end_time) ?>&page=<?= $page + 1 ?>">&raquo;</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        </div>
    </div>

    <!-- Filter Modal -->
    <div class="modal fade" id="filterModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title d-flex align-items-center gap-2" id="filterModalLabel">
                        <span class="material-icons-outlined">tune</span>
                        Filter Audit Logs
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="filterForm" method="GET" action="audit-log.php">
                        <div class="mb-3">
                            <label for="start_time" class="form-label">Start Time</label>
                            <input type="datetime-local" class="form-control" id="start_time" name="start_time" value="<?php echo htmlspecialchars($start_time); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="end_time" class="form-label">End Time</label>
                            <input type="datetime-local" class="form-control" id="end_time" name="end_time" value="<?php echo htmlspecialchars($end_time); ?>">
                        </div>
                        <input type="hidden" name="page" value="1">
                    </form>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="submit" form="filterForm" class="btn btn-primary add-product-button d-flex align-items-center gap-2 py-2 rounded-4">
                        <span class="material-icons-outlined">filter_list</span>
                        <span>Apply Filter</span>
                    </button>
                    <a href="audit-log.php?page=1" class="btn btn-outline-secondary d-flex align-items-center gap-2 rounded-4">
                        <span class="material-icons-outlined">clear</span>
                        <span>Clear Filter</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
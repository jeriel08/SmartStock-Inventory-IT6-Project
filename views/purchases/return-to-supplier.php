<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}
include '../../database/database.php';

if (!isset($conn) || $conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Get ReceivingID from URL (passed from purchases.php)
$receivingId = isset($_GET['receiving_id']) ? (int)$_GET['receiving_id'] : null;
if (!$receivingId) {
    $_SESSION['error'] = "No ReceivingID provided.";
    header('Location: ../purchases.php');
    exit();
}

// Fetch receiving header details
$stmt = $conn->prepare("
    SELECT r.ReceivingID, r.SupplierID, s.Name AS supplier_name, r.Date 
    FROM receiving r 
    JOIN suppliers s ON r.SupplierID = s.SupplierID 
    WHERE r.ReceivingID = ?
");
$stmt->bind_param('i', $receivingId);
$stmt->execute();
$headerResult = $stmt->get_result();
$header = $headerResult->fetch_assoc();
$stmt->close();

if (!$header) {
    $_SESSION['error'] = "Receiving record not found.";
    header('Location: ../purchases.php');
    exit();
}

// Fetch receiving details (products)
$stmt = $conn->prepare("
    SELECT rd.ProductID, p.Name AS product_name, rd.Quantity, rd.UnitCost 
    FROM receiving_details rd 
    JOIN products p ON rd.ProductID = p.ProductID 
    WHERE rd.ReceivingID = ?
");
$stmt->bind_param('i', $receivingId);
$stmt->execute();
$detailsResult = $stmt->get_result();
$products = [];
while ($row = $detailsResult->fetch_assoc()) {
    $products[] = $row;
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" type="image/x-icon" href="../../statics/images/app-logo.ico" />
    <link rel="stylesheet" href="../../statics/purchases-style.css" />
    <link rel="stylesheet" href="../../statics/products-style.css" />
    <link rel="stylesheet" href="../../statics/style.css" />
    <link rel="stylesheet" href="../../statics/css/bootstrap.min.css" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Round" rel="stylesheet" />
    <script src="../../statics/js/bootstrap.bundle.js"></script>
    <title>Return to Supplier | SmartStock Inventory</title>
</head>

<body class="main">
    <nav class="navbar bg-body-tertiary fixed-top shadow-sm">
        <div class="container-fluid">
            <div class="d-flex align-items-center">
                <button class="navbar-toggler mx-3 border-0 shadow-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
                    <span class="material-icons-outlined navbar-icon">menu</span>
                </button>
                <a class="navbar-brand fw-semibold" href="../purchases.php">SUPPLIER ORDERS</a>
                <span class="material-icons-outlined me-3">chevron_right</span>
                <a class="navbar-brand fw-semibold" href="return-to-supplier.php?receiving_id=<?php echo $receivingId; ?>">RETURN TO SUPPLIER</a>
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

            <!-- Offcanvas Menu (unchanged, copied from add-purchases.php) -->
            <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
                <div class="offcanvas-header d-flex align-items-center mt-4">
                    <div class="col-10">
                        <img src="../../statics/images/logo-2.png" alt="SmartStock Inventory Logo" class="img-fluid" />
                    </div>
                </div>
                <div class="offcanvas-body">
                    <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                        <li class="nav-item"><a class="nav-link btn btn-outline-dark d-flex align-items-center gap-2 my-3 py-2 px-4" href="../dashboard.php"><span class="material-icons-outlined">dashboard</span>Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link btn btn-outline-dark d-flex align-items-center gap-2 my-3 py-2 px-4" href="../products.php"><span class="material-icons-outlined">inventory_2</span>Products</a></li>
                        <li class="nav-item"><a class="nav-link btn btn-outline-dark d-flex align-items-center gap-2 my-3 py-2 px-4" href="../orders.php"><span class="material-icons-outlined">shopping_cart</span>Customer Orders</a></li>
                        <li class="nav-item"><a class="nav-link btn btn-outline-dark d-flex align-items-center gap-2 my-3 py-2 px-4" href="../suppliers.php"><span class="material-icons-outlined">inventory</span>Suppliers</a></li>
                        <li class="nav-item"><a class="nav-link btn btn-outline-dark d-flex align-items-center gap-2 my-3 py-2 px-4 active" href="../purchases.php"><span class="material-icons-outlined">local_shipping</span>Supplier Orders</a></li>
                        <li class="nav-item"><a class="nav-link btn btn-outline-dark d-flex align-items-center gap-2 my-3 py-2 px-4" href="../returns.php"><span class="material-icons-outlined">assignment_return</span>Returns</a></li>
                        <?php if (isset($_SESSION['role']) && strtoupper($_SESSION['role']) === 'ADMIN'): ?>
                            <hr>
                            <li class="nav-item">
                                <h6 class="text-muted mb-3 px-4 ">Admin Controls</h6>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link btn btn-outline-dark d-flex align-items-center gap-2 my-3 py-2 px-4" href="../admin/audit-log.php">
                                    <span class="material-icons-outlined">local_activity</span>
                                    Audit Log
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link btn btn-outline-dark d-flex align-items-center gap-2 my-3 py-2 px-4" href="../admin/employee-accounts.php">
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

    <div class="container pt-5 custom-container rounded-4 mb-5 shadow">
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success mb-4"><?php echo $_SESSION['success'];
                                                    unset($_SESSION['success']); ?></div>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger mb-4"><?php echo $_SESSION['error'];
                                                    unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <form action="../../handlers/SupplierOrder/return-to-supplier-handler.php" method="POST">
            <!-- Hidden ReceivingID -->
            <input type="hidden" name="receivingId" value="<?php echo $header['ReceivingID']; ?>">

            <!-- Return Header Fields -->
            <div class="row justify-content-center mb-5">
                <h4 class="fw-semibold mb-3 text-center">Return Details</h4>
                <div class="col-md-8 pe-5">
                    <div class="row mb-3">
                        <label for="supplier" class="col-md-3 col-form-label text-md-end fw-semibold">Supplier</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($header['supplier_name']); ?>" readonly>
                            <input type="hidden" name="supplierId" value="<?php echo $header['SupplierID']; ?>">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="date" class="col-md-3 col-form-label text-md-end fw-semibold">Date Received</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" value="<?php echo date('Y-m-d', strtotime($header['Date'])); ?>" readonly>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="returnDate" class="col-md-3 col-form-label text-md-end fw-semibold">Return Date</label>
                        <div class="col-md-9">
                            <input type="date" class="form-control" id="returnDate" name="returnDate" required value="<?php echo date('Y-m-d'); ?>" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products Table (Borderless) -->
            <div class="row justify-content-center mb-5">
                <div class="col-md-10">
                    <h4 class="fw-semibold mb-3 text-center">Products to Return</h4>
                    <table class="table table-borderless">
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Quantity Received</th>
                                <th>Quantity to be Returned</th>
                                <th>Reason</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($products as $index => $product): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($product['product_name']); ?>
                                        <input type="hidden" name="products[<?php echo $index; ?>][productId]" value="<?php echo $product['ProductID']; ?>">
                                    </td>
                                    <td><?php echo $product['Quantity']; ?></td>
                                    <td>
                                        <input type="number" class="form-control" name="products[<?php echo $index; ?>][quantityReturned]"
                                            min="0" max="<?php echo $product['Quantity']; ?>" value="0" placeholder="0" />
                                    </td>
                                    <td>
                                        <select class="form-select" name="products[<?php echo $index; ?>][reason]">
                                            <option value="" selected disabled>Select a reason</option>
                                            <option value="Damaged">Damaged</option>
                                            <option value="Wrong Item">Wrong Item</option>
                                            <option value="Defective">Defective</option>
                                            <option value="Overstock">Overstock</option>
                                            <option value="Expired">Expired</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="row justify-content-center mb-4">
                <div class="col-md-6 text-center">
                    <button type="submit" class="btn btn-outline-danger btn-outline-secondary d-flex align-items-center gap-2 py-2 px-4 mx-auto">
                        <span class="material-icons-outlined">assignment_return</span>
                        <span>Submit Return</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</body>

</html>
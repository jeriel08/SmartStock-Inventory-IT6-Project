<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../index.php');
    exit();
}

include '../../database/database.php';

// Initialize variables
$start_date = isset($_POST['start_date']) ? $_POST['start_date'] : date('Y-m-d', strtotime('-30 days'));
$end_date = isset($_POST['end_date']) ? $_POST['end_date'] : date('Y-m-d');
$sales_data = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Query to fetch sales data
    $query = "
        SELECT 
            p.ProductID,
            p.Name AS ProductName,
            c.Name AS CategoryName,
            SUM(ol.Quantity) AS QuantitySold,
            SUM(ol.Quantity * ol.Price) AS TotalSales
        FROM orders o
        JOIN orderline ol ON o.OrderID = ol.OrderID
        JOIN products p ON ol.ProductID = p.ProductID
        JOIN categories c ON p.CategoryID = c.CategoryID
        WHERE o.Date BETWEEN ? AND ?
        GROUP BY p.ProductID, p.Name, c.Name
        ORDER BY TotalSales DESC
    ";

    $stmt = $conn->prepare($query);
    $stmt->bind_param('ss', $start_date, $end_date);
    $stmt->execute();
    $result = $stmt->get_result();
    $sales_data = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    // After fetching $sales_data, calculate summary stats
    $total_sales = array_sum(array_column($sales_data, 'TotalSales'));
    $total_products_sold = array_sum(array_column($sales_data, 'QuantitySold'));

    // Query for number of orders
    $order_count_query = "SELECT COUNT(DISTINCT OrderID) as OrderCount 
        FROM orders 
        WHERE Date BETWEEN ? AND ?
    ";
    $stmt = $conn->prepare($order_count_query);
    $stmt->bind_param('ss', $start_date, $end_date);
    $stmt->execute();
    $order_result = $stmt->get_result();
    $order_count = $order_result->fetch_assoc()['OrderCount'];
    $stmt->close();
    $avg_sale_per_order = $order_count ? $total_sales / $order_count : 0;

    // Query for returns value
    $returns_value_query = "
        SELECT SUM(rd.QuantityReturned * ol.Price) AS ReturnsValue
        FROM returns r
        JOIN return_details rd ON r.ReturnID = rd.ReturnID
        JOIN orderline ol ON r.OrderID = ol.OrderID AND rd.ProductID = ol.ProductID
        WHERE r.ReturnDate BETWEEN ? AND ?
    ";
    $stmt = $conn->prepare($returns_value_query);
    $stmt->bind_param('ss', $start_date, $end_date);
    $stmt->execute();
    $result = $stmt->get_result();
    $returns_value = $result->fetch_assoc()['ReturnsValue'] ?? 0; // Default to 0 if no returns
    $stmt->close();

    // Calculate Net Sales
    $net_sales = $total_sales - $returns_value;

    // Query for returns data (from previous response)
    $returns_query = "
        SELECT 
        rd.Reason,
        SUM(rd.QuantityReturned) AS QuantityReturned
        FROM returns r
        JOIN return_details rd ON r.ReturnID = rd.ReturnID
        WHERE r.ReturnDate BETWEEN ? AND ?
        GROUP BY rd.Reason
    ";
    $stmt = $conn->prepare($returns_query);
    $stmt->bind_param('ss', $start_date, $end_date);
    $stmt->execute();
    $result = $stmt->get_result();
    $returns_data = [];
    while ($row = $result->fetch_assoc()) {
        $returns_data[$row['Reason']] = $row['QuantityReturned'];
    }
    $stmt->close();

    $wrong_item_returns = isset($returns_data['Wrong Item']) ? $returns_data['Wrong Item'] : 0;
    $damaged_expired_returns = isset($returns_data['Damaged/Expired']) ? $returns_data['Damaged/Expired'] : 0;
    $total_returns = $wrong_item_returns + $damaged_expired_returns;
}
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
    <link rel="stylesheet" href="../../statics/products-style.css" />
    <link rel="stylesheet" href="../../statics/style.css" />
    <link rel="stylesheet" href="../../statics/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../../statics/dashboard-style.css">
    <link rel="stylesheet" href="../../statics/sales-report-style.css">

    <!-- Google Font Icon Links -->
    <link
        href="https://fonts.googleapis.com/icon?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Round"
        rel="stylesheet" />



    <title>Dashboard | SmartStock Inventory</title>
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
                <a class="navbar-brand fw-semibold" href="sales_report.php">SALES REPORT</a>
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
                                class="nav-link btn btn-outline-dark d-flex align-items-center gap-2 my-3 py-2 px-4 active"
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


    <div class="container pt-5 mt-3 mb-4">
        <h2 class="mb-4 fw-semibold">Sales Report</h2>

        <!-- Date Range Form -->
        <form method="POST" class="mb-4">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo htmlspecialchars($start_date); ?>" required>
                </div>
                <div class="col-md-3">
                    <label for="end_date" class="form-label">End Date</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" value="<?php echo htmlspecialchars($end_date); ?>" required>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary add-product-button w-100">Generate Report</button>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn add-product-button w-100" onclick="window.print()">Print Report</button>
                </div>
            </div>
        </form>

        <!-- Sales Report Table -->
        <?php if (!empty($sales_data)): ?>
            <!-- Summary Table with Net Sales -->
            <div class="my-4">
                <h4>Summary</h4>
                <div class="table-responsive table-responsive-rounded px-3 pb-3">
                    <table class="table table-hover rounded-5">
                        <thead class="table-light">
                            <tr>
                                <th>Total Sales Made</th>
                                <th>Number of Products Sold</th>
                                <th>Number of Orders</th>
                                <th>Average Sale per Order</th>
                                <th>Net Sales</th>
                            </tr>
                        </thead>
                        <tbody class="table-group-divider">
                            <tr>
                                <td>$<?php echo number_format($total_sales, 2); ?></td>
                                <td><?php echo number_format($total_products_sold); ?></td>
                                <td><?php echo number_format($order_count); ?></td>
                                <td>$<?php echo number_format($avg_sale_per_order, 2); ?></td>
                                <td>$<?php echo number_format($net_sales, 2); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Products Returned Table -->
            <div class="mb-4">
                <h4>Products Returned</h4>
                <div class="table-responsive table-responsive-rounded px-3 pb-3">
                    <table class="table table-striped-odd table-hover">
                        <thead>
                            <tr>
                                <th>Total Returns (Wrong Item)</th>
                                <th>Total Returns (Damaged/Expired)</th>
                                <th>Total Quantity Returned</th>
                            </tr>
                        </thead>
                        <tbody class="table-group-divider">
                            <tr>
                                <td><?php echo number_format($wrong_item_returns); ?></td>
                                <td><?php echo number_format($damaged_expired_returns); ?></td>
                                <td><?php echo number_format($total_returns); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Product Summary -->
            <div class="mb-4">
                <h4>Products Summary</h4>
                <div class="table-responsive table-responsive-rounded px-3 pb-3">

                    <table class="table table-striped-odd table-hover">
                        <thead>
                            <tr>
                                <th>Product ID</th>
                                <th>Product Name</th>
                                <th>Category</th>
                                <th>Quantity Sold</th>
                                <th>Total Sales</th>
                            </tr>
                        </thead>
                        <tbody class="table-group-divider">
                            <?php foreach ($sales_data as $row): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['ProductID']); ?></td>
                                    <td><?php echo htmlspecialchars($row['ProductName']); ?></td>
                                    <td><?php echo htmlspecialchars($row['CategoryName']); ?></td>
                                    <td><?php echo htmlspecialchars($row['QuantitySold']); ?></td>
                                    <td>$<?php echo number_format($row['TotalSales'], 2); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
            <div class="card">
                <div class="card-body rounded-3">
                    <div class="alert alert-info mb-0 border-0 align-items-center">
                        <span class="fw-semibold">
                            No sales data found for the selected date range.

                        </span>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script src="../../statics/js/bootstrap.bundle.js"></script>

</body>

</html>
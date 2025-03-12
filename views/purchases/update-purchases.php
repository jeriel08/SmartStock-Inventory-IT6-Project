<?php
session_start();
include '../../database/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}

$receivingId = isset($_GET['receiving_id']) ? (int)$_GET['receiving_id'] : null;
if (!$receivingId) {
    $_SESSION['error'] = "No ReceivingID provided.";
    header('Location: ../purchases.php');
    exit();
}

// Fetch header details
$stmt = $conn->prepare("SELECT r.ReceivingID, r.SupplierID, s.Name AS supplier_name, r.Date, r.Status FROM receiving r JOIN suppliers s ON r.SupplierID = s.SupplierID WHERE r.ReceivingID = ?");
$stmt->bind_param('i', $receivingId);
$stmt->execute();
$header = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Fetch product details
$stmt = $conn->prepare("SELECT rd.ProductID, p.Name AS product_name, rd.Quantity, rd.UnitCost FROM receiving_details rd JOIN products p ON rd.ProductID = p.ProductID WHERE rd.ReceivingID = ?");
$stmt->bind_param('i', $receivingId);
$stmt->execute();
$products = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
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
    <script src="../../statics/js/bootstrap.min.js"></script>
    <title>Update Purchase | SmartStock Inventory</title>
</head>

<body class="main">
    <nav class="navbar bg-body-tertiary fixed-top shadow-sm">
        <!-- Same navbar as add-purchases.php, update active link to "SUPPLIER ORDERS" -->
        <div class="container-fluid">
            <div class="d-flex align-items-center">
                <button class="navbar-toggler mx-3 border-0 shadow-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
                    <span class="material-icons-outlined navbar-icon">menu</span>
                </button>
                <a class="navbar-brand fw-semibold" href="../purchases.php">SUPPLIER ORDERS</a>
                <span class="material-icons-outlined me-3">chevron_right</span>
                <a class="navbar-brand fw-semibold" href="#">UPDATE PURCHASE</a>
            </div>
            <div class="d-flex align-items-center me-5 ms-auto">
                <span class="material-icons-outlined me-2 fs-1">account_circle</span>
                <div>
                    <p class="fw-bold mb-0"><?php echo htmlspecialchars($_SESSION['first_name'] . ' ' . $_SESSION['last_name']); ?></p>
                    <small class="mt-0"><?php echo htmlspecialchars($_SESSION['role']); ?></small>
                </div>
            </div>
            <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
                <div class="offcanvas-header d-flex align-items-center mt-4">
                    <div class="col-10"><img src="../../statics/images/logo-2.png" alt="SmartStock Inventory Logo" class="img-fluid" /></div>
                </div>
                <div class="offcanvas-body">
                    <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                        <li class="nav-item"><a class="nav-link btn btn-outline-dark d-flex align-items-center gap-2 my-3 py-2 px-4" href="../dashboard.php"><span class="material-icons-outlined">dashboard</span>Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link btn btn-outline-dark d-flex align-items-center gap-2 my-3 py-2 px-4" href="../products.php"><span class="material-icons-outlined">inventory_2</span>Products</a></li>
                        <li class="nav-item"><a class="nav-link btn btn-outline-dark d-flex align-items-center gap-2 my-3 py-2 px-4" href="../orders.php"><span class="material-icons-outlined">shopping_cart</span>Customer Orders</a></li>
                        <li class="nav-item"><a class="nav-link btn btn-outline-dark d-flex align-items-center gap-2 my-3 py-2 px-4" href="../suppliers.php"><span class="material-icons-outlined">inventory</span>Suppliers</a></li>
                        <li class="nav-item"><a class="nav-link btn btn-outline-dark d-flex align-items-center gap-2 my-3 py-2 px-4 active" href="../purchases.php"><span class="material-icons-outlined">local_shipping</span>Supplier Orders</a></li>
                        <li class="nav-item"><a class="nav-link btn btn-outline-dark d-flex align-items-center gap-2 my-3 py-2 px-4" href="../returns.php"><span class="material-icons-outlined">assignment_return</span>Returns</a></li>
                        <li class="nav-item"><a class="nav-link btn btn-outline-dark d-flex align-items-center gap-2 my-3 py-2 px-4" href="../account.php"><span class="material-icons-outlined">account_circle</span>Account</a></li>
                        <li class="nav-item"><a class="nav-link btn btn-outline-dark d-flex align-items-center gap-2 my-3 py-2 px-4" href="../../handlers/Authentication/logout-handler.php"><span class="material-icons-outlined">logout</span>Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="container pt-5 custom-container rounded-4 mb-5 shadow">
        <form action="../../handlers/SupplierOrder/update-purchase-handler.php" method="POST">
            <input type="hidden" name="receivingId" value="<?php echo $header['ReceivingID']; ?>">
            <div class="row justify-content-center mb-5">
                <h4 class="fw-semibold mb-3 text-center">Suppliers</h4>
                <div class="col-md-8 pe-5">
                    <div class="row mb-3">
                        <label for="supplier" class="col-md-3 col-form-label text-md-end fw-semibold">Supplier</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($header['supplier_name']); ?>" readonly>
                            <input type="hidden" name="supplierId" value="<?php echo $header['SupplierID']; ?>">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="date" class="col-md-3 col-form-label text-md-end fw-semibold">Date</label>
                        <div class="col-md-9">
                            <input type="date" class="form-control" id="date" name="date" value="<?php echo date('Y-m-d', strtotime($header['Date'])); ?>" required />
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="status" class="col-md-3 col-form-label text-md-end fw-semibold">Status</label>
                        <div class="col-md-9">
                            <select name="status" id="status" class="form-select" required>
                                <option value="Pending" <?php echo $header['Status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                <option value="Received" <?php echo $header['Status'] == 'Received' ? 'selected' : ''; ?>>Received</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center mb-5">
                <div class="col-md-10">
                    <h4 class="fw-semibold mb-3 text-center">Products</h4>
                    <table class="table table-borderless">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Unit Cost</th>
                                <th>Selling Price</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="product-rows">
                            <?php foreach ($products as $index => $product): ?>
                                <tr class="product-row">
                                    <td>
                                        <select name="products[<?php echo $index; ?>][productId]" class="form-select" required>
                                            <option value="<?php echo $product['ProductID']; ?>" selected><?php echo htmlspecialchars($product['product_name']); ?></option>
                                            <?php
                                            $stmt = $conn->prepare("SELECT ProductID, Name FROM products WHERE ProductID != ?");
                                            $stmt->bind_param('i', $product['ProductID']);
                                            $stmt->execute();
                                            $result = $stmt->get_result();
                                            while ($row = $result->fetch_assoc()) {
                                                echo "<option value='{$row['ProductID']}'>" . htmlspecialchars($row['Name']) . "</option>";
                                            }
                                            $stmt->close();
                                            ?>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control" name="products[<?php echo $index; ?>][quantity]"
                                            value="<?php echo $product['Quantity']; ?>" min="1" required />
                                    </td>
                                    <td>
                                        <input type="number" class="form-control" name="products[<?php echo $index; ?>][cost]"
                                            value="<?php echo $product['UnitCost']; ?>" step="0.01" min="0" required />
                                    </td>
                                    <td>
                                        <input type="number" class="form-control" name="products[<?php echo $index; ?>][sellingPrice]"
                                            placeholder="Price" step="0.01" min="0" required />
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger remove-row d-flex justify-content-center align-items-center" <?php echo count($products) == 1 ? 'disabled' : ''; ?>>
                                            <span class="material-icons-outlined">remove</span>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="text-center">
                        <button type="button" id="add-product-row" class="btn btn-outline-primary mb-3 d-flex justify-content-center align-items-center gap-2 mx-auto">
                            <span class="material-icons-outlined">add</span> Add Another Product
                        </button>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center mb-4">
                <div class="col-md-6 text-center">
                    <button type="submit" class="btn btn-outline-primary d-flex align-items-center gap-2 py-2 px-4 mx-auto">
                        <span class="material-icons-outlined">save</span> Update Purchase
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let rowCount = <?php echo count($products); ?>;
            const addButton = document.getElementById('add-product-row');
            addButton.addEventListener('click', function() {
                const productRows = document.getElementById('product-rows');
                const newRow = document.createElement('tr');
                newRow.className = 'product-row';
                newRow.innerHTML = `
                    <td>
                        <select name="products[${rowCount}][productId]" class="form-select" required>
                            <option value="" selected disabled>Select a product</option>
                            <?php
                            $stmt = $conn->prepare("SELECT ProductID, Name FROM products");
                            $stmt->execute();
                            $result = $stmt->get_result();
                            while ($row = $result->fetch_assoc()) {
                                echo "<option value='{$row['ProductID']}'>" . htmlspecialchars($row['Name']) . "</option>";
                            }
                            $stmt->close();
                            ?>
                        </select>
                    </td>
                    <td><input type="number" class="form-control" name="products[${rowCount}][quantity]" placeholder="Qty" min="1" required /></td>
                    <td><input type="number" class="form-control" name="products[${rowCount}][cost]" placeholder="Cost" step="0.01" min="0" required /></td>
                    <td><input type="number" class="form-control" name="products[${rowCount}][sellingPrice]" placeholder="Price" step="0.01" min="0" required /></td>
                    <td><button type="button" class="btn btn-danger remove-row d-flex justify-content-center align-items-center"><span class="material-icons-outlined">remove</span></button></td>
                `;
                productRows.appendChild(newRow);
                rowCount++;
                updateRemoveButtons();
            });

            function updateRemoveButtons() {
                const rows = document.querySelectorAll('.product-row');
                const removeButtons = document.querySelectorAll('.remove-row');
                removeButtons.forEach(btn => {
                    btn.disabled = rows.length === 1;
                    btn.onclick = function() {
                        if (rows.length > 1) btn.closest('.product-row').remove();
                    };
                });
            }
            updateRemoveButtons();
        });
    </script>
</body>

</html>
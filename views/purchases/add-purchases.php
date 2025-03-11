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
    <link rel="stylesheet" href="../../statics/purchases-style.css" />
    <link rel="stylesheet" href="../../statics/products-style.css" />
    <link rel="stylesheet" href="../../statics/style.css" />
    <link rel="stylesheet" href="../../statics/css/bootstrap.min.css" />

    <!-- Google Font Icon Links -->
    <link
        href="https://fonts.googleapis.com/icon?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Round"
        rel="stylesheet" />

    <script src="../../statics/js/bootstrap.min.js"></script>

    <title>Supplier Orders | SmartStock Inventory</title>
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
                <a class="navbar-brand fw-semibold" href="../purchases.php">SUPPLIER ORDERS</a>
                <span class="material-icons-outlined me-3">chevron_right</span>
                <a class="navbar-brand fw-semibold" href="add-purchases.php">ADD PURCHASES</a>
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
                                class="nav-link btn btn-outline-dark d-flex align-items-center gap-2 my-3 py-2 px-4 active"
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
                                class="nav-link btn btn-outline-dark d-flex align-items-center gap-2 my-3 py-2 px-4"
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

    <div class="container pt-5 custom-container rounded-4 mb-5 shadow">
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success mb-4">
                <?php echo $_SESSION['success'];
                unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger mb-4">
                <?php echo $_SESSION['error'];
                unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>
        <form action="../../handlers/SupplierOrder/add-purchase-handler.php" method="POST">
            <!-- Receiving Header Fields (unchanged) -->
            <div class="row justify-content-center mb-5">
                <h4 class="fw-semibold mb-3 text-center">Suppliers</h4>
                <div class="col-md-8 pe-5">
                    <!-- Supplier, Date, Status fields remain the same -->
                    <div class="row mb-3">
                        <label for="supplier" class="col-md-3 col-form-label text-md-end fw-semibold">Supplier</label>
                        <div class="col-md-9">
                            <select name="supplierId" id="supplier" class="form-select" required>
                                <option value="" selected disabled>Select a supplier</option>
                                <?php
                                $stmt = $conn->prepare("SELECT SupplierID, Name FROM suppliers WHERE Status = 'Active'");
                                $stmt->execute();
                                $result = $stmt->get_result();
                                while ($row = $result->fetch_assoc()) {
                                    echo "<option value='{$row['SupplierID']}'>" . htmlspecialchars($row['Name']) . "</option>";
                                }
                                $stmt->close();
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="date" class="col-md-3 col-form-label text-md-end fw-semibold">Date</label>
                        <div class="col-md-9">
                            <input type="date" class="form-control" id="date" name="date" required
                                value="<?php echo date('Y-m-d'); ?>" />
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="status" class="col-md-3 col-form-label text-md-end fw-semibold">Status</label>
                        <div class="col-md-9">
                            <select name="status" id="status" class="form-select" required>
                                <option value="Pending" selected>Pending</option>
                                <option value="Received">Received</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Receiving Details (Dynamic Product Rows) -->
            <div class="row justify-content-center mb-5">
                <div class="col-md-10">
                    <h4 class="fw-semibold mb-3 text-center">Products</h4>
                    <div id="product-rows">
                        <div class="product-row row mb-3">
                            <div class="col-md-3">
                                <label for="productId_0" class="form-label fw-semibold">Product</label>
                                <select name="products[0][productId]" id="productId_0" class="form-select" required>
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
                            </div>
                            <div class="col-md-2">
                                <label for="quantity_0" class="form-label fw-semibold">Quantity</label>
                                <input type="number" class="form-control" id="quantity_0" name="products[0][quantity]"
                                    placeholder="Qty" min="1" required />
                            </div>
                            <div class="col-md-2">
                                <label for="cost_0" class="form-label fw-semibold">Unit Cost</label>
                                <input type="number" class="form-control" id="cost_0" name="products[0][cost]"
                                    placeholder="Cost" step="0.01" min="0" required />
                            </div>
                            <div class="col-md-3">
                                <label for="sellingPrice_0" class="form-label fw-semibold">Selling Price</label>
                                <input type="number" class="form-control" id="sellingPrice_0" name="products[0][sellingPrice]"
                                    placeholder="Price" step="0.01" min="0" required />
                            </div>
                            <div class="col-md-2 d-flex align-items-center justify-content-center">
                                <button type="button" class="btn btn-danger remove-row d-flex justify-content-center align-items-center" disabled>
                                    <span class="material-icons-outlined">remove</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="button" id="add-product-row" class="btn btn-outline-primary mb-3 d-flex justify-content-center align-items-center gap-2 mx-auto">
                            <span class="material-icons-outlined">add</span> Add Another Product
                        </button>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="row justify-content-center mb-4">
                <div class="col-md-6 text-center">
                    <button type="submit" class="btn btn-outline-primary d-flex align-items-center gap-2 py-2 px-4 mx-auto">
                        <span class="material-icons-outlined">save</span>
                        <span>Save Purchase</span>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let rowCount = 1;

            const addButton = document.getElementById('add-product-row');
            if (!addButton) {
                console.error("Add product button not found!");
                return;
            }

            addButton.addEventListener('click', function() {
                const productRows = document.getElementById('product-rows');
                if (!productRows) {
                    console.error("Product rows container not found!");
                    return;
                }

                const newRow = document.createElement('div');
                newRow.className = 'product-row row mb-3';
                newRow.innerHTML = `
            <div class="col-md-3">
                <label for="productId_${rowCount}" class="form-label fw-semibold">Product</label>
                <select name="products[${rowCount}][productId]" id="productId_${rowCount}" class="form-select" required>
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
            </div>
            <div class="col-md-2">
                <label for="quantity_${rowCount}" class="form-label fw-semibold">Quantity</label>
                <input type="number" class="form-control" id="quantity_${rowCount}" name="products[${rowCount}][quantity]" 
                    placeholder="Qty" min="1" required />
            </div>
            <div class="col-md-2">
                <label for="cost_${rowCount}" class="form-label fw-semibold">Unit Cost</label>
                <input type="number" class="form-control" id="cost_${rowCount}" name="products[${rowCount}][cost]" 
                    placeholder="Cost" step="0.01" min="0" required />
            </div>
            <div class="col-md-3">
                <label for="sellingPrice_${rowCount}" class="form-label fw-semibold">Selling Price</label>
                <input type="number" class="form-control" id="sellingPrice_${rowCount}" name="products[${rowCount}][sellingPrice]" 
                    placeholder="Price" step="0.01" min="0" required />
            </div>
            <div class="col-md-2 d-flex align-items-center justify-content-center">
                <button type="button" class="btn btn-danger remove-row d-flex justify-content-center align-items-center">
                    <span class="material-icons-outlined">remove</span>
                </button>
            </div>
        `;
                productRows.appendChild(newRow);
                rowCount++;
                updateRemoveButtons();
            });

            // Form submission validation
            const form = document.querySelector('form');
            form.addEventListener('submit', function(event) {
                let isValid = true;
                const rows = document.querySelectorAll('.product-row');
                rows.forEach((row, index) => {
                    const cost = parseFloat(row.querySelector(`#cost_${index}`).value) || 0;
                    const sellingPrice = parseFloat(row.querySelector(`#sellingPrice_${index}`).value) || 0;
                    if (sellingPrice < cost) {
                        isValid = false;
                        if (!confirm(`Selling Price ($${sellingPrice.toFixed(2)}) is below Unit Cost ($${cost.toFixed(2)}) for row ${index + 1}. Proceed anyway?`)) {
                            event.preventDefault();
                        }
                    }
                });
            });

            function updateRemoveButtons() {
                const rows = document.querySelectorAll('.product-row');
                const removeButtons = document.querySelectorAll('.remove-row');
                removeButtons.forEach((btn, index) => {
                    btn.disabled = rows.length === 1; // Disable if only one row
                    btn.onclick = function() {
                        if (rows.length > 1) {
                            btn.closest('.product-row').remove();
                        }
                    };
                });
            }

            // Initial setup
            updateRemoveButtons();
        });
    </script>
</body>

</html>
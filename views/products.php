<?php
session_start();
include '../database/database.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}

// Get filter, search, and pagination parameters
$filter = $_GET['filter'] ?? 'In Stock'; // Default filter
$search = $_GET['search'] ?? ''; // Search query
$records_per_page = 10; // Products per page
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $records_per_page;

try {
    // Fetch all units once and store in an array
    $unitOptions = [];
    $query = "SELECT UnitID, Name FROM units ORDER BY Name ASC";
    $result = $conn->query($query);
    while ($row = $result->fetch_assoc()) {
        $unitOptions[] = $row;
    }

    // Fetch total product count for pagination with search
    $countQuery = "SELECT COUNT(*) AS total FROM Products WHERE 1";
    $params = [];
    $types = "";

    if ($filter !== 'All') {
        $countQuery .= " AND Status = ?";
        $params[] = $filter;
        $types .= "s";
    }

    if (!empty($search)) {
        $countQuery .= " AND Name LIKE ?";
        $params[] = "%" . $search . "%";
        $types .= "s";
    }

    $countStmt = $conn->prepare($countQuery);
    if (!empty($params)) {
        $countStmt->bind_param($types, ...$params);
    }
    $countStmt->execute();
    $countResult = $countStmt->get_result();
    $total_records = $countResult->fetch_assoc()['total'];
    $total_pages = ceil($total_records / $records_per_page);
    $countStmt->close();

    // Fetch paginated products using stored procedure with search
    $stmt = $conn->prepare("CALL GetProductsWithPageAndSearch(?, ?, ?, ?)");
    $stmt->bind_param("ssii", $filter, $search, $records_per_page, $offset);
    $stmt->execute();
    $products = $stmt->get_result();
    $stmt->close();
} catch (Exception $e) {
    die("Error fetching products: " . $e->getMessage());
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" type="image/x-icon" href="../statics/images/app-logo.ico" />
    <link rel="stylesheet" href="../statics/products-style.css" />
    <link rel="stylesheet" href="../statics/style.css" />
    <link rel="stylesheet" href="../statics/css/bootstrap.min.css" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Round" rel="stylesheet" />
    <script src="../statics/js/bootstrap.min.js"></script>
    <title>Products | SmartStock Inventory</title>
</head>

<body class="main">
    <nav class="navbar bg-body-tertiary fixed-top shadow-sm">
        <div class="container-fluid">
            <!-- Left side: Button and Header -->
            <div class="d-flex align-items-center">
                <button class="navbar-toggler mx-3 border-0 shadow-none" type="button"
                    data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar"
                    aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
                    <span class="material-icons-outlined navbar-icon">menu</span>
                </button>
                <a class="navbar-brand fw-semibold" href="products.php">PRODUCTS</a>
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
            <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
                <div class="offcanvas-header d-flex align-items-center mt-4">
                    <div class="col-10">
                        <img src="../statics/images/logo-2.png" alt="SmartStock Inventory Logo" class="img-fluid" />
                    </div>
                </div>
                <div class="offcanvas-body">
                    <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                        <li class="nav-item">
                            <a class="nav-link btn btn-outline-dark d-flex align-items-center gap-2 my-3 py-2 px-4" href="dashboard.php">
                                <span class="material-icons-outlined">dashboard</span>
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn btn-outline-dark d-flex align-items-center gap-2 my-3 py-2 px-4 active" href="products.php">
                                <span class="material-icons-outlined">inventory_2</span>
                                Products
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn btn-outline-dark d-flex align-items-center gap-2 my-3 py-2 px-4" href="orders.php">
                                <span class="material-icons-outlined">shopping_cart</span>
                                Customer Orders
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn btn-outline-dark d-flex align-items-center gap-2 my-3 py-2 px-4" href="suppliers.php">
                                <span class="material-icons-outlined">inventory</span>
                                Suppliers
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn btn-outline-dark d-flex align-items-center gap-2 my-3 py-2 px-4" href="purchases.php">
                                <span class="material-icons-outlined">local_shipping</span>
                                Supplier Orders
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn btn-outline-dark d-flex align-items-center gap-2 my-3 py-2 px-4" href="returns.php">
                                <span class="material-icons-outlined">assignment_return</span>
                                Returns
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn btn-outline-dark d-flex align-items-center gap-2 my-3 py-2 px-4" href="account.php">
                                <span class="material-icons-outlined">account_circle</span>
                                Account
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn btn-outline-dark d-flex align-items-center gap-2 my-3 py-2 px-4" href="../handlers/Authentication/logout-handler.php">
                                <span class="material-icons-outlined">logout</span>
                                Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mt-4 pt-5">
        <div class="row align-items-center justify-content-between mb-5">
            <div class="col-md-5 d-flex">
                <form method="GET" action="products.php" class="mb-3">
                    <div class="input-group gap-2">
                        <input type="text" name="search" class="form-control" placeholder="Search products..." value="<?= htmlspecialchars($search) ?>">
                        <input type="hidden" name="filter" value="<?= htmlspecialchars($filter) ?>">
                        <button type="submit" class="btn btn-primary add-product-button d-flex align-items-center gap-2 rounded-4">
                            <span class="material-icons-outlined">search</span>
                            <span>Search</span>
                        </button>
                    </div>
                </form>
            </div>
            <div class="col-md-auto d-flex gap-2">
                <button type="button" class="btn btn-outline-secondary d-flex align-items-center gap-2 py-2 rounded-4"
                    data-bs-toggle="modal" data-bs-target="#filterModal">
                    <span class="material-icons-outlined">tune</span>
                    <span>Filter</span>
                </button>
                <button type="button" class="btn btn-outline-secondary d-flex align-items-center gap-2 py-2 rounded-4"
                    data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                    <span class="material-icons-outlined">category</span>
                    <span>Add Category</span>
                </button>
                <button type="button" class="btn btn-primary add-product-button d-flex align-items-center gap-2 py-2 rounded-4"
                    data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                    <span class="material-icons-outlined">add</span>
                    <span>Add Product</span>
                </button>
            </div>

        </div>
        <?php if (isset($_SESSION['product_success'])): ?>
            <div class="alert alert-success mt-4">
                <?php echo $_SESSION['product_success'];
                unset($_SESSION['product_success']); ?>
            </div>
        <?php endif; ?>
        <?php if (isset($_SESSION['product_error'])): ?>
            <div class="alert alert-danger">
                <?php echo $_SESSION['product_error'];
                unset($_SESSION['product_error']); ?>
            </div>
        <?php endif; ?>
        <div class="container-fluid rounded-5 shadow">
            <div class="table-responsive mb-3">
                <!-- Products Table -->
                <table class="table table-striped rounded-3">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Product ID</th>
                            <th>Price</th>
                            <th>Supplier</th>
                            <th>Category</th>
                            <th>Stock</th>
                            <th>Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $products->fetch_assoc()): ?>
                            <tr>
                                <td class="align-middle"><?php echo htmlspecialchars($row['Name']); ?></td>
                                <td class="align-middle"><?php echo htmlspecialchars($row['ProductID']); ?></td>
                                <td class="align-middle">₱<?php echo number_format($row['Price'], 2); ?></td>
                                <td class="align-middle"><?php echo htmlspecialchars($row['SupplierName'] ?? 'N/A'); ?></td>
                                <td class="align-middle"><?php echo htmlspecialchars($row['CategoryName'] ?? 'N/A'); ?></td>
                                <td class="align-middle">
                                    <?php
                                    $stockQuantity = htmlspecialchars($row['StockQuantity']);
                                    $unit = htmlspecialchars($row['Abbreviation'] ?? '');
                                    echo $stockQuantity . ' ' . $unit;
                                    ?>
                                </td>

                                <td class="align-middle"><?php echo htmlspecialchars($row['Status']); ?></td>
                                <td class="align-middle text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <button class="btn edit-button btn-primary add-product-button rounded-4"
                                            data-bs-toggle="modal" data-bs-target="#editProductModal"
                                            data-product-id="<?php echo $row['ProductID']; ?>"
                                            data-price="<?php echo $row['Price']; ?>"
                                            data-status="<?php echo $row['Status']; ?>"
                                            data-unit-id="<?php echo $row['UnitID']; ?>">
                                            <span class="material-icons-outlined">edit</span>
                                        </button>

                                        <button type="button" class="btn btn-danger d-flex align-items-center gap-2 py-2 rounded-4 discard-button"
                                            data-bs-toggle="modal" data-bs-target="#discardStockModal">
                                            <span class="material-icons-outlined">remove_circle_outline</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>

                        <?php if ($products->num_rows === 0): ?>
                            <tr>
                                <td colspan="8" class="text-center">No products found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <!-- Pagination Controls -->
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        <?php if ($page > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?filter=<?= urlencode($filter) ?>&search=<?= urlencode($search) ?>&page=<?= $page - 1 ?>">Previous</a>
                            </li>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="page-item <?= ($i === $page) ? 'active' : '' ?>">
                                <a class="page-link" href="?filter=<?= urlencode($filter) ?>&search=<?= urlencode($search) ?>&page=<?= $i ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>

                        <?php if ($page < $total_pages): ?>
                            <li class="page-item">
                                <a class="page-link" href="?filter=<?= urlencode($filter) ?>&search=<?= urlencode($search) ?>&page=<?= $page + 1 ?>">Next</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        </div>

    </div>

    <!-- Add Product Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5 d-flex align-items-center gap-2" id="staticBackdropLabel">
                        <span class="material-icons-outlined fs-2">add_box</span>
                        Add Product
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="../handlers/Product/add-product-handler.php" method="POST">
                    <div class="modal-body">
                        <!-- Product Name -->
                        <div class="row mb-3">
                            <label for="productName" class="col-md-3 col-form-label text-md-end">Product Name</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="productName" name="productName"
                                    placeholder="Enter product name" required />
                            </div>
                        </div>

                        <!-- Unit of Measurement -->
                        <div class="row mb-3">
                            <label for="unit" class="col-md-3 col-form-label text-md-end">Unit</label>
                            <div class="col-md-9">
                                <select name="unitId" id="unit" class="form-select" required>
                                    <option value="" selected disabled>Select a unit</option>
                                    <?php
                                    $stmt = $conn->prepare("SELECT UnitID, Name FROM units WHERE Status = 'Active'");
                                    if ($stmt === false) {
                                        error_log("Prepare failed for units: " . $conn->error);
                                        echo "<option value=''>Error loading units</option>";
                                    } else {
                                        $stmt->execute();
                                        $result = $stmt->get_result();
                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                echo "<option value='{$row['UnitID']}'>" . htmlspecialchars($row['Name']) . "</option>";
                                            }
                                        } else {
                                            echo "<option value=''>No active units available</option>";
                                        }
                                        $stmt->close();
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <!-- Category -->
                        <div class="row mb-3">
                            <label for="category" class="col-md-3 col-form-label text-md-end">Category</label>
                            <div class="col-md-9">
                                <select name="categoryId" id="category" class="form-select" required>
                                    <option value="" selected disabled>Select a category</option>
                                    <?php
                                    $stmt = $conn->prepare("SELECT CategoryID, Name FROM categories WHERE Status = 'Active'");
                                    if ($stmt === false) {
                                        error_log("Prepare failed for categories: " . $conn->error);
                                        echo "<option value=''>Error loading categories</option>";
                                    } else {
                                        $stmt->execute();
                                        $result = $stmt->get_result();
                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                echo "<option value='{$row['CategoryID']}'>" . htmlspecialchars($row['Name']) . "</option>";
                                            }
                                        } else {
                                            echo "<option value=''>No active categories available</option>";
                                        }
                                        $stmt->close();
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="submit" class="btn btn-primary add-product-button d-flex align-items-center gap-2 py-2 rounded-4">
                            <span class="material-icons-outlined">add</span>
                            <span>Add Product</span>
                        </button>
                        <button type="button" class="btn btn-outline-secondary d-flex align-items-center gap-2 rounded-4" data-bs-dismiss="modal">
                            <span class="material-icons-outlined">close</span>
                            Close
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Add Category Modal -->
    <div class="modal fade" id="addCategoryModal" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title me-3 d-flex align-items-center gap-2" id="addCategoryModalLabel">
                        <span class="material-icons-outlined fs-2">add_box</span>
                        Add Category
                    </h5>
                    <a class="btn btn-outline-secondary d-flex align-items-center gap-2 rounded-4" href="products/categories.php">
                        <span class="material-icons-outlined">category</span>
                        <span>Manage Categories</span>
                    </a>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="../handlers/Product/add-category-handler.php" method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="categoryName" class="form-label">Name</label>
                            <input type="text" class="form-control" id="categoryName" name="categoryName" required />
                        </div>
                        <div class="mb-3">
                            <label for="categoryDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="categoryDescription" name="categoryDescription"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary add-product-button d-flex align-items-center gap-2 py-2 rounded-4">
                            <span class="material-icons-outlined">add</span>
                            <span>Add Category</span>
                        </button>
                        <button type="button" class="btn btn-outline-secondary d-flex align-items-center gap-2 rounded-4"
                            data-bs-dismiss="modal">
                            <span class="material-icons-outlined">close</span>
                            Close
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Product Modal -->
    <div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title d-flex align-items-center gap-2" id="editProductModalLabel">
                        <span class="material-icons-outlined fs-2">edit</span>
                        Edit Product
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="../handlers/Product/edit-product-handler.php" method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="productId" id="editProductId">
                        <div class="row mb-3">
                            <label for="editPrice" class="col-md-3 col-form-label text-md-end">Price</label>
                            <div class="col-md-9">
                                <input type="number" class="form-control" id="editPrice" name="price" step="0.01" min="0" required />
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="editUnit" class="col-md-3 col-form-label text-md-end">Unit</label>
                            <div class="col-md-9">
                                <select class="form-select" id="editUnit" name="unit" required>
                                    <?php foreach ($unitOptions as $unit): ?>
                                        <option value="<?= htmlspecialchars($unit['UnitID']); ?>">
                                            <?= htmlspecialchars($unit['Name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="editStatus" class="col-md-3 col-form-label text-md-end">Status</label>
                            <div class="col-md-9">
                                <select class="form-select" id="editStatus" name="status" required>
                                    <option value="In Stock">In Stock</option>
                                    <option value="Out of Stock">Out of Stock</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="submit" class="btn btn-primary add-product-button d-flex align-items-center gap-2 py-2 rounded-4">
                            <span class="material-icons-outlined">save</span>
                            <span>Save Changes</span>
                        </button>
                        <button type="button" class="btn btn-outline-secondary d-flex align-items-center gap-2 rounded-4"
                            data-bs-dismiss="modal">
                            <span class="material-icons-outlined">close</span>
                            Close
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Discard Stock Modal / Opened within the Edit Product Modal -->
    <div class="modal fade" id="discardStockModal" tabindex="-1" aria-labelledby="discardStockModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="discardStockModalLabel">Discard Stock</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="../handlers/Product/discard-stock-handler.php" method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="productId" id="discardProductId"> <!-- ✅ This should now receive the correct ProductID -->
                        <p>Are you sure you want to discard stock for <strong id="discardProductName"></strong>?</p>
                        <div class="mb-3">
                            <label for="discardQuantity" class="form-label">Quantity to Discard</label>
                            <input type="number" class="form-control" id="discardQuantity" name="quantity" min="1" required>
                        </div>
                        <div class="mb-3">
                            <label for="discardReason" class="form-label">Reason</label>
                            <select class="form-select" id="discardReason" name="reason" required>
                                <option value="Expired">Expired</option>
                                <option value="Damaged">Damaged</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger">Confirm Discard</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
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
                        Filter Products
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-footer justify-content-center">
                    <button class="btn btn-outline-success filter-btn" data-filter="In Stock">Show In Stock</button>
                    <button class="btn btn-outline-danger filter-btn" data-filter="Out of Stock">Show Out of Stock</button>
                    <button class="btn btn-outline-primary filter-btn" data-filter="All">Show All</button>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript to Populate Edit Modal -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".edit-button").forEach(button => {
                button.addEventListener("click", function() {
                    const productId = this.getAttribute("data-product-id");
                    const price = this.getAttribute("data-price");
                    const status = this.getAttribute("data-status");
                    const unitId = this.getAttribute("data-unit-id");

                    document.getElementById("editProductId").value = productId;
                    document.getElementById("editPrice").value = price;
                    document.getElementById("editStatus").value = status;

                    // Set the correct unit in dropdown
                    const unitDropdown = document.getElementById("editUnit");
                    if (unitId) {
                        unitDropdown.value = unitId;
                    }
                });
            });
        });

        document.querySelectorAll('.filter-btn').forEach(button => {
            button.addEventListener('click', function() {
                const filterStatus = this.getAttribute('data-filter');
                const url = new URL(window.location.href);
                url.searchParams.set('filter', filterStatus);
                window.location.href = url.toString();
            });
        });
    </script>
    <script src="../statics/discard-stock.js" defer>
        console.log("JavaScript file loaded!");
    </script>
</body>

</html>
<?php $conn->close(); ?>
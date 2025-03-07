<?php
session_start();
include '../database/database.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}

$filter = $_GET['filter'] ?? 'In Stock'; // Default filter

// Start the base query
$query = "SELECT p.Name, p.ProductID, p.Price, s.Name AS SupplierName, 
                 c.Name AS CategoryName, p.StockQuantity, p.Status 
          FROM products p
          LEFT JOIN suppliers s ON p.SupplierID = s.SupplierID
          LEFT JOIN categories c ON p.CategoryID = c.CategoryID";

// Apply filtering based on user selection
if ($filter === 'In Stock') {
    $query .= " WHERE p.Status = 'In Stock'";
} elseif ($filter === 'Out of Stock') {
    $query .= " WHERE p.Status = 'Out of Stock'";
} // No condition needed for 'All' (fetch everything)

$query .= " ORDER BY p.Name ASC"; // Optional sorting

$products = $conn->query($query);

if (!$products) {
    die("Query Failed: " . $conn->error); // Debugging in case of errors
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
    <nav class="navbar bg-body-tertiary fixed-top shadow">
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
                <form action="#" class="d-flex w-100">
                    <input type="text" name="search" placeholder="Search an item" class="form-control me-2" />
                    <button type="submit" class="btn btn-primary add-product-button d-flex align-items-center gap-2 rounded-4">
                        <span class="material-icons-outlined">search</span>
                        <span>Search</span>
                    </button>
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
        <div class="container-fluid rounded-5">
            <div class="table-responsive mb-3">
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
                                <td class="align-middle"><?php echo number_format($row['Price'], 2); ?></td>
                                <td class="align-middle"><?php echo htmlspecialchars($row['SupplierName'] ?? 'N/A'); ?></td>
                                <td class="align-middle"><?php echo htmlspecialchars($row['CategoryName'] ?? 'N/A'); ?></td>
                                <td class="align-middle"><?php echo htmlspecialchars($row['StockQuantity']); ?></td>
                                <td class="align-middle"><?php echo htmlspecialchars($row['Status']); ?></td>
                                <td class="align-middle text-center">
                                    <button class="btn edit-button btn-primary add-product-button rounded-4"
                                        data-bs-toggle="modal" data-bs-target="#editProductModal"
                                        data-product-id="<?php echo $row['ProductID']; ?>"
                                        data-price="<?php echo $row['Price']; ?>"
                                        data-status="<?php echo $row['Status']; ?>">
                                        <span class="material-icons-outlined">edit</span>
                                    </button>
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

                        <!-- Category -->
                        <div class="row mb-3">
                            <label for="category" class="col-md-3 col-form-label text-md-end">Category</label>
                            <div class="col-md-9">
                                <select name="categoryId" id="category" class="form-select" required>
                                    <option value="" selected disabled>Select a category</option>
                                    <?php
                                    $stmt = $conn->prepare("SELECT CategoryID, Name FROM categories");
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
                                            echo "<option value=''>No categories available</option>";
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
        document.addEventListener('DOMContentLoaded', function() {
            var editModal = document.getElementById('editProductModal');
            editModal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget;
                var productId = button.getAttribute('data-product-id');
                var price = button.getAttribute('data-price');
                var status = button.getAttribute('data-status');

                var modal = this;
                modal.querySelector('#editProductId').value = productId;
                modal.querySelector('#editPrice').value = price;
                modal.querySelector('#editStatus').value = status;
            });
        });

        // Script for the filter
        document.querySelectorAll('.filter-btn').forEach(button => {
            button.addEventListener('click', function() {
                const filterValue = this.getAttribute('data-filter');
                const url = new URL(window.location.href);
                url.searchParams.set('filter', filterValue);
                window.location.href = url.toString(); // Redirect with the new filter
            });
        });
    </script>
</body>

</html>
<?php $conn->close(); ?>
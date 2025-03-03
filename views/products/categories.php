<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}
include '../../database/database.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
      rel="icon"
      type="image/x-icon"
      href="../../statics/images/app-logo.ico"
    />
    <link rel="stylesheet" href="../../statics/categories-style.css" />
    <link rel="stylesheet" href="../../statics/products-style.css" />
    <link rel="stylesheet" href="../../statics/style.css" />
    <link rel="stylesheet" href="../../statics/css/bootstrap.min.css" />

    <!-- Google Font Icon Links -->
    <link
      href="https://fonts.googleapis.com/icon?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Round"
      rel="stylesheet"
    />

    <script src="../../statics/js/bootstrap.min.js"></script>

    <title>Categories | SmartStock Inventory</title>
  </head>
</head>
<body class="main">
    <nav class="navbar bg-body-tertiary fixed-top shadow">
      <div class="container-fluid">
        <!-- Left side: Button and Header -->
        <div class="d-flex align-items-center">
          <button
            class="navbar-toggler mx-3 border-0 shadow-none"
            type="button"
            data-bs-toggle="offcanvas"
            data-bs-target="#offcanvasNavbar"
            aria-controls="offcanvasNavbar"
            aria-label="Toggle navigation"
          >
            <span class="material-icons-outlined navbar-icon"> menu </span>
          </button>
          <a class="navbar-brand fw-semibold" href="../products.php">PRODUCTS</a>
          <span class="material-icons-outlined me-3">chevron_right</span>
          <a class="navbar-brand fw-semibold" href="../products.php">CATEGORIES</a>
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
          aria-labelledby="offcanvasNavbarLabel"
        >
          <div class="offcanvas-header d-flex align-items-center mt-4">
            <div class="col-10">
              <img
                src="../../statics/images/logo-2.png"
                alt="SmartStock Inventory Logo"
                class="img-fluid"
              />
            </div>
          </div>
          <div class="offcanvas-body">
            <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
              <li class="nav-item">
                <a
                  class="nav-link btn btn-outline-dark d-flex align-items-center gap-2 my-3 py-2 px-4"
                  href="../dashboard.php"
                >
                  <span class="material-icons-outlined"> dashboard </span>
                  Dashboard
                </a>
              </li>
              <li class="nav-item">
                <a
                  class="nav-link btn btn-outline-dark d-flex align-items-center gap-2 my-3 py-2 px-4 active"
                  href="../products.php"
                >
                  <span class="material-icons-outlined"> inventory_2 </span>
                  Products
                </a>
              </li>
              <li class="nav-item">
                <a
                  class="nav-link btn btn-outline-dark d-flex align-items-center gap-2 my-3 py-2 px-4"
                  href="../orders.php"
                >
                  <span class="material-icons-outlined"> shopping_cart </span>
                  Orders
                </a>
              </li>
              <li class="nav-item">
                <a
                  class="nav-link btn btn-outline-dark d-flex align-items-center gap-2 my-3 py-2 px-4"
                  href="../suppliers.php"
                >
                  <span class="material-icons-outlined"> inventory </span>
                  Suppliers
                </a>
              </li>
              <li class="nav-item">
                <a
                  class="nav-link btn btn-outline-dark d-flex align-items-center gap-2 my-3 py-2 px-4"
                  href="../purchases.php"
                >
                  <span class="material-icons-outlined"> shopping_bag </span>
                  Purchases
                </a>
              </li>
              <li class="nav-item">
                <a
                  class="nav-link btn btn-outline-dark d-flex align-items-center gap-2 my-3 py-2 px-4"
                  href="../returns.php"
                >
                  <span class="material-icons-outlined">
                    assignment_return
                  </span>
                  Returns
                </a>
              </li>
              <li class="nav-item">
                <a
                  class="nav-link btn btn-outline-dark d-flex align-items-center gap-2 my-3 py-2 px-4"
                  href="../account.php"
                >
                  <span class="material-icons-outlined"> account_circle </span>
                  Account
                </a>
              </li>
              <li class="nav-item">
                <a
                  class="nav-link btn btn-outline-dark d-flex align-items-center gap-2 my-3 py-2 px-4"
                  href="../../handlers/logout-handler.php"
                >
                  <span class="material-icons-outlined"> logout </span>
                  Logout
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </nav>
    <div class="container mt-4 pt-5 custom-container rounded-4">
        <button type="button" class="btn btn-primary add-product-button d-flex align-items-center gap-2 py-2 rounded-4 mb-4" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
            <span class="material-icons-outlined">add</span>
            Add Category
        </button>

        <!-- Category List -->
        <?php
        $stmt = $conn->prepare("SELECT CategoryID, Name, Description FROM categories");
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()): ?>
            <div class="card mb-3">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title"><?php echo htmlspecialchars($row['Name']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($row['Description'] ?: 'No description'); ?></p>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-primary">Edit</button>
                        <button class="btn btn-outline-danger">Delete</button>
                    </div>
                </div>
            </div>
        <?php endwhile; $stmt->close(); ?>
    </div>

    <!-- Add Category Modal -->
    <div class="modal fade" id="addCategoryModal" data-bs-backdrop="static" data-bs-keyboard="false" 
             tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title me-3 d-flex align-items-center gap-2" id="addCategoryModalLabel">
                          <span class="material-icons-outlined fs-2"> add_box </span>
                          Add Category
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="../../handlers/add-category-handler.php" method="POST">
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
                            <button
                              type="button"
                              class="btn btn-outline-secondary d-flex align-items-center gap-2 rounded-4"
                              data-bs-dismiss="modal"
                            >
                              <span class="material-icons-outlined">close</span>
                              Close
                            </button>
                        </div>
                    </form>
                </div>
            </div>


    <script src="../statics/js/bootstrap.min.js"></script>
</body>
</html>
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
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link
    rel="icon"
    type="image/x-icon"
    href="../../statics/images/app-logo.ico" />
  <link rel="stylesheet" href="../../statics/categories-style.css" />
  <link rel="stylesheet" href="../../statics/products-style.css" />
  <link rel="stylesheet" href="../../statics/style.css" />
  <link rel="stylesheet" href="../../statics/css/bootstrap.min.css" />

  <!-- Google Font Icon Links -->
  <link
    href="https://fonts.googleapis.com/icon?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Round"
    rel="stylesheet" />

  <script src="../../statics/js/bootstrap.min.js"></script>

  <title>Categories | SmartStock Inventory</title>
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
                class="nav-link btn btn-outline-dark d-flex align-items-center gap-2 my-3 py-2 px-4 active"
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

  <div class="container mt-4 pt-5 custom-container rounded-4">
    <div class="row justify-content-between align-items-center mb-4">
      <div class="col-auto">
        <a href="../products.php" class="btn btn-outline-secondary d-flex align-items-center gap-2 py-2 rounded-4">
          <span class="material-icons-outlined">arrow_back</span>
          Go back
        </a>
      </div>
      <div class="col-auto">
        <button type="button" class="btn btn-primary add-product-button d-flex align-items-center gap-2 py-2 rounded-4" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
          <span class="material-icons-outlined">add</span>
          Add Category
        </button>
      </div>
    </div>
    <div class="row justify-content-between align-items-center mb-1">
      <div class="col-auto">
        <?php if (isset($_SESSION['success'])): ?>
          <div class="alert alert-success mt-4">
            <?php echo $_SESSION['success'];
            unset($_SESSION['success']); ?>
          </div>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
          <div class="alert alert-danger">
            <?php echo $_SESSION['error'];
            unset($_SESSION['error']); ?>
          </div>
        <?php endif; ?>
      </div>
    </div>


    <!-- Category List -->
    <?php
    $stmt = $conn->prepare("SELECT CategoryID, Name, Description FROM categories");
    if ($stmt === false) {
      echo '<div class="card mb-3"><div class="card-body text-center"><p>Error preparing statement!</p></div></div>';
    }
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()): ?>
        <div class="card mb-3" style="border-color: #f66435;">
          <div class="card-body d-flex justify-content-between align-items-center">
            <div>
              <h3 class="card-title fw-semibold" style="color: #f66435;"><?php echo htmlspecialchars($row['Name']); ?></h3>
              <p class="card-text" style="color: #f66435;"><?php echo htmlspecialchars($row['Description'] ?: 'No description'); ?></p>
            </div>
            <div class="d-flex gap-2">
              <button class="btn btn-primary add-product-button d-flex align-items-center gap-2 py-2 rounded-4"
                data-bs-toggle="modal"
                data-bs-target="#editCategoryModal"
                data-category-id="<?php echo $row['CategoryID']; ?>"
                data-category-name="<?php echo htmlspecialchars($row['Name']); ?>"
                data-category-desc="<?php echo htmlspecialchars($row['Description']); ?>">
                <span class="material-icons-outlined">edit</span>
              </button>
              <button class="btn btn-outline-danger d-flex align-items-center gap-2 py-2 rounded-4"
                data-bs-toggle="modal"
                data-bs-target="#deleteCategoryModal"
                data-category-id="<?php echo $row['CategoryID']; ?>"
                data-category-name="<?php echo htmlspecialchars($row['Name']); ?>">
                <span class="material-icons-outlined">remove_circle_outline</span>
              </button>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    <?php } else { ?>
      <div class="card mb-3" style="border-color: #f66435;">
        <div class="card-body text-center">
          <span class="material-icons-outlined fs-2" style="color: #f66435;">category</span>
          <p class="card-text" style="color: #f66435;">No category yet.</p>
        </div>
      </div>
    <?php } ?>
    <?php $stmt->close(); ?>
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
        <form action="../../handlers/Category/add-category-handler.php" method="POST">
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
              data-bs-dismiss="modal">
              <span class="material-icons-outlined">close</span>
              Close
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Edit Category Modal -->
  <div class="modal fade" id="editCategoryModal" data-bs-backdrop="static" data-bs-keyboard="false"
    tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title me-3 d-flex align-items-center gap-2" id="editCategoryModalLabel">
            <span class="material-icons-outlined fs-2">edit</span>
            Edit Category
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="../../handlers/Category/update-category-handler.php" method="POST">
          <div class="modal-body">
            <input type="hidden" name="categoryID" id="editCategoryID">
            <div class="mb-3">
              <label for="editCategoryName" class="form-label">Name</label>
              <input type="text" class="form-control" id="editCategoryName" name="categoryName" required />
            </div>
            <div class="mb-3">
              <label for="editCategoryDescription" class="form-label">Description</label>
              <textarea class="form-control" id="editCategoryDescription" name="categoryDescription"></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary add-product-button d-flex align-items-center gap-2 py-2 rounded-4">
              <span class="material-icons-outlined">save</span>
              <span>Save Changes</span>
            </button>
            <button type="button" class="btn btn-outline-secondary d-flex align-items-center gap-2 rounded-4" data-bs-dismiss="modal">
              <span class="material-icons-outlined">close</span>
              Cancel
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Delete Category Modal -->
  <div class="modal fade" id="deleteCategoryModal" data-bs-backdrop="static" data-bs-keyboard="false"
    tabindex="-1" aria-labelledby="deleteCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title me-3 d-flex align-items-center gap-2" id="deleteCategoryModalLabel">
            <span class="material-icons-outlined fs-2">delete</span>
            Delete Category
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="../../handlers/Category/delete-category-handler.php" method="POST">
          <div class="modal-body">
            <input type="hidden" name="categoryID" id="deleteCategoryID">
            <p>Are you sure you want to delete the category "<span id="deleteCategoryName"></span>"? This action cannot be undone.</p>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-danger d-flex align-items-center gap-2 py-2 rounded-4">
              <span class="material-icons-outlined">delete_forever</span>
              <span>Delete</span>
            </button>
            <button type="button" class="btn btn-outline-secondary d-flex align-items-center gap-2 rounded-4" data-bs-dismiss="modal">
              <span class="material-icons-outlined">close</span>
              Cancel
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>


  <script src="../statics/js/bootstrap.min.js"></script>
  <script>
    // JavaScript to populate Edit and Delete modals with data
    document.addEventListener('DOMContentLoaded', function() {
      // Debug: Ensure script runs
      console.log("DOMContentLoaded event fired");

      // Edit Modal
      const editModal = document.getElementById('editCategoryModal');
      if (editModal) {
        editModal.addEventListener('show.bs.modal', function(event) {
          try {
            console.log("Edit modal show event triggered");
            const button = event.relatedTarget; // Button that triggered the modal
            const categoryId = button.getAttribute('data-category-id');
            const categoryName = button.getAttribute('data-category-name');
            const categoryDesc = button.getAttribute('data-category-desc');

            // Populate the form fields
            editModal.querySelector('#editCategoryID').value = categoryId;
            editModal.querySelector('#editCategoryName').value = categoryName;
            editModal.querySelector('#editCategoryDescription').value = categoryDesc || '';
          } catch (e) {
            console.error("Error in edit modal handler:", e);
          }
        });
      } else {
        console.error("Edit modal element not found");
      }

      // Delete Modal
      const deleteModal = document.getElementById('deleteCategoryModal');
      if (deleteModal) {
        deleteModal.addEventListener('show.bs.modal', function(event) {
          try {
            console.log("Delete modal show event triggered");
            const button = event.relatedTarget; // Button that triggered the modal
            const categoryId = button.getAttribute('data-category-id');
            const categoryName = button.getAttribute('data-category-name');

            // Populate the hidden input and confirmation text
            deleteModal.querySelector('#deleteCategoryID').value = categoryId;
            deleteModal.querySelector('#deleteCategoryName').textContent = categoryName;
          } catch (e) {
            console.error("Error in delete modal handler:", e);
          }
        });
      } else {
        console.error("Delete modal element not found");
      }
    });
  </script>
</body>

</html>
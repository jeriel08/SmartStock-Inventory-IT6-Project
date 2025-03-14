<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header('Location: ../index.php');
  exit();
}
include '../database/database.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link
    rel="icon"
    type="image/x-icon"
    href="../statics/images/app-logo.ico" />
  <link rel="stylesheet" href="../statics/products-style.css" />
  <link rel="stylesheet" href="../statics/style.css" />
  <link rel="stylesheet" href="../statics/supplier-style.css" />
  <link rel="stylesheet" href="../statics/css/bootstrap.min.css" />

  <!-- Google Font Icon Links -->
  <link
    href="https://fonts.googleapis.com/icon?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Round"
    rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=call" />

  <script src="../statics/js/bootstrap.bundle.js"></script>

  <title>Supplier | SmartStock Inventory</title>
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
        <a class="navbar-brand fw-semibold" href="suppliers.php">SUPPLIER</a>
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
            <li><a class="dropdown-item" href="account.php">Account Settings</a></li>
            <li><a class="dropdown-item" href="../handlers/Authentication/logout-handler.php">Logout</a></li>
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
              src="../statics/images/logo-2.png"
              alt="SmartStock Inventory Logo"
              class="img-fluid" />
          </div>
        </div>
        <div class="offcanvas-body">
          <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
            <li class="nav-item">
              <a
                class="nav-link btn btn-outline-dark d-flex align-items-center gap-2 my-3 py-2 px-4"
                href="dashboard.php">
                <span class="material-icons-outlined"> dashboard </span>
                Dashboard
              </a>
            </li>
            <li class="nav-item">
              <a
                class="nav-link btn btn-outline-dark d-flex align-items-center gap-2 my-3 py-2 px-4"
                href="products.php">
                <span class="material-icons-outlined"> inventory_2 </span>
                Products
              </a>
            </li>
            <li class="nav-item">
              <a
                class="nav-link btn btn-outline-dark d-flex align-items-center gap-2 my-3 py-2 px-4"
                href="orders.php">
                <span class="material-icons-outlined"> shopping_cart </span>
                Customer Orders
              </a>
            </li>
            <li class="nav-item">
              <a
                class="nav-link btn btn-outline-dark d-flex align-items-center gap-2 my-3 py-2 px-4 active"
                href="suppliers.php">
                <span class="material-icons-outlined"> inventory </span>
                Suppliers
              </a>
            </li>
            <li class="nav-item">
              <a
                class="nav-link btn btn-outline-dark d-flex align-items-center gap-2 my-3 py-2 px-4"
                href="purchases.php">
                <span class="material-icons-outlined"> local_shipping </span>
                Supplier Orders
              </a>
            </li>
            <li class="nav-item">
              <a
                class="nav-link btn btn-outline-dark d-flex align-items-center gap-2 my-3 py-2 px-4"
                href="returns.php">
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
                <a class="nav-link btn btn-outline-dark d-flex align-items-center gap-2 my-3 py-2 px-4" href="admin/audit-log.php">
                  <span class="material-icons-outlined">local_activity</span>
                  Audit Log
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link btn btn-outline-dark d-flex align-items-center gap-2 my-3 py-2 px-4" href="admin/employee-accounts.php">
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

  <div class="container mt-4 pt-5">
    <div class="row align-items-center justify-content-end">
      <!-- Add Product Buttons -->
      <div class="col-md-auto d-flex gap-2">
        <button
          type="button"
          class="btn btn-primary add-product-button d-flex align-items-center gap-2 py-2 rounded-4"
          data-bs-toggle="modal"
          data-bs-target="#staticBackdrop">
          <span class="material-icons-outlined">add</span>
          <span>Add Supplier</span>
        </button>
      </div>
      <?php if (isset($_SESSION['supplier_success'])): ?>
        <div class="alert alert-success mt-4">
          <?php echo $_SESSION['supplier_success'];
          unset($_SESSION['supplier_success']); ?>
        </div>
      <?php endif; ?>
      <?php if (isset($_SESSION['success_error'])): ?>
        <div class="alert alert-danger">
          <?php echo $_SESSION['success_error'];
          unset($_SESSION['success_error']); ?>
        </div>
      <?php endif; ?>
    </div>

    <!-- Supplier Cards Section -->
    <div class="supplier-page my-4">
      <div class="row mt-4">
        <?php
        $stmt = $conn->prepare('SELECT SupplierID, Name, Address, PhoneNumber, ProfileImage, Status FROM suppliers');
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
            $supplierId = htmlspecialchars($row['SupplierID']);
            $name = htmlspecialchars($row['Name']);
            $address = htmlspecialchars($row['Address']);
            $phone = htmlspecialchars($row['PhoneNumber']);
            $status = htmlspecialchars($row['Status']);
            $imagePath = $row['ProfileImage'] ? htmlspecialchars($row['ProfileImage']) : '../statics/images/default_supplier_profile.png';
            $statusClass = ($status === 'Active') ? 'badge bg-success' : 'badge bg-danger';
        ?>
            <div class="col-12 mb-3">
              <div class="card supplier-card p-3 d-flex flex-row align-items-center">
                <!-- Supplier Image -->
                <img src="<?php echo $imagePath; ?>" alt="<?php echo $name; ?>" class="rounded-circle me-3" style="width: 150px; height: 150px; object-fit: cover;" />

                <!-- Supplier Details -->
                <div class="flex-grow-1 ms-2">
                  <h5 class="mb-1"><?php echo $name; ?></h5>
                  <p class="mb-1">
                    <strong>Address:</strong> <?php echo $address; ?><br />
                    <strong>Phone:</strong> <?php echo $phone; ?>
                  </p>
                  <span class="<?php echo $statusClass; ?>"> <?php echo $status; ?> </span>
                </div>

                <!-- Buttons -->
                <div class="d-flex flex-column gap-2">
                  <button class="btn btn-primary d-flex align-items-center justify-content-center gap-2 py-2 px-5 rounded-4"
                    data-bs-toggle="modal" data-bs-target="#contactModal<?php echo $supplierId; ?>">
                    <span class="material-icons-outlined">phone</span>
                    <span>Contact</span>
                  </button>
                  <button class="btn btn-danger d-flex align-items-center justify-content-center gap-2 py-2 rounded-4"
                    data-bs-toggle="modal" data-bs-target="#deleteModal<?php echo $supplierId; ?>">
                    <span class="material-icons-outlined">delete</span>
                    <span>Delete</span>
                  </button>
                  <button class="btn btn-primary d-flex align-items-center justify-content-center gap-2 py-2 rounded-4"
                    data-bs-toggle="modal" data-bs-target="#editModal<?php echo $supplierId; ?>">
                    <span class="material-icons-outlined">edit</span>
                    <span>Edit</span>
                  </button>
                </div>
              </div>
            </div>

            <!-- Contact Supplier Modal -->
            <div class="modal fade" id="contactModal<?php echo $supplierId; ?>" tabindex="-1"
              aria-labelledby="contactModalLabel<?php echo $supplierId; ?>" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="contactModalLabel<?php echo $supplierId; ?>">Contact <?php echo $name; ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <p><strong>Phone Number:</strong> <?php echo $phone; ?></p>
                    <p>Contact this supplier by calling the number above.</p>
                  </div>
                  <div class="modal-footer">
                    <a href="tel:<?php echo $phone; ?>" class="btn btn-primary d-flex align-items-center gap-2 py-2 rounded-4">
                      <span class="material-icons-outlined">phone</span>
                      Call Now
                    </a>
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                      <span class="material-icons-outlined">close</span>
                      Close
                    </button>
                  </div>
                </div>
              </div>
            </div>

            <!-- Edit Supplier Modal -->
            <div class="modal fade" id="editModal<?php echo $supplierId; ?>" tabindex="-1"
              aria-labelledby="editModalLabel<?php echo $supplierId; ?>" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title d-flex align-items-center gap-2">
                      <span class="material-icons-outlined">edit</span>
                      Edit Supplier
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <form action="../handlers/Supplier/update-supplier-handler.php" method="POST">
                    <div class="modal-body">
                      <input type="hidden" name="supplierId" value="<?php echo $supplierId; ?>">
                      <div class="mb-3">
                        <label for="supplierName<?php echo $supplierId; ?>" class="form-label">Supplier Name</label>
                        <input type="text" class="form-control" id="supplierName<?php echo $supplierId; ?>"
                          name="supplierName" value="<?php echo $name; ?>" required>
                      </div>
                      <div class="mb-3">
                        <label for="supplierAddress<?php echo $supplierId; ?>" class="form-label">Address</label>
                        <input type="text" class="form-control" id="supplierAddress<?php echo $supplierId; ?>"
                          name="supplierAddress" value="<?php echo $address; ?>" required>
                      </div>
                      <div class="mb-3">
                        <label for="supplierPhone<?php echo $supplierId; ?>" class="form-label">Phone Number</label>
                        <input type="text" class="form-control" id="supplierPhone<?php echo $supplierId; ?>"
                          name="supplierPhone" value="<?php echo $phone; ?>" required>
                      </div>
                      <div class="mb-3">
                        <label for="supplierStatus<?php echo $supplierId; ?>" class="form-label">Status</label>
                        <select class="form-select" id="supplierStatus<?php echo $supplierId; ?>" name="supplierStatus" required>
                          <option value="Active" <?php echo ($status === 'Active') ? 'selected' : ''; ?>>Active</option>
                          <option value="Inactive" <?php echo ($status === 'Inactive') ? 'selected' : ''; ?>>Inactive</option>
                        </select>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="submit" class="btn btn-primary add-product-button d-flex align-items-center gap-2 py-2 rounded-4">
                        <span class="material-icons-outlined">save</span>
                        <span>Save Changes</span>
                      </button>
                      <button type="button" class="btn btn-outline-secondary d-flex align-items-center gap-2 rounded-4"
                        data-bs-dismiss="modal">
                        <span class="material-icons-outlined">close</span>
                        Cancel
                      </button>
                    </div>
                  </form>
                </div>
              </div>
            </div>

            <!-- Delete Supplier Modal -->
            <div class="modal fade" id="deleteModal<?php echo $supplierId; ?>" tabindex="-1"
              aria-labelledby="deleteModalLabel<?php echo $supplierId; ?>" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel<?php echo $supplierId; ?>">Delete <?php echo $name; ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    Are you sure you want to delete "<?php echo $name; ?>"? This action cannot be undone.
                  </div>
                  <div class="modal-footer">
                    <form action="../handlers/Supplier/delete-supplier-handler.php" method="POST">
                      <input type="hidden" name="supplierId" value="<?php echo $supplierId; ?>">
                      <button type="submit" class="btn btn-danger">Delete</button>
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </form>
                  </div>
                </div>
              </div>
            </div>
        <?php
          }
        } else {
          echo '<div class="col-12 text-center">No suppliers found.</div>';
        }
        $stmt->close();
        ?>
      </div>
    </div>

  </div>
  </div>

  <!-- Modal -->
  <div
    class="modal fade"
    id="staticBackdrop"
    data-bs-backdrop="static"
    data-bs-keyboard="false"
    tabindex="-1"
    aria-labelledby="staticBackdropLabel"
    aria-hidden="true">
    <div
      class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h1
            class="modal-title fs-5 d-flex align-items-center gap-2"
            id="staticBackdropLabel">
            <span class="material-icons-outlined fs-2"> add_box </span>
            Add Supplier
          </h1>
          <button
            type="button"
            class="btn-close"
            data-bs-dismiss="modal"
            aria-label="Close"></button>
        </div>
        <form action="../handlers/Supplier/add-supplier-handler.php" method="POST" class="py-3" enctype="multipart/form-data">
          <div class="modal-body">
            <!-- Supplier Name -->
            <div class="row mb-3">
              <label
                for="supplierName"
                class="col-md-3 col-form-label text-md-end">Supplier Name</label>
              <div class="col-md-9">
                <input
                  type="text"
                  class="form-control"
                  id="supplierName"
                  name="supplierName"
                  placeholder="Enter supplier name"
                  required />
              </div>
            </div>

            <!-- Address -->
            <div class="row mb-3">
              <label
                for="address"
                class="col-md-3 col-form-label text-md-end">Address</label>
              <div class="col-md-9">
                <input
                  type="text"
                  class="form-control"
                  id="address"
                  name="address"
                  placeholder="Enter supplier address"
                  required />
              </div>
            </div>

            <!-- Contact Number -->
            <div class="row mb-3">
              <label
                for="contactNumber"
                class="col-md-3 col-form-label text-md-end">Contact Number</label>
              <div class="col-md-9">
                <input
                  type="text"
                  class="form-control"
                  id="contactNumber"
                  name="contactNumber"
                  placeholder="Enter contact number"
                  required />
              </div>
            </div>

            <!-- Supplier Profile -->
            <div class="row mb-3">
              <label
                for="profileImage"
                class="col-md-3 col-form-label text-md-end">Profile Image</label>
              <div class="col-md-9">
                <input
                  type="file"
                  class="form-control"
                  id="profileImage"
                  name="profileImage"
                  accept="image/*" />
              </div>
            </div>
          </div>

          <div class="modal-footer justify-content-center">
            <button
              type="submit"
              class="btn btn-primary add-product-button d-flex align-items-center gap-2 py-2 rounded-4">
              <span class="material-icons-outlined">add</span>
              <span>Add Supplier</span>
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




</body>

</html>
<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header('Location: ../index.php');
  exit();
}

include '../database/database.php';
// Fetch existing orders with all details using the Supplier_Order_View
$stmt = $conn->prepare("
    SELECT 
        ReceivingDetailID,
        product_name, 
        product_quantity, 
        unit_cost, 
        total_cost, 
        order_date, 
        supplier_name, 
        order_status
    FROM Supplier_Order_View
    ORDER BY order_date DESC
");

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
    href="../statics/images/app-logo.ico" />
  <link rel="stylesheet" href="../statics/products-style.css" />
  <link rel="stylesheet" href="../statics/style.css" />
  <link rel="stylesheet" href="../statics/css/bootstrap.min.css" />

  <!-- Google Font Icon Links -->
  <link
    href="https://fonts.googleapis.com/icon?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Round"
    rel="stylesheet" />

  <script src="../statics/js/bootstrap.min.js"></script>

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
        <a class="navbar-brand fw-semibold" href="purchases.php">SUPPLIER ORDERS</a>
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
                class="nav-link btn btn-outline-dark d-flex align-items-center gap-2 my-3 py-2 px-4"
                href="suppliers.php">
                <span class="material-icons-outlined"> inventory </span>
                Suppliers
              </a>
            </li>
            <li class="nav-item">
              <a
                class="nav-link btn btn-outline-dark d-flex align-items-center gap-2 my-3 py-2 px-4 active"
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
            <li class="nav-item">
              <a
                class="nav-link btn btn-outline-dark d-flex align-items-center gap-2 my-3 py-2 px-4"
                href="account.php">
                <span class="material-icons-outlined"> account_circle </span>
                Account
              </a>
            </li>
            <li class="nav-item">
              <a
                class="nav-link btn btn-outline-dark d-flex align-items-center gap-2 my-3 py-2 px-4"
                href="../handlers/Authentication/logout-handler.php">
                <span class="material-icons-outlined"> logout </span>
                Logout
              </a>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </nav>

  <!-- Add padding-top to the container to avoid overlap with the fixed navbar -->
  <div class="container mt-4 pt-5">
    <div class="row align-items-center justify-content-between">
      <!-- Added justify-content-between -->
      <div class="col-md-5 d-flex">
        <form action="#" class="d-flex w-100">
          <input
            type="text"
            name="search"
            placeholder="Search an item"
            class="form-control me-2" />
          <button
            type="submit"
            class="btn btn-primary add-product-button d-flex align-items-center gap-2 rounded-4">
            <span class="material-icons-outlined">search</span>
            <span>Search</span>
          </button>
        </form>
      </div>

      <div class="col-md-auto d-flex gap-2">
        <button
          class="btn btn-outline-secondary d-flex align-items-center gap-2 rounded-4">
          <span class="material-icons-outlined">tune</span>
          <span>Filter</span>
        </button>
        <a
          href="purchases/add-purchases.php"
          class="btn btn-primary add-product-button d-flex align-items-center gap-2 py-2 rounded-4">
          <span class="material-icons-outlined">add</span>
          <span>Add Purchases</span>
        </a>
      </div>
      <?php if (isset($_SESSION['supplierorder_success'])): ?>
        <div class="alert alert-success mt-4">
          <?php echo $_SESSION['supplierorder_success'];
          unset($_SESSION['supplierorder_success']); ?>
        </div>
      <?php endif; ?>
      <?php if (isset($_SESSION['supplierorder_error'])): ?>
        <div class="alert alert-danger">
          <?php echo $_SESSION['supplierorder_error'];
          unset($_SESSION['supplierorder_error']); ?>
        </div>
      <?php endif; ?>
      <div class="container-fluid mt-5 rounded-5 shadow">
        <div class="table-responsive mb-3">
          <table class="table table-striped rounded-3">
            <thead>
              <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Unit Cost</th>
                <th>Total</th>
                <th>Date</th>
                <th>Supplier</th>
                <th>Status</th>
                <th class="text-center">Action</th>
              </tr>
            </thead>
            <tbody>
              <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                  <tr>
                    <td class="align-middle"><?php echo htmlspecialchars($row['product_name']); ?></td>
                    <td class="align-middle"><?php echo $row['product_quantity']; ?></td>
                    <td class="align-middle"><?php echo number_format($row['unit_cost'], 2); ?></td>
                    <td class="align-middle"><?php echo $row['total_cost']; ?></td>
                    <td class="align-middle"><?php echo date("m-d-y", strtotime($row['order_date'])); ?></td>
                    <td class="align-middle"><?php echo htmlspecialchars($row['supplier_name']); ?></td>
                    <td class="align-middle">
                      <span class="badge text-bg-<?php echo ($row['order_status'] == 'Received') ? 'success' : 'warning'; ?> fs-6">
                        <?php echo htmlspecialchars($row['order_status']); ?>
                      </span>
                    </td>
                    <td class="align-middle text-center">
                      <div class="d-flex justify-content-center gap-2">
                        <button class="btn edit-button btn-primary add-product-button rounded-4 d-flex justify-content-center align-items-center editPurchaseBtn"
                          data-id="<?= $row['ReceivingDetailID']; ?>"
                          data-product="<?= $row['product_name']; ?>"
                          data-quantity="<?= $row['product_quantity']; ?>"
                          data-cost="<?= $row['unit_cost']; ?>"
                          data-status="<?= $row['order_status']; ?>">
                          <span class="material-icons-outlined">edit</span>
                        </button>
                        <!-- Return to Supplier Button -->
                        <?php if ($row['order_status'] == 'Received'): ?>
                          <button class="btn btn-danger rounded-4 returnToSupplierBtn d-flex justify-content-center align-items-center"
                            data-id="<?= $row['ReceivingDetailID']; ?>"
                            data-product="<?= $row['product_name']; ?>"
                            data-quantity="<?= $row['product_quantity']; ?>"
                            data-supplier="<?= $row['supplier_name']; ?>">
                            <span class="material-icons-outlined">assignment_return</span>
                          </button>
                        <?php endif; ?>
                      </div>

                    </td>
                  </tr>
                <?php endwhile; ?>
              <?php else: ?>
                <tr>
                  <td colspan="7" class="text-center text-muted py-2">
                    <p class="fs-6">No purchases have been made yet.</p>
                  </td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Edit Purchase Modal -->
  <div class="modal fade" id="editPurchaseModal" tabindex="-1" aria-labelledby="editPurchaseLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editPurchaseLabel">Edit Purchase</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="../handlers/SupplierOrder/update-purchase-handler.php" method="POST">
          <div class="modal-body">
            <input type="hidden" id="editReceivingID" name="receivingDetailID">

            <!-- Product Name (Read-only) -->
            <div class="mb-3">
              <label class="form-label">Product</label>
              <input type="text" class="form-control" id="editProductName" name="productName" readonly>
            </div>

            <!-- Quantity -->
            <div class="mb-3">
              <label class="form-label">Quantity</label>
              <input type="number" class="form-control" id="editQuantity" name="quantity" min="1" required>
            </div>

            <!-- Unit Cost -->
            <div class="mb-3">
              <label class="form-label">Unit Cost</label>
              <input type="number" class="form-control" id="editCost" name="cost" step="0.01" min="0" required>
            </div>

            <!-- Status Dropdown -->
            <div class="mb-3">
              <label class="form-label">Status</label>
              <select class="form-select" id="editStatus" name="status" required>
                <option value="Pending">Pending</option>
                <option value="Received">Received</option>
                <option value="Cancelled">Cancelled</option>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Return to Supplier Modal -->
  <div class="modal fade" id="returnToSupplierModal" tabindex="-1" aria-labelledby="returnToSupplierLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title d-flex align-items-center gap-2" id="returnToSupplierLabel">
            <span class="material-icons-outlined fs-2">assignment_return</span>
            Return to Supplier
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="../handlers/SupplierOrder/return-to-supplier-handler.php" method="POST">
          <div class="modal-body">
            <input type="hidden" name="receivingDetailId" id="returnReceivingDetailId">
            <input type="hidden" name="productId" id="returnProductId">

            <div class="row mb-3">
              <label class="col-md-4 col-form-label text-md-end">Product</label>
              <div class="col-md-8">
                <input type="text" class="form-control" id="returnProductName" readonly>
              </div>
            </div>

            <div class="row mb-3">
              <label class="col-md-4 col-form-label text-md-end">Quantity</label>
              <div class="col-md-8">
                <input type="number" class="form-control" id="returnQuantity" name="quantity" required min="1">
              </div>
            </div>

            <div class="row mb-3">
              <label class="col-md-4 col-form-label text-md-end">Supplier</label>
              <div class="col-md-8">
                <input type="text" class="form-control" id="returnSupplier" readonly>
              </div>
            </div>

            <div class="row mb-3">
              <label class="col-md-4 col-form-label text-md-end">Reason</label>
              <div class="col-md-8">
                <textarea class="form-control" name="reason" id="returnReason" rows="3" required></textarea>
              </div>
            </div>
          </div>
          <div class="modal-footer justify-content-center">
            <button type="submit" class="btn btn-danger d-flex align-items-center gap-2 py-2 rounded-4">
              <span class="material-icons-outlined">assignment_return</span>
              <span>Confirm Return</span>
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

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const editButtons = document.querySelectorAll(".editPurchaseBtn");

      editButtons.forEach(button => {
        button.addEventListener("click", function() {
          const receivingID = this.getAttribute("data-id");
          const productName = this.getAttribute("data-product");
          const quantity = this.getAttribute("data-quantity");
          const cost = this.getAttribute("data-cost");
          const status = this.getAttribute("data-status");

          document.getElementById("editReceivingID").value = receivingID;
          document.getElementById("editProductName").value = productName;
          document.getElementById("editQuantity").value = quantity;
          document.getElementById("editCost").value = cost;
          document.getElementById("editStatus").value = status;

          const editModal = new bootstrap.Modal(document.getElementById("editPurchaseModal"));
          editModal.show();
        });
      });
    });
  </script>
  <script src="../statics/return-to-supplier.js"></script>
</body>

</html>
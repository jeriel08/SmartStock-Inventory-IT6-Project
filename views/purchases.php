<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header('Location: ../index.php');
  exit();
}

include '../database/database.php';

// Get filter values from GET parameters
$status_filter = $_GET['status'] ?? 'All';
$date_from = $_GET['date_from'] ?? '';
$date_to = $_GET['date_to'] ?? '';

// Pagination variables
$records_per_page = 10;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $records_per_page;

// Base query for counting total records
$count_query = "SELECT COUNT(*) AS total FROM Supplier_Order_View WHERE 1";
$params = [];
$types = "";

if ($status_filter !== 'All') {
  $count_query .= " AND order_status = ?";
  $params[] = $status_filter;
  $types .= "s";
}

if (!empty($date_from) && !empty($date_to)) {
  $count_query .= " AND order_date BETWEEN ? AND ?";
  $params[] = $date_from;
  $params[] = $date_to;
  $types .= "ss";
}

// Prepare and execute the count query
$count_stmt = $conn->prepare($count_query);
if (!empty($params)) {
  $count_stmt->bind_param($types, ...$params);
}
$count_stmt->execute();
$count_result = $count_stmt->get_result();
$total_records = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_records / $records_per_page);
$count_stmt->close();

// Base query for fetching paginated results
$query = "
    SELECT 
        ReceivingID,
        order_date,
        supplier_name,
        order_status
    FROM Supplier_Order_View
    WHERE 1
";

$params = [];
$types = "";

if ($status_filter !== 'All') {
  $query .= " AND order_status = ?";
  $params[] = $status_filter;
  $types .= "s";
}

if (!empty($date_from) && !empty($date_to)) {
  $query .= " AND order_date BETWEEN ? AND ?";
  $params[] = $date_from;
  $params[] = $date_to;
  $types .= "ss";
}

// Add pagination
$query .= " ORDER BY order_date DESC LIMIT ? OFFSET ?";
$params[] = $records_per_page;
$params[] = $offset;
$types .= "ii";

// Prepare and execute the main query
$stmt = $conn->prepare($query);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
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

  <script src="../statics/js/bootstrap.bundle.js"></script>

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

  <!-- Add padding-top to the container to avoid overlap with the fixed navbar -->
  <div class="container mt-4 pt-5 pb-4">
    <div class="row align-items-center justify-content-end">

      <div class="col-md-auto d-flex gap-2">
        <!-- Filter Button -->
        <button class="btn btn-outline-secondary d-flex align-items-center gap-2 rounded-4"
          data-bs-toggle="modal" data-bs-target="#filterModal">
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
          <table class="table table-striped table-hover rounded-3">
            <thead>
              <tr>
                <th class="text-center">Date</th>
                <th class="text-center">Supplier</th>
                <th class="text-center">Status</th>
                <th class="text-center" style="width: 200px;">Action</th> <!-- Fixed width -->
              </tr>
            </thead>
            <tbody class="table-group-divider">
              <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                  <tr>
                    <td class="align-middle text-center"><?php echo date("m-d-y", strtotime($row['order_date'])); ?></td>
                    <td class="align-middle text-center"><?php echo htmlspecialchars($row['supplier_name']); ?></td>
                    <td class="align-middle text-center">
                      <span class="badge text-bg-<?php echo ($row['order_status'] == 'Received') ? 'success' : 'warning'; ?> fs-6">
                        <?php echo htmlspecialchars($row['order_status']); ?>
                      </span>
                    </td>
                    <td class="align-middle text-center">
                      <div class="d-flex justify-content-center gap-1">
                        <!-- Preview Button -->
                        <button class="btn add-product-button px-3 btn-sm rounded-4 d-flex justify-content-center align-items-center previewBtn"
                          data-id="<?= $row['ReceivingID']; ?>"
                          data-bs-toggle="modal"
                          data-bs-target="#previewModal">
                          <span class="material-icons-outlined">visibility</span>
                        </button>
                        <?php if ($row['order_status'] == 'Pending'): ?>
                          <button class="btn add-product-button px-3 btn-sm rounded-4 d-flex justify-content-center align-items-center editPurchaseBtn"
                            onclick="window.location.href='purchases/update-purchases.php?receiving_id=<?= $row['ReceivingID']; ?>'"
                            data-status="<?= $row['order_status']; ?>">
                            <span class="material-icons-outlined">edit</span>
                          </button>
                        <?php endif; ?>
                        <?php if ($row['order_status'] == 'Received'): ?>
                          <button class="btn btn-danger btn-sm px-3 rounded-4 d-flex justify-content-center align-items-center returnToSupplierBtn"
                            onclick="window.location.href='purchases/return-to-supplier.php?receiving_id=<?= $row['ReceivingID']; ?>'"
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
                  <td colspan="4" class="text-center text-muted py-2">
                    <p class="fs-6">No purchases have been made yet.</p>
                  </td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>

          <!-- Pagination -->
          <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
              <?php if ($page > 1): ?>
                <li class="page-item">
                  <a class="page-link" href="?status=<?= urlencode($status_filter) ?>&date_from=<?= urlencode($date_from) ?>&date_to=<?= urlencode($date_to) ?>&page=<?= $page - 1 ?>">Previous</a>
                </li>
              <?php endif; ?>

              <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?= ($i === $page) ? 'active' : '' ?>">
                  <a class="page-link" href="?status=<?= urlencode($status_filter) ?>&date_from=<?= urlencode($date_from) ?>&date_to=<?= urlencode($date_to) ?>&page=<?= $i ?>"><?= $i ?></a>
                </li>
              <?php endfor; ?>

              <?php if ($page < $total_pages): ?>
                <li class="page-item">
                  <a class="page-link" href="?status=<?= urlencode($status_filter) ?>&date_from=<?= urlencode($date_from) ?>&date_to=<?= urlencode($date_to) ?>&page=<?= $page + 1 ?>">Next</a>
                </li>
              <?php endif; ?>
            </ul>
          </nav>
        </div>
      </div>
    </div>
  </div>

  <!-- Filter Modal -->
  <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="filterModalLabel">Filter Orders</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="purchases.php" method="GET">
            <!-- Status Filter -->
            <div class="mb-3">
              <label for="statusFilter" class="form-label">Status</label>
              <select class="form-select" id="statusFilter" name="status">
                <option value="All">All</option>
                <option value="Received">Received</option>
                <option value="Pending">Pending</option>
                <option value="Cancelled">Cancelled</option>
              </select>
            </div>

            <!-- Date Range Filter -->
            <div class="mb-3">
              <label for="dateFrom" class="form-label">From Date</label>
              <input type="date" class="form-control" id="dateFrom" name="date_from">
            </div>

            <div class="mb-3">
              <label for="dateTo" class="form-label">To Date</label>
              <input type="date" class="form-control" id="dateTo" name="date_to">
            </div>

            <!-- Submit Button -->
            <div class="text-end">
              <button type="submit" class="btn btn-primary add-product-button">Apply Filters</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Preview Modal -->
  <div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="previewModalLabel">Order Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div id="modal-details">
            <p><strong>ReceivingID:</strong> <span id="modal-receiving-id"></span></p>
            <p><strong>SupplierID:</strong> <span id="modal-supplier-id"></span></p>
            <p><strong>Date:</strong> <span id="modal-date"></span></p>
            <h6>Products</h6>
            <table class="table table-striped table-hover">
              <thead>
                <tr>
                  <th>Product Name</th>
                  <th>Quantity</th>
                  <th>Unit Cost</th>
                  <th>Total</th>
                </tr>
              </thead>
              <tbody id="modal-products-table" class="table-group-divider"></tbody>
            </table>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <!-- Edit Script -->
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

  <!-- Return to Supplier Script -->
  <script src="../statics/return-to-supplier.js"></script>

  <!-- Preview Script -->
  <script>
    $(document).ready(function() {
      $('.previewBtn').on('click', function() {
        var receivingId = $(this).data('id');

        // AJAX request to fetch receiving details
        $.ajax({
          url: '../handlers/SupplierOrder/fetch-receiving-details.php',
          type: 'POST',
          data: {
            receiving_id: receivingId
          },
          dataType: 'json',
          success: function(response) {
            // Populate modal with data
            $('#modal-receiving-id').text(response.receiving_id);
            $('#modal-supplier-id').text(response.supplier_id);
            $('#modal-date').text(response.date);

            // Clear previous table data
            $('#modal-products-table').empty();

            // Populate products table
            if (response.products.length > 0) {
              response.products.forEach(function(product) {
                var row = `
                            <tr>
                                <td>${product.product_name}</td>
                                <td>${product.quantity}</td>
                                <td>₱${parseFloat(product.unit_cost).toFixed(2)}</td>
                                <td>₱${(product.quantity * product.unit_cost).toFixed(2)}</td>
                            </tr>
                        `;
                $('#modal-products-table').append(row);
              });
            } else {
              $('#modal-products-table').append('<tr><td colspan="4" class="text-center">No products found.</td></tr>');
            }
          },
          error: function(xhr, status, error) {
            console.error('Error fetching details:', error);
            $('#modal-products-table').html('<tr><td colspan="4" class="text-center">Error loading details.</td></tr>');
          }
        });
      });
    });
  </script>
</body>

</html>
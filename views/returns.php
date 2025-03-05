<?php
session_start();
include '../database/database.php'; // Assuming this connects to your DB

if (!isset($_SESSION['user_id'])) {
  header('Location: ../index.php');
  exit();
}

// Fetch existing returns with all details
$stmt = $conn->prepare("
    SELECT r.ReturnID, c.Name AS CustomerName, c.Address, c.PhoneNumber, r.OrderID, r.ReturnDate, r.Reason
    FROM returns r
    LEFT JOIN customers c ON r.CustomerID = c.CustomerID
");
$stmt->execute();
$returns = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" type="image/x-icon" href="../statics/images/app-logo.ico" />
  <link rel="stylesheet" href="../statics/css/bootstrap.min.css" />
  <link rel="stylesheet" href="../statics/products-style.css" />
  <link rel="stylesheet" href="../statics/style.css" />
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Round" rel="stylesheet" />
  <script src="../statics/js/bootstrap.min.js"></script>
  <title>Returns | SmartStock Inventory</title>
  <style>
    .clickable-icon {
      cursor: pointer;
      color: #007bff;
      /* Blue color, matches Bootstrap's primary */
    }

    .clickable-icon:hover {
      color: #0056b3;
      /* Darker blue on hover for feedback */
    }
  </style>
</head>

<body class="main">
  <nav class="navbar bg-body-tertiary fixed-top shadow">
    <div class="container-fluid">
      <!-- Left side: Button and Header -->
      <div class="d-flex align-items-center">
        <button class="navbar-toggler mx-3 border-0 shadow-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
          <span class="material-icons-outlined navbar-icon">menu</span>
        </button>
        <a class="navbar-brand fw-semibold" href="returns.php">RETURNS</a>
      </div>

      <!-- Right side: Account Section -->
      <div class="d-flex align-items-center me-5 ms-auto">
        <span class="material-icons-outlined me-2 fs-1">account_circle</span>
        <div>
          <p class="fw-bold mb-0"><?php echo htmlspecialchars($_SESSION['first_name'] . ' ' . $_SESSION['last_name']); ?></p>
          <small class="mt-0"><?php echo htmlspecialchars($_SESSION['role']); ?></small>
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
                <span class="material-icons-outlined">dashboard</span> Dashboard
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link btn btn-outline-dark d-flex align-items-center gap-2 my-3 py-2 px-4" href="products.php">
                <span class="material-icons-outlined">inventory_2</span> Products
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link btn btn-outline-dark d-flex align-items-center gap-2 my-3 py-2 px-4" href="orders.php">
                <span class="material-icons-outlined">shopping_cart</span> Customer Orders
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link btn btn-outline-dark d-flex align-items-center gap-2 my-3 py-2 px-4" href="suppliers.php">
                <span class="material-icons-outlined">inventory</span> Suppliers
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link btn btn-outline-dark d-flex align-items-center gap-2 my-3 py-2 px-4" href="purchases.php">
                <span class="material-icons-outlined">local_shipping</span> Supplier Orders
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link btn btn-outline-dark d-flex align-items-center gap-2 my-3 py-2 px-4 active" href="returns.php">
                <span class="material-icons-outlined">assignment_return</span> Returns
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link btn btn-outline-dark d-flex align-items-center gap-2 my-3 py-2 px-4" href="account.php">
                <span class="material-icons-outlined">account_circle</span> Account
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link btn btn-outline-dark d-flex align-items-center gap-2 my-3 py-2 px-4" href="../handlers/logout-handler.php">
                <span class="material-icons-outlined">logout</span> Logout
              </a>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </nav>

  <div class="container mt-4 pt5">
    <div class="row align-items-center justify-content-end">
      <!-- Filter and Add Product Buttons -->
      <div class="col-md-auto d-flex gap-2">
        <button type="button" class="btn btn-primary add-product-button d-flex align-items-center gap-2 py-2 rounded-4" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
          <span class="material-icons-outlined">add</span>
          <span>Add Return</span>
        </button>
      </div>

      <div class="container-fluid mt-4 rounded-5">
        <div class="table-responsive mb-3">
          <table class="table table-striped rounded-3">
            <thead>
              <tr>
                <th>Return ID</th>
                <th>Customer Name</th>
                <th>Order ID</th>
                <th>Return Date</th>
                <th class="text-center">Action</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($row = $returns->fetch_assoc()): ?>
                <tr>
                  <td class="align-middle"><?php echo htmlspecialchars($row['ReturnID']); ?></td>
                  <td class="align-middle"><?php echo htmlspecialchars($row['CustomerName'] ?? 'N/A'); ?></td>
                  <td class="align-middle"><?php echo htmlspecialchars($row['OrderID'] ?? 'N/A'); ?></td>
                  <td class="align-middle"><?php echo htmlspecialchars(date('m-d-Y', strtotime($row['ReturnDate']))); ?></td>
                  <td class="align-middle text-center">
                    <span class="material-icons-outlined clickable-icon"
                      data-bs-toggle="modal" data-bs-target="#viewReturnModal"
                      data-return-id="<?php echo $row['ReturnID']; ?>"
                      data-customer-name="<?php echo htmlspecialchars($row['CustomerName'] ?? 'N/A'); ?>"
                      data-address="<?php echo htmlspecialchars($row['Address'] ?? 'N/A'); ?>"
                      data-phone-number="<?php echo htmlspecialchars($row['PhoneNumber'] ?? 'N/A'); ?>"
                      data-order-id="<?php echo htmlspecialchars($row['OrderID'] ?? 'N/A'); ?>"
                      data-return-date="<?php echo htmlspecialchars(date('Y-m-d', strtotime($row['ReturnDate']))); ?>"
                      data-reason="<?php echo htmlspecialchars($row['Reason'] ?? 'N/A'); ?>">
                      remove_red_eye
                    </span>
                  </td>
                </tr>
              <?php endwhile; ?>
              <?php if ($returns->num_rows === 0): ?>
                <tr>
                  <td colspan="5" class="text-center">No returns found.</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Add Return Modal -->
  <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5 d-flex align-items-center gap-2" id="staticBackdropLabel">
            <span class="material-icons-outlined fs-2">add_box</span>
            Add Return
          </h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="../handlers/add-return-handler.php" method="POST" class="py-3">
          <div class="modal-body">
            <!-- Customer Name -->
            <div class="row mb-3">
              <label for="customerName" class="col-md-3 col-form-label text-md-end">Customer Name</label>
              <div class="col-md-9">
                <input type="text" class="form-control" id="customerName" name="customerName" placeholder="Enter customer name" required />
              </div>
            </div>

            <!-- Address -->
            <div class="row mb-3">
              <label for="address" class="col-md-3 col-form-label text-md-end">Address</label>
              <div class="col-md-9">
                <input type="text" class="form-control" id="address" name="address" placeholder="Enter address" required />
              </div>
            </div>

            <!-- Phone Number -->
            <div class="row mb-3">
              <label for="phoneNumber" class="col-md-3 col-form-label text-md-end">Phone Number</label>
              <div class="col-md-9">
                <input type="text" class="form-control" id="phoneNumber" name="phoneNumber" placeholder="Enter phone number" required />
              </div>
            </div>

            <!-- Order ID -->
            <div class="row mb-3">
              <label for="orderId" class="col-md-3 col-form-label text-md-end">Order ID</label>
              <div class="col-md-9">
                <input type="number" class="form-control" id="orderId" name="orderId" placeholder="Enter order ID" required />
              </div>
            </div>

            <!-- Return Date -->
            <div class="row mb-3">
              <label for="returnDate" class="col-md-3 col-form-label text-md-end">Return Date</label>
              <div class="col-md-9">
                <input type="date" class="form-control" id="returnDate" name="returnDate" required />
              </div>
            </div>

            <!-- Reason -->
            <div class="row mb-3">
              <label for="reason" class="col-md-3 col-form-label text-md-end">Reason</label>
              <div class="col-md-9">
                <textarea class="form-control" id="reason" name="reason" placeholder="Enter reason for the return" required></textarea>
              </div>
            </div>
          </div>

          <div class="modal-footer justify-content-center">
            <button type="submit" class="btn btn-primary add-product-button d-flex align-items-center gap-2 py-2 rounded-4">
              <span class="material-icons-outlined">add</span>
              <span>Add Return</span>
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

  <!-- View Return Modal -->
  <div class="modal fade" id="viewReturnModal" tabindex="-1" aria-labelledby="viewReturnModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5 d-flex align-items-center gap-2" id="viewReturnModalLabel">
            <span class="material-icons-outlined fs-2">visibility</span>
            Return Details
          </h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row mb-3">
            <label class="col-md-3 col-form-label text-md-end">Customer Name: </label>
            <div class="col-md-9">
              <p class="form-control-plaintext" id="viewCustomerName"></p>
            </div>
          </div>
          <div class="row mb-3">
            <label class="col-md-3 col-form-label text-md-end">Address: </label>
            <div class="col-md-9">
              <p class="form-control-plaintext" id="viewAddress"></p>
            </div>
          </div>
          <div class="row mb-3">
            <label class="col-md-3 col-form-label text-md-end">Phone Number: </label>
            <div class="col-md-9">
              <p class="form-control-plaintext" id="viewPhoneNumber"></p>
            </div>
          </div>
          <div class="row mb-3">
            <label class="col-md-3 col-form-label text-md-end">Order ID: </label>
            <div class="col-md-9">
              <p class="form-control-plaintext" id="viewOrderId"></p>
            </div>
          </div>
          <div class="row mb-3">
            <label class="col-md-3 col-form-label text-md-end">Return Date: </label>
            <div class="col-md-9">
              <p class="form-control-plaintext" id="viewReturnDate"></p>
            </div>
          </div>
          <div class="row mb-3">
            <label class="col-md-3 col-form-label text-md-end">Reason: </label>
            <div class="col-md-9">
              <p class="form-control-plaintext" id="viewReason"></p>
            </div>
          </div>
        </div>
        <div class="modal-footer justify-content-center">
          <button type="button" class="btn btn-outline-secondary d-flex align-items-center gap-2 rounded-4" data-bs-dismiss="modal">
            <span class="material-icons-outlined">close</span>
            Close
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- JavaScript for Populating View Modal -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      var viewModal = document.getElementById('viewReturnModal');
      viewModal.addEventListener('show.bs.modal', function(event) {
        var icon = event.relatedTarget;
        var customerName = icon.getAttribute('data-customer-name');
        var address = icon.getAttribute('data-address');
        var phoneNumber = icon.getAttribute('data-phone-number');
        var orderId = icon.getAttribute('data-order-id');
        var returnDate = icon.getAttribute('data-return-date');
        var reason = icon.getAttribute('data-reason');

        viewModal.querySelector('#viewCustomerName').textContent = customerName;
        viewModal.querySelector('#viewAddress').textContent = address;
        viewModal.querySelector('#viewPhoneNumber').textContent = phoneNumber;
        viewModal.querySelector('#viewOrderId').textContent = orderId;
        viewModal.querySelector('#viewReturnDate').textContent = returnDate;
        viewModal.querySelector('#viewReason').textContent = reason;
      });
    });

    <?php if (isset($_SESSION['error'])): ?>
      alert('<?php echo $_SESSION['error']; ?>');
      <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
  </script>
</body>

</html>
<?php $conn->close(); ?>
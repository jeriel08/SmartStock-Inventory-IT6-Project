<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header('Location: ../index.php');
  exit();
}

// Include the database connection (avoid redefining $conn)
include '../database/database.php';

// Set how many records per page
$records_per_page = 7;

// Get the current page from URL, default to 1
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;

// Calculate OFFSET
$offset = ($page - 1) * $records_per_page;

// Fetch total number of records
$total_query = "SELECT COUNT(*) AS total FROM Orders";
$total_result = $conn->query($total_query);
$total_row = $total_result->fetch_assoc();
$total_records = $total_row['total'];

// Calculate total pages
$total_pages = ceil($total_records / $records_per_page);

// Fetch paginated orders using prepared statement
$stmt = $conn->prepare("SELECT OrderID, CustomerID, Date, Status, Total 
                        FROM Orders 
                        ORDER BY Date DESC 
                        LIMIT ? OFFSET ?");
$stmt->bind_param("ii", $records_per_page, $offset);
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

  <title>Customer Orders | SmartStock Inventory</title>
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
        <a class="navbar-brand fw-semibold" href="orders.php">CUSTOMER ORDERS</a>
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
                class="nav-link btn btn-outline-dark d-flex align-items-center gap-2 my-3 py-2 px-4 active"
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
    <div class="row align-items-center justify-content-end">

      <div class="col-md-auto d-flex gap-2">
        <button
          class="btn btn-outline-secondary d-flex align-items-center gap-2 rounded-4 py-2 px-3">
          <span class="material-icons-outlined">tune</span>
          <span>Filter</span>
      </div>

      <div class="container-fluid mt-4 rounded-5 shadow">
        <div class="table-responsive mb-3">
          <table class="table table-striped rounded-3">
            <thead>
              <tr>
                <th class="text-center">OrderID</th>
                <th class="text-center">CustomerID</th>
                <th class="text-center">Total</th>
                <th class="text-center">Date</th>
                <th class="text-center">Status</th>
                <th class="text-center">Action</th>
              </tr>
            </thead>
            <tbody class="table-group-divider">
              <?php

              if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                  echo "<tr>";
                  echo "<td class='align-middle text-center'>{$row['OrderID']}</td>";
                  echo "<td class='align-middle text-center'>{$row['CustomerID']}</td>";
                  echo "<td class='align-middle text-center'>₱" . number_format($row['Total'], 2) . "</td>";
                  echo "<td class='align-middle text-center'>" . date("Y-m-d", strtotime($row['Date'])) . "</td>";

                  // Status Badge Styling
                  $status = $row['Status'];
                  $badgeClass = ($status == 'Paid') ? 'text-bg-success' : (($status == 'Pending') ? 'text-bg-warning' : 'text-bg-danger');

                  echo "<td class='align-middle text-center'><span class='badge $badgeClass fs-6'>{$status}</span></td>";

                  // Preview Button
                  echo "<td class='align-middle text-center'>
                    <button 
                    class='btn add-product-button btn-primary rounded-4 d-block mx-auto d-flex align-items-center justify-content-center preview-order'
                    data-id='{$row['OrderID']}'
                    >
                        <span class='material-icons-outlined'>receipt_long</span>
                    </button>
                  </td>";
                  echo "</tr>";
                }
              } else {
                echo "<tr><td colspan='6' class='text-center text-muted py-3'>There's no order yet.</td></tr>";
              }
              ?>
            </tbody>
          </table>
          <!-- Pagination Controls -->
          <nav>
            <ul class="pagination justify-content-center">
              <?php if ($page > 1): ?>
                <li class="page-item">
                  <a class="page-link focus-ring" href="?page=<?php echo $page - 1; ?>">Previous</a>
                </li>
              <?php endif; ?>

              <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                  <a class="page-link focus-ring" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                </li>
              <?php endfor; ?>

              <?php if ($page < $total_pages): ?>
                <li class="page-item">
                  <a class="page-link focus-ring" href="?page=<?php echo $page + 1; ?>">Next</a>
                </li>
              <?php endif; ?>
            </ul>
          </nav>
        </div>
      </div>
    </div>
  </div>

  <!-- Order Preview Modal -->
  <div class="modal fade" id="orderPreviewModal" tabindex="-1" aria-labelledby="orderPreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="orderPreviewModalLabel">Order Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div id="orderDetailsContent">
            <!-- Order details will be loaded here dynamically -->
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    $(document).ready(function() {
      $(".preview-order").click(function() {
        var orderID = $(this).data("id");

        $.ajax({
          url: "../handlers/Order/fetch_order_details.php",
          method: "POST",
          data: {
            orderID: orderID
          },
          dataType: "json",
          success: function(response) {
            if (response.success) {
              var order = response.order;
              var orderLines = response.orderLines;

              var html = `<p><strong>Order ID:</strong> ${order.OrderID}</p>
                          <p><strong>Customer ID:</strong> ${order.CustomerID}</p>
                          <p><strong>Date:</strong> ${order.Date}</p>
                          <p><strong>Total:</strong> ₱${parseFloat(order.Total).toFixed(2)}</p>
                          <p><strong>Status:</strong> ${order.Status}</p>
                          <hr>
                          <h5>Order Items</h5>
                          <table class="table table-hover">
                            <thead>
                              <tr>
                                <th>Product Name</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Total</th>
                              </tr>
                            </thead>
                            <tbody class='table-group-divider'>`;

              orderLines.forEach(function(item) {
                html += `<tr>
                          <td>${item.ProductName}</td>
                          <td>${item.Quantity}</td>
                          <td>₱${parseFloat(item.Price).toFixed(2)}</td>
                          <td>₱${parseFloat(item.Total).toFixed(2)}</td>
                        </tr>`;
              });

              html += `</tbody></table>`;

              $("#orderDetailsContent").html(html);
              $("#orderPreviewModal").modal("show");
            } else {
              alert("Failed to fetch order details.");
            }
          }
        });
      });
    });
  </script>
</body>

</html>
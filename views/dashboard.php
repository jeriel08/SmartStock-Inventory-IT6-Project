<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header('Location: ../index.php');
  exit();
}

include '../database/database.php';

// Call the stored procedure
$sql = "CALL GetDashboardStats()";
$result = $conn->multi_query($sql);

$dashboardData = [];
if ($result) {
  do {
    if ($res = $conn->store_result()) {
      $dashboardData[] = $res->fetch_assoc();
      $res->free();
    }
  } while ($conn->more_results() && $conn->next_result());
}

$conn->close();

// Assign values
$totalSales = $dashboardData[0]['total_sales'] ?? 0;
$totalOrders = $dashboardData[1]['total_orders'] ?? 0;
$totalProducts = $dashboardData[2]['total_products'] ?? 0;
$lowStockProducts = $dashboardData[3]['low_stock_products'] ?? 0;
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
  <link rel="stylesheet" href="../statics/style.css" />
  <link rel="stylesheet" href="../statics/css/bootstrap.min.css" />
  <link rel="stylesheet" href="../statics/dashboard-style.css">

  <!-- Google Font Icon Links -->
  <link
    href="https://fonts.googleapis.com/icon?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Round"
    rel="stylesheet" />



  <title>Dashboard | SmartStock Inventory</title>
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
        <a class="navbar-brand fw-semibold" href="dashboard.php">DASHBOARD</a>
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
                class="nav-link btn btn-outline-dark d-flex align-items-center gap-2 my-3 py-2 px-4 active"
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


  <div class="container pt-5 mt-3">
    <div class="row">
      <div class="col-md-12 mt-5 d-inline-flex align-items-center">
        <p class="fw-semibold fs-5 mb-0">Activity</p>
      </div>
    </div>

    <!-- Row for summary cards -->
    <div class="row mt-4">
      <div class="col-md-3">
        <div class="card shadow-sm rounded-4 p-3">
          <p class="fw-semibold">Total Sales</p>
          <h3 class="fw-bold">₱<?php echo number_format($totalSales, 2); ?></h3>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card shadow-sm rounded-4 p-3">
          <p class="fw-semibold">Total Orders</p>
          <h3 class="fw-bold"><?php echo $totalOrders; ?></h3>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card shadow-sm rounded-4 p-3">
          <p class="fw-semibold">Total Products in Stock</p>
          <h3 class="fw-bold"><?php echo $totalProducts; ?></h3>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card shadow-sm rounded-4 p-3">
          <p class="fw-semibold">Low Stock Products</p>
          <h3 class="fw-bold"><?php echo $lowStockProducts; ?></h3>
        </div>
      </div>
    </div>

    <div class="card shadow-sm p-3 rounded-4 mt-4">
      <div class="card-body">
        <h5 class="card-title fw-semibold">Sales Overview</h5>
        <canvas id="salesChart" style="max-height: 380px;"></canvas>
      </div>
    </div>

    <div class="card shadow-sm p-4 rounded-4 mt-4 mb-5">
      <div class="card-body">
        <h5 class="card-title fw-semibold mb-3">Sales by Category</h5>
        <canvas id="categorySalesChart" style="max-height: 380px;"></canvas>
      </div>
    </div>


  </div>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="../statics/js/bootstrap.bundle.js"></script>

  <!-- Line Chart / Sales Overview -->
  <script>
    fetch("../handlers/Dashboard/get_sales_data.php") // Adjust path if needed
      .then((response) => response.json())
      .then((data) => {
        const labels = data.map((row) => row.order_date);
        const sales = data.map((row) => row.total_sales);

        const ctx = document.getElementById("salesChart").getContext("2d");
        new Chart(ctx, {
          type: "line",
          data: {
            labels: labels,
            datasets: [{
              label: "Total Sales",
              data: sales,
              borderColor: "#f66435",
              backgroundColor: "rgba(0, 0, 255, 0.1)",
              borderWidth: 2,
              fill: true,
            }, ],
          },
          options: {
            responsive: true,
            scales: {
              x: {
                title: {
                  display: true,
                  text: "Date",
                  font: {
                    family: "'Poppins', sans-serif",
                    size: 12,
                  },
                },
                ticks: {
                  font: {
                    family: "'Poppins', sans-serif",
                    size: 12,
                    weight: "bold"
                  },
                },
              },
              y: {
                title: {
                  display: true,
                  text: "Sales (₱)",
                  font: {
                    family: "'Poppins', sans-serif",
                    size: 12,
                  },
                },
                ticks: {
                  font: {
                    family: "'Poppins', sans-serif",
                    size: 12,
                    weight: "bold"
                  },
                },
              },
            },
          },
        });
      })
      .catch((error) => console.error("Error fetching sales data:", error));
  </script>

  <!-- Bar Graph / Sales by Category -->
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      fetch("../handlers/Dashboard/get_sales_data_by_category.php")
        .then(response => response.json())
        .then(data => {
          const categories = data.map(item => item.category);
          const sales = data.map(item => item.sales);

          const ctxBar = document.getElementById("categorySalesChart").getContext("2d");

          new Chart(ctxBar, {
            type: "bar",
            data: {
              labels: categories,
              datasets: [{
                label: "Categories",
                data: sales,
                backgroundColor: ["#007bff", "#28a745", "#ffc107", "#dc3545", "#17a2b8"],
                borderRadius: 8
              }]
            },
            options: {
              responsive: true,
              plugins: {
                legend: {
                  labels: {
                    font: {
                      family: "'Poppins', sans-serif",
                      size: 14,
                      weight: "bold"
                    },
                    color: "#333"
                  }
                }
              },
              scales: {
                x: {
                  ticks: {
                    font: {
                      family: "'Poppins', sans-serif",
                      size: 12,
                      weight: "bold"
                    }
                  }
                },
                y: {
                  ticks: {
                    font: {
                      family: "'Poppins', sans-serif",
                      size: 12,
                      weight: "bold"
                    }
                  },
                  beginAtZero: true
                }
              }
            }
          });
        })
        .catch(error => console.error("Error fetching sales data:", error));
    });
  </script>

</body>

</html>
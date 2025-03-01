<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
      rel="icon"
      type="image/x-icon"
      href="../statics/images/app-logo.ico"
    />
    <link rel="stylesheet" href="../statics/products-style.css" />
    <link rel="stylesheet" href="../statics/style.css" />
    <link rel="stylesheet" href="../statics/supplier-style.css" />
    <link rel="stylesheet" href="../statics/css/bootstrap.min.css" />

    <!-- Google Font Icon Links -->
    <link
      href="https://fonts.googleapis.com/icon?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Round"
      rel="stylesheet"
      href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=call"
    />

    <script src="../statics/js/bootstrap.min.js"></script>

    <title>Supplier | SmartStock Inventory</title>
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
          <a class="navbar-brand fw-semibold" href="suppliers.php">SUPPLIER</a>
        </div>

        <!-- Right side: Account Section -->
        <div class="d-flex align-items-center me-5 ms-auto">
          <span class="material-icons-outlined me-2 fs-1">account_circle</span>
          <div>
            <p class="fw-bold mb-0">Username</p>
            <small>Role</small>
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
                src="../statics/images/logo-2.png"
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
                  href="dashboard.php"
                >
                  <span class="material-icons-outlined"> dashboard </span>
                  Dashboard
                </a>
              </li>
              <li class="nav-item">
                <a
                  class="nav-link btn btn-outline-dark d-flex align-items-center gap-2 my-3 py-2 px-4"
                  href="products.php"
                >
                  <span class="material-icons-outlined"> inventory_2 </span>
                  Products
                </a>
              </li>
              <li class="nav-item">
                <a
                  class="nav-link btn btn-outline-dark d-flex align-items-center gap-2 my-3 py-2 px-4"
                  href="orders.php"
                >
                  <span class="material-icons-outlined"> shopping_cart </span>
                  Orders
                </a>
              </li>
              <li class="nav-item">
                <a
                  class="nav-link btn btn-outline-dark d-flex align-items-center gap-2 my-3 py-2 px-4 active"
                  href="suppliers.php"
                >
                  <span class="material-icons-outlined"> inventory </span>
                  Suppliers
                </a>
              </li>
              <li class="nav-item">
                <a
                  class="nav-link btn btn-outline-dark d-flex align-items-center gap-2 my-3 py-2 px-4"
                  href="purchases.php"
                >
                  <span class="material-icons-outlined"> shopping_bag </span>
                  Purchases
                </a>
              </li>
              <li class="nav-item">
                <a
                  class="nav-link btn btn-outline-dark d-flex align-items-center gap-2 my-3 py-2 px-4"
                  href="returns.php"
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
                  href="account.php"
                >
                  <span class="material-icons-outlined"> account_circle </span>
                  Account
                </a>
              </li>
              <li class="nav-item">
                <a
                  class="nav-link btn btn-outline-dark d-flex align-items-center gap-2 my-3 py-2 px-4"
                  href="../handlers/logout-handler.php"
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

    <div class="container mt-4 pt-5">
      <div class="row align-items-center justify-content-end">
        <!-- Filter and Add Product Buttons -->
        <div class="col-md-auto d-flex gap-2">
          <button
            type="button"
            class="btn btn-primary add-product-button d-flex align-items-center gap-2 py-2 rounded-4"
            data-bs-toggle="modal"
            data-bs-target="#staticBackdrop"
          >
            <span class="material-icons-outlined">add</span>
            <span>Add Supplier</span>
          </button>
        </div>
      </div>

      <!-- Supplier Cards Section -->
      <div class="supplier-page mt-4">
        <div class="row mt-4">
          <!-- Supplier Card 1 -->
          <div class="col-md-4">
            <div class="card supplier-card">
              <div class="card-body">
                <img src="./picsdemo/jeriel.jpg" alt="Supplier 1" class="pic" />
                <h5 class="card-title">Supplier Name 1</h5>
                <p class="card-text">
                  <strong>Address:</strong> 123 Supplier St, City, Country<br />
                  <strong>Phone:</strong> +123 456 7890<br />
                  <strong>Email:</strong> supplier1@example.com
                </p>
                <div class="btn-container">
                  <button class="btn btn-primary gap-2 rounded-4">
                    <span class="material-symbols-outlined"></span>
                    <span>Contact Supplier</span>
                  </button>
                  <button class="btn btn-danger gap-2 rounded-4">
                    Delete Supplier
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- Supplier Card 2 -->
          <div class="col-md-4">
            <div class="card supplier-card">
              <div class="card-body">
                <img src="./picsdemo/russel.jpg" alt="Supplier 2" class="pic" />
                <h5 class="card-title">Supplier Name 2</h5>
                <p class="card-text">
                  <strong>Address:</strong> 456 Supplier Ave, City, Country<br />
                  <strong>Phone:</strong> +123 456 7891<br />
                  <strong>Email:</strong> supplier2@example.com
                </p>
                <div class="btn-container">
                  <button class="btn btn-primary gap-2 rounded-4">
                    Contact Supplier
                  </button>
                  <button class="btn btn-danger gap-2 rounded-4">
                    Delete Supplier
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- Supplier Card 3 -->
          <div class="col-md-4">
            <div class="card supplier-card">
              <div class="card-body">
                <img src="./picsdemo/paula.jpg" alt="Supplier 3" class="pic" />
                <h5 class="card-title">Supplier Name 3</h5>
                <p class="card-text">
                  <strong>Address:</strong> 789 Supplier Blvd, City, Country<br />
                  <strong>Phone:</strong> +123 456 7892<br />
                  <strong>Email:</strong> supplier3@example.com
                </p>
                <div class="btn-container">
                  <button class="btn btn-primary gap-2 rounded-4">
                    Contact Supplier
                  </button>
                  <button class="btn btn-danger gap-2 rounded-4">
                    Delete Supplier
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- START -->

    <!-- STOP  -->
  </body>
</html>

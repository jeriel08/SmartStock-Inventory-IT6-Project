<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header('Location: ../index.php');
  exit();
}
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
                href="../handlers/logout-handler.php">
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

      <div class="container-fluid mt-5 rounded-5">
        <div class="table-responsive mb-3">
          <table class="table table-striped rounded-3">
            <thead>
              <tr>
                <th>Product</th>
                <th>Date</th>
                <th>Supplier</th>
                <th>Status</th>
                <th>Grand Total</th>
                <th>Payment</th>
                <th class="text-center">Action</th>
              </tr>
            </thead>
            <tbody>
              <!-- Sample Rows - Replace with your actual data -->
              <tr>
                <td class="align-middle">Product Name 1</td>
                <td class="align-middle">01-01-25</td>
                <td class="align-middle">NCCC SUpermarket</td>
                <td class="align-middle">
                  <span class="badge text-bg-success fs-6">Received</span>
                </td>
                <td class="align-middle">900</td>
                <td class="align-middle">
                  <span class="badge text-bg-success fs-6">Paid</span>
                </td>

                <td class="align-middle">
                  <button
                    class="btn edit-button btn-primary add-product-button rounded-4">
                    <span class="material-icons-outlined">edit</span>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
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
                Add Purchases
              </h1>
              <button
                type="button"
                class="btn-close"
                data-bs-dismiss="modal"
                aria-label="Close"></button>
            </div>
            <form action="#" class="py-3">
              <div class="modal-body">
                <!-- Product Name -->
                <div class="row mb-3">
                  <label
                    for="productName"
                    class="col-md-3 col-form-label text-md-end">Product Name</label>
                  <div class="col-md-9">
                    <input
                      type="text"
                      class="form-control"
                      id="productName"
                      name="productName"
                      placeholder="Enter product name" />
                  </div>
                </div>

                <!-- Date -->
                <div class="row mb-3">
                  <label
                    for="date"
                    class="col-md-3 col-form-label text-md-end">Date</label>
                  <div class="col-md-9">
                    <input
                      type="date"
                      class="form-control"
                      id="date"
                      name="date"
                      placeholder="Enter date" />
                  </div>
                </div>

                <!-- Supplier -->
                <div class="row mb-3">
                  <label
                    for="supplier"
                    class="col-md-3 col-form-label text-md-end">Supplier</label>
                  <div class="col-md-9">
                    <select class="form-select" name="supplier" id="supplier">
                      <option selected disabled>Select Supplier</option>
                      <option value="nccc">NCCC</option>
                    </select>
                  </div>
                </div>

                <!-- Status -->
                <div class="row mb-3">
                  <label
                    for="status"
                    class="col-md-3 col-form-label text-md-end">Status</label>
                  <div class="col-md-9">
                    <select class="form-select" name="status" id="status">
                      <option selected disabled>Set Status</option>
                      <option value="received">Received</option>
                      <option value="pending">Pending</option>
                      <option value="denied">Denied</option>
                    </select>
                  </div>
                </div>

                <!-- Total -->
                <div class="row mb-3">
                  <label
                    for="total"
                    class="col-md-3 col-form-label text-md-end">Grand Total</label>
                  <div class="col-md-9">
                    <input
                      type="number"
                      class="form-control"
                      id="total"
                      name="total"
                      placeholder="Enter total"
                      step="0.01"
                      min="0" />
                  </div>
                </div>

                <!-- Price -->
                <div class="row mb-3">
                  <label
                    for="payment"
                    class="col-md-3 col-form-label text-md-end">Payment</label>
                  <div class="col-md-9">
                    <select class="form-select" id="payment" name="payment">
                      <option selected disabled>Select payment</option>
                      <option value="paid">Paid</option>
                      <option value="pending">Pending</option>
                      <option value="cancelled">Cancelled</option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="modal-footer justify-content-center">
                <button
                  type="button"
                  class="btn btn-primary add-product-button d-flex align-items-center gap-2 py-2 rounded-4">
                  <span class="material-icons-outlined">add</span>
                  <span>Add Purchase</span>
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
    </div>
  </div>
</body>

</html>
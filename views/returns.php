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

    <link rel="stylesheet" href="../statics/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../statics/products-style.css" />
    <link rel="stylesheet" href="../statics/style.css" />

    <!-- Google Font Icon Links -->
    <link
      href="https://fonts.googleapis.com/icon?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Round"
      rel="stylesheet"
    />

    <script src="../statics/js/bootstrap.min.js"></script>

    <title>Returns | SmartStock Inventory</title>
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
          <a class="navbar-brand fw-semibold" href="returns.php">RETURNS</a>
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
                  class="nav-link btn btn-outline-dark d-flex align-items-center gap-2 my-3 py-2 px-4"
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
                  class="nav-link btn btn-outline-dark d-flex align-items-center gap-2 my-3 py-2 px-4 active"
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

    <div class="container mt-4 pt5">
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
                <!-- Sample Rows - Replace with your actual data -->
                <tr>
                  <td class="align-middle">1</td>
                  <td class="align-middle">John Doe</td>
                  <td class="align-middle">1</td>
                  <td class="align-middle">01-01-1999</td>
                  <td class="align-middle">
                    <button
                      class="btn edit-button btn-primary add-product-button rounded-4"
                    >
                      Review
                    </button>
                    <button
                      class="btn edit-button btn-primary add-product-button rounded-4"
                    >
                      <span class="material-icons-outlined fs-6">edit</span>
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
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
      aria-hidden="true"
    >
      <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header">
            <h1
              class="modal-title fs-5 d-flex align-items-center gap-2"
              id="staticBackdropLabel"
            >
              <span class="material-icons-outlined fs-2"> add_box </span>
              Add Return
            </h1>
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
              aria-label="Close"
            ></button>
          </div>
          <form action="#" class="py-3">
            <div class="modal-body">
              <!-- Customer Name -->
              <div class="row mb-3">
                <label
                  for="customerName"
                  class="col-md-3 col-form-label text-md-end"
                  >Customer Name</label
                >
                <div class="col-md-9">
                  <input
                    type="text"
                    class="form-control"
                    id="customerName"
                    name="customerName"
                    placeholder="Enter customer name"
                  />
                </div>
              </div>

              <!-- Order ID -->
              <div class="row mb-3">
                <label for="orderID" class="col-md-3 col-form-label text-md-end"
                  >Order ID</label
                >
                <div class="col-md-9">
                  <input
                    type="text"
                    class="form-control"
                    id="orderID"
                    name="orderID"
                    placeholder="Enter order ID"
                  />
                </div>
              </div>

              <!-- Return Date -->
              <div class="row mb-3">
                <label
                  for="returnDate"
                  class="col-md-3 col-form-label text-md-end"
                  >Return Date</label
                >
                <div class="col-md-9">
                  <input
                    type="text"
                    class="form-control"
                    id="returnDate"
                    name="returnDate"
                    placeholder="Enter return date"
                  />
                </div>
              </div>

              <!-- Reason -->
              <div class="row mb-3">
                <label for="reason" class="col-md-3 col-form-label text-md-end"
                  >Reason</label
                >
                <div class="col-md-9">
                  <textarea
                    type="number"
                    class="form-control"
                    id="reason"
                    name="reason"
                    placeholder="Enter reason for the return."
                    min="0"
                  ></textarea>
                </div>
              </div>
            </div>

            <div class="modal-footer justify-content-center">
              <button
                type="button"
                class="btn btn-primary add-product-button d-flex align-items-center gap-2 py-2 rounded-4"
              >
                <span class="material-icons-outlined">add</span>
                <span>Add Order</span>
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
    </div>
  </body>
</html>

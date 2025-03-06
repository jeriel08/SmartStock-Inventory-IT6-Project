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
  <link rel="stylesheet" href="../statics/account-style.css" />
  <link rel="stylesheet" href="../statics/style.css" />
  <link rel="stylesheet" href="../statics/css/bootstrap.min.css" />

  <!-- Google Font Icon Links -->
  <link
    href="https://fonts.googleapis.com/icon?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Round"
    rel="stylesheet" />

  <script src="../statics/js/bootstrap.min.js"></script>

  <title>Account | SmartStock Inventory</title>
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
        <a class="navbar-brand fw-semibold" href="account.php">ACCOUNT</a>
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
                class="nav-link btn btn-outline-dark d-flex align-items-center gap-2 my-3 py-2 px-4 active"
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
  <div class="container pt-4 mb-0">
    <!-- Conditionally show the Add New Employee form only for Admins -->
    <?php if (isset($_SESSION['role']) && strtoupper($_SESSION['role']) === 'ADMIN'): ?>
      <!-- Add New Employee -->
      <div class="card mb-4 shadow border-dark-subtle">
        <div class="card-header align-items-center d-flex">
          <h2 class="fw-semibold">Add New Employee</h2>
        </div>
        <?php if (isset($_SESSION['success'])): ?>
          <div class="alert alert-success mt-2">
            <?php echo $_SESSION['success'];
            unset($_SESSION['success']); ?>
          </div>
        <?php endif; ?>
        <?php if (isset($_SESSION['add_employee_error'])): ?>
          <div class="alert alert-danger mt-2">
            <?php echo $_SESSION['add_employee_error'];
            unset($_SESSION['add_employee_error']); ?>
          </div>
        <?php endif; ?>
        <div class="card-body px-5 py-4">
          <form action="../handlers/Employee/add-employee-handler.php" method="POST">
            <div class="mb-3 row">
              <div class="col-6">
                <label for="employeeFirstName" class="form-label fw-semibold">First Name</label>
                <input
                  type="text"
                  class="form-control"
                  id="employeeFirstName"
                  name="employeeFirstName"
                  placeholder="Enter first name"
                  required />
              </div>
              <div class="col-6">
                <label for="employeeLastName" class="form-label fw-semibold">Last Name</label>
                <input
                  type="text"
                  class="form-control"
                  id="employeeLastName"
                  name="employeeLastName"
                  placeholder="Enter last name"
                  required />
              </div>
            </div>

            <div class="mb-3">
              <label for="employeeUsername" class="form-label fw-semibold">Username</label>
              <input
                type="text"
                class="form-control"
                id="employeeUsername"
                name="employeeUsername"
                placeholder="Enter employee username"
                required />
            </div>

            <div class="mb-3">
              <label for="employeePassword" class="form-label fw-semibold">Password</label>
              <input
                type="password"
                class="form-control"
                id="employeePassword"
                name="employeePassword"
                placeholder="Enter employee password"
                required />
            </div>
            <div class="mb-3">
              <label for="employeeRole" class="form-label fw-semibold">Role</label>
              <select class="form-select" id="employeeRole" name="employeeRole" required>
                <option selected disabled>Select Role</option>
                <option value="Admin">Admin</option>
                <option value="Employee">Employee</option>
              </select>
            </div>
            <button type="submit" class="btn btn-primary mt-3">
              Add Employee
            </button>
          </form>
        </div>
      </div>
    <?php endif; ?>

    <!-- Account Settings -->
    <div class="card shadow border-dark-subtle">
      <div class="card-header">
        <h2 class="fw-semibold">Account Settings</h2>
      </div>
      <div class="card-body px-5 py-4">
        <?php if (isset($_SESSION['update_account_success'])): ?>
          <div class="alert alert-success mt-2">
            <?php echo $_SESSION['update_account_success'];
            unset($_SESSION['update_account_success']); ?>
          </div>
        <?php endif; ?>
        <?php if (isset($_SESSION['update_account_error'])): ?>
          <div class="alert alert-danger mt-2">
            <?php echo $_SESSION['update_account_error'];
            unset($_SESSION['update_account_error']); ?>
          </div>
        <?php endif; ?>
        <form action="../handlers/Employee/update-account-handler.php" method="POST">
          <div class="mb-3 row">
            <div class="col-6">
              <label for="employeeFirstName" class="form-label fw-semibold">First Name</label>
              <input
                type="text"
                class="form-control"
                id="employeeFirstName"
                name="employeeFirstName"
                value="<?php echo htmlspecialchars($_SESSION['first_name']); ?>"
                placeholder="First name"
                required />
            </div>
            <div class="col-6">
              <label for="employeeLastName" class="form-label fw-semibold">Last Name</label>
              <input
                type="text"
                class="form-control"
                id="employeeLastName"
                name="employeeLastName"
                value="<?php echo htmlspecialchars($_SESSION['last_name']); ?>"
                placeholder="Last name"
                required />
            </div>
          </div>

          <div class="mb-3">
            <label for="employeeUsername" class="form-label fw-semibold">Username</label>
            <input
              type="text"
              class="form-control"
              id="employeeUsername"
              name="employeeUsername"
              value="<?php echo htmlspecialchars($_SESSION['username']); ?>"
              placeholder="Username"
              required />
          </div>

          <div class="mb-3">
            <label for="employeePassword" class="form-label fw-semibold">Password</label>
            <input
              type="password"
              class="form-control"
              id="employeePassword"
              name="employeePassword"
              placeholder="Enter new password (leave blank to keep current)" />
          </div>

          <div class="mb-3">
            <label
              for="employeeConfirmPassword"
              class="form-label fw-semibold">Confirm Password</label>
            <input
              type="password"
              class="form-control"
              id="employeeConfirmPassword"
              name="employeeConfirmPassword"
              placeholder="Confirm new password" />
          </div>

          <hr />

          <div class="mt-2 mb-3">
            <label for="employeeOldPassword" class="form-label fw-semibold">Old Password</label>
            <input
              type="password"
              class="form-control"
              id="employeeOldPassword"
              name="employeeOldPassword"
              placeholder="Enter old password"
              required />
            <div id="passwordHelpBlock" class="form-text">
              Please enter your old password before comitting any changes.
            </div>
          </div>

          <button type="submit" class="btn btn-primary mt-3">
            Save Changes
          </button>
        </form>
      </div>
    </div>
  </div>
</body>

</html>
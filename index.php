<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" type="image/x-icon" href="statics/images/app-logo.ico" />

  <link rel="stylesheet" href="statics/css/bootstrap.min.css" />
  <link rel="stylesheet" href="statics/style.css" />
  <!-- Google Font Icon Links -->
  <link
    href="https://fonts.googleapis.com/icon?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Round"
    rel="stylesheet" />

  <script src="statics/js/bootstrap.min.js"></script>
  <title>Login | SmartStock Inventory</title>
</head>

<body class="main">
  <div
    class="container vh-100 d-flex align-items-center justify-content-center">
    <div class="row h-75 w-75 p-4">
      <!-- Left Column/Icon Side -->
      <div
        class="col-md-6 d-flex align-items-center justify-content-center rounded-start left-column shadow">
        <div
          class="col-12 d-flex mx-5 align-items-center justify-content-center">
          <img
            src="statics/images/logo.png"
            alt="SmartStock Inventory Logo"
            class="img-fluid" />
        </div>
      </div>

      <!-- Right Column/Login Side -->
      <div
        class="col-md-6 rounded-end p-5 right-column border shadow d-flex align-items-center">
        <div class="w-100">
          <div class="row mb-3">
            <p class="display-5 fw-bold">Login</p>
          </div>
          <div>

            <form action="handlers/Authentication/login-handler.php" method="POST" class="form">
              <?php if (isset($_SESSION['login_error'])): ?>
                <div class="alert alert-danger">
                  <?php
                  echo $_SESSION['login_error'];
                  unset($_SESSION['login_error']);
                  ?>
                </div>
              <?php endif; ?>
              <div class="mb-3">
                <label for="username" class="fw-semibold mb-1">Username</label>
                <input
                  type="text"
                  class="form-control"
                  id="username"
                  name="username"
                  placeholder="Enter your username"
                  required />
              </div>
              <div class="mb-3">
                <label for="password" class="fw-semibold mb-1">Password</label>
                <div class="input-group">
                  <input
                    type="password"
                    class="form-control password-input"
                    id="password"
                    name="password"
                    placeholder="Enter your password"
                    required />
                  <button
                    type="button"
                    class="btn btn-outline-secondary toggle-password"
                    aria-label="Toggle password visibility">
                    <span class="material-icons-outlined">visibility</span>
                  </button>
                </div>
              </div>
              <div class="d-flex align-items-center mt-4">
                <button type="submit" class="btn btn-primary py-2 px-4 mt-3">
                  Login
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const togglePassword = document.querySelector(".toggle-password");
      const passwordInput = document.querySelector("#password");

      togglePassword.addEventListener("click", function() {
        const type =
          passwordInput.getAttribute("type") === "password" ?
          "text" :
          "password";
        passwordInput.setAttribute("type", type);

        // Toggle the icon between visibility and visibility_off
        const icon = this.querySelector(".material-icons-outlined");
        icon.textContent =
          type === "password" ? "visibility" : "visibility_off";
      });
    });
  </script>
</body>

</html>
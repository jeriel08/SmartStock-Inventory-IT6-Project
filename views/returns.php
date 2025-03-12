<?php
session_start();
echo "<script>console.log('Session user_id in returns.php: " . ($_SESSION['user_id'] ?? 'unset') . "');</script>";
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}
include '../database/database.php';

$stmt = $conn->prepare("
    SELECT r.ReturnID, c.Name AS CustomerName, c.Address, c.PhoneNumber, r.OrderID, r.ReturnDate
    FROM returns r
    LEFT JOIN customers c ON r.CustomerID = c.CustomerID
");
$stmt->execute();
$returns = $stmt->get_result();

$orders = $conn->query("
    SELECT o.OrderID, o.Date, c.Name AS CustomerName, c.CustomerID
    FROM orders o
    LEFT JOIN customers c ON o.CustomerID = c.CustomerID
    WHERE o.Status = 'Paid'
    ORDER BY o.Date DESC
");
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
        }

        .clickable-icon:hover {
            color: #0056b3;
        }

        .modal-body {
            max-height: calc(100vh - 200px);
            overflow-y: scroll;
        }

        #productDetailsTable {
            margin-top: 10px;
        }

        #productDetailsTable input[type="number"] {
            width: 80px;
        }

        #productDetailsTable select {
            width: 150px;
        }

        .modal-lg {
            max-width: 800px;
        }

        #returnDetailsTable {
            margin-top: 20px;
        }
    </style>
</head>

<body class="main">
    <!-- Navbar unchanged -->
    <nav class="navbar bg-body-tertiary fixed-top shadow-sm">
        <div class="container-fluid">
            <div class="d-flex align-items-center">
                <button class="navbar-toggler mx-3 border-0 shadow-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
                    <span class="material-icons-outlined navbar-icon">menu</span>
                </button>
                <a class="navbar-brand fw-semibold" href="returns.php">RETURNS</a>
            </div>
            <div class="d-flex align-items-center me-5 ms-auto">
                <span class="material-icons-outlined me-2 fs-1">account_circle</span>
                <div>
                    <p class="fw-bold mb-0"><?php echo htmlspecialchars($_SESSION['first_name'] . ' ' . $_SESSION['last_name']); ?></p>
                    <small class="mt-0"><?php echo htmlspecialchars($_SESSION['role']); ?></small>
                </div>
            </div>
            <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
                <div class="offcanvas-header d-flex align-items-center mt-4">
                    <div class="col-10">
                        <img src="../statics/images/logo-2.png" alt="SmartStock Inventory Logo" class="img-fluid" />
                    </div>
                </div>
                <div class="offcanvas-body">
                    <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                        <li class="nav-item"><a class="nav-link btn btn-outline-dark d-flex align-items-center gap-2 my-3 py-2 px-4" href="dashboard.php"><span class="material-icons-outlined">dashboard</span> Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link btn btn-outline-dark d-flex align-items-center gap-2 my-3 py-2 px-4" href="products.php"><span class="material-icons-outlined">inventory_2</span> Products</a></li>
                        <li class="nav-item"><a class="nav-link btn btn-outline-dark d-flex align-items-center gap-2 my-3 py-2 px-4" href="orders.php"><span class="material-icons-outlined">shopping_cart</span> Customer Orders</a></li>
                        <li class="nav-item"><a class="nav-link btn btn-outline-dark d-flex align-items-center gap-2 my-3 py-2 px-4" href="suppliers.php"><span class="material-icons-outlined">inventory</span> Suppliers</a></li>
                        <li class="nav-item"><a class="nav-link btn btn-outline-dark d-flex align-items-center gap-2 my-3 py-2 px-4" href="purchases.php"><span class="material-icons-outlined">local_shipping</span> Supplier Orders</a></li>
                        <li class="nav-item"><a class="nav-link btn btn-outline-dark d-flex align-items-center gap-2 my-3 py-2 px-4 active" href="returns.php"><span class="material-icons-outlined">assignment_return</span> Returns</a></li>
                        <li class="nav-item"><a class="nav-link btn btn-outline-dark d-flex align-items-center gap-2 my-3 py-2 px-4" href="account.php"><span class="material-icons-outlined">account_circle</span> Account</a></li>
                        <li class="nav-item"><a class="nav-link btn btn-outline-dark d-flex align-items-center gap-2 my-3 py-2 px-4" href="../handlers/Authentication/logout-handler.php"><span class="material-icons-outlined">logout</span> Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mt-4 pt5">
        <div class="row align-items-center justify-content-end">
            <div class="col-md-auto d-flex gap-2">
                <button type="button" class="btn btn-primary add-product-button d-flex align-items-center gap-2 py-2 rounded-4" data-bs-toggle="modal" data-bs-target="#addReturnModal">
                    <span class="material-icons-outlined">add</span>
                    <span>Add Return</span>
                </button>
            </div>
            <?php if (isset($_SESSION['return_success'])): ?>
                <div class="alert alert-success mt-4 w-100"><?php echo $_SESSION['return_success'];
                                                            unset($_SESSION['return_success']); ?></div>
            <?php endif; ?>
            <?php if (isset($_SESSION['return_error'])): ?>
                <div class="alert alert-danger mt-4 w-100"><?php echo $_SESSION['return_error'];
                                                            unset($_SESSION['return_error']); ?></div>
            <?php endif; ?>
            <div class="container-fluid mt-4 rounded-5 shadow">
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
                                            data-return-date="<?php echo htmlspecialchars(date('Y-m-d', strtotime($row['ReturnDate']))); ?>">
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

    <!-- Add Return Modal (unchanged) -->
    <div class="modal fade" id="addReturnModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addReturnModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5 d-flex align-items-center gap-2" id="addReturnModalLabel">
                        <span class="material-icons-outlined fs-2">add_box</span>
                        Add Return
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="../handlers/Returns/add-return-handler.php" method="POST">
                    <div class="modal-body px-3 py-2">
                        <div class="row mb-2 align-items-center">
                            <label for="orderId" class="col-md-3 col-form-label text-md-end fw-medium">Order ID</label>
                            <div class="col-md-9">
                                <select class="form-control form-control-sm" id="orderId" name="orderId" required>
                                    <option value="" disabled selected>Select an order</option>
                                    <?php while ($order = $orders->fetch_assoc()): ?>
                                        <option value="<?php echo $order['OrderID']; ?>"
                                            data-customer-id="<?php echo $order['CustomerID']; ?>"
                                            data-customer-name="<?php echo htmlspecialchars($order['CustomerName']); ?>">
                                            Order ID: <?php echo $order['OrderID']; ?> (<?php echo date('m-d-Y', strtotime($order['Date'])); ?>)
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-2 align-items-center">
                            <label for="customerId" class="col-md-3 col-form-label text-md-end fw-medium">Customer</label>
                            <div class="col-md-9">
                                <input type="hidden" id="customerId" name="customerId">
                                <input type="text" class="form-control form-control-sm" id="customerName" readonly>
                            </div>
                        </div>
                        <div class="row mb-2 align-items-center">
                            <label for="returnDate" class="col-md-3 col-form-label text-md-end fw-medium">Return Date</label>
                            <div class="col-md-9">
                                <input type="date" class="form-control form-control-sm" id="returnDate" name="returnDate" value="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d'); ?>" required />
                            </div>
                        </div>
                        <div class="row mb-2 align-items-center">
                            <label class="col-md-3 col-form-label text-md-end fw-medium">Products</label>
                            <div class="col-md-9" id="productEntries">
                                <table class="table table-sm" id="productDetailsTable" style="display: none;">
                                    <thead>
                                        <tr>
                                            <th>Product Name</th>
                                            <th>Ordered Qty</th>
                                            <th>Return Qty</th>
                                            <th>Reason</th>
                                        </tr>
                                    </thead>
                                    <tbody id="productDetailsBody"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer px-3 py-2">
                        <button type="submit" class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-2 rounded-4 px-3" id="submitBtn">
                            <span class="material-icons-outlined">add</span>
                            Add Return
                        </button>
                        <button type="button" class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-2 rounded-4 px-3" data-bs-dismiss="modal">
                            <span class="material-icons-outlined">close</span>
                            Close
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Updated View Return Modal -->
    <div class="modal fade" id="viewReturnModal" tabindex="-1" aria-labelledby="viewReturnModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
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
                        <label class="col-md-3 col-form-label text-md-end">Returned Products: </label>
                        <div class="col-md-9">
                            <table class="table table-sm" id="returnDetailsTable">
                                <thead>
                                    <tr>
                                        <th>Product Name</th>
                                        <th>Quantity Returned</th>
                                        <th>Reason</th>
                                    </tr>
                                </thead>
                                <tbody id="returnDetailsBody"></tbody>
                            </table>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var viewModal = document.getElementById('viewReturnModal');
            viewModal.addEventListener('show.bs.modal', function(event) {
                var icon = event.relatedTarget;
                var returnId = icon.getAttribute('data-return-id');
                var customerName = icon.getAttribute('data-customer-name');
                var address = icon.getAttribute('data-address');
                var phoneNumber = icon.getAttribute('data-phone-number');
                var orderId = icon.getAttribute('data-order-id');
                var returnDate = icon.getAttribute('data-return-date');

                viewModal.querySelector('#viewCustomerName').textContent = customerName;
                viewModal.querySelector('#viewAddress').textContent = address;
                viewModal.querySelector('#viewPhoneNumber').textContent = phoneNumber;
                viewModal.querySelector('#viewOrderId').textContent = orderId;
                viewModal.querySelector('#viewReturnDate').textContent = returnDate;

                // Fetch return details
                fetch('../handlers/Returns/get-return-details.php?returnId=' + returnId, {
                        credentials: 'include'
                    })
                    .then(response => {
                        if (!response.ok) throw new Error('Network response not ok: ' + response.status);
                        return response.json();
                    })
                    .then(data => {
                        console.log('Fetched Return Details:', data);
                        updateReturnDetailsTable(data);
                    })
                    .catch(error => {
                        console.error('Fetch Error:', error);
                        document.getElementById('returnDetailsBody').innerHTML = '<tr><td colspan="3">Error loading details</td></tr>';
                    });
            });

            function updateReturnDetailsTable(data) {
                var returnDetailsBody = document.getElementById('returnDetailsBody');
                returnDetailsBody.innerHTML = '';

                if (!data || data.length === 0) {
                    returnDetailsBody.innerHTML = '<tr><td colspan="3">No return details found.</td></tr>';
                    return;
                }

                data.forEach(item => {
                    var row = document.createElement('tr');
                    row.innerHTML = `
                <td>${item.ProductName || 'Unknown'}</td>
                <td>${item.QuantityReturned || 0}</td>
                <td>${item.Reason || 'N/A'}</td>
            `;
                    returnDetailsBody.appendChild(row);
                });
            }

            var orderIdSelect = document.getElementById('orderId');
            var customerIdInput = document.getElementById('customerId');
            var customerNameInput = document.getElementById('customerName');
            var productDetailsTable = document.getElementById('productDetailsTable');
            var productDetailsBody = document.getElementById('productDetailsBody');
            var submitBtn = document.getElementById('submitBtn');

            orderIdSelect.addEventListener('change', function() {
                var orderId = this.value;
                var selectedOption = this.options[this.selectedIndex];
                var customerId = selectedOption.getAttribute('data-customer-id');
                var customerName = selectedOption.getAttribute('data-customer-name');

                console.log('Selected Order ID:', orderId);
                console.log('Customer ID:', customerId, 'Customer Name:', customerName);

                if (orderId) {
                    customerIdInput.value = customerId;
                    customerNameInput.value = customerName;

                    var fetchUrl = '../handlers/Returns/get-order-products.php?orderId=' + orderId;
                    console.log('Fetching from:', fetchUrl);

                    fetch(fetchUrl, {
                            credentials: 'include'
                        })
                        .then(response => {
                            if (!response.ok) throw new Error('Network response not ok: ' + response.status);
                            return response.json();
                        })
                        .then(data => {
                            console.log('Fetched Products:', data);
                            updateProductTable(data);
                        })
                        .catch(error => {
                            console.error('Fetch Error:', error);
                            resetProductTable();
                            alert('Failed to load products. Check console for details.');
                        });
                } else {
                    resetProductTable();
                    customerIdInput.value = '';
                    customerNameInput.value = '';
                }
            });

            function updateProductTable(data) {
                productDetailsBody.innerHTML = '';
                console.log('Updating table with data:', data);

                if (data.error) {
                    productDetailsBody.innerHTML = '<tr><td colspan="4">' + data.error + '</td></tr>';
                    productDetailsTable.style.display = 'table';
                    return;
                }
                if (data.message) {
                    productDetailsBody.innerHTML = '<tr><td colspan="4">' + data.message + '</td></tr>';
                    productDetailsTable.style.display = 'table';
                    return;
                }
                if (!data || data.length === 0) {
                    productDetailsBody.innerHTML = '<tr><td colspan="4">No products found for this order.</td></tr>';
                    productDetailsTable.style.display = 'table';
                    return;
                }

                data.forEach((product, index) => {
                    var row = document.createElement('tr');
                    row.innerHTML = `
                <td><input type="text" class="form-control form-control-sm" value="${product.ProductName || 'Unknown'}" readonly></td>
                <td><input type="number" class="form-control form-control-sm" value="${product.Quantity || 0}" readonly></td>
                <td>
                    <input type="hidden" name="products[${index}][productId]" value="${product.ProductID || ''}">
                    <input type="number" class="form-control form-control-sm" 
                           name="products[${index}][quantity]" 
                           min="0" max="${product.Quantity || 0}" 
                           placeholder="0" 
                           data-product-id="${product.ProductID || ''}">
                </td>
                <td>
                    <select class="form-control form-control-sm" 
                            name="products[${index}][reason]">
                        <option value="" disabled selected>Select reason</option>
                        <option value="Wrong Item">Wrong Item</option>
                        <option value="Damaged/Expired">Damaged/Expired</option>
                    </select>
                </td>
            `;
                    productDetailsBody.appendChild(row);
                });
                productDetailsTable.style.display = 'table';
            }

            function resetProductTable() {
                productDetailsBody.innerHTML = '';
                productDetailsTable.style.display = 'none';
            }

            productDetailsTable.addEventListener('input', function(e) {
                if (e.target.type === 'number' && e.target.name.includes('[quantity]')) {
                    var max = parseInt(e.target.max) || 0;
                    var value = parseInt(e.target.value) || 0;
                    if (value > max) {
                        e.target.value = max;
                        alert(`Cannot return more than ${max} for this product.`);
                    }
                }
            });

            submitBtn.addEventListener('click', function(e) {
                var inputs = productDetailsTable.getElementsByTagName('input');
                var selects = productDetailsTable.getElementsByTagName('select');
                var hasQuantity = false;

                for (var i = 0; i < inputs.length; i++) {
                    if (inputs[i].type === 'number' && inputs[i].name.includes('[quantity]') && inputs[i].value > 0) {
                        hasQuantity = true;
                        break;
                    }
                }

                if (!hasQuantity) {
                    e.preventDefault();
                    alert('Please enter at least one return quantity.');
                    return;
                }

                for (var i = 0; i < inputs.length; i++) {
                    if (inputs[i].type === 'number' && inputs[i].name.includes('[quantity]') && inputs[i].value > 0 && !selects[Math.floor(i / 2)].value) {
                        e.preventDefault();
                        alert('Please select a reason for each product being returned.');
                        return;
                    }
                }
            });

            <?php if (isset($_SESSION['return_error'])): ?>
                var modal = new bootstrap.Modal(document.getElementById('addReturnModal'));
                modal.show();
            <?php endif; ?>
        });
    </script>
</body>

</html>
<?php $conn->close(); ?>
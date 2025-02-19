# SmartStock Inventory System

## Overview

SmartStock Inventory System is a web-based inventory management solution designed to streamline stock tracking, supplier management, and product monitoring. Built using PHP, MySQL, HTML, CSS, and JavaScript, it provides an intuitive interface for managing inventory efficiently.

## Features

- **User Authentication**

  - Secure login system with unique usernames.
  - The super administrator creates users but can be managed by the users themselves.

- **Product Management**

  - Add, update, and delete products.
  - View product details in a structured table.
  - Low-stock and out-of-stock alerts.

- **Supplier Management**

  - Manage suppliers and prevent duplicates.
  - Option to update supplier details instead of adding duplicates.

- **Order Management**

  - Create and track orders.
  - Manage order lines and receiving products.

- **Returns & Adjustments**

  - Track products returned by customers.
  - Record products returned to suppliers.
  - Adjust inventory for damaged or defective products.

- **Admin & Audit Logs**

  - Admin entity for tracking system activities.
  - Logs important system actions for accountability.

- **Reporting & Analytics**

  - Visual reports using bar graphs (Matplotlib integration).
  - Track product trends over time.

## Technologies Used

- **Frontend**: HTML, CSS, Bootstrap, JavaScript
- **Backend**: PHP
- **Database**: MySQL
- **Libraries & Tools**:
  - Chart.js (for visual reports)

## Installation & Setup

1. **Clone the Repository**:

   ```sh
   git clone https://github.com/your-repo-url.git
   cd SmartStock-Inventory
   ```

2. **Set Up the Database**:

   - Import the provided SQL file into MySQL.
   - Update `database.php` with your database credentials.

3. **Run the System**:
   - Run the system in a local server like XAMPP.

## Usage

- **Login:** Enter credentials to access the system.
- **Manage Inventory:** Add, update, or remove products.
- **Track Orders & Returns:** Process customer and supplier transactions.
- **View Reports:** Analyze inventory trends through visual graphs.

## Contribution

Contributions are welcome! To contribute:

1. Fork the repository.
2. Create a feature branch.
3. Commit changes and push to your branch.
4. Open a Pull Request.

---

**SmartStock Inventory System** - Efficient inventory tracking made simple.


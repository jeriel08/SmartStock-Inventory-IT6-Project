<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}
include '../../database/database.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../../statics/css/bootstrap.min.css" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet" />
    <title>Categories | SmartStock Inventory</title>
</head>
<body>
    <div class="container mt-4 pt-5">
        <h2>Categories</h2>
        <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
            Add Category
        </button>

        <!-- Category List -->
        <?php
        $stmt = $conn->prepare("SELECT CategoryID, Name, Description FROM categories");
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()): ?>
            <div class="card mb-3">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title"><?php echo htmlspecialchars($row['Name']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($row['Description'] ?: 'No description'); ?></p>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-primary">Edit</button>
                        <button class="btn btn-outline-danger">Delete</button>
                    </div>
                </div>
            </div>
        <?php endwhile; $stmt->close(); ?>

        <!-- Add Category Modal (same as above) -->
        <div class="modal fade" id="addCategoryModal" data-bs-backdrop="static" data-bs-keyboard="false" 
             tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addCategoryModalLabel">Add Category</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="../handlers/add-category-handler.php" method="POST">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="categoryName" class="form-label">Name</label>
                                <input type="text" class="form-control" id="categoryName" name="categoryName" required />
                            </div>
                            <div class="mb-3">
                                <label for="categoryDescription" class="form-label">Description</label>
                                <textarea class="form-control" id="categoryDescription" name="categoryDescription"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Add Category</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="../statics/js/bootstrap.min.js"></script>
</body>
</html>
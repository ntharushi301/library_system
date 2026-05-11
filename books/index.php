<?php
// Include the header file
require_once '../includes/header.php';

// Include the database connection file
require_once '../config/db.php';

// Initialize success and error message variables
$success = $error = '';

// Check if delete request exists in URL
if (isset($_GET['delete'])) {

    // Get the book ID to delete
    $id = $_GET['delete'];

    // Execute delete query
    if ($conn->query("DELETE FROM book WHERE book_id='$id'")) {

        // Success message after deleting
        $success = "Book deleted successfully.";

    } else {

        // Error message if delete fails
        $error = "Cannot delete — book has related records.";
    }
}

// Fetch all books with category names using JOIN query
$books = $conn->query("
    SELECT b.*, bc.category_Name 
    FROM book b 
    JOIN bookcategory bc 
    ON b.category_id=bc.category_id 
    ORDER BY b.book_id
");
?>

<?php 
// Include the sidebar file
include '../includes/sidebar.php'; 
?>

<!-- Main content section -->
<div class="main-content">

    <!-- Top navigation bar -->
    <div class="topbar">

        <div>

            <!-- Page title -->
            <h5 class="fw-bold mb-0">Books</h5>

            <!-- Page subtitle -->
            <small class="text-muted">
                Manage library books
            </small>

        </div>

        <!-- Add Book button -->
        <a 
            href="create.php" 
            class="btn btn-sm" 
            style="background:#1a237e;color:#fff;border-radius:8px;"
        >

            <!-- Plus icon -->
            <i class="bi bi-plus-lg me-1"></i>

            <!-- Button text -->
            Add Book

        </a>
    </div>

    <!-- Display success message -->
    <?php if ($success): ?>
        <div class="alert alert-success">
            <?= $success ?>
        </div>
    <?php endif; ?>

    <!-- Display error message -->
    <?php if ($error): ?>
        <div class="alert alert-danger">
            <?= $error ?>
        </div>
    <?php endif; ?>

    <!-- Card container -->
    <div class="card">

        <!-- Card header -->
        <div class="card-header bg-white">

            <!-- Card title with icon -->
            <h6 class="fw-bold mb-0">

                <!-- Journal icon -->
                <i class="bi bi-journals me-2"></i>

                <!-- Title text -->
                All Books

            </h6>

        </div>

        <!-- Card body -->
        <div class="card-body p-0">

            <!-- Books table -->
            <table class="table table-hover mb-0">

                <!-- Table header -->
                <thead>

                    <tr>

                        <!-- Table headings -->
                        <th>Book ID</th>
                        <th>Book Name</th>
                        <th>Category</th>
                        <th>Actions</th>

                    </tr>

                </thead>

                <!-- Table body -->
                <tbody>

                <?php
                // Loop through all books
                while($b = $books->fetch_assoc()):
                ?>

                <!-- Table row -->
                <tr>

                    <!-- Book ID column -->
                    <td>

                        <!-- Badge for Book ID -->
                        <span class="badge bg-info text-dark">

                            <!-- Display Book ID safely -->
                            <?= htmlspecialchars($b['book_id']) ?>

                        </span>

                    </td>

                    <!-- Book Name column -->
                    <td>

                        <!-- Display Book Name safely -->
                        <?= htmlspecialchars($b['book_name']) ?>

                    </td>

                    <!-- Category column -->
                    <td>

                        <!-- Badge for Category -->
                        <span class="badge bg-light text-dark border">

                            <!-- Display Category Name safely -->
                            <?= htmlspecialchars($b['category_Name']) ?>

                        </span>

                    </td>

                    <!-- Actions column -->
                    <td>

                        <!-- Edit button -->
                        <a 
                            href="edit.php?id=<?= $b['book_id'] ?>" 
                            class="btn btn-warning btn-action me-1"
                        >

                            <!-- Pencil icon -->
                            <i class="bi bi-pencil"></i>

                            <!-- Button text -->
                            Edit

                        </a>

                        <!-- Delete button -->
                        <a 
                            href="?delete=<?= $b['book_id'] ?>" 
                            class="btn btn-danger btn-action"

                            
                            onclick="return confirm('Delete this book?')"
                        >

                            <!-- Trash icon -->
                            <i class="bi bi-trash"></i>

                            <!-- Button text -->
                            Delete

                        </a>

                    </td>

                </tr>

                <?php endwhile; ?>

                </tbody>
            </table>
        </div>
    </div>
</div>

<?php 
// Include the footer file
include '../includes/footer.php'; 
?>
<?php
// Include the header file
require_once '../includes/header.php';

// Include the database connection file
require_once '../config/db.php';

// Initialize error and success message variables
$error = $success = '';

// Get the book ID from URL parameter
$id = $_GET['id'] ?? '';

// Prepare SQL query to fetch book details by ID
$stmt = $conn->prepare("SELECT * FROM book WHERE book_id=?");

// Bind the book ID parameter to the query
$stmt->bind_param("s", $id);

// Execute the query
$stmt->execute();

// Fetch the book data as an associative array
$book = $stmt->get_result()->fetch_assoc();

// Check if book exists
if (!$book) {

    // Display message if book is not found
    echo "Book not found.";

    // Stop script execution
    exit();
}

// Fetch all categories from database
$categories = $conn->query("SELECT * FROM bookcategory ORDER BY category_id");

// Check if form is submitted using POST method
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Get and clean Book Name input
    $book_name = trim($_POST['book_name']);

    // Get and clean Category ID input
    $category_id = trim($_POST['category_id']);

    // Prepare SQL query to update book details
    $upd = $conn->prepare("UPDATE book SET book_name=?, category_id=? WHERE book_id=?");

    // Bind values to update query
    $upd->bind_param("sss", $book_name, $category_id, $id);

    // Execute update query and set success or error message
    $upd->execute() ? $success = "Book updated!" : $error = $conn->error;

    // Re-execute select query to refresh updated data
    $stmt->execute();

    // Fetch updated book details
    $book = $stmt->get_result()->fetch_assoc();
}
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
            <h5 class="fw-bold mb-0">Edit Book</h5>

            <!-- Breadcrumb navigation -->
            <small class="text-muted">
                <a href="index.php">Books</a> / Edit
            </small>

        </div>
    </div>

    <!-- Card container -->
    <div class="card" style="max-width:500px;">

        <!-- Card header -->
        <div class="card-header bg-white">

            <!-- Display current Book ID -->
            <h6 class="fw-bold mb-0">
                Edit: <?= htmlspecialchars($book['book_id']) ?>
            </h6>

        </div>

        <!-- Card body -->
        <div class="card-body">

            <!-- Display error message -->
            <?php if ($error): ?>
                <div class="alert alert-danger">
                    <?= $error ?>
                </div>
            <?php endif; ?>

            <!-- Display success message -->
            <?php if ($success): ?>
                <div class="alert alert-success">
                    <?= $success ?>
                </div>
            <?php endif; ?>

            <!-- Start form -->
            <form method="POST">

                <!-- Book ID display section -->
                <div class="mb-3">

                    <!-- Label for Book ID -->
                    <label class="form-label fw-semibold">
                        Book ID
                    </label>

                    <!-- Disabled input field showing Book ID -->
                    <input 
                        type="text" 
                        class="form-control bg-light" 
                        value="<?= htmlspecialchars($book['book_id']) ?>" 
                        disabled
                    >

                </div>

                <!-- Book Name input section -->
                <div class="mb-3">

                    <!-- Label for Book Name -->
                    <label class="form-label fw-semibold">
                        Book Name
                    </label>

                    <!-- Input field for Book Name -->
                    <input 
                        type="text" 
                        name="book_name" 
                        class="form-control" 
                        value="<?= htmlspecialchars($book['book_name']) ?>" 
                        required
                    >

                </div>

                <!-- Category selection section -->
                <div class="mb-4">

                    <!-- Label for Category -->
                    <label class="form-label fw-semibold">
                        Category
                    </label>

                    <!-- Category dropdown -->
                    <select name="category_id" class="form-select" required>

                        <?php
                        // Reset category result pointer
                        $categories->data_seek(0);

                        // Loop through categories
                        while($c = $categories->fetch_assoc()):
                        ?>

                        <!-- Category option -->
                        <option 
                            value="<?= $c['category_id'] ?>" 

                            <!-- Select current category -->
                            <?= $c['category_id']==$book['category_id']?'selected':'' ?>
                        >

                            <!-- Display category name safely -->
                            <?= htmlspecialchars($c['category_Name']) ?>

                        </option>

                        <?php endwhile; ?>

                    </select>

                </div>

                <!-- Update button -->
                <button 
                    type="submit" 
                    class="btn me-2" 
                    style="background:#1a237e;color:#fff;"
                >
                    Update Book
                </button>

                <!-- Cancel button -->
                <a href="index.php" class="btn btn-outline-secondary">
                    Cancel
                </a>

            </form>
            <!-- End form -->

        </div>
    </div>
</div>

<?php 
// Include the footer file
include '../includes/footer.php'; 
?>
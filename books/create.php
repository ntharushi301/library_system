<?php
// Include the header file
require_once '../includes/header.php';

// Include the database connection file
require_once '../config/db.php';

// Initialize error and success message variables
$error = $success = '';

// Fetch all book categories from the database
$categories = $conn->query("SELECT * FROM bookcategory ORDER BY category_id");

// Check if the form is submitted using POST method
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Get and clean Book ID input
    $book_id = trim($_POST['book_id']);

    // Get and clean Book Name input
    $book_name = trim($_POST['book_name']);

    // Get and clean Category ID input
    $category_id = trim($_POST['category_id']);

    // Validate Book ID format (Example: B001)
    if (!preg_match('/^B\d+$/', $book_id)) {

        // Set error message if format is invalid
        $error = "Book ID must be in format B001 (B followed by numbers only).";

    } else {

        // Prepare SQL query to check if Book ID already exists
        $chk = $conn->prepare("SELECT book_id FROM book WHERE book_id=?");

        // Bind Book ID parameter to query
        $chk->bind_param("s", $book_id);

        // Execute the query
        $chk->execute();

        // Check if any matching record exists
        if ($chk->get_result()->num_rows > 0) {

            // Set error message for duplicate Book ID
            $error = "Book ID already exists.";

        } else {

            // Prepare SQL query to insert new book record
            $stmt = $conn->prepare("INSERT INTO book (book_id, book_name, category_id) VALUES (?,?,?)");

            // Bind form values to insert query
            $stmt->bind_param("sss", $book_id, $book_name, $category_id);

            // Execute insert query and set success or error message
            $stmt->execute() ? $success = "Book added successfully!" : $error = $conn->error;
        }
    }
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
            <h5 class="fw-bold mb-0">Add Book</h5>

            <!-- Breadcrumb navigation -->
            <small class="text-muted">
                <a href="index.php">Books</a> / Add
            </small>

        </div>
    </div>

    <!-- Card container -->
    <div class="card" style="max-width:500px;">

        <!-- Card header -->
        <div class="card-header bg-white">

            <!-- Card title -->
            <h6 class="fw-bold mb-0">New Book</h6>

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

                <!-- Book ID input section -->
                <div class="mb-3">

                    <!-- Label for Book ID -->
                    <label class="form-label fw-semibold">
                        Book ID 
                        <small class="text-muted">(e.g. B001)</small>
                    </label>

                    <!-- Input field for Book ID -->
                    <input 
                        type="text" 
                        name="book_id" 
                        class="form-control" 
                        placeholder="B001" 
                        required
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

                        <!-- Default option -->
                        <option value="">
                            -- Select Category --
                        </option>

                        <?php
                        // Reset category result pointer
                        $categories->data_seek(0);

                        // Loop through categories
                        while($c = $categories->fetch_assoc()):
                        ?>

                        <!-- Display category option -->
                        <option value="<?= $c['category_id'] ?>">

                            <!-- Display category name safely -->
                            <?= htmlspecialchars($c['category_Name']) ?>

                        </option>

                        <?php endwhile; ?>

                    </select>

                </div>

                <!-- Submit button -->
                <button 
                    type="submit" 
                    class="btn me-2" 
                    style="background:#1a237e;color:#fff;"
                >
                    Save Book
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
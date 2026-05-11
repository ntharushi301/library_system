<?php
// Include the common header file (contains CSS, metadata, etc.)
require_once '../includes/header.php';
// Include the database connection configuration
require_once '../config/db.php';

// Initialize empty variables for error and success messages
$error = $success = '';

// Check if the form has been submitted via the POST method
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and trim whitespace from the input fields
    $category_id   = trim($_POST['category_id']);
    $category_name = trim($_POST['category_name']);
    // Generate the current date and time for the 'date_modified' field
    $date_modified = date('Y-m-d H:i:sa');

    // Validation: Check if the Category ID follows the pattern 'C' followed by numbers
    if (!preg_match('/^C\d+$/', $category_id)) {
        $error = "Category ID must be in format C001 (C followed by numbers only).";
    } else {
        // Prepare a SQL statement to check if the Category ID already exists in the database
        $chk = $conn->prepare("SELECT category_id FROM bookcategory WHERE category_id=?");
        $chk->bind_param("s", $category_id); // Bind the ID as a string to prevent SQL injection
        $chk->execute();
        
        // If the query returns one or more rows, the ID is already taken
        if ($chk->get_result()->num_rows > 0) {
            $error = "Category ID already exists.";
        } else {
            // Prepare an INSERT statement to add the new category to the table
            $stmt = $conn->prepare("INSERT INTO bookcategory (category_id, category_Name, date_modified) VALUES (?,?,?)");
            // Bind the three variables as strings ('sss')
            $stmt->bind_param("sss", $category_id, $category_name, $date_modified);
            
            // Execute the statement and set a success message, otherwise capture the database error
            $stmt->execute() ? $success = "Category added successfully!" : $error = $conn->error;
        }
    }
}
?>

<?php // Include the navigation sidebar 
include '../includes/sidebar.php'; ?>

<div class="main-content">
    <div class="topbar">
        <div>
            <h5 class="fw-bold mb-0">Add Category</h5>
            <small class="text-muted"><a href="index.php">Categories</a> / Add</small>
        </div>
    </div>

    <div class="card" style="max-width:500px;">
        <div class="card-header bg-white">
            <h6 class="fw-bold mb-0">New Category</h6>
        </div>
        <div class="card-body">
            <?php if ($error): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success"><?= $success ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Category ID <small class="text-muted">(e.g. C001)</small></label>
                    <input type="text" name="category_id" class="form-control" placeholder="C001" required>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Category Name</label>
                    <input type="text" name="category_name" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold text-muted">Date Modified</label>
                    <input type="text" class="form-control bg-light" value="<?= date('Y-m-d H:i:s') ?>" disabled>
                    <small class="text-muted">Auto-set to current date/time</small>
                </div>

                <button type="submit" class="btn me-2" style="background:#1a237e;color:#fff;">Save Category</button>
                <a href="index.php" class="btn btn-outline-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>

<?php // Include the common footer file (contains JS scripts, closing tags, etc.)
include '../includes/footer.php'; ?>
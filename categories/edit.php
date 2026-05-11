<?php
// Include the shared header file (contains <head>, CSS links, etc.)
require_once '../includes/header.php';
// Include the database connection configuration
require_once '../config/db.php';

// Initialize feedback variables to empty strings
$error = $success = '';

// Get the category ID from the URL (e.g., edit.php?id=C001), or set to empty if not found
$id = $_GET['id'] ?? '';

// Prepare a SQL statement to fetch the specific category details for the given ID
$stmt = $conn->prepare("SELECT * FROM bookcategory WHERE category_id=?");
$stmt->bind_param("s", $id); // Bind the ID as a string to prevent SQL injection
$stmt->execute();
// Fetch the result as an associative array
$cat = $stmt->get_result()->fetch_assoc();

// If no category is found with that ID, stop the script and notify the user
if (!$cat) { echo "Category not found."; exit(); }

// Check if the form was submitted via the POST method
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize the user input by trimming whitespace
    $category_name = trim($_POST['category_name']);
    // Generate the current timestamp for the update
    $date_modified = date('Y-m-d H:i:sa');
    
    // Prepare the SQL UPDATE statement to modify the category name and date
    $upd = $conn->prepare("UPDATE bookcategory SET category_Name=?, date_modified=? WHERE category_id=?");
    $upd->bind_param("sss", $category_name, $date_modified, $id);
    
    // Execute the update and set a success message; otherwise, capture the database error
    $upd->execute() ? $success = "Category updated!" : $error = $conn->error;
    
    // Re-run the fetch logic to ensure the form displays the newly updated data immediately
    $stmt->execute();
    $cat = $stmt->get_result()->fetch_assoc();
}
?>

<?php // Include the navigation sidebar 
include '../includes/sidebar.php'; ?>

<div class="main-content">
    <div class="topbar">
        <div>
            <h5 class="fw-bold mb-0">Edit Category</h5>
            <small class="text-muted"><a href="index.php">Categories</a> / Edit</small>
        </div>
    </div>

    <div class="card" style="max-width:500px;">
        <div class="card-header bg-white">
            <h6 class="fw-bold mb-0">Edit: <?= htmlspecialchars($cat['category_id']) ?></h6>
        </div>
        
        <div class="card-body">
            <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
            <?php if ($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>
            
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Category ID</label>
                    <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($cat['category_id']) ?>" disabled>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Category Name</label>
                    <input type="text" name="category_name" class="form-control" value="<?= htmlspecialchars($cat['category_Name']) ?>" required>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold text-muted">Date Modified</label>
                    <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($cat['date_modified']) ?>" disabled>
                    <small class="text-muted">Will be updated to current time on save</small>
                </div>

                <button type="submit" class="btn me-2" style="background:#1a237e;color:#fff;">Update Category</button>
                <a href="index.php" class="btn btn-outline-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>

<?php // Include the common footer (scripts and closing tags)
include '../includes/footer.php'; ?>
<?php
// Include the shared header file (CSS, Meta tags, etc.)
require_once '../includes/header.php';
// Include the database connection configuration
require_once '../config/db.php';

// Initialize feedback variables for the user
$success = $error = '';

// Check if a 'delete' request was sent via the URL (e.g., index.php?delete=C001)
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    
    // Execute the DELETE query. 
    // Note: If there is a Foreign Key constraint, this will fail if books are linked to this category.
    if ($conn->query("DELETE FROM bookcategory WHERE category_id='$id'")) {
        $success = "Category deleted successfully.";
    } else {
        // If the query fails (usually due to database constraints), show an error
        $error = "Cannot delete — books exist in this category.";
    }
}

// Fetch all categories from the database, ordered by their ID
$cats = $conn->query("SELECT * FROM bookcategory ORDER BY category_id");
?>

<?php // Include the navigation sidebar 
include '../includes/sidebar.php'; ?>

<div class="main-content">
    <div class="topbar">
        <div>
            <h5 class="fw-bold mb-0">Book Categories</h5>
            <small class="text-muted">Manage book categories</small>
        </div>
        <a href="create.php" class="btn btn-sm" style="background:#1a237e;color:#fff;border-radius:8px;">
            <i class="bi bi-plus-lg me-1"></i> Add Category
        </a>
    </div>

    <?php if ($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>
    <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>

    <div class="card">
        <div class="card-header bg-white">
            <h6 class="fw-bold mb-0"><i class="bi bi-tags me-2"></i>All Categories</h6>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Category ID</th>
                        <th>Category Name</th>
                        <th>Date Modified</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php while($c = $cats->fetch_assoc()): ?>
                <tr>
                    <td><span class="badge bg-success"><?= htmlspecialchars($c['category_id']) ?></span></td>
                    <td><?= htmlspecialchars($c['category_Name']) ?></td>
                    <td><?= htmlspecialchars($c['date_modified']) ?></td>
                    <td>
                        <a href="edit.php?id=<?= $c['category_id'] ?>" class="btn btn-warning btn-action me-1">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <a href="?delete=<?= $c['category_id'] ?>" class="btn btn-danger btn-action" onclick="return confirm('Delete this category?')">
                            <i class="bi bi-trash"></i> Delete
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php // Include the common footer file
include '../includes/footer.php'; ?>
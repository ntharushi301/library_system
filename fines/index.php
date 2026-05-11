<?php
// Include the global header file (navigation, CSS links, etc.)
require_once '../includes/header.php'; 

// Include the database connection configuration
require_once '../config/db.php'; 

// Initialize empty strings for success and error messages
$success = $error = '';

// Check if a 'delete' parameter exists in the URL (e.g., index.php?delete=5)
if (isset($_GET['delete'])) {
    // Assign the ID from the URL to a variable
    $id = $_GET['delete'];
    
    // Execute the DELETE query on the 'fine' table and check if it succeeds
    // NOTE: Using variables directly in queries is risky (SQL Injection); consider prepared statements!
    $conn->query("DELETE FROM fine WHERE fine_id='$id'")
        ? $success = "Fine deleted successfully." // If true, set success message
        : $error = $conn->error;                  // If false, capture the database error
}

// Fetch all fine records joined with book and member information for a complete view
$fines = $conn->query("
    SELECT f.*, b.book_name, CONCAT(m.first_name,' ',m.last_name) AS member_name
    FROM fine f
    JOIN book b ON f.book_id = b.book_id
    JOIN member m ON f.member_id = m.member_id
    ORDER BY f.fine_id
");
?>

<?php // Include the sidebar navigation component ?>
<?php include '../includes/sidebar.php'; ?>

<!-- Main content container -->
<div class="main-content">
    <div class="topbar">
        <div>
            <!-- Page Title -->
            <h5 class="fw-bold mb-0">Fines</h5>
            <small class="text-muted">Manage member fines</small>
        </div>
        <!-- Link to the page where a new fine can be created -->
        <a href="create.php" class="btn btn-sm" style="background:#1a237e;color:#fff;border-radius:8px;">
            <i class="bi bi-plus-lg me-1"></i> Assign Fine
        </a>
    </div>

    <!-- Display success alert if a fine was deleted successfully -->
    <?php if ($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>
    
    <!-- Display error alert if a database error occurred -->
    <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>

    <div class="card">
        <!-- Card header with icon -->
        <div class="card-header bg-white"><h6 class="fw-bold mb-0"><i class="bi bi-cash-coin me-2"></i>All Fines</h6></div>
        <div class="card-body p-0">
            <!-- Table to display all fine details -->
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Fine ID</th>
                        <th>Member ID</th>
                        <th>Member Name</th>
                        <th>Book Name</th>
                        <th>Fine Amount</th>
                        <th>Date Modified</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <!-- Loop through each row returned by the SQL query -->
                <?php while($f = $fines->fetch_assoc()): ?>
                <tr>
                    <!-- Display fine ID inside a red badge -->
                    <td><span class="badge bg-danger"><?= htmlspecialchars($f['fine_id']) ?></span></td>
                    
                    <!-- Display member ID -->
                    <td><?= htmlspecialchars($f['member_id']) ?></td>
                    
                    <!-- Display member full name (concatenated in SQL) -->
                    <td><?= htmlspecialchars($f['member_name']) ?></td>
                    
                    <!-- Display the name of the book associated with the fine -->
                    <td><?= htmlspecialchars($f['book_name']) ?></td>
                    
                    <!-- Display the fine amount with currency prefix -->
                    <td><strong class="text-danger">LKR <?= htmlspecialchars($f['fine_amount']) ?></strong></td>
                    
                    <!-- Display the last modification date -->
                    <td><?= htmlspecialchars($f['fine_date_modified']) ?></td>
                    
                    <td>
                        <!-- Link to edit this specific fine -->
                        <a href="edit.php?id=<?= $f['fine_id'] ?>" class="btn btn-warning btn-action me-1"><i class="bi bi-pencil"></i> Edit</a>
                        
                        <!-- Link to delete this fine with a JavaScript confirmation popup -->
                        <a href="?delete=<?= $f['fine_id'] ?>" class="btn btn-danger btn-action" onclick="return confirm('Delete this fine?')"><i class="bi bi-trash"></i> Delete</a>
                    </td>
                </tr>
                <?php endwhile; // End of the loop ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php // Include the global footer (scripts, copyright, etc.) ?>
<?php include '../includes/footer.php'; ?>
<?php
require_once '../includes/header.php';
require_once '../config/db.php';

$success = $error = '';

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM bookborrower WHERE borrow_id='$id'")
        ? $success = "Borrow record deleted." : $error = $conn->error;
}

$rows = $conn->query("
    SELECT bb.*, b.book_name, CONCAT(m.first_name,' ',m.last_name) AS member_name
    FROM bookborrower bb
    JOIN book b ON bb.book_id = b.book_id
    JOIN member m ON bb.member_id = m.member_id
    ORDER BY bb.borrow_id
");
?>
<?php include '../includes/sidebar.php'; ?>
<div class="main-content">
    <div class="topbar">
        <div>
            <h5 class="fw-bold mb-0">Book Borrowing</h5>
            <small class="text-muted">Track book borrow records</small>
        </div>
        <a href="create.php" class="btn btn-sm" style="background:#1a237e;color:#fff;border-radius:8px;">
            <i class="bi bi-plus-lg me-1"></i> Add Borrow Record
        </a>
    </div>

    <?php if ($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>
    <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>

    <div class="card">
        <div class="card-header bg-white"><h6 class="fw-bold mb-0"><i class="bi bi-arrow-left-right me-2"></i>Borrow Records</h6></div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead>
                    <tr><th>Borrow ID</th><th>Book ID</th><th>Book Name</th><th>Member</th><th>Status</th><th>Date Modified</th><th>Actions</th></tr>
                </thead>
                <tbody>
                <?php while($r = $rows->fetch_assoc()): ?>
                <tr>
                    <td><span class="badge bg-warning text-dark"><?= htmlspecialchars($r['borrow_id']) ?></span></td>
                    <td><?= htmlspecialchars($r['book_id']) ?></td>
                    <td><?= htmlspecialchars($r['book_name']) ?></td>
                    <td><?= htmlspecialchars($r['member_name']) ?></td>
                    <td>
                        <span class="badge badge-status-<?= $r['borrow_status'] ?>">
                            <?= ucfirst($r['borrow_status']) ?>
                        </span>
                    </td>
                    <td><?= htmlspecialchars($r['borrower_date_modified']) ?></td>
                    <td>
                        <a href="edit.php?id=<?= $r['borrow_id'] ?>" class="btn btn-warning btn-action me-1"><i class="bi bi-pencil"></i> Edit</a>
                        <a href="?delete=<?= $r['borrow_id'] ?>" class="btn btn-danger btn-action" onclick="return confirm('Delete this record?')"><i class="bi bi-trash"></i> Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php include '../includes/footer.php'; ?>

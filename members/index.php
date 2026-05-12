<?php
require_once '../includes/header.php';// intializing the database connection
require_once '../config/db.php';//configuring database connection

$success = $error = '';

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    if ($conn->query("DELETE FROM member WHERE member_id='$id'")) {
        $success = "Member deleted successfully.";
    } else {
        $error = "Cannot delete — member has related records.";
    }
}

$members = $conn->query("SELECT * FROM member ORDER BY member_id");
?>
<?php include '../includes/sidebar.php'; ?>
<div class="main-content">
    <div class="topbar">
        <div>
            <h5 class="fw-bold mb-0">Library Members</h5>
            <small class="text-muted">Manage library members</small>
        </div>
        <a href="create.php" class="btn btn-sm" style="background:#1a237e;color:#fff;border-radius:8px;">
            <i class="bi bi-plus-lg me-1"></i> Add Member
        </a>
    </div>

    <?php if ($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>
    <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>

    <div class="card">
        <div class="card-header bg-white"><h6 class="fw-bold mb-0"><i class="bi bi-people me-2"></i>All Members</h6></div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead>
                    <tr><th>Member ID</th><th>First Name</th><th>Last Name</th><th>Birthday</th><th>Email</th><th>Actions</th></tr>
                </thead>
                <tbody>
                <?php while($m = $members->fetch_assoc()): ?>
                <tr>
                    <td><span class="badge bg-secondary"><?= htmlspecialchars($m['member_id']) ?></span></td>
                    <td><?= htmlspecialchars($m['first_name']) ?></td>
                    <td><?= htmlspecialchars($m['last_name']) ?></td>
                    <td><?= htmlspecialchars($m['birthday']) ?></td>
                    <td><?= htmlspecialchars($m['email']) ?></td>
                    <td>
                        <a href="edit.php?id=<?= $m['member_id'] ?>" class="btn btn-warning btn-action me-1"><i class="bi bi-pencil"></i> Edit</a>
                        <a href="?delete=<?= $m['member_id'] ?>" class="btn btn-danger btn-action" onclick="return confirm('Delete this member?')"><i class="bi bi-trash"></i> Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php include '../includes/footer.php'; ?>

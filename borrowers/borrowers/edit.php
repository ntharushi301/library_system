<?php
require_once '../includes/header.php';
require_once '../config/db.php';

$error = $success = '';
$id = $_GET['id'] ?? '';
$stmt = $conn->prepare("SELECT * FROM bookborrower WHERE borrow_id=?");
$stmt->bind_param("s", $id);
$stmt->execute();
$rec = $stmt->get_result()->fetch_assoc();
if (!$rec) { echo "Record not found."; exit(); }

$books = $conn->query("SELECT * FROM book ORDER BY book_id");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $book_id       = trim($_POST['book_id']);
    $borrow_status = trim($_POST['borrow_status']);
    $date_modified = date('Y-m-d H:i:sa');

    if (!preg_match('/^B\d+$/', $book_id)) {
        $error = "Book ID must be in format B001 (B followed by numbers).";
    } else {
        $upd = $conn->prepare("UPDATE bookborrower SET book_id=?, borrow_status=?, borrower_date_modified=? WHERE borrow_id=?");
        $upd->bind_param("ssss", $book_id, $borrow_status, $date_modified, $id);
        $upd->execute() ? $success = "Record updated!" : $error = $conn->error;
        $stmt->execute();
        $rec = $stmt->get_result()->fetch_assoc();
    }
}
?>
<?php include '../includes/sidebar.php'; ?>
<div class="main-content">
    <div class="topbar">
        <div>
            <h5 class="fw-bold mb-0">Edit Borrow Record</h5>
            <small class="text-muted"><a href="index.php">Borrowing</a> / Edit</small>
        </div>
    </div>
    <div class="card" style="max-width:520px;">
        <div class="card-header bg-white"><h6 class="fw-bold mb-0">Edit: <?= htmlspecialchars($rec['borrow_id']) ?></h6></div>
        <div class="card-body">
            <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
            <?php if ($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Borrow ID</label>
                    <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($rec['borrow_id']) ?>" disabled>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Book</label>
                    <select name="book_id" class="form-select" required>
                        <?php $books->data_seek(0); while($b = $books->fetch_assoc()): ?>
                        <option value="<?= $b['book_id'] ?>" <?= $b['book_id']==$rec['book_id']?'selected':'' ?>>
                            <?= $b['book_id'] ?> – <?= htmlspecialchars($b['book_name']) ?>
                        </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Member ID</label>
                    <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($rec['member_id']) ?>" disabled>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Borrow Status</label>
                    <select name="borrow_status" class="form-select" required>
                        <option value="borrowed" <?= $rec['borrow_status']=='borrowed'?'selected':'' ?>>Borrowed</option>
                        <option value="available" <?= $rec['borrow_status']=='available'?'selected':'' ?>>Available</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold text-muted">Date Modified</label>
                    <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($rec['borrower_date_modified']) ?>" disabled>
                    <small class="text-muted">Will update to current time on save</small>
                </div>
                <button type="submit" class="btn me-2" style="background:#1a237e;color:#fff;">Update Record</button>
                <a href="index.php" class="btn btn-outline-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
<?php include '../includes/footer.php'; ?>

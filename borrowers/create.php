<?php
require_once '../includes/header.php';//initializing database
require_once '../config/db.php';//getting database connection

$error = $success = '';
$books   = $conn->query("SELECT * FROM book ORDER BY book_id");
$members = $conn->query("SELECT * FROM member ORDER BY member_id");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $borrow_id    = trim($_POST['borrow_id']);
    $book_id      = trim($_POST['book_id']);
    $member_id    = trim($_POST['member_id']);
    $borrow_status = trim($_POST['borrow_status']);
    $date_modified = date('Y-m-d H:i:sa');

    if (!preg_match('/^BR\d+$/', $borrow_id)) {
        $error = "Borrow ID must be in format BR001 (BR followed by numbers only).";
    } elseif (!preg_match('/^B\d+$/', $book_id)) {
        $error = "Book ID must be in format B001 (B followed by numbers only).";
    } elseif (!preg_match('/^M\d+$/', $member_id)) {
        $error = "Member ID must be in format M001 (M followed by numbers only).";
    } else {
        $chk = $conn->prepare("SELECT borrow_id FROM bookborrower WHERE borrow_id=?");
        $chk->bind_param("s", $borrow_id);
        $chk->execute();
        if ($chk->get_result()->num_rows > 0) {
            $error = "Borrow ID already exists.";
        } else {
            $stmt = $conn->prepare("INSERT INTO bookborrower (borrow_id, book_id, member_id, borrow_status, borrower_date_modified) VALUES (?,?,?,?,?)");
            $stmt->bind_param("sssss", $borrow_id, $book_id, $member_id, $borrow_status, $date_modified);
            $stmt->execute() ? $success = "Borrow record added!" : $error = $conn->error;
        }
    }
}
?>
<?php include '../includes/sidebar.php'; ?>
<div class="main-content">
    <div class="topbar">
        <div>
            <h5 class="fw-bold mb-0">Add Borrow Record</h5>
            <small class="text-muted"><a href="index.php">Borrowing</a> / Add</small>
        </div>
    </div>
    <div class="card" style="max-width:560px;">
        <div class="card-header bg-white"><h6 class="fw-bold mb-0">New Borrow Record</h6></div>
        <div class="card-body">
            <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
            <?php if ($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Borrow ID <small class="text-muted">(e.g. BR001)</small></label>
                    <input type="text" name="borrow_id" class="form-control" placeholder="BR001" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Book</label>
                    <select name="book_id" class="form-select" required>
                        <option value="">-- Select Book --</option>
                        <?php $books->data_seek(0); while($b = $books->fetch_assoc()): ?>
                        <option value="<?= $b['book_id'] ?>"><?= $b['book_id'] ?> – <?= htmlspecialchars($b['book_name']) ?></option>
                        <?php endwhile; ?>
                    </select>
                    <small class="text-muted">Or type manually: <input type="text" name="book_id_manual" placeholder="B001" class="form-control form-control-sm mt-1" style="display:inline;width:120px;"></small>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Member</label>
                    <select name="member_id" class="form-select" required>
                        <option value="">-- Select Member --</option>
                        <?php $members->data_seek(0); while($m = $members->fetch_assoc()): ?>
                        <option value="<?= $m['member_id'] ?>"><?= $m['member_id'] ?> – <?= htmlspecialchars($m['first_name'].' '.$m['last_name']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Borrow Status</label>
                    <select name="borrow_status" class="form-select" required>
                        <option value="borrowed">Borrowed</option>
                        <option value="available">Available</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold text-muted">Date Modified</label>
                    <input type="text" class="form-control bg-light" value="<?= date('Y-m-d H:i:s') ?>" disabled>
                    <small class="text-muted">Auto-set to current date/time</small>
                </div>
                <button type="submit" class="btn me-2" style="background:#1a237e;color:#fff;">Save Record</button>
                <a href="index.php" class="btn btn-outline-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
<?php include '../includes/footer.php'; ?>

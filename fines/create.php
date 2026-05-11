<?php
require_once '../includes/header.php'; // include header file
require_once '../config/db.php'; // include database connection file

$error = $success = ''; // create two variables $error-> Store error message $success-> Store success message
$members = $conn->query("SELECT * FROM member ORDER BY member_id"); // get all data from member table
$books   = $conn->query("SELECT * FROM book ORDER BY book_id"); // get all data from book table

if ($_SERVER['REQUEST_METHOD'] === 'POST') { // check the form submit
    $fine_id       = trim($_POST['fine_id']); // get fine id from form
    $member_id     = trim($_POST['member_id']);// get member_id from form
    $book_id       = trim($_POST['book_id']);// get book_id  from form
    $fine_amount   = trim($_POST['fine_amount']);// get fine_amount from form
    $date_modified = date('Y-m-d H:i:sa');// Create current data and time

    // Validate fine_id format
    if (!preg_match('/^F\d+$/', $fine_id)) { // Check the fine id in right format 
        $error = "Fine ID must be in format F001 (F followed by numbers only)."; // store error message 
    }
    // Validate fine amount range
    elseif (!is_numeric($fine_amount) || $fine_amount < 2 || $fine_amount > 500) { // Check the valid fine amount
        $error = "Fine amount must be between LKR 2 and LKR 500.";
    } else {
        $chk = $conn->prepare("SELECT fine_id FROM fine WHERE fine_id=?"); // check fine_id already exisits in database
        $chk->bind_param("s", $fine_id); // Bind the fine_id in placeholder
        $chk->execute(); // execute the query
        if ($chk->get_result()->num_rows > 0) { // check matching rows
            $error = "Fine ID already exists.";
        } else {
            $stmt = $conn->prepare("INSERT INTO fine (fine_id, book_id, member_id, fine_amount, fine_date_modified) VALUES (?,?,?,?,?)"); // Prepare inset query
            $stmt->bind_param("sssss", $fine_id, $book_id, $member_id, $fine_amount, $date_modified); // Connect variables in placeholders
            $stmt->execute() ? $success = "Fine assigned successfully!" : $error = $conn->error; 
        }
    }
}
?>
<?php include '../includes/sidebar.php'; ?>
<div class="main-content">
    <div class="topbar">
        <div>
            <h5 class="fw-bold mb-0">Assign Fine</h5>
            <small class="text-muted"><a href="index.php">Fines</a> / Assign</small>
        </div>
    </div>
    <div class="card" style="max-width:520px;">
        <div class="card-header bg-white"><h6 class="fw-bold mb-0">New Fine</h6></div>
        <div class="card-body">
            <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
            <?php if ($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Fine ID <small class="text-muted">(e.g. F001)</small></label>
                    <input type="text" name="fine_id" class="form-control" placeholder="F001" required>
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
                    <label class="form-label fw-semibold">Book</label>
                    <select name="book_id" class="form-select" required>
                        <option value="">-- Select Book --</option>
                        <?php $books->data_seek(0); while($b = $books->fetch_assoc()): ?>
                        <option value="<?= $b['book_id'] ?>"><?= $b['book_id'] ?> – <?= htmlspecialchars($b['book_name']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Fine Amount (LKR) <small class="text-muted">Min: 2 / Max: 500</small></label>
                    <div class="input-group">
                        <span class="input-group-text">LKR</span>
                        <input type="number" name="fine_amount" class="form-control" min="2" max="500" placeholder="e.g. 200" required>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold text-muted">Date Modified</label>
                    <input type="text" class="form-control bg-light" value="<?= date('Y-m-d H:i:s') ?>" disabled>
                </div>
                <button type="submit" class="btn me-2" style="background:#1a237e;color:#fff;">Assign Fine</button>
                <a href="index.php" class="btn btn-outline-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
<?php include '../includes/footer.php'; ?>

<?php
require_once '../includes/header.php';
require_once '../config/db.php';

$error = $success = ''; // store error succes messages
$id = $_GET['id'] ?? ''; // get id in url
$stmt = $conn->prepare("SELECT * FROM fine WHERE fine_id=?"); // Prepare database query
$stmt->bind_param("s", $id); // bind id valuue
$stmt->execute(); // execute
$fine = $stmt->get_result()->fetch_assoc(); // get database result in associative array
if (!$fine) { echo "Fine not found."; exit(); }

$members = $conn->query("SELECT * FROM member ORDER BY member_id"); // Get members in member table 

// Check if the form was submitted via the POST method
if ($_SERVER['REQUEST_METHOD'] === 'POST') { 
    // Sanitize and capture inputs from the form
    $member_id     = trim($_POST['member_id']);
    $fine_amount   = trim($_POST['fine_amount']);
    
    // Generate a current timestamp for the record update
    $date_modified = date('Y-m-d H:i:sa');

    // Validation: Ensure amount is numeric and stays within the 2 to 500 range
    if (!is_numeric($fine_amount) || $fine_amount < 2 || $fine_amount > 500) {
        $error = "Fine amount must be between LKR 2 and LKR 500.";
    } else {
        // Prepare an SQL statement to prevent SQL Injection
        $upd = $conn->prepare("UPDATE fine SET member_id=?, fine_amount=?, fine_date_modified=? WHERE fine_id=?");
        
        // Bind the variables to the prepared statement ("ssss" means 4 strings)
        $upd->bind_param("ssss", $member_id, $fine_amount, $date_modified, $id);
        
        // Execute the update and set a success or error message based on the result
        $upd->execute() ? $success = "Fine updated!" : $error = $conn->error;
        
        // Refresh the $fine object so the form displays the updated data immediately
        $stmt->execute();
        $fine = $stmt->get_result()->fetch_assoc();
    }
}
?>
<?php include '../includes/sidebar.php'; ?>
<div class="main-content">
    <div class="topbar">
        <div>
            <h5 class="fw-bold mb-0">Edit Fine</h5>
            <small class="text-muted"><a href="index.php">Fines</a> / Edit</small>
        </div>
    </div>
    <div class="card" style="max-width:520px;">
        <div class="card-header bg-white"><h6 class="fw-bold mb-0">Edit: <?= htmlspecialchars($fine['fine_id']) ?></h6></div>
        <div class="card-body">
            <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
            <?php if ($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Fine ID</label>
                    <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($fine['fine_id']) ?>" disabled> 
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Member</label>
                    <select name="member_id" class="form-select" required>
                        <?php $members->data_seek(0); while($m = $members->fetch_assoc()): ?>
                        <option value="<?= $m['member_id'] ?>" <?= $m['member_id']==$fine['member_id']?'selected':'' ?>>
                            <?= $m['member_id'] ?> – <?= htmlspecialchars($m['first_name'].' '.$m['last_name']) ?>
                        </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Book ID</label>
                    <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($fine['book_id']) ?>" disabled>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Fine Amount (LKR) <small class="text-muted">Min: 2 / Max: 500</small></label>
                    <div class="input-group">
                        <span class="input-group-text">LKR</span>
                        <input type="number" name="fine_amount" class="form-control" min="2" max="500" value="<?= htmlspecialchars($fine['fine_amount']) ?>" required>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold text-muted">Date Modified</label>
                    <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($fine['fine_date_modified']) ?>" disabled>
                    <small class="text-muted">Will update to current time on save</small>
                </div>
                <button type="submit" class="btn me-2" style="background:#1a237e;color:#fff;">Update Fine</button>
                <a href="index.php" class="btn btn-outline-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
<?php include '../includes/footer.php'; ?>

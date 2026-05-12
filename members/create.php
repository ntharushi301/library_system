<?php
require_once '../includes/header.php';
require_once '../config/db.php';

$error = $success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $member_id  = trim($_POST['member_id']);
    $first_name = trim($_POST['first_name']);
    $last_name  = trim($_POST['last_name']);
    $birthday   = trim($_POST['birthday']);
    $email      = trim($_POST['email']);

    if (!preg_match('/^M\d+$/', $member_id)) {
        $error = "Member ID must be in format M001 (M followed by numbers only).";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address (e.g. sample@mymail.com).";
    } else {
        $chk = $conn->prepare("SELECT member_id FROM member WHERE member_id=?");
        $chk->bind_param("s", $member_id);
        $chk->execute();
        if ($chk->get_result()->num_rows > 0) {
            $error = "Member ID already exists.";
        } else {
            $stmt = $conn->prepare("INSERT INTO member (member_id, first_name, last_name, birthday, email) VALUES (?,?,?,?,?)");
            $stmt->bind_param("sssss", $member_id, $first_name, $last_name, $birthday, $email);
            $stmt->execute() ? $success = "Member added successfully!" : $error = $conn->error;
        }
    }
}
?>
<?php include '../includes/sidebar.php'; ?>
<div class="main-content">
    <div class="topbar">
        <div>
            <h5 class="fw-bold mb-0">Add Member</h5>
            <small class="text-muted"><a href="index.php">Members</a> / Add</small>
        </div>
    </div>
    <div class="card" style="max-width:520px;">
        <div class="card-header bg-white"><h6 class="fw-bold mb-0">New Member</h6></div>
        <div class="card-body">
            <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
            <?php if ($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Member ID <small class="text-muted">(e.g. M001)</small></label>
                    <input type="text" name="member_id" class="form-control" placeholder="M001" required>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <label class="form-label fw-semibold">First Name</label>
                        <input type="text" name="first_name" class="form-control" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-semibold">Last Name</label>
                        <input type="text" name="last_name" class="form-control" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Birthday</label>
                    <input type="date" name="birthday" class="form-control" required>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold">Email Address</label>
                    <input type="email" name="email" class="form-control" placeholder="sample@mymail.com" required>
                </div>
                <button type="submit" class="btn me-2" style="background:#1a237e;color:#fff;">Save Member</button>
                <a href="index.php" class="btn btn-outline-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
<?php include '../includes/footer.php'; ?>

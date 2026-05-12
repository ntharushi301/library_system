<?php
require_once '../includes/header.php';
require_once '../config/db.php';

$error = $success = '';
$id = $_GET['id'] ?? '';
$stmt = $conn->prepare("SELECT * FROM member WHERE member_id=?");
$stmt->bind_param("s", $id);
$stmt->execute();
$member = $stmt->get_result()->fetch_assoc();
if (!$member) { echo "Member not found."; exit(); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['first_name']);
    $last_name  = trim($_POST['last_name']);
    $birthday   = trim($_POST['birthday']);
    $email      = trim($_POST['email']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } else {
        $upd = $conn->prepare("UPDATE member SET first_name=?, last_name=?, birthday=?, email=? WHERE member_id=?");
        $upd->bind_param("sssss", $first_name, $last_name, $birthday, $email, $id);
        $upd->execute() ? $success = "Member updated!" : $error = $conn->error;
        $stmt->execute();
        $member = $stmt->get_result()->fetch_assoc();
    }
}
?>
<?php include '../includes/sidebar.php'; ?>
<div class="main-content">
    <div class="topbar">
        <div>
            <h5 class="fw-bold mb-0">Edit Member</h5>
            <small class="text-muted"><a href="index.php">Members</a> / Edit</small>
        </div>
    </div>
    <div class="card" style="max-width:520px;">
        <div class="card-header bg-white"><h6 class="fw-bold mb-0">Edit: <?= htmlspecialchars($member['member_id']) ?></h6></div>
        <div class="card-body">
            <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
            <?php if ($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Member ID</label>
                    <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($member['member_id']) ?>" disabled>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <label class="form-label fw-semibold">First Name</label>
                        <input type="text" name="first_name" class="form-control" value="<?= htmlspecialchars($member['first_name']) ?>" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-semibold">Last Name</label>
                        <input type="text" name="last_name" class="form-control" value="<?= htmlspecialchars($member['last_name']) ?>" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Birthday</label>
                    <input type="date" name="birthday" class="form-control" value="<?= htmlspecialchars($member['birthday']) ?>" required>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold">Email Address</label>
                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($member['email']) ?>" required>
                </div>
                <button type="submit" class="btn me-2" style="background:#1a237e;color:#fff;">Update Member</button>
                <a href="index.php" class="btn btn-outline-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
<?php include '../includes/footer.php'; ?>

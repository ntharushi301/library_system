<?php
session_start();
require_once '../config/db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id   = trim($_POST['user_id']);
    $first_name = trim($_POST['first_name']);
    $last_name  = trim($_POST['last_name']);
    $username  = trim($_POST['username']);
    $email     = trim($_POST['email']);
    $password  = trim($_POST['password']);

    // Validate User ID format: U followed by digits
    if (!preg_match('/^U\d+$/', $user_id)) {
        $error = "User ID must be in format U001 (U followed by numbers only).";
    }
    // Validate password length
    elseif (strlen($password) <= 8) {
        $error = "Password must be more than 8 characters.";
    }
    // Validate email format
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    }
    else {
        // Check duplicate email
        $stmt = $conn->prepare("SELECT user_id FROM user WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            $error = "Email is already registered.";
        } else {
            // Check duplicate username
            $stmt2 = $conn->prepare("SELECT user_id FROM user WHERE username = ?");
            $stmt2->bind_param("s", $username);
            $stmt2->execute();
            if ($stmt2->get_result()->num_rows > 0) {
                $error = "Username is already taken.";
            } else {
                // Check duplicate user_id
                $stmt3 = $conn->prepare("SELECT user_id FROM user WHERE user_id = ?");
                $stmt3->bind_param("s", $user_id);
                $stmt3->execute();
                if ($stmt3->get_result()->num_rows > 0) {
                    $error = "User ID already exists.";
                } else {
                    $stmt4 = $conn->prepare("INSERT INTO user (user_id, email, first_name, last_name, username, password) VALUES (?,?,?,?,?,?)");
                    $stmt4->bind_param("ssssss", $user_id, $email, $first_name, $last_name, $username, $password);
                    if ($stmt4->execute()) {
                        $success = "Registration successful! You can now login.";
                    } else {
                        $error = "Error: " . $conn->error;
                    }
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register – LibraryMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #1a237e 0%, #42a5f5 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 30px 0; }
        .reg-card { background: #fff; border-radius: 18px; padding: 40px; box-shadow: 0 20px 60px rgba(0,0,0,0.2); width: 100%; max-width: 500px; }
        .form-control { border-radius: 8px; padding: 9px 13px; }
        .btn-reg { background: #1a237e; color: #fff; border-radius: 8px; padding: 10px; font-weight: 600; border: none; }
        .btn-reg:hover { background: #283593; color:#fff; }
    </style>
</head>
<body>
<div class="reg-card">
    <h4 class="fw-bold mb-1" style="color:#1a237e;"><i class="bi bi-book-half me-2"></i>Staff Registration</h4>
    <p class="text-muted mb-3" style="font-size:0.88rem;">Create a new library staff account</p>

    <?php if ($error): ?>
        <div class="alert alert-danger py-2"><?= $error ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="alert alert-success py-2"><?= $success ?></div>
    <?php endif; ?>

    <form method="POST" novalidate>
        <div class="row g-3">
            <div class="col-12">
                <label class="form-label fw-semibold">User ID <span class="text-muted fw-normal">(e.g. U001)</span></label>
                <input type="text" name="user_id" class="form-control" placeholder="U001" required>
            </div>
            <div class="col-6">
                <label class="form-label fw-semibold">First Name</label>
                <input type="text" name="first_name" class="form-control" required>
            </div>
            <div class="col-6">
                <label class="form-label fw-semibold">Last Name</label>
                <input type="text" name="last_name" class="form-control" required>
            </div>
            <div class="col-12">
                <label class="form-label fw-semibold">Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="col-12">
                <label class="form-label fw-semibold">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="col-12">
                <label class="form-label fw-semibold">Password <span class="text-muted fw-normal">(more than 8 chars)</span></label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="col-12 mt-2">
                <button type="submit" class="btn btn-reg w-100">Register</button>
                <p class="mt-3 text-center text-muted" style="font-size:0.88rem;">Already have an account? <a href="login.php">Login here</a></p>
            </div>
        </div>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

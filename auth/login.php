<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: /library_system/dashboard.php");
    exit();
}
require_once '../config/db.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM user WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && $password === $user['password']) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['first_name'] = $user['first_name'];
        header("Location: /library_system/dashboard.php");
        exit();
    } else {
        $error = "Invalid username or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login – LibraryMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #1a237e 0%, #42a5f5 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .login-card { background: #fff; border-radius: 18px; padding: 45px 40px; box-shadow: 0 20px 60px rgba(0,0,0,0.2); width: 100%; max-width: 420px; }
        .login-card .logo { font-size: 2.5rem; color: #1a237e; }
        .form-control { border-radius: 8px; padding: 10px 14px; }
        .btn-login { background: #1a237e; color: #fff; border-radius: 8px; padding: 11px; font-weight: 600; border: none; }
        .btn-login:hover { background: #283593; color: #fff; }
    </style>
</head>
<body>
<div class="login-card text-center">
    <div class="logo mb-2"><i class="bi bi-book-half"></i></div>
    <h4 class="fw-bold mb-1" style="color:#1a237e;">Library Management System</h4>
    <p class="text-muted mb-4" style="font-size:0.9rem;">Sign in to your account</p>

    <?php if ($error): ?>
        <div class="alert alert-danger py-2"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3 text-start">
            <label class="fw-semibold mb-2">Username</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-person"></i></span>
                <input type="text" name="username" class="form-control" placeholder="Enter username" required>
            </div>
        </div>
        <div class="mb-4 text-start">
            <label class="form-label fw-semibold">Password</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                <input type="password" name="password" class="form-control" placeholder="Enter password" required>
            </div>
        </div>
        <button type="submit" class="btn btn-login w-100">Login</button>
        <p class="mt-3 text-muted" style="font-size:0.88rem;">Don't have an account? <a href="register.php">Register here</a></p>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

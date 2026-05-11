<?php
// Start the session to store user login data
session_start();

// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {

    // Redirect logged-in user to dashboard
    header("Location: /library_system/dashboard.php");

    // Stop further execution
    exit();
}

// Include database connection file
require_once '../config/db.php';

// Variable to store error messages
$error = '';

// Check if the form is submitted using POST method
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Get username from form and remove extra spaces
    $username = trim($_POST['username']);

    // Get password from form and remove extra spaces
    $password = trim($_POST['password']);

    // Prepare SQL query to find user by username
    $stmt = $conn->prepare("SELECT * FROM user WHERE username = ?");

    // Bind username parameter safely to prevent SQL injection
    $stmt->bind_param("s", $username);

    // Execute SQL query
    $stmt->execute();

    // Store query result
    $result = $stmt->get_result();

    // Fetch user data as associative array
    $user = $result->fetch_assoc();

    // Check if user exists and password matches
    if ($user && $password === $user['password']) {

        // Store user ID in session
        $_SESSION['user_id'] = $user['user_id'];

        // Store username in session
        $_SESSION['username'] = $user['username'];

        // Store first name in session
        $_SESSION['first_name'] = $user['first_name'];

        // Redirect user to dashboard after successful login
        header("Location: /library_system/dashboard.php");

        // Stop script execution
        exit();

    } else {

        // Display error if login fails
        $error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Character encoding -->
    <meta charset="UTF-8">

    <!-- Browser tab title -->
    <title>Login – LibraryMS</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        /* Page background and center alignment */
        body {
            background: linear-gradient(135deg, #1a237e 0%, #42a5f5 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Login card design */
        .login-card {
            background: #fff;
            border-radius: 18px;
            padding: 45px 40px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 420px;
        }

        /* Logo styling */
        .login-card .logo {
            font-size: 2.5rem;
            color: #1a237e;
        }

        /* Input field styling */
        .form-control {
            border-radius: 8px;
            padding: 10px 14px;
        }

        /* Login button styling */
        .btn-login {
            background: #1a237e;
            color: #fff;
            border-radius: 8px;
            padding: 11px;
            font-weight: 600;
            border: none;
        }

        /* Login button hover effect */
        .btn-login:hover {
            background: #283593;
            color: #fff;
        }
    </style>
</head>

<body>

<!-- Main login container -->
<div class="login-card text-center">

    <!-- Logo icon -->
    <div class="logo mb-2">
        <i class="bi bi-book-half"></i>
    </div>

    <!-- System title -->
    <h4 class="fw-bold mb-1" style="color:#1a237e;">
        Library Management System
    </h4>

    <!-- Subtitle -->
    <p class="text-muted mb-4" style="font-size:0.9rem;">
        Sign in to your account
    </p>

    <!-- Check if error exists -->
    <?php if ($error): ?>

        <!-- Display error message -->
        <div class="alert alert-danger py-2">
            <?= $error ?>
        </div>

    <?php endif; ?>

    <!-- Login form -->
    <form method="POST">

        <!-- Username field -->
        <div class="mb-3 text-start">

            <!-- Username label -->
            <label class="fw-semibold mb-2">Username</label>

            <div class="input-group">

                <!-- Username icon -->
                <span class="input-group-text">
                    <i class="bi bi-person"></i>
                </span>

                <!-- Username input -->
                <input 
                    type="text"
                    name="username"
                    class="form-control"
                    placeholder="Enter username"
                    required
                >
            </div>
        </div>

        <!-- Password field -->
        <div class="mb-4 text-start">

            <!-- Password label -->
            <label class="form-label fw-semibold">Password</label>

            <div class="input-group">

                <!-- Password icon -->
                <span class="input-group-text">
                    <i class="bi bi-lock"></i>
                </span>

                <!-- Password input -->
                <input 
                    type="password"
                    name="password"
                    class="form-control"
                    placeholder="Enter password"
                    required
                >
            </div>
        </div>

        <!-- Login button -->
        <button type="submit" class="btn btn-login w-100">
            Login
        </button>

        <!-- Registration page link -->
        <p class="mt-3 text-muted" style="font-size:0.88rem;">
            Don't have an account?
            <a href="register.php">Register here</a>
        </p>

    </form>
</div>

<!-- Bootstrap JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
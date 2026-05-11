<?php

// Start session to store user data
session_start();

// Include database connection file
require_once '../config/db.php';

// Variable to store error messages
$error = '';

// Variable to store success messages
$success = '';

// Check if form is submitted using POST method
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Get user ID and remove extra spaces
    $user_id = trim($_POST['user_id']);

    // Get first name and remove extra spaces
    $first_name = trim($_POST['first_name']);

    // Get last name and remove extra spaces
    $last_name = trim($_POST['last_name']);

    // Get username and remove extra spaces
    $username = trim($_POST['username']);

    // Get email and remove extra spaces
    $email = trim($_POST['email']);

    // Get password and remove extra spaces
    $password = trim($_POST['password']);

    // Validate User ID format (must start with U followed by numbers)
    if (!preg_match('/^U\d+$/', $user_id)) {

        // Show error if format is invalid
        $error = "User ID must be in format U001 (U followed by numbers only).";
    }

    // Validate password length
    elseif (strlen($password) <= 8) {

        // Show error if password is too short
        $error = "Password must be more than 8 characters.";
    }

    // Validate email format
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        // Show error if email is invalid
        $error = "Please enter a valid email address.";
    }

    else {

        // Prepare SQL query to check duplicate email
        $stmt = $conn->prepare("SELECT user_id FROM user WHERE email = ?");

        // Bind email parameter safely
        $stmt->bind_param("s", $email);

        // Execute query
        $stmt->execute();

        // Check if email already exists
        if ($stmt->get_result()->num_rows > 0) {

            // Show duplicate email error
            $error = "Email is already registered.";

        } else {

            // Prepare SQL query to check duplicate username
            $stmt2 = $conn->prepare("SELECT user_id FROM user WHERE username = ?");

            // Bind username parameter safely
            $stmt2->bind_param("s", $username);

            // Execute query
            $stmt2->execute();

            // Check if username already exists
            if ($stmt2->get_result()->num_rows > 0) {

                // Show duplicate username error
                $error = "Username is already taken.";

            } else {

                // Prepare SQL query to check duplicate user ID
                $stmt3 = $conn->prepare("SELECT user_id FROM user WHERE user_id = ?");

                // Bind user ID parameter safely
                $stmt3->bind_param("s", $user_id);

                // Execute query
                $stmt3->execute();

                // Check if user ID already exists
                if ($stmt3->get_result()->num_rows > 0) {

                    // Show duplicate user ID error
                    $error = "User ID already exists.";

                } else {

                    // Prepare SQL query to insert new user
                    $stmt4 = $conn->prepare("
                        INSERT INTO user 
                        (user_id, email, first_name, last_name, username, password) 
                        VALUES (?,?,?,?,?,?)
                    ");

                    // Bind all user data safely
                    $stmt4->bind_param(
                        "ssssss",
                        $user_id,
                        $email,
                        $first_name,
                        $last_name,
                        $username,
                        $password
                    );

                    // Execute insert query
                    if ($stmt4->execute()) {

                        // Show success message
                        $success = "Registration successful! You can now login.";

                    } else {

                        // Show database error
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

    <!-- Character encoding -->
    <meta charset="UTF-8">

    <!-- Browser tab title -->
    <title>Register – LibraryMS</title>

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
            padding: 30px 0;
        }

        /* Registration card styling */
        .reg-card {
            background: #fff;
            border-radius: 18px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 500px;
        }

        /* Input field styling */
        .form-control {
            border-radius: 8px;
            padding: 9px 13px;
        }

        /* Register button styling */
        .btn-reg {
            background: #1a237e;
            color: #fff;
            border-radius: 8px;
            padding: 10px;
            font-weight: 600;
            border: none;
        }

        /* Register button hover effect */
        .btn-reg:hover {
            background: #283593;
            color:#fff;
        }

    </style>
</head>

<body>

<!-- Main registration card -->
<div class="reg-card">

    <!-- Registration title -->
    <h4 class="fw-bold mb-1" style="color:#1a237e;">
        <i class="bi bi-book-half me-2"></i>
        Staff Registration
    </h4>

    <!-- Subtitle -->
    <p class="text-muted mb-3" style="font-size:0.88rem;">
        Create a new library staff account
    </p>

    <!-- Display error message -->
    <?php if ($error): ?>
        <div class="alert alert-danger py-2">
            <?= $error ?>
        </div>
    <?php endif; ?>

    <!-- Display success message -->
    <?php if ($success): ?>
        <div class="alert alert-success py-2">
            <?= $success ?>
        </div>
    <?php endif; ?>

    <!-- Registration form -->
    <form method="POST" novalidate>

        <!-- Bootstrap grid -->
        <div class="row g-3">

            <!-- User ID field -->
            <div class="col-12">

                <label class="form-label fw-semibold">
                    User ID
                    <span class="text-muted fw-normal">(e.g. U001)</span>
                </label>

                <input
                    type="text"
                    name="user_id"
                    class="form-control"
                    placeholder="U001"
                    required
                >
            </div>

            <!-- First name field -->
            <div class="col-6">

                <label class="form-label fw-semibold">
                    First Name
                </label>

                <input
                    type="text"
                    name="first_name"
                    class="form-control"
                    required
                >
            </div>

            <!-- Last name field -->
            <div class="col-6">

                <label class="form-label fw-semibold">
                    Last Name
                </label>

                <input
                    type="text"
                    name="last_name"
                    class="form-control"
                    required
                >
            </div>

            <!-- Username field -->
            <div class="col-12">

                <label class="form-label fw-semibold">
                    Username
                </label>

                <input
                    type="text"
                    name="username"
                    class="form-control"
                    required
                >
            </div>

            <!-- Email field -->
            <div class="col-12">

                <label class="form-label fw-semibold">
                    Email
                </label>

                <input
                    type="email"
                    name="email"
                    class="form-control"
                    required
                >
            </div>

            <!-- Password field -->
            <div class="col-12">

                <label class="form-label fw-semibold">
                    Password
                    <span class="text-muted fw-normal">
                        (more than 8 chars)
                    </span>
                </label>

                <input
                    type="password"
                    name="password"
                    class="form-control"
                    required
                >
            </div>

            <!-- Register button -->
            <div class="col-12 mt-2">

                <button type="submit" class="btn btn-reg w-100">
                    Register
                </button>

                <!-- Login page link -->
                <p class="mt-3 text-center text-muted" style="font-size:0.88rem;">
                    Already have an account?
                    <a href="login.php">Login here</a>
                </p>

            </div>

        </div>

    </form>

</div>

<!-- Bootstrap JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
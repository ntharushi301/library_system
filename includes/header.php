<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user_id']) && basename($_SERVER['PHP_SELF']) !== 'login.php' && basename($_SERVER['PHP_SELF']) !== 'register.php') {
    header("Location: /library_system/auth/login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background: #f0f2f5; }
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, #1a237e 0%, #283593 100%);
            width: 260px;
            position: fixed;
            top: 0; left: 0;
            padding-top: 20px;
            z-index: 100;
        }
        .sidebar .brand {
            color: #fff;
            font-size: 1.2rem;
            font-weight: 700;
            padding: 10px 20px 30px;
            border-bottom: 1px solid rgba(255,255,255,0.15);
            display: flex; align-items: center; gap: 10px;
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.75);
            padding: 12px 20px;
            border-radius: 8px;
            margin: 2px 10px;
            display: flex; align-items: center; gap: 10px;
            transition: all 0.2s;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background: rgba(255,255,255,0.15);
            color: #fff;
        }
        .sidebar .nav-label {
            color: rgba(255,255,255,0.4);
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 15px 20px 5px;
        }
        .main-content {
            margin-left: 260px;
            padding: 30px;
        }
        .topbar {
            background: #fff;
            padding: 15px 25px;
            border-radius: 12px;
            margin-bottom: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.06);
        }
        .card { border: none; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.07); }
        .card-header { border-radius: 12px 12px 0 0 !important; border-bottom: none; padding: 18px 24px; }
        .table th { font-size: 0.82rem; text-transform: uppercase; letter-spacing: 0.5px; color: #666; border-bottom: 2px solid #e9ecef; }
        .badge-status-borrowed { background: #fff3cd; color: #856404; }
        .badge-status-available { background: #d1e7dd; color: #0a3622; }
        .btn-action { padding: 4px 10px; font-size: 0.82rem; border-radius: 6px; }
        .alert { border-radius: 10px; }
    </style>
</head>
<body>

<?php 
require_once 'config.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Completed</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #0a1929;
            color: white;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .completion-card {
            text-align: center;
            max-width: 500px;
            width: 90%;
            background-color: rgba(255, 255, 255, 0.1);
            padding: 2rem;
            border-radius: 1rem;
            backdrop-filter: blur(10px);
        }
        .btn-logout {
            background-color: #1976d2;
            border: none;
            padding: 0.75rem 2rem;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }
        .btn-logout:hover {
            background-color: #1565c0;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="completion-card">
    <img src="logo.webp" class="img-fluid d-block m-auto" alt="logo" width="100">
        <h1 class="mb-4">Quiz Attempted!</h1>
        <p class="mb-4">You are doing Great! Results will be Announced Soon</p>
        <p class="mb-4">Now just Logout and Go Home.</p>
        <a href="logout.php" class="btn btn-primary btn-logout">Logout</a>
    </div>
</body>
</html>

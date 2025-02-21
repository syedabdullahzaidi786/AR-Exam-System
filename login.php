<?php require_once 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Portal - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body">
                    <img src="logo.webp" class="img-fluid d-block m-auto" alt="Admin" width="100">
                        <h3 class="text-center mb-4"> Student Login</h3>
                        
                        <?php
                        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                            $email = $_POST['email'];
                            $password = $_POST['password'];
                            
                            // Direct password comparison
                            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND password = ?");
                            $stmt->execute([$email, $password]);
                            $user = $stmt->fetch();
                            
                            if ($user) {
                                $_SESSION['user_id'] = $user['id'];
                                $_SESSION['user_name'] = $user['name'];
                                
                                if($user['is_admin'] == 1) {
                                    header("Location: admin/index.php");
                                } else {
                                    header("Location: dashboard.php");
                                }
                                exit();
                            } else {
                                echo "<div class='alert alert-danger'>Invalid email or password</div>";
                            }
                        }
                        ?>

                        <form method="POST" action="">
                            <div class="mb-3">
                                <label class="form-label">Student ID</label>
                                <input type="text" name="email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Login</button>
                        </form>
                        <p class="text-center mt-3">
                            Don't have an account? <a href="signup.php">Sign up here</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
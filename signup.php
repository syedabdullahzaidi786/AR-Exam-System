<?php require_once 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Portal - Sign Up</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body">
                        <img src="logo.webp" class="img-fluid d-block m-auto" alt="Admin" width="100">
                        <h3 class="text-center mb-4">Quiz Sign Up</h3>
                        
                        <?php
                        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                            $name = $_POST['name'];
                            $student_rn = $_POST['student_rn']; // Ensure we're using the correct field here
                            $password = $_POST['password']; // Store password as plain text

                            // Debugging: Check if student_rn is being passed correctly
                            echo "<p>Student RN: " . htmlspecialchars($student_rn) . "</p>"; // Show passed student_rn

                            // Check if student_rn is empty or not
                            if(empty($student_rn)) {
                                echo "<div class='alert alert-danger'>Error: Student RN cannot be empty</div>";
                            } else {
                                try {
                                    // Prepare the SQL statement with the correct column name
                                    $stmt = $pdo->prepare("INSERT INTO users (name, student_rn, password, is_admin) VALUES (?, ?, ?, FALSE)");
                                    if ($stmt->execute([$name, $student_rn, $password])) {
                                        echo "<div class='alert alert-success'>Registration successful! <a href='login.php'>Login here</a></div>";
                                    }
                                } catch (PDOException $e) {
                                    // Check for unique constraint violation on student_rn
                                    if ($e->getCode() == 23000) {
                                        echo "<div class='alert alert-danger'>Error: Student ID already exists</div>";
                                    } else {
                                        echo "<div class='alert alert-danger'>Error: " . $e->getMessage() . "</div>";
                                    }
                                }
                            }
                        }
                        ?>

                        <form method="POST" action="">
                            <div class="mb-3">
                                <label class="form-label">Full Name</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Student RN</label>
                                <input type="text" name="student_rn" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Sign Up</button>
                        </form>
                        <p class="text-center mt-3">
                            Already have an account? <a href="login.php">Login here</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

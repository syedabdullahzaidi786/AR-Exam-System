<?php
require_once '../config.php';

if(!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Handle quiz deletion
if(isset($_POST['delete_quiz'])) {
    $quiz_id = $_POST['quiz_id'];
    $stmt = $pdo->prepare("DELETE FROM quizzes WHERE id = ?");
    $stmt->execute([$quiz_id]);
    header("Location: quizzes.php");
    exit();
}

// Handle quiz addition/update
if(isset($_POST['save_quiz'])) {
    $title = $_POST['title'];
    $course_code = $_POST['course_code'];
    $duration = $_POST['duration'];
    $total_questions = $_POST['total_questions'];
    $exam_key = $_POST['exam_key'];
    $status = $_POST['status'];
    
    if(isset($_POST['quiz_id'])) {
        // Update existing quiz
        $stmt = $pdo->prepare("UPDATE quizzes SET title = ?, course_code = ?, duration = ?, 
                              total_questions = ?, exam_key = ?, status = ? WHERE id = ?");
        $stmt->execute([$title, $course_code, $duration, $total_questions, $exam_key, 
                       $status, $_POST['quiz_id']]);
    } else {
        // Add new quiz
        $stmt = $pdo->prepare("INSERT INTO quizzes (title, course_code, duration, 
                              total_questions, exam_key, status) 
                              VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $course_code, $duration, $total_questions, $exam_key, $status]);
    }
    header("Location: quizzes.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
    <style>
            .sidebar {
                background-color: #1a237e;
                min-height: 100vh;
                color: white;
            }
            .sidebar .nav-link {
                color: rgba(255,255,255,.8);
                padding: 1rem 1.5rem;
                font-size: 1rem;
            }
            .sidebar .nav-link:hover {
                color: white;
                background-color: rgba(255,255,255,.1);
            }
            .sidebar .nav-link.active {
                color: white;
                background-color: rgba(255,255,255,.2);
            }
            .main-content {
                background-color: #f5f5f5;
                min-height: 100vh;
            }
            .search-box {
                max-width: 400px;
                margin-bottom: 2rem;
            }
            .results-table {
                background: white;
                border-radius: 8px;
                box-shadow: 0 2px 4px rgba(0,0,0,.1);
            }
            .score-cell {
                font-weight: 500;
            }
            .percentage-badge {
                font-size: 0.875rem;
                padding: 0.25rem 0.5rem;
            }
            .high-score {
                background-color: #4caf50;
                color: white;
            }
            .medium-score {
                background-color: #ff9800;
                color: white;
            }
            .low-score {
                background-color: #f44336;
                color: white;
            }
        </style>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Quizzes - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar (same as index.php) -->
            <!-- Sidebar -->
                        <div class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                            <div class="position-sticky pt-3">
                                <ul class="nav flex-column">
                                    <li class="nav-item">
                                        <a class="nav-link" href="index.php">Dashboard</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="quizzes.php">Manage Quizzes</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="questions.php">Manage Questions</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="users.php">Users</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link active" href="results.php">Results</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="logout.php">Logout</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
            
            <!-- Main content -->
            <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Manage Quizzes</h1>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#quizModal">
                        Add New Quiz
                    </button>
                </div>

                <!-- Quizzes Table -->
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Course Code</th>
                                <th>Duration</th>
                                <th>Questions</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $stmt = $pdo->query("SELECT * FROM quizzes ORDER BY id DESC");
                            while ($quiz = $stmt->fetch()) {
                                echo "<tr>";
                                echo "<td>{$quiz['title']}</td>";
                                echo "<td>{$quiz['course_code']}</td>";
                                echo "<td>{$quiz['duration']} mins</td>";
                                echo "<td>{$quiz['total_questions']}</td>";
                                echo "<td>{$quiz['status']}</td>";
                                echo "<td>
                                        <button class='btn btn-sm btn-primary edit-quiz' 
                                                data-quiz='" . htmlspecialchars(json_encode($quiz)) . "'>
                                            Edit
                                        </button>
                                        <form method='POST' class='d-inline' 
                                              onsubmit='return confirm(\"Are you sure?\");'>
                                            <input type='hidden' name='quiz_id' value='{$quiz['id']}'>
                                            <button type='submit' name='delete_quiz' 
                                                    class='btn btn-sm btn-danger'>Delete</button>
                                        </form>
                                      </td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Quiz Modal -->
    <div class="modal fade" id="quizModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add/Edit Quiz</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="quizForm" method="POST">
                        <input type="hidden" name="quiz_id" id="quiz_id">
                        <div class="mb-3">
                            <label class="form-label">Title</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Course Code</label>
                            <input type="text" name="course_code" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Duration (minutes)</label>
                            <input type="number" name="duration" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Total Questions</label>
                            <input type="number" name="total_questions" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Exam Key</label>
                            <input type="text" name="exam_key" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-control" required>
                                <option value="open">Open</option>
                                <option value="closed">Closed</option>
                            </select>
                        </div>
                        <button type="submit" name="save_quiz" class="btn btn-primary">Save Quiz</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Handle edit quiz button clicks
        document.querySelectorAll('.edit-quiz').forEach(button => {
            button.addEventListener('click', () => {
                const quiz = JSON.parse(button.dataset.quiz);
                document.getElementById('quiz_id').value = quiz.id;
                document.querySelector('[name="title"]').value = quiz.title;
                document.querySelector('[name="course_code"]').value = quiz.course_code;
                document.querySelector('[name="duration"]').value = quiz.duration;
                document.querySelector('[name="total_questions"]').value = quiz.total_questions;
                document.querySelector('[name="exam_key"]').value = quiz.exam_key;
                document.querySelector('[name="status"]').value = quiz.status;
                new bootstrap.Modal(document.getElementById('quizModal')).show();
            });
        });
    </script>
</body>
</html>
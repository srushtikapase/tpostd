<?php
session_start();
include('../includes/db.php');
include('../includes/functions.php');

// Check if the student is logged in
if (!isset($_SESSION['student'])) {
    header("Location: login.php");
    exit();
}

// Get student username from session
$student_username = $_SESSION['student'];

// Retrieve student ID
$sql_student = "SELECT id FROM students WHERE username='$student_username'";
$result_student = $conn->query($sql_student);
$student = $result_student->fetch_assoc();
$student_id = $student['id'];

// Query to get the history of the student's applications
$sql = "SELECT 
            j.title AS job_title,
            j.description AS job_description,
            j.domain AS job_domain,
            a.status AS application_status
        FROM applications a
        JOIN jobs j ON a.job_id = j.id
        WHERE a.student_id = '$student_id'";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application History</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
        }
        .navbar {
            margin-bottom: 30px;
        }
        .navbar a {
            color: white;
            padding: 14px 20px;
            text-decoration: none;
            font-size: 18px;
        }
        .navbar a.active {
            background-color: #0056b3;
        }
        .container {
            margin-top: 50px;
        }
        .card {
            border: none;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background-color: #007bff;
            color: white;
            font-size: 1.5em;
            border-bottom: 2px solid #0056b3;
        }
        .btn-logout {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <a class="navbar-brand" href="#">Student Portal</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="dashboard.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="sign-in.php">Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="student_history.php">Application History</a>
                </li>
            </ul>
            <a class="btn btn-danger btn-logout" href="logout.php">Logout</a>
        </div>
    </nav>

    <div class="container">
        <div class="card">
            <div class="card-header">
                My Application History
            </div>
            <div class="card-body">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Job Title</th>
                            <th>Job Description</th>
                            <th>Job Domain</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['job_title']); ?></td>
                                    <td><?php echo htmlspecialchars($row['job_description']); ?></td>
                                    <td><?php echo htmlspecialchars($row['job_domain']); ?></td>
                                    <td><?php echo htmlspecialchars($row['application_status']); ?></td>
                                </tr>
                                <?php
                            }
                        } else {
                            echo "<tr><td colspan='4' class='text-center'>No applications found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

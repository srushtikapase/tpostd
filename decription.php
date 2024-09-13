<?php
session_start();
include('../includes/db.php');
include('../includes/functions.php');

// Check if the student is logged in
if (!isset($_SESSION['student'])) {
    header("Location: login.php");
    exit();
}

// Retrieve student username from session
$student_username = $_SESSION['student'];

// Get the student's branch from the students table
$sql_student = "SELECT branch FROM students WHERE username='$student_username'";
$result_student = $conn->query($sql_student);
$student = $result_student->fetch_assoc();
$student_branch = $student['branch'];

// Retrieve job listings based on student's branch
$sql_jobs = "SELECT * FROM jobs WHERE domain='$student_branch'";
$result_jobs = $conn->query($sql_jobs);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Jobs</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }
        .navbar {
            margin-bottom: 20px;
        }
        .table {
            margin-top: 20px;
        }
        .table th, .table td {
            text-align: center;
        }
        .btn-apply {
            margin: 0;
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
                    <a class="nav-link" href="history.php">Application History</a>
                </li>
            </ul>
            <a class="btn btn-danger my-2 my-sm-0" href="logout.php">Logout</a>
        </div>
    </nav>

    <div class="container">
        <h2 class="my-4">Job Listings</h2>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Job Title</th>
                    <th>Description</th>
                    <th>Skills</th>
                    <th>Domain</th>
                    <th>Position</th>
                    <th>Experience</th>
                    <th>Salary</th>
                    <th>Openings</th>
                    <th>Eligibility</th>
                    <th>Shift</th>
                    <th>Schedule</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result_jobs->num_rows > 0) {
                    while($row = $result_jobs->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                        <td><?php echo htmlspecialchars($row['description']); ?></td>
                        <td><?php echo htmlspecialchars($row['skills']); ?></td>
                        <td><?php echo htmlspecialchars($row['domain']); ?></td>
                        <td><?php echo htmlspecialchars($row['position']); ?></td>
                        <td><?php echo htmlspecialchars($row['experience']); ?></td>
                        <td><?php echo htmlspecialchars($row['salary']); ?></td>
                        <td><?php echo htmlspecialchars($row['openings']); ?></td>
                        <td><?php echo htmlspecialchars($row['eligibility']); ?></td>
                        <td><?php echo htmlspecialchars($row['shift']); ?></td>
                        <td><?php echo htmlspecialchars($row['schedule']); ?></td>
                        <td>
                            <a href="internship_form.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm">Apply</a>
                        </td>
                    </tr>
                <?php } 
                } else {
                    echo "<tr><td colspan='12' class='text-center'>No jobs available for your branch.</td></tr>";
                } ?>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

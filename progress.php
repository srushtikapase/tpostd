<?php
session_start();
include('../includes/db.php');
include('../includes/functions.php');

// Check if the student is logged in
if (!isset($_SESSION['student'])) {
    header("Location: login.php");
    exit();
}

// Get the student ID using the username stored in the session
$student_username = $_SESSION['student'];
$sql_student = "SELECT id FROM students WHERE username='$student_username'";
$result_student = $conn->query($sql_student);
$student = $result_student->fetch_assoc();
$student_id = $student['id'];

// Query to get job application details
$result = $conn->query("SELECT applications.*, jobs.title AS job_title FROM applications 
                        JOIN jobs ON applications.job_id = jobs.id 
                        WHERE student_id='$student_id'");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Progress</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        .navbar {
            background-color: #007bff;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .navbar a {
            float: left;
            display: block;
            color: #ffffff;
            text-align: center;
            padding: 16px 20px;
            text-decoration: none;
            font-size: 16px;
            font-weight: 500;
        }
        .navbar a:hover {
            background-color: #0056b3;
        }
        .navbar a.active {
            background-color: #0056b3;
            color: #ffffff;
        }
        .container {
            width: 90%;
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h2 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
            color: #333;
        }
        th {
            background-color: #007bff;
            color: #ffffff;
            font-weight: 600;
        }
        td {
            background-color: #ffffff;
        }
        .progress-bar-container {
            width: 100%;
            background-color: #e9ecef;
            border-radius: 4px;
            overflow: hidden;
        }
        .progress-bar {
            height: 24px;
            background-color: #28a745;
            color: #ffffff;
            text-align: center;
            line-height: 24px;
            border-radius: 4px;
            transition: width 0.4s ease;
            font-weight: 500;
        }
        .icon {
            font-size: 16px;
            color: #007bff;
            margin-right: 8px;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="dashboard.php" class="active">Dashboard</a>
        <a href="performance.php">Performance Tracking</a>
        <a href="profile.html">Settings</a>
        <a href="logout.php" style="float: right;">Logout <i class="fas fa-sign-out-alt"></i></a>
    </div>
    <div class="container">
        <h2><i class="fas fa-briefcase icon"></i> My Job Application Progress</h2>
        <table>
            <thead>
                <tr>
                    <th>Job Title</th>
                    <th>Status</th>
                    <th>Progress</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['job_title']); ?></td>
                    <td><?php echo htmlspecialchars($row['status']); ?></td>
                    <td>
                        <div class="progress-bar-container">
                            <div class="progress-bar" style="width: <?php echo ($row['status'] == 'applied' ? '25%' : ($row['status'] == 'mock interview' ? '50%' : ($row['status'] == 'technical interview' ? '75%' : '100%'))); ?>">
                                <?php echo htmlspecialchars($row['status']); ?>
                            </div>
                        </div>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>

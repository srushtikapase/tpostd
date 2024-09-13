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
$sql_student = "SELECT id, name, email, phone, branch FROM students WHERE username='$student_username'";
$result_student = $conn->query($sql_student);
$student = $result_student->fetch_assoc();
$student_id = $student['id'];

// Step 1: Insert records into performance_tracking if they don't exist
$insert_sql = "INSERT INTO performance_tracking (application_id, aptitude, technical_interview, offer_letter, placed, rejected)
               SELECT a.id, 'pending', 'pending', 'pending', 'pending', 'no'
               FROM applications a
               LEFT JOIN performance_tracking pt ON a.id = pt.application_id
               WHERE a.status = 'accepted' AND pt.application_id IS NULL AND a.student_id = '$student_id'";
$conn->query($insert_sql);

// Step 2: Query to get performance tracking data along with job details for the logged-in student
$sql = "SELECT 
            pt.application_id, 
            j.title AS job_title, 
            j.description AS job_description, 
            pt.aptitude, 
            pt.technical_interview, 
            pt.offer_letter, 
            pt.placed, 
            pt.rejected
        FROM performance_tracking pt 
        JOIN applications a ON pt.application_id = a.id 
        JOIN jobs j ON a.job_id = j.id 
        WHERE a.status = 'accepted' AND a.student_id = '$student_id'";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Performance Tracking</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        .navbar {
            background-color: #333;
            overflow: hidden;
        }
        .navbar a {
            float: left;
            display: block;
            color: #f2f2f2;
            text-align: center;
            padding: 14px 20px;
            text-decoration: none;
        }
        .navbar a:hover {
            background-color: #ddd;
            color: black;
        }
        .container {
            width: 90%;
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .student-info {
            margin-bottom: 20px;
            padding: 10px;
            border-bottom: 2px solid #ddd;
        }
        .student-info h2 {
            margin: 0;
            font-size: 24px;
            color: #333;
        }
        .student-info p {
            margin: 5px 0;
            font-size: 16px;
            color: #555;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        .progress-bar {
            display: flex;
            align-items: center;
            margin: 5px 0;
        }
        .milestone {
            flex: 1;
            text-align: center;
            position: relative;
            padding: 5px;
        }
        .milestone.active .milestone-icon {
            background-color: #4CAF50;
            color: white;
        }
        .milestone.rejected .milestone-icon {
            background-color: #ff0000;
            color: white;
        }
        .milestone-icon {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: #e0e0e0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 14px;
            color: #333;
            margin: 0 auto;
            transition: background-color 0.3s;
        }
        .milestone-title {
            font-size: 12px;
            margin-top: 5px;
        }
        .milestone-desc {
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="dashboard.php">Dashboard</a>
        <a href="progress.php">Performance Tracking</a>
        <a href="logout.php" style="float: right;">Logout</a>
    </div>
    <div class="container">
        <div class="student-info">
            <h2><?php echo htmlspecialchars($student['name']); ?></h2>
            <p><strong>Branch:</strong> <?php echo htmlspecialchars($student['branch']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($student['email']); ?></p>
            <p><strong>Phone:</strong> <?php echo htmlspecialchars($student['phone']); ?></p>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Job Title</th>
                    <th>Job Description</th>
                    <th>Progress</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <?php
                        $totalStages = 5;
                        $completedStages = 0;
                        if ($row['aptitude'] == 'completed') $completedStages++;
                        if ($row['technical_interview'] == 'completed') $completedStages++;
                        if ($row['offer_letter'] == 'completed') $completedStages++;
                        if ($row['placed'] == 'completed') $completedStages++;
                        if ($row['rejected'] == 'yes') $completedStages = 0; // Reset if rejected
                        $progressPercentage = ($completedStages / $totalStages) * 100;
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['job_title']); ?></td>
                            <td><?php echo htmlspecialchars($row['job_description']); ?></td>
                            <td>
                                <div class="progress-bar">
                                    <div class="milestone <?php echo $row['aptitude'] == 'completed' ? 'active' : ($row['rejected'] == 'yes' ? 'rejected' : ''); ?>">
                                        <div class="milestone-icon">1</div>
                                        <div class="milestone-title">Aptitude</div>
                                        <div class="milestone-desc">Aptitude Test</div>
                                    </div>
                                    <div class="milestone <?php echo $row['technical_interview'] == 'completed' ? 'active' : ($row['rejected'] == 'yes' ? 'rejected' : ''); ?>">
                                        <div class="milestone-icon">2</div>
                                        <div class="milestone-title">Technical Interview</div>
                                        <div class="milestone-desc">Technical Interview</div>
                                    </div>
                                    <div class="milestone <?php echo $row['offer_letter'] == 'completed' ? 'active' : ($row['rejected'] == 'yes' ? 'rejected' : ''); ?>">
                                        <div class="milestone-icon">3</div>
                                        <div class="milestone-title">Offer Letter</div>
                                        <div class="milestone-desc">Offer Letter Sent</div>
                                    </div>
                                    <div class="milestone <?php echo $row['placed'] == 'completed' ? 'active' : ($row['rejected'] == 'yes' ? 'rejected' : ''); ?>">
                                        <div class="milestone-icon">4</div>
                                        <div class="milestone-title">Placed</div>
                                        <div class="milestone-desc">Placed in Company</div>
                                    </div>
                                    <div class="milestone <?php echo $row['rejected'] == 'yes' ? 'rejected' : ''; ?>">
                                        <div class="milestone-icon">5</div>
                                        <div class="milestone-title">Rejected</div>
                                        <div class="milestone-desc">Rejected from Process</div>
                                    </div>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-line">
                                        <div class="progress-line-fill" style="width: <?php echo $progressPercentage; ?>%;"></div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3">No records found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

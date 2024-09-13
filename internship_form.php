<?php
session_start();
include('../includes/db.php');
include('../includes/functions.php');

if (!isset($_SESSION['student'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $job_id = $_GET['id'];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_username = $_SESSION['student'];
    $sql_student = "SELECT id FROM students WHERE username='$student_username'";
    $result_student = $conn->query($sql_student);
    $student = $result_student->fetch_assoc();
    $student_id = $student['id'];

    $name = $_POST['name'];
    $year = $_POST['year'];
    $branch = $_POST['branch'];
    $domain = $_POST['domain'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $experience = $_POST['experience'];

    // Handling file uploads
    $resume = $_FILES['resume']['name'];
    $photo = $_FILES['photo']['name'];
    $target_dir = "../uploads/";

    // Define the full paths for the files
    $target_file_resume = $target_dir . basename($_FILES["resume"]["name"]);
    $target_file_photo = $target_dir . basename($_FILES["photo"]["name"]);

    // Move the uploaded files to the target directory
    move_uploaded_file($_FILES["resume"]["tmp_name"], $target_file_resume);
    move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file_photo);

    // Insert into the database
    $sql = "INSERT INTO applications (student_id, job_id, name, year, branch, domain, address, phone, email, experience, resume, photo) 
            VALUES ('$student_id', '$job_id', '$name', '$year', '$branch', '$domain', '$address', '$phone', '$email', '$experience', '$target_file_resume', '$target_file_photo')";

    if ($conn->query($sql) === TRUE) {
        echo "Application submitted successfully";
        header("Location: view_applications.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$job_result = $conn->query("SELECT * FROM jobs WHERE id='$job_id'");
$job = $job_result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply for Job</title>
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
            padding: 14px 20px;
            text-decoration: none;
            font-size: 16px;
            font-weight: 500;
        }
        .navbar a:hover {
            background-color: #0056b3;
        }
        .container {
            width: 90%;
            max-width: 900px;
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
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }
        input[type="text"], input[type="date"], input[type="email"], textarea, input[type="file"] {
            margin-bottom: 16px;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            width: 100%;
            box-sizing: border-box;
        }
        textarea {
            height: 100px;
            resize: vertical;
        }
        button {
            background-color: #007bff;
            color: #ffffff;
            border: none;
            padding: 12px 20px;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #0056b3;
        }
        .message {
            margin-top: 20px;
            padding: 10px;
            border-radius: 4px;
            font-size: 16px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="dashboard.php">Dashboard</a>
        <a href="apply.php" class="active">Apply for Job</a>
        <a href="../pages/tables.php">performance analysis</a>
        <a href="profile.html">Settings</a>
        <a href="logout.php" style="float: right;">Logout <i class="fas fa-sign-out-alt"></i></a>
    </div>
    <div class="container">
        <h2><i class="fas fa-briefcase"></i> Apply for Job: <?php echo htmlspecialchars($job['title']); ?></h2>

        <?php if (isset($message)): ?>
            <div class="message <?php echo strpos($message, 'Error') !== false ? 'error' : 'success'; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" action="">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="year">Year:</label>
            <input type="text" id="year" name="year" required>

            <label for="branch">Branch:</label>
            <input type="text" id="branch" name="branch" required>

            <label for="domain">Domain of Work:</label>
            <input type="text" id="domain" name="domain" required>

            <label for="address">Address:</label>
            <input type="text" id="address" name="address" required>

            <label for="phone">Phone No:</label>
            <input type="text" id="phone" name="phone" required>

            <label for="email">Email (Personal):</label>
            <input type="email" id="email" name="email" required>

            <label for="experience">Short Description about Experience:</label>
            <textarea id="experience" name="experience" required></textarea>

            <label for="resume">Upload Resume (PDF):</label>
            <input type="file" id="resume" name="resume" accept="application/pdf" required>

            <label for="photo">Upload Photo (JPEG, PNG):</label>
            <input type="file" id="photo" name="photo" accept="image/jpeg, image/png" required>

            <button type="submit">Apply</button>
        </form>
    </div>
</body>
</html>

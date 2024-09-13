<?php
session_start();
include('../includes/db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $prn = $_POST['prn'];
    $roll_no = $_POST['roll_no'];
    $name = $_POST['name'];
    $year = $_POST['year'];
    $branch = $_POST['branch'];
    $division = $_POST['division'];
    $batch = $_POST['batch'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "INSERT INTO students (prn, roll_no, name, year, branch, division, batch, address, email, phone, username, password) 
            VALUES ('$prn', '$roll_no', '$name', '$year', '$branch', '$division', '$batch', '$address', '$email', '$phone', '$username', '$password')";

    if ($conn->query($sql) === TRUE) {
        // Store the user's data in session
        $_SESSION['user'] = [
            'prn' => $prn,
            'roll_no' => $roll_no,
            'name' => $name,
            'year' => $year,
            'branch' => $branch,
            'division' => $division,
            'batch' => $batch,
            'address' => $address,
            'email' => $email,
            'phone' => $phone,
            'username' => $username
        ];
        $message = "Registration successful!";
        header("Location: profile.php");
        exit();
    } else {
        $message = "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration</title>
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
            max-width: 600px;
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
        input[type="text"], input[type="email"], input[type="password"] {
            margin-bottom: 16px;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            width: 100%;
            box-sizing: border-box;
        }
        input[type="text"]::placeholder, input[type="email"]::placeholder, input[type="password"]::placeholder {
            color: #aaa;
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
        <a href="dashboard.php">Home</a>
        <a href="sign-in.php">Login</a>
        <a href="register.php" class="active">Register</a>
    </div>
    <div class="container">
        <h2><i class="fas fa-user-plus"></i> Student Registration</h2>

        <?php if (isset($message)): ?>
            <div class="message <?php echo strpos($message, 'Error') !== false ? 'error' : 'success'; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <label for="prn">PRN:</label>
            <input type="text" id="prn" name="prn" placeholder="Enter PRN" required>

            <label for="roll_no">Roll No:</label>
            <input type="text" id="roll_no" name="roll_no" placeholder="Enter Roll Number" required>

            <label for="name">Name:</label>
            <input type="text" id="name" name="name" placeholder="Enter Full Name" required>

            <label for="year">Year:</label>
            <input type="text" id="year" name="year" placeholder="Enter Year of Study" required>

            <label for="branch">Branch:</label>
            <input type="text" id="branch" name="branch" placeholder="Enter Branch" required>

            <label for="division">Division:</label>
            <input type="text" id="division" name="division" placeholder="Enter Division" required>

            <label for="batch">Batch:</label>
            <input type="text" id="batch" name="batch" placeholder="Enter Batch" required>

            <label for="address">Address:</label>
            <input type="text" id="address" name="address" placeholder="Enter Address" required>

            <label for="email">Email (College email):</label>
            <input type="email" id="email" name="email" placeholder="Enter College Email" required>

            <label for="phone">Phone No:</label>
            <input type="text" id="phone" name="phone" placeholder="Enter Phone Number" required>

            <label for="username">Username:</label>
            <input type="text" id="username" name="username" placeholder="Choose Username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="Choose Password" required>

            <button type="submit">Register</button>
        </form>
    </div>
</body>
</html>

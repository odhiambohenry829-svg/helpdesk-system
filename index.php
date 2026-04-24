<?php
session_start();
include 'db.php';

//  LOGIN PROTECTION


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
//  HANDLE FORM SUBMISSION


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name       = trim($_POST['name'] ?? '');
    $department = trim($_POST['department'] ?? '');
    $issue      = trim($_POST['issue'] ?? '');

    if (empty($name) || empty($department) || empty($issue)) {
        echo "<script>alert('All fields are required!'); window.history.back();</script>";
        exit();
    }

    $stmt = $conn->prepare("INSERT INTO issues (name, department, issue) VALUES (?, ?, ?)");
    
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("sss", $name, $department, $issue);

    if ($stmt->execute()) {
        echo "<script>
                alert('Issue submitted successfully!');
                window.location.href = 'index.php';
              </script>";
        exit();
    } else {
        echo "<script>alert('Error: " . addslashes($stmt->error) . "'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Submit Issue - Judiciary ICT Helpdesk</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 45%;
            margin: 40px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        h2 { 
            text-align: center; 
            color: #2c3e50; 
        }
        .welcome {
            text-align: right;
            margin-bottom: 20px;
            font-size: 15px;
            color: #34495e;
        }
        .logout {
            color: #e74c3c;
            text-decoration: none;
        }
        .logout:hover { text-decoration: underline; }

        label { font-weight: bold; }
        input, textarea {
            width: 100%;
            padding: 10px;
            margin: 8px 0 15px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #2c3e50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover { background-color: #34495e; }
        .header { text-align: center; margin-bottom: 20px; }
        .header small { color: gray; }
    </style>
</head>
<body>

<div class="container">
    <div class="welcome">
        Welcome, <?= htmlspecialchars($_SESSION['full_name']) ?> 
        | <a href="logout.php" class="logout">Logout</a>
    </div>

    <div class="header">
        <h2>ICT Helpdesk System</h2>
        <small>Judiciary ICT Support Portal</small>
    </div>

    <form action="" method="POST">
        <label>Full Name</label>
        <input type="text" name="name" placeholder="Enter your name" required>

        <label>Department</label>
        <input type="text" name="department" placeholder="e.g. Finance, Registry" required>

        <label>Describe Issue</label>
        <textarea name="issue" rows="5" placeholder="Explain the problem clearly..." required></textarea>

        <button type="submit">Submit Issue</button>
    </form>
</div>

</body>
</html>
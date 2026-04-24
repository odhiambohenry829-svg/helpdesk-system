<?php
session_start();
include 'db.php';

 // If already logged in, go directly to index.php
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = "Username and password are required!";
    } else {
        $stmt = $conn->prepare("SELECT id, username, password, full_name, role FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            
            if (password_verify($password, $user['password'])) {
                session_regenerate_id(true);
                
                $_SESSION['user_id']   = $user['id'];
                $_SESSION['username']  = $user['username'];
                $_SESSION['full_name'] = $user['full_name'];
                $_SESSION['role']      = $user['role'];

                header("Location: index.php");
                exit();
            } else {
                $error = "Incorrect password!";
            }
        } else {
            $error = "Username not found!";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Judiciary ICT Helpdesk</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .login-container {
            width: 380px;
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        h2 { 
            text-align: center; 
            color: #2c3e50; 
            margin-bottom: 8px; 
        }
        .subtitle { 
            text-align: center; 
            color: gray; 
            margin-bottom: 30px; 
        }
        label { 
            font-weight: bold; 
            display: block; 
            margin: 15px 0 5px; 
        }
        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;
        }
        button {
            width: 100%;
            padding: 14px;
            background-color: #2c3e50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 25px;
        }
        button:hover { 
            background-color: #34495e; 
        }
        .error { 
            color: red; 
            text-align: center; 
            margin: 15px 0; 
            font-weight: bold;
        }
        .note {
            text-align: center;
            font-size: 14px;
            color: #555;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2>Judiciary ICT Helpdesk</h2>
    <p class="subtitle">Logbook System - Staff Login</p>

    <?php if ($error): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <label>Username</label>
        <input type="text" name="username" required autofocus>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit">Login</button>
    </form>

    <div class="note">
        Default Login:<br>
        <strong>Username:</strong> admin<br>
        <strong>Password:</strong> password
    </div>
</div>

</body>
</html>
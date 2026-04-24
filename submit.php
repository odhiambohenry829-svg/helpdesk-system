<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST['name']);
    $department = trim($_POST['department']);
    $issue = trim($_POST['issue']);

    if (empty($name) || empty($department) || empty($issue)) {
        echo "<script>
                alert('All fields are required!');
                window.history.back();
              </script>";
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
                window.location.href='index.php';
              </script>";
    } else {
        die("Execute failed: " . $stmt->error);
    }

    $stmt->close();
    $conn->close();
}
?>
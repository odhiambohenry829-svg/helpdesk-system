<?php
include 'db.php';

// CHECK if ID exists
if (!isset($_GET['id'])) {
    echo "❌ No issue selected!";
    exit();
}

$id = $_GET['id'];

// Fetch issue
$result = $conn->query("SELECT * FROM issues WHERE id=$id");

if ($result->num_rows == 0) {
    echo "❌ Issue not found!";
    exit();
}

$row = $result->fetch_assoc();
?>
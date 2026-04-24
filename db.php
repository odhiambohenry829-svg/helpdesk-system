<?php
$conn = new mysqli("localhost", "root", "", "helpdesk2.1");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
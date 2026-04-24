<?php
include 'db.php';

$id = $_GET['id'];

$sql = "DELETE FROM issues WHERE id=$id";

if ($conn->query($sql) === TRUE) {
    header("Location: view_issue.php");
} else {
    echo "Error deleting record";
}
?>
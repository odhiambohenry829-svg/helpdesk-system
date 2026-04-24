<?php
include 'db.php';
session_start();

$id = $_GET['id'];
$row = $conn->query("SELECT * FROM issues WHERE id=$id")->fetch_assoc();

if(isset($_POST['update'])){
$status = $_POST['status'];
$conn->query("UPDATE issues SET status='$status' WHERE id=$id");
header("Location: view_issue.php");
}
?>

<form method="POST" class="container mt-5">
<div class="card p-4">
<h3>Edit Issue</h3>

<select name="status" class="form-control">
<option>Pending</option>
<option>Resolved</option>
</select>

<br>
<button class="btn btn-primary" name="update">Update</button>
</div>
</form>
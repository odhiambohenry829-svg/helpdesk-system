<?php
include 'db.php';

$id = $_GET['id'];

$result = $conn->query("SELECT * FROM issues WHERE id=$id");
$row = $result->fetch_assoc();

if (isset($_POST['update'])) {
    $status = $_POST['status'];

    $conn->query("UPDATE issues SET status='$status' WHERE id=$id");

    header("Location: view_issue.php");
}
?>

<form method="POST">
    <h3>Edit Issue Status</h3>

    <label>Status:</label>
    <select name="status">
        <option value="Pending">Pending</option>
        <option value="Resolved">Resolved</option>
    </select>

    <br><br>
    <button type="submit" name="update">Update</button>
</form>
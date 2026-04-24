<?php
include 'db.php';

$q = $_GET['q'] ?? '';

$result = $conn->query("SELECT * FROM issues 
WHERE name LIKE '%$q%' 
OR issue LIKE '%$q%' 
ORDER BY id DESC");

echo "<table class='table table-striped'>";
echo "<tr><th>ID</th><th>Name</th><th>Issue</th></tr>";

while($row = $result->fetch_assoc()){
echo "<tr>
<td>{$row['id']}</td>
<td>{$row['name']}</td>
<td>{$row['issue']}</td>
</tr>";
}

echo "</table>";
?>
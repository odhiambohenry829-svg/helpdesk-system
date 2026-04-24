<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'] ?? 'staff';

// stats
$total = $conn->query("SELECT COUNT(*) as t FROM issues")->fetch_assoc()['t'];
$pending = $conn->query("SELECT COUNT(*) as t FROM issues WHERE status='Pending'")->fetch_assoc()['t'];
$resolved = $conn->query("SELECT COUNT(*) as t FROM issues WHERE status='Resolved'")->fetch_assoc()['t'];

function timeAgo($t){
    if(!$t) return "No date";
    $d = time() - strtotime($t);
    if($d < 60) return "$d sec ago";
    $d=floor($d/60);
    if($d<60) return "$d min ago";
    $d=floor($d/60);
    if($d<24) return "$d hr ago";
    return floor($d/24)." days ago";
}

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Use prepared statement to prevent SQL injection
$stmt = $conn->prepare("SELECT * FROM issues 
WHERE name LIKE CONCAT('%', ?, '%') 
OR department LIKE CONCAT('%', ?, '%') 
OR issue LIKE CONCAT('%', ?, '%') 
ORDER BY id DESC");

if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("sss", $search, $search, $search);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
<title>Helpdesk</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
body{background:#f5f6fa}
.card{border-radius:15px}
</style>
</head>

<body>

<div class="container mt-3">

<h3 class="text-center">ICT Helpdesk</h3>

<a href="logout.php" class="btn btn-danger btn-sm float-end">Logout</a>

<!-- DASHBOARD -->
<div class="row text-center mt-3">

<div class="col-md-4">
<div class="card bg-primary text-white p-3">
Total<br><h3><?php echo $total; ?></h3>
</div>
</div>

<div class="col-md-4">
<div class="card bg-warning text-white p-3">
Pending<br><h3><?php echo $pending; ?></h3>
</div>
</div>

<div class="col-md-4">
<div class="card bg-success text-white p-3">
Resolved<br><h3><?php echo $resolved; ?></h3>
</div>
</div>

</div>

<!-- CHART -->
<canvas id="chart" class="mt-4"></canvas>

<!-- SEARCH -->
<input type="text" id="search" class="form-control mt-4" placeholder="Search issues...">

<!-- TABLE -->
<div id="tableArea">

<table class="table table-striped mt-3">
<tr>
<th>ID</th>
<th>Name</th>
<th>Issue</th>
<th>Status</th>
<th>Time</th>
<th>Actions</th>
</tr>

<?php while($row = $result->fetch_assoc()): ?>
<tr>
<td><?php echo $row['id']; ?></td>
<td><?php echo $row['name']; ?></td>
<td><?php echo $row['issue']; ?></td>

<td>
<?php if($row['status']=="Pending"): ?>
<span class="badge bg-warning">Pending</span>
<?php else: ?>
<span class="badge bg-success">Resolved</span>
<?php endif; ?>
</td>

<td><?php echo timeAgo($row['created_at']); ?></td>

<td>
<?php if($role=="admin"): ?>
<a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
<a href="delete.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger">Delete</a>
<?php else: ?>
<span class="text-muted">No access</span>
<?php endif; ?>
</td>

</tr>
<?php endwhile; ?>

</table>

</div>

</div>

<script>
// CHART
new Chart(document.getElementById('chart'),{
type:'bar',
data:{
labels:['Pending','Resolved'],
datasets:[{
data:[<?php echo $pending; ?>,<?php echo $resolved; ?>],
backgroundColor:['orange','green']
}]
}
});

// AJAX SEARCH
document.getElementById("search").addEventListener("keyup", function(){
let q = this.value;

fetch("search.php?q="+q)
.then(res=>res.text())
.then(data=>{
document.getElementById("tableArea").innerHTML = data;
});
});

// LIVE REFRESH (5 sec)
setInterval(()=>{
fetch(location.href)
.then(r=>r.text())
.then(html=>{
let doc = new DOMParser().parseFromString(html,'text/html');
document.getElementById("tableArea").innerHTML =
doc.getElementById("tableArea").innerHTML;
});
},5000);

</script>

</body>
</html>
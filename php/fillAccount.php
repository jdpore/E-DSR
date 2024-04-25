<?php
include('db_conn.php');

$accountName = $_GET['accountName'];
$sql = "SELECT * FROM encoded WHERE accName LIKE '%$accountName%'";
$result = mysqli_query($conn, $sql);

// Process the result and send it as JSON
$accounts = mysqli_fetch_all($result, MYSQLI_ASSOC);
echo json_encode($accounts);
?>
<?php
include('db_conn.php');
include('autoRedirect.php');

// Initialize variables to avoid undefined index warnings
$accountExecutive = '';
$accountName = '';
$callDate = '';
$currentMonthStart = date('Y-m-01');
$currentMonthEnd = date('Y-m-t');

// Set the callDateStart and callDateEnd to the current month
$callDateStart = $currentMonthStart;
$callDateEnd = $currentMonthEnd;

if (isset($_POST['accountExecutiveSearch'])) {
    $accountExecutive = $_POST['accountExecutiveSearch'];
}

if (isset($_POST['accountName'])) {
    $accountName = $_POST['accountName'];
}

if (isset($_POST['callDate'])) {
    $callDate = $_POST['callDate'];
}

if (isset($_POST['callDateStart'])) {
    $callDateStart = $_POST['callDateStart'];
}

if (isset($_POST['callDateEnd'])) {
    $callDateEnd = $_POST['callDateEnd'];
}

// Construct the WHERE clause based on the form input
$whereConditions = [];

if ($category == 'Manager') {
    $whereConditions[] = "dept LIKE '%$dept%'";
    if (!empty($accountExecutive)) {
        $whereConditions[] = "accExec LIKE '%$accountExecutive%'";
    }
}

if ($category == 'Admin' || $category == 'VP') {
    if (!empty($accountExecutive)) {
        $whereConditions[] = "accExec LIKE '%$accountExecutive%'";
    }
}

if ($category == 'User') {
    $whereConditions[] = "accExec LIKE '%$name%'";
}

if (!empty($accountName)) {
    $whereConditions[] = "accName LIKE '%$accountName%'";
}

if (!empty($callDate)) {
    $whereConditions[] = "callDate = '$callDate'";
}

// Assuming 'callDateStart' and 'callDateEnd' are always provided together
if (!empty($callDateStart) && !empty($callDateEnd)) {
    $whereConditions[] = "callDate BETWEEN '$callDateStart' AND '$callDateEnd'";
}

// Combine conditions with AND
$condition = implode(" AND ", $whereConditions);

// Add the condition to the SQL query
$sql = "SELECT * FROM encoded";
if (!empty($condition)) {
    $sql .= " WHERE $condition";
}
$sql .= " ORDER BY callDate DESC";

// Execute the query
$accountResult = mysqli_query($conn, $sql);
$accountCount = mysqli_num_rows($accountResult);
?>
<?php
include ('db_conn.php');
include ('autoRedirect.php');
include ('dates.php');

$whereConditions = [];
$datesArray = [];
$deptPerformance = '';
$callDateStartPerformance = '';
$callDateEndPerformance = '';

if (isset($_POST['deptPerformance'])) {
    $deptPerformance = $_POST['deptPerformance'];
}

if (isset($_POST['callDateStartPerformance'])) {
    $callDateStartPerformance = $_POST['callDateStartPerformance'];
}

if (isset($_POST['callDateEndPerformance'])) {
    $callDateEndPerformance = $_POST['callDateEndPerformance'];
}

if ($category == 'Manager') {
    $whereConditionsPerformance[] = "dept LIKE '%$dept%'";
}

if ($category == 'User') {
    $whereConditionsPerformance[] = "accExec LIKE '%$name%'";
}

if (!empty($deptPerformance)) {
    $whereConditionsPerformance[] = "dept LIKE '%$deptPerformance%'";
}

if (!empty($callDateStartPerformance) && !empty($callDateEndPerformance)) {
    $whereConditionsPerformance[] = "callDate BETWEEN '$callDateStartPerformance' AND '$callDateEndPerformance'";
    $dateCondition = "callDate BETWEEN '$callDateStartPerformance' AND '$callDateEndPerformance'";
}

if (empty($callDateStartPerformance) || empty($callDateEndPerformance)) {
    $whereConditionsPerformance[] = "callDate BETWEEN '$min_date' AND '$max'";
    $dateCondition = "callDate BETWEEN '$min_date' AND '$max'";
}

$dateSql = "SELECT DISTINCT callDate FROM encoded WHERE $dateCondition";
$dateQuery = mysqli_query($conn, $dateSql);
if ($dateQuery) {
    // Fetch data from the result set
    while ($row = mysqli_fetch_assoc($dateQuery)) {
        $date = $row['callDate'];
        // Do something with the date, for example, add it to an array
        $datesArray[] = $date;
    }
} else {
    // Handle query error
    echo "Error: " . mysqli_error($conn);
}

$conditionPerformance = implode(" AND ", $whereConditionsPerformance);
$performanceSql = "SELECT
            accExec AS AccountExecutive,
            " . implode(', ', array_map(function ($date) {
    return "SUM(CASE WHEN callDate = '$date' THEN 1 ELSE 0 END) AS '$date'";
}, $datesArray)) . "
        FROM
            encoded
        WHERE
            $conditionPerformance
        GROUP BY
            accExec;";

$accountResultPerformance = mysqli_query($conn, $performanceSql);

$rows = []; // Initialize an array to store all rows
?>
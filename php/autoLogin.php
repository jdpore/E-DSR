<?php
include('db_conn.php');

if (isset($_COOKIE['edsr-user'])) {
    include_once('graphData.php');
    $coockieUser = $_COOKIE['edsr-user'];
    $sql1 = "SELECT * FROM users WHERE user_id = '$coockieUser'";
    $result1 = mysqli_query($conn, $sql1);

    while ($qResult = mysqli_fetch_array($result1)) {
        $id = $qResult['id'];
        $name = $qResult['name'];
        $username = $qResult['user_id'];
        $password = $qResult['password'];
        $category = $qResult['category'];
        $stat = $qResult['stat'];
    }
    header("Location: /e-dsr/pages/welcome_page.php");
}
?>
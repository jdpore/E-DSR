<?php
include ('db_conn.php');
include ('autoRedirect.php');

$sql = "SELECT * FROM categories";
$categoryResult = mysqli_query($conn, $sql);

$sql = "SELECT * FROM categories WHERE field = 'Account Category'";
$accountCategoryResult = mysqli_query($conn, $sql);

$sql = "SELECT * FROM categories WHERE field = 'Segment'";
$segmentResult = mysqli_query($conn, $sql);

$sql = "SELECT * FROM categories WHERE field = 'Industry'";
$industryResult = mysqli_query($conn, $sql);

$sql = "SELECT * FROM categories WHERE field = 'Source of Account'";
$accountSourceResult = mysqli_query($conn, $sql);

$sql = "SELECT * FROM categories WHERE field = 'Contract Type'";
$contractTypeResult = mysqli_query($conn, $sql);

$sql = "SELECT * FROM categories WHERE field = 'Terms of Payment'";
$paymentTermsResult = mysqli_query($conn, $sql);

$sql = "SELECT * FROM categories WHERE field = 'Nature of Call'";
$callNatureResult = mysqli_query($conn, $sql);

$sql = "SELECT * FROM categories WHERE field = 'Account Status'";
$accountstatusResult = mysqli_query($conn, $sql);

?>
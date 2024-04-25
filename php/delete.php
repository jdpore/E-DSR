<?php
include ('db_conn.php');

if (isset($_GET['deleteUserId'])) {
  $id = $_GET['deleteUserId'];
  $sql = "DELETE FROM users WHERE id = '$id'";
  $result = mysqli_query($conn, $sql);
  echo '<script>
                alert("User Deleted.");
                window.location.href = "/e-dsr/pages/user.php";
              </script>';
  exit();
}

if (isset($_GET['deleteAccountId'])) {
  $id = $_GET['deleteAccountId'];
  $sql = "DELETE FROM encoded WHERE id = '$id'";
  $result = mysqli_query($conn, $sql);
  echo '<script>
                alert("Account Deleted.");
                window.location.href = "/e-dsr/pages/search.php";
              </script>';
  exit();
}

if (isset($_GET['category_id'])) {
  $id = $_GET['category_id'];
  $sql = "DELETE FROM categories WHERE id = '$id'";
  $result = mysqli_query($conn, $sql);
  echo '<script>
                alert("Category Deleted.");
                window.location.href = "/e-dsr/pages/customize.php";
              </script>';
  exit();
}


?>
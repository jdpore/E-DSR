<?php
include('db_conn.php');
include('userList.php');

if (isset($_POST['addUser'])) {
    $id = $userCount + 1;
    $name = $_POST['name'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $department = $_POST['department'];
    $category = $_POST['category'];
    $logAt = "CURRENT_TIMESTAMP";
    $passwordChange = $_POST['passwordChange'];
    $status = "New";
    $branch = $_POST['branch'];

    $sql = "INSERT INTO users (id, name, user_id, 
            password, dept, category, log_at, pass_change, 
            stat, branch)
            VALUES ('$id', '$name', '$username', '$hashedPassword', 
            '$department', '$category', '$logAt', '$passwordChange', 
            '$status', '$branch')";
    $addUserResult = mysqli_query($conn, $sql);

    if ($addUserResult) {
        echo '<script>
                alert("User Added.");
                window.location.href = "/e-dsr/pages/user.php";
              </script>';
        exit();
    } else {
        // Display an error message or log the error
        echo "Error: " . mysqli_error($conn);
    }
}
?>
<?php
include('../php/autoRedirect.php');
include('../php/userList.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="/e-dsr/css/sidebar.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>E-DSR - Welcome Page</title>
</head>

<body>
    <nav class="sticky-top navbar navbar-expand-lg navbar-dark bg px-5" style="min-height: 12vh">
        <div class="container-fluid p-0">
            <a class="navbar-brand" href="#">
                <h3>E-DSR</h3>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto"> <!-- Modified class here -->
                    <li class="nav-item">
                        <a class="nav-link text-light">
                            <?php echo $name ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-light" href="../php/logout.php?logoutid=<?php echo $name; ?>"
                            onclick="return confirm('Logout Account?')">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <div class="container-fluid">
        <div class="row">
        <?php include ('sidebar.php'); ?>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 p-0 overflow-auto" style="height: 88vh">
                <div
                    class="sticky-top d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center px-5 pt-3 pb-3 mb-3 border-bottom bg-white">
                    <h1 class="h3">Users</h1>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUser">
                        Add User
                    </button>
                </div>
                <div class="d-flex align-items-center px-5 pt-3 pb-2 mb-3 overflow-auto">
                    <div class="modal fade" id="addUser" tabindex="-1" aria-labelledby="addUserLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addUserLabel">Add User</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form class="row g-3" action="../php/addUser.php" onsubmit="return isvalid()"
                                        method="POST">
                                        <div class="col-md-6">
                                            <label for="name" class="form-label">Name</label>
                                            <input type="text" class="form-control" id="name" name="name" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="branch" class="form-label">Branch</label>
                                            <select id="branch" name="branch" class="form-select" required>
                                                <option value="N/A" selected disabled>Choose...</option>
                                                <option value="Main Office">Main Office</option>
                                                <option value="Angeles">Angeles</option>
                                                <option value="Batangas">Batangas</option>
                                                <option value="Cabanatuan">Cabanatuan</option>
                                                <option value="La Union">La Union</option>
                                                <option value="Naga">Naga</option>
                                                <option value="Subic">Subic</option>
                                                <option value="Bacolod">Bacolod</option>
                                                <option value="Cebu">Cebu</option>
                                                <option value="Dumaguete">Dumaguete</option>
                                                <option value="Iloilo">Iloilo</option>
                                                <option value="Tacloban">Tacloban</option>
                                                <option value="Cagayan De Oro">Cagayan De Oro</option>
                                                <option value="Davao">Davao</option>
                                                <option value="Gensan">Gensan</option>
                                                <option value="Zamboanga">Zamboanga</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="username" class="form-label">Username</label>
                                            <input type="text" class="form-control" id="username" name="username"
                                                required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="password" class="form-label">Password</label>
                                            <div class="input-group">
                                                <input type="password" class="form-control" id="password"
                                                    name="password" required>
                                                <button class="btn btn-outline-secondary" type="button"
                                                    id="showPasswordBtn" onmousedown="showPassword()"
                                                    onmouseup="hidePassword()">
                                                    <i class="fa-solid fa-eye"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="department" class="form-label">Department</label>
                                            <select id="department" name="department" class="form-select" required>
                                                <option value="N/A" selected disabled>Choose...</option>
                                                <option value="OP Sales - PP">OP Sales - PP</option>
                                                <option value="OP Sales - MFP/RISO">OP Sales - MFP/RISO</option>
                                                <option value="OP Consumables ">OP Consumables</option>
                                                <option value="CSD">CSD</option>
                                                <option value="Furniture">Furniture</option>
                                                <option value="UIC">UIC</option>
                                                <option value="MIS">MIS</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="category" class="form-label">Category</label>
                                            <select id="category" name="category" class="form-select" required>
                                                <option value="N/A" selected disabled>Choose...</option>
                                                <option value="User">User</option>
                                                <option value="Manager">Manager</option>
                                                <option value="VP">VP</option>
                                                <option value="Admin">Admin</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="passwordChange" class="form-label">Change Password?</label>
                                            <select id="passwordChange" name="passwordChange" class="form-select"
                                                required>
                                                <option value="N/A" selected disabled>Choose...</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                        <div class="col-12">
                                            <button name="addUser" id="addUser" type="submit"
                                                class="btn btn-primary">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editUserLabel">Add User</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form class="row g-3" action="../php/editUser.php" onsubmit="return isvalid()"
                                        method="POST">
                                        <div class="col-md-6 d-none">
                                            <label for="editId" class="form-label">ID</label>
                                            <input type="number" class="form-control" id="editId" name="editId"
                                                required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="editName" class="form-label">Name</label>
                                            <input type="text" class="form-control" id="editName" name="editName"
                                                required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="editBranch" class="form-label">Branch</label>
                                            <select id="editBranch" name="editBranch" class="form-select" required>
                                                <option value="N/A" selected disabled>Choose...</option>
                                                <option value="Main Office">Main Office</option>
                                                <option value="Angeles">Angeles</option>
                                                <option value="Batangas">Batangas</option>
                                                <option value="Cabanatuan">Cabanatuan</option>
                                                <option value="La Union">La Union</option>
                                                <option value="Naga">Naga</option>
                                                <option value="Subic">Subic</option>
                                                <option value="Bacolod">Bacolod</option>
                                                <option value="Cebu">Cebu</option>
                                                <option value="Dumaguete">Dumaguete</option>
                                                <option value="Iloilo">Iloilo</option>
                                                <option value="Tacloban">Tacloban</option>
                                                <option value="Cagayan De Oro">Cagayan De Oro</option>
                                                <option value="Davao">Davao</option>
                                                <option value="Gensan">Gensan</option>
                                                <option value="Zamboanga">Zamboanga</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="editUsername" class="form-label">Username</label>
                                            <input type="text" class="form-control" id="editUsername"
                                                name="editUsername" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="editPassword" class="form-label">Password</label>
                                            <div class="input-group">
                                                <input type="password" class="form-control" id="editPassword"
                                                    name="editPassword">
                                                <button class="btn btn-outline-secondary" type="button"
                                                    id="showEditPasswordBtn" onmousedown="showPassword()"
                                                    onmouseup="hidePassword()">
                                                    <i class="fa-solid fa-eye"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="editDepartment" class="form-label">Department</label>
                                            <select id="editDepartment" name="editDepartment" class="form-select"
                                                required>
                                                <option value="N/A" selected disabled>Choose...</option>
                                                <option value="OP Sales - PP">OP Sales - PP</option>
                                                <option value="OP Sales - MFP/RISO">OP Sales - MFP/RISO</option>
                                                <option value="OP Consumables ">OP Consumables</option>
                                                <option value="CSD">CSD</option>
                                                <option value="Furniture">Furniture</option>
                                                <option value="UIC">UIC</option>
                                                <option value="MIS">MIS</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="editCategory" class="form-label">Category</label>
                                            <select id="editCategory" name="editCategory" class="form-select" required>
                                                <option value="N/A" selected disabled>Choose...</option>
                                                <option value="User">User</option>
                                                <option value="Manager">Manager</option>
                                                <option value="VP">VP</option>
                                                <option value="Admin">Admin</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="editPasswordChange" class="form-label">Change Password?</label>
                                            <select id="editPasswordChange" name="editPasswordChange"
                                                class="form-select" required>
                                                <option value="N/A" selected disabled>Choose...</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                        <div class="col-12">
                                            <button name="editUser" id="editUser" type="submit"
                                                class="btn btn-primary">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">Action</th>
                                <th scope="col">Name</th>
                                <th scope="col">Username</th>
                                <th scope="col">Department</th>
                                <th scope="col">Category</th>
                                <th scope="col">Last Log in</th>
                                <th scope="col">Change Password</th>
                                <th scope="col">Status</th>
                                <th scope="col">Branch</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($userList as $row) {
                                $id = $row['id'];
                                $status = $row['stat']; ?>
                                <tr>
                                    <th scope="row">
                                        <button class="btn btn-success btn-lg" type="button">
                                            <a onclick="editUser(<?php echo $id; ?>)" style='color: white'><i
                                                    class='fa fa-pen'></i></a>
                                        </button>
                                        <button class="btn btn-danger btn-lg">
                                            <?php
                                            echo "
                                                    <a onclick=\"javascript:return confirm('Confirm Delete?')\" style='color: white' href='../php/delete.php?deleteUserId=$id'><i class='fa fa-trash'></i></a>
                                                 ";
                                            ?>
                                        </button>
                                        <button onclick="updateStatus('<?php echo $id; ?>', '<?php echo $status; ?>')"
                                            class="btn <?php echo $status == 'online' ? 'btn-primary' : 'btn-secondary'; ?> btn-lg"
                                            type="button">
                                            <?php echo $status == 'online' ? '<i class="fa-solid fa-check"></i>' : '<i class="fa-solid fa-x"></i>'; ?>
                                        </button>
                                    </th>
                                    <td>
                                        <?php echo $row['name']; ?>
                                    </td>
                                    <td>
                                        <?php echo $row['user_id']; ?>
                                    </td>
                                    <td>
                                        <?php echo $row['dept']; ?>
                                    </td>
                                    <td>
                                        <?php echo $row['category']; ?>
                                    </td>
                                    <td>
                                        <?php echo $row['log_at']; ?>
                                    </td>
                                    <td>
                                        <?php echo $row['pass_change']; ?>
                                    </td>
                                    <td>
                                        <?php echo $row['stat']; ?>
                                    </td>
                                    <td>
                                        <?php echo $row['branch']; ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script type="text/javascript" src="../js/reveal.js"></script>
    <script type="text/javascript" src="../js/edit.js"></script>
    <script>
        function updateStatus(id, status) {
            console.log(id, status);
            var newStatus = status === 'online' ? 'offline' : 'online'; // Toggle status
            $.ajax({
                url: "../php/update.php",
                type: "POST",
                data: { id: id, status: newStatus }, // Send both name and status
                success: function (response) {
                    location.reload();
                },
                error: function (xhr, id, error) {
                }
            });
        }
    </script>
</body>

</html>
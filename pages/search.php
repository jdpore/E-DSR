<?php
include('../php/autoRedirect.php');
include('../php/accountList.php');
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
    <link rel="stylesheet" href="/e-dsr/css/table.css">
    <title>E-DSR - Welcome Page</title>
    <style>
        .account-list {
            background-color: white;
            display: none;
            list-style-type: none;
            margin: 0;
            padding: 0;
            height: 300px;
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
            overflow: auto;
        }

        .account-list li {
            padding: 8px;
            cursor: pointer;
        }

        .account-list li:hover {
            background-color: #f0f0f0;
        }

        .sticky {
            position: sticky;
            top: 0;
            background-color: white;
            z-index: 1;
            /* Set z-index to a lower value */
        }

        .dropdown-menu {
            z-index: 2;
            /* Set z-index to a higher value */
            position: absolute;
            /* or position: relative; depending on your layout */
        }
    </style>
    <script>
        var category = "<?php echo $category; ?>";
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.5/xlsx.full.min.js"></script>
    <script src="../js/editEncode.js" defer></script>
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
            <main class="col-md-9 ms-sm-auto col-lg-10 p-0" style="height: 88vh">
                <div
                    class="sticky-top bg-white d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center px-5 pt-3 pb-3 mb-3 border-bottom">
                    <h1 class="h3">Search</h1>
                    <div>
                        <button onclick="exportToExcel()" type="button" class="btn">
                            Download
                        </button>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#searchAccount">
                            Search Accounts
                        </button>
                    </div>
                </div>
                <div class="bg-white align-items-center px-5 pb-2 mb-3 overflow-auto" style="height: 75vh">
                    <div class="modal fade" id="searchAccount" tabindex="-1" aria-labelledby="searchAccountLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-md">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="searchAccountLabel">Search Accounts</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form class="row g-3" action="" onsubmit="return isvalid()" method="POST">
                                        <div class="col-md-12">
                                            <label id="accountExecutiveLabelSearch" for="accountExecutiveSearch"
                                                class="form-label">Account Executive</label>
                                            <input type="text" class="form-control" id="accountExecutiveSearch"
                                                name="accountExecutiveSearch">

                                            <label for="accountName" class="form-label">Account Name</label>
                                            <input type="text" class="form-control" id="accountName" name="accountName"
                                                oninput="searchAccounts(this.value)">
                                            <ul id="accountList" class="col-md-12 account-list"></ul>

                                            <label for="callDate" class="form-label">Date of Call</label>
                                            <input type="date" class="form-control" id="callDate" name="callDate">

                                            <label for="callDateStart" class="form-label">Date of Call From</label>
                                            <input type="date" class="form-control" id="callDateStart"
                                                name="callDateStart">

                                            <label for="callDateEnd" class="form-label">Date of Call To</label>
                                            <input type="date" class="form-control" id="callDateEnd" name="callDateEnd">

                                            <button name="searchAccount" id="searchAccount" type="submit"
                                                class="btn btn-primary mt-3">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <table id="largeTable" class="table table-striped">
                        <thead class="bg-white sticky-top sticky">
                            <tr>
                                <th id="action" scope="col">Action</th>
                                <th scope="col">Account Executive</th>
                                <th scope="col">Account Name</th>
                                <th scope="col">Call Date</th>
                                <th scope="col">End User</th>
                                <th scope="col">Address</th>
                                <th scope="col">Area</th>
                                <th scope="col">Account Category</th>
                                <th scope="col">Segment</th>
                                <th scope="col">Industry</th>
                                <th scope="col">Account Source</th>
                                <th scope="col">Contact Person</th>
                                <th scope="col">Designation</th>
                                <th scope="col">Contact Number</th>
                                <th scope="col">Email Address</th>
                                <th scope="col">Decision Maker</th>
                                <th scope="col">DM Contact Number</th>
                                <th scope="col">DM Designation</th>
                                <th scope="col">Existing System</th>
                                <th scope="col">Contract Type</th>
                                <th scope="col">Contact Start Date</th>
                                <th scope="col">Contact End Date</th>
                                <th scope="col">Proposed System</th>
                                <th scope="col">Proposed Price</th>
                                <th scope="col">Payment Terms</th>
                                <th scope="col">Call Nature</th>
                                <th scope="col">Account Status</th>
                                <th scope="col">Follow Up Action</th>
                                <th scope="col">What Transpired</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // ... (Previous PHP code remains unchanged)
                            
                            foreach ($accountResult as $row) {
                                $id = $row['id'];
                                ?>
                                <tr>
                                    <th scope="row" class="adminButton" data-id="<?php echo $id; ?>">
                                        <button class="btn btn-success btn-lg editButton" type="button"
                                            onclick="redirectToPHPPage(<?php echo $id ?>)">
                                            <a style="color: white"><i class="fa fa-pen"></i></a>
                                        </button>
                                        <button class="btn btn-danger btn-lg deleteButton">
                                            <?php
                                            echo "<a onclick=\"javascript:return confirm('Confirm Delete?')\" style='color: white' href='../php/delete.php?deleteAccountId=$id'><i class='fa fa-trash'></i></a>";
                                            ?>
                                        </button>
                                    </th>
                                    <td>
                                        <?php echo $row['accExec']; ?>
                                    </td>
                                    <td>
                                        <?php echo $row['accName']; ?>
                                    </td>
                                    <td>
                                        <?php echo $row['callDate']; ?>
                                    </td>
                                    <td>
                                        <?php echo $row['endUser']; ?>
                                    </td>
                                    <td>
                                        <?php echo $row['address']; ?>
                                    </td>
                                    <td>
                                        <?php echo $row['area']; ?>
                                    </td>
                                    <td>
                                        <?php echo $row['accCat']; ?>
                                    </td>
                                    <td>
                                        <?php echo $row['segment']; ?>
                                    </td>
                                    <td>
                                        <?php echo $row['industry']; ?>
                                    </td>
                                    <td>
                                        <?php echo $row['accSource']; ?>
                                    </td>
                                    <td>
                                        <?php echo $row['contactPerson']; ?>
                                    </td>
                                    <td>
                                        <?php echo $row['designation']; ?>
                                    </td>
                                    <td>
                                        <?php echo $row['contactNumber']; ?>
                                    </td>
                                    <td>
                                        <?php echo $row['email']; ?>
                                    </td>
                                    <td>
                                        <?php echo $row['decisionMaker']; ?>
                                    </td>
                                    <td>
                                        <?php echo $row['dmNumber']; ?>
                                    </td>
                                    <td>
                                        <?php echo $row['dmDesignation']; ?>
                                    </td>
                                    <td>
                                        <?php echo $row['existingSystem']; ?>
                                    </td>
                                    <td>
                                        <?php echo $row['contactType']; ?>
                                    </td>
                                    <td>
                                        <?php echo $row['startContractDate']; ?>
                                    </td>
                                    <td>
                                        <?php echo $row['endContractDate']; ?>
                                    </td>
                                    <td>
                                        <?php echo $row['proposedSystem']; ?>
                                    </td>
                                    <td>
                                        <?php echo $row['proposedPrice']; ?>
                                    </td>
                                    <td>
                                        <?php echo $row['paymentTerms']; ?>
                                    </td>
                                    <td>
                                        <?php echo $row['callNature']; ?>
                                    </td>
                                    <td>
                                        <?php echo $row['accStatus']; ?>
                                    </td>
                                    <td>
                                        <?php echo $row['actionFollow']; ?>
                                    </td>
                                    <td>
                                        <?php echo $row['whatTranspired']; ?>
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
    <script>
        function redirectToPHPPage(id) {
            window.location.href = '/e-dsr/php/accountSelect.php?id=' + id;
        }
    </script>
    <script>
        var category = "<?php echo $category; ?>";
    </script>
    <script src="../js/hideElement.js"></script>
    <script type="text/javascript" src="../js/autoFill.js"></script>
    <script type="text/javascript" src="../js/download.js"></script>
</body>

</html>
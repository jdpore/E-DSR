<?php
include ('../php/uploadFile.php');
include ('../php/autoRedirect.php');
include ('../php/dates.php');
include ('../php/userList.php');
include ('../php/categoryList.php');
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
    <title>E-DSR - Welcome Page</title>
    <style>
        .dropdown-container {
            position: relative;
        }

        .account-list {
            background-color: white;
            display: none;
            position: absolute;
            list-style-type: none;
            margin: 0;
            padding: 0;
            height: 300px;
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
            z-index: 1;
            overflow: auto;
        }

        .account-list li {
            padding: 8px;
            cursor: pointer;
        }

        .account-list li:hover {
            background-color: #f0f0f0;
        }
    </style>
    <script src="../js/hideElement.js" defer></script>
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
                    <h1 class="h3">Encode</h1>
                    <button id="uploadButton" type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#uploadExcel">
                        Upload Excel
                    </button>
                </div>
                <div
                    class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center px-5 pt-3 pb-2 mb-3">
                    <div class="modal fade" id="uploadExcel" tabindex="-1" aria-labelledby="uploadExcelLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="uploadExcelLabel">Upload Excel</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form enctype="multipart/form-data" id="upload" name="upload" action=""
                                        onsubmit="return isvalid()" method="POST">
                                        <label for="uploadFile" class="form-label">
                                        </label>
                                        <input name="uploadFile" class="form-control" type="file" id="uploadFile">
                                        <button type="submit" name="uploadFileButton"
                                            class="btn btn-primary mt-3">Upload</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <form class="row g-3" action="../php/encodeAccount.php" onsubmit="return isvalid()" method="POST">
                        <div class="col-md-4">
                            <label for="accountExecutive" class="form-label">Account Executive</label>
                            <div id="accountExecutiveContainer">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="accountName" class="form-label">Account Name</label>
                            <input type="text" class="form-control" id="accountName" name="accountName"
                                oninput="searchAccounts(this.value)" required>
                            <ul id="accountList" class="col-md-3 account-list"></ul>
                        </div>
                        <div class="col-4">
                            <label for="callDate" class="form-label">Date of Call</label>
                            <input type="date" class="form-control" id="callDate" name="callDate"
                                min="<?php echo $min ?>" max="<?php echo $max ?>" required>
                        </div>
                        <div class="col-4">
                            <label for="endUser" class="form-label">End User</label>
                            <input type="text" class="form-control" id="endUser" name="endUser" required>
                        </div>
                        <div class="col-4">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" class="form-control" id="address" name="address" required>
                        </div>
                        <div class="col-4">
                            <label for="area" class="form-label">Area</label>
                            <input type="text" class="form-control" id="area" name="area" required>
                        </div>
                        <div class="col-md-4">
                            <label for="accountCategory" class="form-label">Account Category</label>
                            <select id="accountCategory" name="accountCategory" class="form-select" required>
                                <option value="N/A" selected disabled>Choose...</option>
                                <?php foreach ($accountCategoryResult as $accountCategoryRow) { ?>
                                    <option value="<?php echo $accountCategoryRow['category_name'] ?>">
                                        <?php echo $accountCategoryRow['category_name'] ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="segment" class="form-label">Segment</label>
                            <select id="segment" name="segment" class="form-select" required>
                                <option value="N/A" selected disabled>Choose...</option>
                                <?php foreach ($segmentResult as $segmentRow) { ?>
                                    <option value="<?php echo $segmentRow['category_name'] ?>">
                                        <?php echo $segmentRow['category_name'] ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="industry" class="form-label">Industry</label>
                            <select id="industry" name="industry" class="form-select" required>
                                <option value="N/A" selected disabled>Choose...</option>
                                <?php foreach ($industryResult as $industryRow) { ?>
                                        <option value="<?php echo $industryRow['category_name'] ?>">
                                            <?php echo $industryRow['category_name'] ?>
                                        </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="accountSource" class="form-label">Source of Account</label>
                            <select id="accountSource" name="accountSource" class="form-select" required>
                                <option value="N/A" selected disabled>Choose...</option>
                                <?php foreach ($accountSourceResult as $accountSourceRow) { ?>
                                    <option value="<?php echo $accountSourceRow['category_name'] ?>">
                                        <?php echo $accountSourceRow['category_name'] ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="contactPerson" class="form-label">Contact Person</label>
                            <input type="text" class="form-control" id="contactPerson" name="contactPerson" required>
                        </div>
                        <div class="col-md-4">
                            <label for="designation" class="form-label">Designation</label>
                            <input type="text" class="form-control" id="designation" name="designation" required>
                        </div>
                        <div class="col-md-4">
                            <label for="contactNumber" class="form-label">Contact Number</label>
                            <input type="text" class="form-control" id="contactNumber" name="contactNumber" required>
                        </div>
                        <div class="col-md-4">
                            <label for="emailAddress" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="emailAddress" name="emailAddress" required>
                        </div>
                        <div class="col-md-4">
                            <label for="decisionMaker" class="form-label">Decision Maker</label>
                            <input type="text" class="form-control" id="decisionMaker" name="decisionMaker" required>
                        </div>
                        <div class="col-md-4">
                            <label for="dmContactNumber" class="form-label">Decision Maker: Contact Number</label>
                            <input type="tel" class="form-control" id="dmContactNumber" name="dmContactNumber" required>
                        </div>
                        <div class="col-md-4">
                            <label for="dmDesignation" class="form-label">Decision Maker: Designation</label>
                            <input type="text" class="form-control" id="dmDesignation" name="dmDesignation" required>
                        </div>
                        <div class="col-md-4">
                            <label for="existingSystem" class="form-label">Existing System</label>
                            <input type="text" class="form-control" id="existingSystem" name="existingSystem" required>
                        </div>
                        <div class="col-md-4">
                            <label for="contractType" class="form-label">Contract Type</label>
                            <select id="contractType" name="contractType" class="form-select" required>
                                <option value="N/A" selected disabled>Choose...</option>
                                <?php foreach ($contractTypeResult as $contractTypeRow) { ?>
                                    <option value="<?php echo $contractTypeRow['category_name'] ?>">
                                        <?php echo $contractTypeRow['category_name'] ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-4">
                            <label for="contractStartDate" class="form-label">Start Date of Contract</label>
                            <input type="date" class="form-control" id="contractStartDate" name="contractStartDate"
                                required>
                        </div>
                        <div class="col-4">
                            <label for="contractEndDate" class="form-label">End Date of Contract</label>
                            <input type="date" class="form-control" id="contractEndDate" name="contractEndDate"
                                required>
                        </div>
                        <div class="col-md-4">
                            <label for="proposedSystem" class="form-label">Proposed System</label>
                            <input type="text" class="form-control" id="proposedSystem" name="proposedSystem" required>
                        </div>
                        <div class="col-md-4">
                            <label for="proposedPrice" class="form-label">Proposed Price</label>
                            <input type="text" class="form-control" id="proposedPrice" name="proposedPrice" required>
                        </div>
                        <div class="col-md-4">
                            <label for="paymentTerms" class="form-label">Terms of Payment</label>
                            <select id="paymentTerms" name="paymentTerms" class="form-select" required>
                                <option value="N/A" selected disabled>Choose...</option>
                                <?php foreach ($paymentTermsResult as $paymentTermsRow) { ?>
                                        <option value="<?php echo $paymentTermsRow['category_name'] ?>">
                                            <?php echo $paymentTermsRow['category_name'] ?>
                                        </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="callNature" class="form-label">Nature of Call</label>
                            <select id="callNature" name="callNature" class="form-select" required>
                                <option value="N/A" selected disabled>Choose...</option>
                                <?php foreach ($callNatureResult as $callNatureRow) { ?>
                                    <option value="<?php echo $callNatureRow['category_name'] ?>">
                                        <?php echo $callNatureRow['category_name'] ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="accountStatus" class="form-label">Account Status</label>
                            <select id="accountStatus" name="accountStatus" class="form-select" required>
                                <option value="N/A" selected disabled>Choose...</option>
                                <?php foreach ($accountstatusResult as $accountstatusRow) { ?>
                                        <option value="<?php echo $accountstatusRow['category_name'] ?>">
                                            <?php echo $accountstatusRow['category_name'] ?>
                                        </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="followUpAction" class="form-label">Follow up Action</label>
                            <input type="text" class="form-control" id="followUpAction" name="followUpAction" required>
                        </div>
                        <div class="col-md-12">
                            <label for="whatTranspired" class="form-label">What Transpired</label>
                            <textarea class="form-control" id="whatTranspired" name="whatTranspired"
                                required></textarea>
                        </div>
                        <div class="col-12">
                            <button name="encodeAccount" id="encodeAccount" type="submit"
                                class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        var userCategory = '<?php echo $category; ?>';
        var userName = '<?php echo $name; ?>';
        var userArrayManager = <?php echo $userArrayManagerJson; ?>;
        var userArrayAdmin = <?php echo $userArrayAdminJson; ?>;
        var category = "<?php echo $category; ?>";

        $(document).ready(function () {
            // Function to toggle date fields and set required attribute
            function toggleDateFields() {
                var contractType = $("#contractType").val();
                var accountStatus = $("#accountStatus").val();

                if (contractType === "Rental" && accountStatus === "Delivered") {
                    $("#contractStartDate").prop("disabled", false).prop("required", true);
                    $("#contractEndDate").prop("disabled", false).prop("required", true);
                } else {
                    $("#contractStartDate").prop("disabled", true).prop("required", false);
                    $("#contractEndDate").prop("disabled", true).prop("required", false);
                }
            }
            toggleDateFields();

            // Event listener for contractType and accountStatus change
            $("#contractType, #accountStatus").change(function () {
                toggleDateFields();
            });
        });
    </script>
    <script src="../js/hideElement.js"></script>
    <script type="text/javascript" src="../js/accExec.js"></script>
    <script type="text/javascript" src="../js/autoFill.js"></script>
</body>

</html>
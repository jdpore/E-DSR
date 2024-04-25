<?php
include ('../php/autoRedirect.php');
include ('../php/performanceList.php');
include ('../php/employeeList.php');
include ('../php/db_conn.php');
include ('../php/addLeave.php');

$table = 'leave';
if (isset($_GET['table'])) {
    $table = $_GET['table'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="/e-dsr/css/sidebar.css">
    <link rel="stylesheet" href="/e-dsr/css/table.css">
    <title>E-DSR - Welcome Page</title>
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .sticky-header {
            position: sticky;
            top: 0;
            background-color: #fff;
            /* Set the background color as needed */
            z-index: 100;
            /* Set a higher z-index value to make sure it stays on top */
        }
    </style>
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
            <main class="col-md-9 ms-sm-auto col-lg-10 p-0  overflow-auto" style="height: 88vh">
                <div class="sticky-top d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center px-5 pt-3 pb-3 border-bottom bg-white"
                    style="height: 8vh">
                    <h1 class="h3">Leave Data</h1>
                    <div class="dropdown">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#eventModal">
                            Event/Training
                        </button>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#holidayModal">
                            Add Holiday
                        </button>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#leaveModal">
                            Add Leave
                        </button>
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            Table Data
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                            <li><a class="dropdown-item" href="leaveData.php?table=leave">Leave</a></li>
                            <li><a class="dropdown-item" href="leaveData.php?table=holiday">Holiday</a></li>
                            <li><a class="dropdown-item" href="leaveData.php?table=event">Event/Training</a></li>
                        </ul>
                    </div>
                </div>
                <div class="bg-white align-items-center px-5 pb-2 mb-3 overflow-auto" style="height: 75vh">
                    <div class="modal fade" id="leaveModal" tabindex="-1" aria-labelledby="leaveLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-md">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="leaveLabel">Leave Data</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form class="row g-3" id="leaveForm" class="row g-3" action="" onsubmit=""
                                        method="POST">
                                        <div class="col-md-12">
                                            <label for="employeeName" class="form-label">Employee Name</label>
                                            <select class="form-select" id="employeeName" name="employeeName" required>
                                                <option value="" disabled selected>Select Employee</option>
                                                <?php foreach ($employee_list_result as $employee_list_row) { ?>
                                                    <option value="<?php echo $employee_list_row['name'] ?>">
                                                        <?php echo $employee_list_row['name'] ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                            <label for="leaveDuration" class="form-label">Duration</label>
                                            <select class="form-select" id="leaveDuration" name="leaveDuration"
                                                required>
                                                <option value="" disabled selected>Duration</option>
                                                <option value="1">Whole Day</option>
                                                <option value="0.5">Half Day</option>
                                            </select>
                                            <label for="leaveDate" class="form-label">Date of Leave</label>
                                            <input type="date" class="form-control" id="leaveDate" name="leaveDate">
                                            <button name="leave" id="leave" type="submit"
                                                class="btn btn-primary mt-3">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="holidayModal" tabindex="-1" aria-labelledby="holidayLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-md">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="holidayLabel">Holiday</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form class="row g-3" id="holidayForm" class="row g-3" action="" onsubmit=""
                                        method="POST">
                                        <div class="col-md-12">
                                            <label for="branch" class="form-label">Branch</label>
                                            <select class="form-select" id="branch" name="branch" required>
                                                <option value="" disabled selected>Select Branch</option>
                                                <option value="All">All</option>
                                                <?php
                                                $branchData = "SELECT DISTINCT branch FROM users";
                                                $branchResult = mysqli_query($conn, $branchData);
                                                foreach ($branchResult as $branchRow) {
                                                    echo "<option value='" . $branchRow['branch'] . "'>" . $branchRow['branch'] . "</option>";
                                                }
                                                ?>

                                            </select>
                                            <label for="holidayDate" class="form-label">Date of Holiday
                                                From</label>
                                            <input type="date" class="form-control" id="holidayDate" name="holidayDate">
                                            <button name="holiday" id="holiday" type="submit"
                                                class="btn btn-primary mt-3">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModal"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-md">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="eventModal">Event/Training</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form class="row g-3" id="eventForm" class="row g-3" action="" onsubmit=""
                                        method="POST">
                                        <div class="col-md-12">
                                            <label for="employeeName" class="form-label">Employee Name</label>
                                            <select class="form-select" id="employeeName" name="employeeName" required>
                                                <option value="" disabled selected>Select Employee</option>
                                                <?php foreach ($employee_list_result as $employee_list_row) { ?>
                                                    <option value="<?php echo $employee_list_row['name'] ?>">
                                                        <?php echo $employee_list_row['name'] ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                            <label for="type" class="form-label">Type</label>
                                            <select class="form-select" id="type" name="type" required>
                                                <option value="" disabled selected>Select Type</option>
                                                <option value="event">Event</option>
                                                <option value="training">Training</option>
                                            </select>
                                            <label for="duration" class="form-label">Duration</label>
                                            <select class="form-select" id="duration" name="duration" required>
                                                <option value="" disabled selected>Select Duration</option>
                                                <option value="1">1 hr</option>
                                                <option value="2">2 hrs</option>
                                                <option value="2">3 hrs</option>
                                                <option value="4">4 hrs</option>
                                                <option value="5">5 hrs</option>
                                                <option value="6">6 hrs</option>
                                                <option value="7">7 hrs</option>
                                                <option value="8">8 hrs</option>
                                            </select>
                                            <label for="date" class="form-label">Date of Event/Training</label>
                                            <input type="date" class="form-control" id="date" name="date">
                                            <button name="event" id="event" type="submit"
                                                class="btn btn-primary mt-3">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <table class='table table-striped mt-5 mb-5'>
                        <?php include 'leaveTable.php' ?>
                    </table>
                </div>
            </main>
        </div>
    </div>
</body>

</html>
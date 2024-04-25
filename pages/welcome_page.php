<?php
include ('../php/autoRedirect.php');
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
    <link rel="stylesheet" href="/e-dsr/css/counters.css">
    <title>E-DSR - Welcome Page</title>
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                <div class="sticky-top d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center px-5 pt-3 pb-3 border-bottom bg-white"
                    style="height: 8vh">
                    <h1 class="h3">Dashboard</h1>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#updateGraphModal">
                        Update Graph
                    </button>
                </div>

                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center mt-4 px-5 pb-2"
                    style="height: 74vh">
                    <div class="modal fade" id="updateGraphModal" tabindex="-1" aria-labelledby="updateGraphLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-md">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="updateGraphLabel">Search Accounts</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form class="row g-3" id="updateGraphForm" class="row g-3" action="" onsubmit=""
                                        method="POST">
                                        <div class="col-md-12">
                                            <label for="callDateStart" class="form-label">Date of Call From</label>
                                            <input type="date" class="form-control" id="callDateStart"
                                                name="callDateStart">

                                            <label for="callDateEnd" class="form-label">Date of Call To</label>
                                            <input type="date" class="form-control" id="callDateEnd" name="callDateEnd">

                                            <button name="updateGraph" id="updateGraph" type="submit"
                                                class="btn btn-primary mt-3">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-9" style="height: 74vh">
                        <div class="container-fluid ps-0 d-flex" style="height: 70vh">
                            <div class="card h-100"> <!-- Set the card to full height -->
                                <div class="card-header text-center bg-white">
                                    <h2 class="card-title mb-0">Daily Calls</h2>
                                </div>
                                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                                    <!-- Center text vertically -->
                                    <canvas id="barLineChart" class="flex-grow-1"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-3" style="height: 74vh">
                        <div class="card text-white bg-primary mb-3" style="height: 16.2vh">
                            <!-- Set the card to full height -->
                            <div class="card-body d-flex flex-row">
                                <!-- Center text vertically -->
                                <i class="fa fa-phone"></i>
                                <span class="count-numbers" id="callCountSpan">
                                </span>
                                <span class="count-name">Calls Made</span>
                            </div>
                        </div>
                        <div class="card text-white bg-success mb-3" style="height: 16.2vh">
                            <!-- Set the card to full height -->
                            <div class="card-body d-flex flex-row">
                                <!-- Center text vertically -->
                                <i class="fa fa-user"></i>
                                <span class="count-numbers" id="actualCountSpan">
                                </span>
                                <span class="count-name">Account Numbers</span>
                            </div>
                        </div>
                        <div class="card text-white bg-danger mb-3" style="height: 16.2vh">
                            <!-- Set the card to full height -->
                            <div class="card-body d-flex flex-row">
                                <!-- Center text vertically -->
                                <i class="fa fa-phone-slash"></i>
                                <span class="count-numbers" id="actualClosedCountSpan">
                                </span>
                                <span class="count-name">Closed Call</span>
                            </div>
                        </div>
                        <div class="card text-white bg-info mb-3" style="height: 16.2vh">
                            <!-- Set the card to full height -->
                            <div class="card-body d-flex flex-row">
                                <!-- Center text vertically -->
                                <i class="fa fa-percent"></i>
                                <span class="count-numbers" id="conversionSpan">
                                </span>
                                <span class="count-name">Conversion</span>
                            </div>
                        </div>
                    </div>

                </div>
            </main>
        </div>
    </div>
    <script src="../js/barLineGraph.js"></script>
    <script>
        var category = "<?php echo $category; ?>";
    </script>
    <script src="../js/hideElement.js"></script>

</body>

</html>
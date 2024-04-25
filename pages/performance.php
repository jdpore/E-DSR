<?php
include ('../php/autoRedirect.php');
include ('../php/employeeList.php');
include ('../php/dates.php');
$scopeSelect = "daily";
$businessUnit = "all";
$department = "all";
if (isset($_POST['updatePerformance'])) {
    if (isset($_POST['scope'])) {
        $scopeSelect = $_POST['scope'];
    }
    if (isset($_POST['businessUnit'])) {
        $businessUnit = $_POST['businessUnit'];
    }
    if (isset($_POST['department'])) {
        $department = $_POST['department'];
    }
    if (isset($_POST['date'])) {
        $department = $_POST['department'];
    }
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
    <title>E-DSR - Welcome Page</title>
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.0/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.27/jspdf.plugin.autotable.js"></script>

    <style>
        .sticky-header {
            position: sticky;
            top: 0;
            background-color: #fff;
            /* Set the background color as needed */
            z-index: 100;
            /* Set a higher z-index value to make sure it stays on top */
        }

        .table td {
            text-align: center;
            margin-top: 0%;
            white-space: nowrap;
            background-color: white;
            padding-top: 25px;
            padding-bottom: 25px;
            border-width: 1px;
            /* Adjust the thickness as desired */
            border-style: solid;
            border-color: black;
        }

        .table th {
            text-align: center;
            margin-top: 0%;
            background-color: #002060;
            white-space: nowrap;
            padding-top: 25px;
            padding-bottom: 25px;
            color: white;
            border-width: 1px;
            /* Adjust the thickness as desired */
            border-style: solid;
            border-color: white;
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
            <main class="col-md-9 ms-sm-auto col-lg-10 p-0 overflow-auto" style="height: 88vh">
                <div class="sticky-top d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center px-5 pt-3 pb-3 border-bottom bg-white"
                    style="height: 8vh">
                    <h1 class="h3">Performance</h1>
                    <div>
                        <button id="downloadPdfBtn" type="button" class="btn btn-primary">
                            Download
                        </button>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#updatePerformanceModal">
                            Filter
                        </button>
                    </div>
                </div>
                <div class="container">
                    <div class="bg-white align-items-center px-5 pb-2 my-5">
                        <div class="card-header text-center bg-white">
                            <?php
                            if ($scopeSelect == "daily") {
                                echo "<h2 class='card-title mb-0'>Daily Calls Percentage</h2>";
                            } elseif ($scopeSelect == "weekly") {
                                echo "<h2 class='card-title mb-0'>Weekly Calls Percentage</h2>";
                            } elseif ($scopeSelect == "monthly") {
                                echo "<h2 class='card-title mb-0'>Monthly Calls Percentage</h2>";
                            } elseif ($scopeSelect == "yearly") {
                                echo "<h2 class='card-title mb-0'>Yearly Calls Percentage</h2>";
                            }
                            ?>
                        </div>
                        <div class="card-body d-flex flex-column align-items-center justify-content-center">
                            <!-- Center text vertically -->
                            <canvas id="performanceChart" class="flex-grow-1"></canvas>
                        </div>
                    </div>
                </div>
                <div class="bg-white align-items-center pt-5 px-5 pb-2 mb-5 d-flex justify-content-center"
                    style="height: 70vh">
                    <div class="modal fade" id="updatePerformanceModal" tabindex="-1"
                        aria-labelledby="updatePerformanceLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-md">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="updatePerformanceLabel">Performance</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form class="row g-3" id="updatePerformanceForm" class="row g-3"
                                        action="performance.php" onsubmit="" method="POST">
                                        <div class="col-md-12">
                                            <label for="department" class="form-label">Department</label>
                                            <select id="department" name="department" class="form-select">
                                                <option value="N/A" selected disabled>Choose...</option>
                                                <option value="OP Sales - PP">OP Sales - PP</option>
                                                <option value="OP Sales - MFP/RISO">OP Sales - MFP/RISO</option>
                                                <option value="OP Consumables">OP Consumables</option>
                                                <option value="CSD">CSD</option>
                                                <option value="Furniture">Furniture</option>
                                            </select>

                                            <label for="businessUnit" class="form-label">Business Unit</label>
                                            <select name="businessUnit" id="businessUnit" class="form-select"
                                                aria-label="Business Unit">
                                                <option value="N/A" selected disabled>Choose...</option>
                                                <?php foreach ($unit_list as $unit) { ?>
                                                    <option value="<?php echo $unit ?>">
                                                        <?php echo $unit ?>
                                                    </option>
                                                <?php } ?>
                                            </select>

                                            <label for="scopeSelect" class="form-label">Scope</label>
                                            <select name="scope" id="scopeSelect" class="form-select"
                                                aria-label="Select Scope">
                                                <option value="N/A" disabled>Choose...</option>
                                                <option value="daily">Daily</option>
                                                <option value="weekly">Weekly</option>
                                                <option value="monthly">Monthly</option>
                                                <option value="yearly">Yearly</option>
                                            </select>

                                            <label for="callDateStart" class="form-label">Date of Call From</label>
                                            <input type="date" class="form-control" id="callDateStart"
                                                name="callDateStart">

                                            <label for="callDateEnd" class="form-label">Date of Call To</label>
                                            <input type="date" class="form-control" id="callDateEnd" name="callDateEnd">

                                            <button name="updatePerformance" id="updatePerformance" type="submit"
                                                class="btn btn-primary mt-3">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="container-fluid" style="height: 70vh; overflow-x: auto;">
                        <table class='table' id="table">
                            <?php
                            include 'performance_table.php';
                            ?>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
<script>
    document.getElementById('downloadPdfBtn').addEventListener('click', function () {
        const { jsPDF } = window.jspdf; // Access the jsPDF constructor
        const doc = new jsPDF({
            format: 'a3',
            orientation: 'landscape'
        });

        // Use the autoTable function to convert the HTML table to a PDF
        doc.autoTable({
            html: '.table',
            startY: 10,
            theme: 'plain', // Use 'plain' theme for customization
            styles: {
                halign: 'center',
                font: 'helvetica',
                fontSize: 10,
                textColor: 'black',
                valign: 'middle',
                borderWidth: 1,
                borderColor: 'black'
            },
            headStyles: {
                fillColor: '#002060', // Use the same background color as your HTML table headers
                textColor: 'white', // Use the same text color as your HTML table headers
                borderColor: 'white' // Use the same border color as your HTML table headers
            },
            bodyStyles: {
                fillColor: 'white', // Use the same background color as your HTML table body
                textColor: 'black', // Use the same text color as your HTML table body
                borderColor: 'black' // Use the same border color as your HTML table body
            },
            didParseCell: function (data) {
                // Check if data.cell.raw exists and has a classList property
                if (data.cell.raw && data.cell.raw.classList) {
                    if (data.cell.section === 'head' && data.cell.raw.classList.contains('days')) {
                        data.cell.styles.fillColor = '#92cddc';
                    }
                    if (data.cell.section === 'body' && data.cell.raw.classList.contains('dayCalls')) {
                        data.cell.styles.fillColor = '#92d050';
                    }
                    if (data.cell.section === 'body' && data.cell.raw.classList.contains('names')) {
                        data.cell.styles.halign = 'start';
                    }
                    if (data.cell.section === 'head' && data.cell.raw.classList.contains('total')) {
                        data.cell.styles.fillColor = '#938953';
                    }
                    if (data.cell.section === 'body' && data.cell.raw.classList.contains('achievement')) {
                        data.cell.styles.fillColor = '#8db3e2';
                    }
                    if (data.cell.section === 'body' && data.cell.raw.classList.contains('actual')) {
                        data.cell.styles.textColor = '#4f81bd';
                    }
                }
            }

        });

        // Save the PDF
        doc.save('performance_table.pdf');
    });
</script>
<script>
    // Get the context of the canvas element where the chart will be rendered
    var ctx = document.getElementById('performanceChart').getContext('2d');

    // Define labels for the x-axis
    var labels = <?php echo json_encode($labels) ?>;

    var sets = <?php echo json_encode($sets) ?>;

    // Define the number of datasets you want to create
    var numDatasets = sets.length;


    // Initialize an array to hold the datasets
    var datasets = [];
    var eachPercent = <?php echo json_encode($eachPercent) ?>;
    console.log(eachPercent);
    var data = <?php echo json_encode($result2DArray) ?>;
    console.log(data);

    // Loop through and create each dataset
    for (var i = 1; i <= numDatasets; i++) {
        // Define random data for each dataset

        // Define colors for the dataset
        var borderColor = `rgba(${Math.floor(Math.random() * 255)}, ${Math.floor(Math.random() * 255)}, ${Math.floor(Math.random() * 255)}, 1)`;
        var backgroundColor = `rgba(${Math.floor(Math.random() * 255)}, ${Math.floor(Math.random() * 255)}, ${Math.floor(Math.random() * 255)}, 0.2)`;

        // Create a dataset object
        var dataset = {
            label: sets[i - 1],
            data: data[i - 1],
            borderColor: borderColor,
            backgroundColor: backgroundColor,
            borderWidth: 1
        };

        // Add the dataset to the datasets array
        datasets.push(dataset);
    }

    // Define chart data
    var chartData = {
        labels: labels,
        datasets: datasets
    };

    // Define chart options
    var options = {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    };

    // Create the chart
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: chartData,
        options: options
    });

</script>

</html>
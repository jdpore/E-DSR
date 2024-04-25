<?php
include ('../php/autoRedirect.php');
include ('../php/categoryList.php');
include ('../php/addCategory.php');
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
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#addCategory">
                            Add Category
                        </button>
                    </div>
                </div>
                <div class="bg-white align-items-center px-5 pb-2 mb-3 overflow-auto" style="height: 75vh">
                    <div class="modal fade" id="addCategory" tabindex="-1" aria-labelledby="addCategoryLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-md">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addCategoryLabel">Add Category</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form class="row g-3" action="" onsubmit="return isValid()" method="POST">
                                        <div class="form-floating col-md-12 mb-2">
                                            <select class="form-select" name="field">
                                                <option disabled selected value="">Select Field...</option>
                                                <option value='Account Category'>Account Category</option>
                                                <option value='Segment'>Segment</option>
                                                <option value='Industry'>Industry</option>
                                                <option value='Source of Account'>Terms of Payment</option>
                                                <option value='Contract Type'>Contract Type</option>
                                                <option value='Terms of Payment'>Terms of Payment</option>
                                                <option value='Nature of Call'>Nature of Call</option>
                                                <option value='Account Status'>Account Status</option>
                                            </select>
                                            <label class="ms-2 form-control-placeholder" for="field">
                                                Field </label>
                                        </div>
                                        <div class="form-floating col-md-12 mb-2">
                                            <input type="text" class="form-control" name="category"
                                                placeholder="Category Name">
                                            <label class="ms-2 form-control-placeholder" for="category">
                                                Category Name</label>
                                        </div>
                                        <div class="col-md-12 mt-5">
                                            <button id="add_category_button" name="add_category_button" type="submit"
                                                class="btn btn-primary">Submit</button>
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
                                <th scope="col">Field</th>
                                <th scope="col">Category Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($categoryResult as $category_result_row) {
                                echo '<tr>';
                                echo '<th scope="row">
                                            <a href="../php/delete.php?category_id=' . $category_result_row['id'] . '&category_name=' . $category_result_row['category_name'] . '" onclick="return confirm(\'Delete Category?\')" class="btn btn-danger btn-lg">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </th>';
                                echo "<td>" . $category_result_row['field'] . "</td>";
                                echo "<td>" . $category_result_row['category_name'] . "</td>";
                                echo '</tr>';
                            }
                            ?>
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
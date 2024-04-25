<?php
include 'db_conn.php';
require 'C:\xampp\htdocs\E-DSR\vendor\autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if (isset($_POST['uploadFileButton'])) {
    $fileName = $_FILES['uploadFile']['name'];
    $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);
    $allowedExt = ['xls', 'csv', 'xlsx'];

    if (in_array($fileExt, $allowedExt)) {
        $inputFileName = $_FILES['uploadFile']['tmp_name'];
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileName);
        $data = $spreadsheet->getActiveSheet()->toArray();

        $total_id = 0;
        $count = 1; // Start with 0 to skip the first row

        $errorOccurred = false; // Flag to track if an error occurred

        foreach ($data as $row) {
            // Skip the first row
            if ($count === 1) {
                $count++;
                continue;
            }

            if (!empty($row['0'])) {
                $total_id++;
                $accExec = $row['0'];
                $callDate = $row['1'];
                $accName = $row['2'];
                $endUser = $row['3'];
                $segment = $row['4'];
                $industry = $row['5'];
                $accCat = $row['6'];
                $accSource = $row['7'];
                $address = $row['8'];
                $area = $row['9'];
                $contactPerson = $row['10'];
                $designation = $row['11'];
                $contactNumber = $row['12'];
                $email = $row['13'];
                $decisionMaker = $row['14'];
                $dmNumber = $row['15'];
                $dmDesignation = $row['16'];
                $existingSystem = $row['17'];
                $contractDateStart = $row['18'];
                $contractDateEnd = $row['19'];
                $proposedSystem = $row['20'];
                $proposedPrice = $row['21'];
                $paymentTerms = $row['22'];
                $contactType = $row['23'];
                $callNature = $row['24'];
                $accStatus = $row['25'];
                $whatTranspired = $row['26'];
                $actionFollow = $row['27'];

                $branch1 = "SELECT branch, dept FROM users WHERE name LIKE '%$accExec%'";
                $branchquery = mysqli_query($conn, $branch1);
                $branchData = mysqli_fetch_assoc($branchquery);

                // Check if 'branch' key exists in $branchData
                if (isset($branchData['branch'])) {
                    $branchValue = $branchData['branch'];
                    $deptValue = $branchData['dept'];

                    $sql = "INSERT INTO encoded (accExec, branch, callDate, accName, endUser, segment, industry, accCat, accSource, address, 
                                                    area, contactPerson, designation, contactNumber, email, decisionMaker, dmNumber, 
                                                    dmDesignation, existingSystem, startContractDate, endContractDate, proposedSystem, proposedPrice, paymentTerms, 
                                                    contactType, callNature, accStatus, whatTranspired, actionFollow, dept)
                        VALUES ('$accExec', '$branchValue', '$callDate', '$accName', '$endUser', '$segment', '$industry', '$accCat', '$accSource', '$address',
                                '$area', '$contactPerson', '$designation', '$contactNumber', '$email', '$decisionMaker', '$dmNumber',
                                '$dmDesignation', '$existingSystem', '$contractDateStart', '$contractDateEnd', '$proposedSystem', '$proposedPrice', '$paymentTerms',
                                '$contactType', '$callNature', '$accStatus', '$whatTranspired', '$actionFollow', '$deptValue');";
		$result = mysqli_query($conn, $sql);

                } else {
                    // Set the flag to true if an error occurred
                    $errorOccurred = true;
                }
            }

            $count++;
        }

        if ($errorOccurred) {
            echo '<script>
                    alert("Error: Unable to retrieve branch information.")
                 </script>';
        } else {
echo '<script>
                alert("File Uploaded.")
            </script>';
        }
    } else {
        echo '<script>
                alert("Invalid File.")
            </script>';
    }
}
?>
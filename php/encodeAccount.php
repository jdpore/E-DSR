<?php
include('db_conn.php');

$sql1 = "SELECT * FROM encoded";
$accountResult = mysqli_query($conn, $sql1);

if (isset($_POST['encodeAccount'])) {
    $accountExecutive = $_POST['accountExecutive'];
    $accountName = $_POST['accountName'];
    $callDate = $_POST['callDate'];
    $endUser = $_POST['endUser'];
    $address = $_POST['address'];
    $area = $_POST['area'];
    $accountCategory = $_POST['accountCategory'];
    $segment = $_POST['segment'];
    $industry = $_POST['industry'];
    $accountSource = $_POST['accountSource'];
    $contactPerson = $_POST['contactPerson'];
    $designation = $_POST['designation'];
    $contactNumber = $_POST['contactNumber'];
    $emailAddress = $_POST['emailAddress'];
    $decisionMaker = $_POST['decisionMaker'];
    $dmContactNumber = $_POST['dmContactNumber'];
    $dmDesignation = $_POST['dmDesignation'];
    $existingSystem = $_POST['existingSystem'];
    $contractType = $_POST['contractType'];
    $contactStartDate = isset($_POST['contractStartDate']) ? $_POST['contractStartDate'] : '0000-00-00';
    $contactEndDate = isset($_POST['contractEndDate']) ? $_POST['contractEndDate'] : '0000-00-00';
    $proposedSystem = $_POST['proposedSystem'];
    $proposedPrice = $_POST['proposedPrice'];
    $paymentTerms = $_POST['paymentTerms'];
    $callNature = $_POST['callNature'];
    $accountStatus = $_POST['accountStatus'];
    $followUpAction = $_POST['followUpAction'];
    $whatTranspired = $_POST['whatTranspired'];

    $sql = "SELECT * FROM users WHERE name = '$accountExecutive'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $branch = $row['branch'];
    $department = $row['dept'];

    $sql = "INSERT INTO encoded (
    accExec, 
    branch, 
    callDate, 
    accName, 
    endUser, 
    segment,  
    industry, 
    accCat, 
    accSource, 
    address, 
    area, 
    contactPerson, 
    designation, 
    contactNumber, 
    email, 
    decisionMaker,
    dmNumber, 
    dmDesignation, 
    existingSystem, 
    startContractDate, 
    endContractDate, 
    proposedSystem, 
    proposedPrice, 
    paymentTerms, 
    contactType, 
    callNature, 
    accStatus, 
    whatTranspired, 
    actionFollow, 
    dept
) VALUES (
    '$accountExecutive', 
    '$branch', 
    '$callDate', 
    '$accountName', 
    '$endUser', 
    '$segment',  
    '$industry', 
    '$accountCategory', 
    '$accountSource', 
    '$address', 
    '$area', 
    '$contactPerson', 
    '$designation', 
    '$contactNumber', 
    '$emailAddress', 
    '$decisionMaker', 
    '$dmContactNumber', 
    '$dmDesignation', 
    '$existingSystem', 
    '$contactStartDate', 
    '$contactEndDate', 
    '$proposedSystem', 
    '$proposedPrice', 
    '$paymentTerms', 
    '$contractType', 
    '$callNature', 
    '$accountStatus', 
    '$whatTranspired', 
    '$followUpAction', 
    '$department'
);";

    $addAccountResult = mysqli_query($conn, $sql);

    if ($addAccountResult) {
        echo '<script>
                window.location.href = "/e-dsr/pages/encode.php";
                alert("Account Added.");
              </script>';
        exit();
    } else {
        // Display an error message or log the error
        echo "Error: " . mysqli_error($conn);
    }
}

if (isset($_POST['editEncodeAccount'])) {
    $id = $_POST['id'];
    $accountExecutive = $_POST['accountExecutive'];
    $accountName = $_POST['accountName'];
    $callDate = $_POST['callDate'];
    $endUser = $_POST['endUser'];
    $address = $_POST['address'];
    $area = $_POST['area'];
    $accountCategory = $_POST['accountCategory'];
    $segment = $_POST['segment'];
    $industry = $_POST['industry'];
    $accountSource = $_POST['accountSource'];
    $contactPerson = $_POST['contactPerson'];
    $designation = $_POST['designation'];
    $contactNumber = $_POST['contactNumber'];
    $emailAddress = $_POST['emailAddress'];
    $decisionMaker = $_POST['decisionMaker'];
    $dmContactNumber = $_POST['dmContactNumber'];
    $dmDesignation = $_POST['dmDesignation'];
    $existingSystem = $_POST['existingSystem'];
    $contractType = $_POST['contractType'];
    $contactStartDate = isset($_POST['contractStartDate']) ? $_POST['contractStartDate'] : '0000-00-00';
    $contactEndDate = isset($_POST['contractEndDate']) ? $_POST['contractEndDate'] : '0000-00-00';
    $proposedSystem = $_POST['proposedSystem'];
    $proposedPrice = $_POST['proposedPrice'];
    $paymentTerms = $_POST['paymentTerms'];
    $callNature = $_POST['callNature'];
    $accountStatus = $_POST['accountStatus'];
    $followUpAction = $_POST['followUpAction'];
    $whatTranspired = $_POST['whatTranspired'];

    $sql = "SELECT * FROM users WHERE name = '$accountExecutive'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $branch = $row['branch'];
    $department = $row['dept'];

    $sql = "UPDATE encoded 
            SET 
            accExec = '$accountExecutive',
            branch = '$branch',
            callDate = '$callDate',
            accName = '$accountName',
            endUser = '$endUser',
            segment = '$segment',
            industry = '$industry',
            accCat = '$accountCategory',
            accSource = '$accountSource',
            address = '$address',
            area = '$area',
            contactPerson = '$contactPerson',
            designation = '$designation',
            contactNumber = '$contactNumber',
            email = '$emailAddress',
            decisionMaker = '$decisionMaker',
            dmNumber = '$dmContactNumber',
            dmDesignation = '$dmDesignation',
            existingSystem = '$existingSystem',
            startContractDate = '$contactStartDate',
            endContractDate = '$contactEndDate',
            proposedSystem = '$proposedSystem',
            proposedPrice = '$proposedPrice',
            paymentTerms = '$paymentTerms',
            contactType = '$contractType',
            callNature = '$callNature',
            accStatus = '$accountStatus',
            whatTranspired = '$whatTranspired',
            actionFollow = '$followUpAction',
            dept = '$department'
            WHERE id = '$id';";
    $addAccountResult = mysqli_query($conn, $sql);

    if ($addAccountResult) {
        echo '<script>
                window.location.href = "/e-dsr/pages/encode.php";
                alert("Account Edited.");
              </script>';
        exit();
    } else {
        // Display an error message or log the error
        echo "Error: " . mysqli_error($conn);
    }
}
?>

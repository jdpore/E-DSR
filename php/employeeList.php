<?php
include ('db_conn.php');
include ('../php/autoRedirect.php');

$employee_list = "SELECT * FROM users";
$employee_list_result = mysqli_query($conn, $employee_list);

$departmentBusinessUnits = [
    'OP Sales - PP' => ['PP SALES'],
    'OP Sales - MFP/RISO' => ['OP MFP', 'OP RISO'],
    'OP Consumables' => ['OP CONSUMABLES SALES'],
    'CSD' => ['RENTAL SALES - MAKATI/BGC', 'RENTAL SALES - SOUTH MANILA', 'RENTAL SALES - QC/ORTIGAS'],
    'Furniture' => ['FURNITURE']
];

$unit_list = [
    'PP SALES',
    'OP MFP',
    'OP RISO',
    'OP CONSUMABLES SALES',
    'FURNITURE',
    'RENTAL SALES - MAKATI/BGC',
    'RENTAL SALES - SOUTH MANILA',
    'RENTAL SALES - QC/ORTIGAS',
    'BRANCH - LA UNION',
    'BRANCH - ANGELES',
    'BRANCH - CABANATUAN',
    'BRANCH - BACOLOD',
    'BRANCH - CEBU',
    'BRANCH - ILO-ILO',
    'BRANCH - CDO'
];


?>
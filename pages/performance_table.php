<?php
$thursdaysArray = [];
$lastThursdayOfPreviousMonth = new DateTime('last Thursday of last month');
$thursdaysArray[] = $lastThursdayOfPreviousMonth->format('Y-m-d');

$months = [
    'January',
    'February',
    'March',
    'April',
    'May',
    'June',
    'July',
    'August',
    'September',
    'October',
    'November',
    'December'
];

$currentMonth = date('m');
$currentYear = date('Y');

$firstDayOfCurrentMonth = new DateTime("$currentYear-$currentMonth-01");

$startDate = clone $firstDayOfCurrentMonth;

while ($startDate->format('m') == $currentMonth) {
    if ($startDate->format('N') == 4) {
        $thursdaysArray[] = $startDate->format('Y-m-d');
    }
    $startDate->modify('+1 day');
}


// Function to fetch user lists based on authority type and unit handled
function fetchUserList($conn, $unit, $authorityType)
{
    $query = "SELECT * FROM users WHERE authority IN (?, ?) AND handled = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sss', $authorityType[0], $authorityType[1], $unit);
    $stmt->execute();
    return $stmt->get_result();
}

// Function to fetch holidays on a specific date
function fetchHolidays($conn, $date, $branch)
{
    $query = "SELECT * FROM holidays WHERE date = ? AND (branch = ? OR branch = 'All')";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ss', $date, $branch);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function fetchHolidaysWhole($conn, $date1, $date2)
{
    $query = "SELECT * FROM holidays WHERE date BETWEEN ? AND ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ss', $date1, $date2);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result;
}

// Function to fetch leave status for a specific date and user
function fetchLeaveStatus($conn, $date, $userName)
{
    $query = "SELECT leave_duration FROM leave_status WHERE leave_date = ? AND employee_name = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ss', $date, $userName);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function fetchEvent($conn, $date, $userName)
{
    $query = "SELECT * FROM event WHERE date = ? AND employee_name = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ss', $date, $userName);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

// Function to fetch call count for a specific date and user
function fetchCallCount($conn, $userName, $date)
{
    $query = "SELECT COUNT(id) AS num_calls FROM encoded WHERE accExec = ? AND callDate = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ss', $userName, $date);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

// Function to fetch distinct call dates for a given month
function fetchDistinctCallDates($conn, $month, $year)
{
    $query = "SELECT DISTINCT callDate FROM encoded WHERE MONTH(callDate) = ? AND YEAR(callDate) = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ii', $month, $year);
    $stmt->execute();
    return $stmt->get_result();
}

function fetchSpecificCallDates($conn, $start, $end)
{
    $query = "SELECT DISTINCT callDate FROM encoded WHERE callDate BETWEEN '$start' AND '$end'";
    $result = mysqli_query($conn, $query);
    return $result;
}

function fetchDistinctCallYears($conn)
{
    $query = "SELECT DISTINCT YEAR(callDate) as year FROM encoded";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    return $stmt->get_result();
}

function fetchDistinctCallDatesWeekly($conn, $week1, $week2, $week3)
{
    $query = "SELECT DISTINCT callDate FROM encoded WHERE callDate = '$week1' OR callDate BETWEEN '$week2' AND '$week3'";
    $result = mysqli_query($conn, $query);
    return $result;
}

// Function to calculate the completion ratio
function calculateCompletionRatio($total, $target)
{
    $result = $target > 0 ? ($total / $target) * 100 : 0;
    return number_format($result, 0);
}

function splitArrayInto2D($inputArray, $n)
{
    $output2DArray = [];

    // Number of columns you want in each inner array
    $numColumns = count($inputArray) / $n;

    // Calculate the number of rows needed (ceil of array length divided by numColumns)
    $numRows = ceil(count($inputArray) / $numColumns);

    // Initialize the 2D array with the correct structure
    for ($i = 0; $i < $numColumns; $i++) {
        $output2DArray[] = [];
    }

    // Fill the 2D array by iterating over the input array
    for ($index = 0; $index < count($inputArray); $index++) {
        // Calculate the current column based on the index
        $column = $index % $numColumns;
        // Calculate the current row based on the index
        $row = intdiv($index, $numColumns);
        // Add the current element to the correct position in the 2D array
        $output2DArray[$column][$row] = $inputArray[$index];
    }

    return $output2DArray;
}


$all_dates = fetchDistinctCallDates($conn, $currentMonth, $currentYear);
$all_dates = array_map(function ($row) {
    return $row['callDate'];
}, iterator_to_array($all_dates));
sort($all_dates);
$all_dates = array_slice($all_dates, -5);
$firstDate = $all_dates[4];

$holiday = fetchHolidaysWhole($conn, $all_dates[0], $all_dates[4]);
$holiday = array_map(function ($row) {
    return $row['date'];
}, iterator_to_array($holiday));

$all_dates = array_merge($all_dates, $holiday);
$all_dates = array_unique($all_dates);

sort($all_dates);
$all_dates = array_slice($all_dates, -5);
$firstDate = $all_dates[4];
$currentDayNumber = (int) date('j', strtotime($firstDate));
$weekNumber = intdiv($currentDayNumber, 7);

// Display the table headers
echo "
<thead class='bg-white text-white sticky-header'>
    <tr>
        <th scope='col' rowspan='2'>Business Unit</th>
        <th scope='col' rowspan='2'>Account Executive</th>";
if ($scopeSelect == "daily") {
    if (isset($_POST['callDateStart']) && isset($_POST['callDateEnd'])) {
        $callDateStart = $_POST['callDateStart'];
        $callDateEnd = $_POST['callDateEnd'];

        $all_dates = fetchSpecificCallDates($conn, $callDateStart, $callDateEnd);
        $all_dates = array_map(function ($row) {
            return $row['callDate'];
        }, iterator_to_array($all_dates));
        sort($all_dates);
        $holiday = fetchHolidaysWhole($conn, $callDateStart, $callDateEnd);
        $holiday = array_map(function ($row) {
            return $row['date'];
        }, iterator_to_array($holiday));
        $all_dates = array_merge($all_dates, $holiday);
        $all_dates = array_unique($all_dates);
        sort($all_dates);
        $formatted_date1 = date('F j', strtotime($all_dates[0]));
        $formatted_date2 = date('F j', strtotime($all_dates[count($all_dates) - 1]));
        echo "
            <th scope='col' colspan='" . count($all_dates) + 1 . "'>$formatted_date1 to $formatted_date2</th>
            <th scope='col' colspan='2'>As of {$formatted_date}</th>
        </tr>
        <tr>";
    } else {
        echo "
        <th scope='col' colspan='6'>Week $weekNumber</th>
        <th scope='col' colspan='2'>As of {$formatted_date}</th>
        <th scope='col' colspan='3'>For the Month of {$formatted_month}</th>
    </tr>
    <tr>";
    }
    foreach ($all_dates as $date) {
        $dateObject = new DateTime($date);
        $formattedDate = $dateObject->format('d');
        echo "<th class='days' style='background-color: #92cddc' scope='col'>{$formattedDate}</th>";
    }
} elseif ($scopeSelect == "weekly") {
    echo "
        <th scope='col' colspan='" . count($thursdaysArray) + 1 . "'>Weeks</th>
        <th scope='col' colspan='2'>As of {$formatted_date}</th>
    </tr>
    <tr>";
    $count = 0;
    for ($i = 0; $i < count($thursdaysArray) - 1; $i++) {
        $date = $thursdaysArray[$i];
        $dateObject = new DateTime($date);
        $formattedDate = $dateObject->format('F-d');
        $count += 1;
        $head = "Week " . $count;
        echo "<th class='days' style='background-color: #92cddc' scope='col'>" . $head . "<br>" . $formattedDate . "</th>";
        $set[] = $head;
    }
    $sets = $set;
} elseif ($scopeSelect == "monthly") {
    $filteredMonths = array_slice($months, 0, $currentMonth);
    echo "
        <th scope='col' colspan='" . count($filteredMonths) + 1 . "'>Months</th>
        <th scope='col' colspan='2'>As of {$formatted_date}</th>
    </tr>
    <tr>";
    foreach ($filteredMonths as $month) {
        echo "<th class='days' style='background-color: #92cddc' scope='col'>" . $month . "</th>";
    }
    $sets = $filteredMonths;
} elseif ($scopeSelect == "yearly") {
    $filteredMonths = array_slice($months, 0, $currentMonth);

    $years = fetchDistinctCallYears($conn);
    $years = array_map(function ($row) {
        return $row['year'];
    }, iterator_to_array($years));
    sort($years);
    echo "
        <th scope='col' colspan='" . count($years) + 1 . "'>Years</th>
        <th scope='col' colspan='2'>As of {$formatted_date}</th>
    </tr>
    <tr>";
    foreach ($years as $year) {
        echo "<th class='days' style='background-color: #92cddc' scope='col'>" . $year . "</th>";
    }
    $sets = $years;
}

echo "
        <th class='total' style='background-color: #938953' scope='col'>Total</th>
        <th scope='col'>Target<br>Calls</th>
        <th scope='col'>%<br>Achievement</th>";
if ($scopeSelect == "daily") {
    if (isset($_POST['callDateStart']) && isset($_POST['callDateEnd'])) {

    } else {
        echo "
        <th scope='col'>Actual<br>Calls</th>
        <th scope='col'>Target<br>Calls</th>
        <th scope='col'>%<br>Achievement</th>";
    }
} else {
    echo "";
}
echo "
    </tr>
</thead>
<tbody>
";
$executives = array();
$percentages = array();
// Calculate and display weekly and monthly reports for each unit

if ($department == 'all') {
    if ($businessUnit == 'all') {
        $unit_list;
    } else {
        $unit_list = [$businessUnit];
    }
} else {
    $unit_list = $departmentBusinessUnits[$department];
}


foreach ($unit_list as $unit) {
    $userList = fetchUserList($conn, $unit, ['User', 'Team Leader']);
    $rowCount = $userList->num_rows;
    if ($rowCount !== 0) {
        echo "<tr><td class='names' colspan='13' style='text-align: start;'>" . htmlspecialchars($unit) . "</td></tr>";

        // Fetch executives and users lists
        $execUnit = $unit === "OP RISO" ? "OP MFP" : $unit;
        $executiveList = fetchUserList($conn, $execUnit, ['Team Leader', 'Manager']);

        // Display executive name for the unit
        $executiveName = $executiveList->fetch_assoc()['name'] ?? '';
        echo "<tr><td class='names' rowspan='{$rowCount}' style='text-align: start;'>{$executiveName}</td>";

        // Iterate over each user in the unit
        while ($userRow = $userList->fetch_assoc()) {
            $userName = htmlspecialchars($userRow['name']);
            $executives[] = $userName;
            $labels = $executives;
            echo "<td class='names' style='text-align: start;'>{$userName}</td>";

            if ($scopeSelect == "daily") {
                $weekTotal = 0;
                $weekTarget = 0;
                foreach ($all_dates as $date) {
                    // Check if the date is a holiday
                    $holiday = fetchHolidays($conn, $date, $userRow['branch']);
                    if (!empty($holiday)) {
                        $sets[] = $date;
                        $eachPercent[] = calculateCompletionRatio(0, 0);
                        echo "<td class='dayCalls' style='background-color: #92d050'>Holiday</td>";
                    } else {
                        $sets[] = $date;
                        // Fetch leave status and call count for the date
                        $leaveStatus = fetchLeaveStatus($conn, $date, $userName);
                        $leaveDuration = $leaveStatus['leave_duration'] ?? null;
                        $event = fetchEvent($conn, $date, $userRow['name']);
                        $eventDuration = $event['duration'] ?? null;

                        if ($eventDuration != null) {
                            $callCount = fetchCallCount($conn, $userName, $date);
                            $weekTotal += ($event['duration'] + $callCount['num_calls']);
                            $weekTarget += 8;
                            $eachPercent[] = calculateCompletionRatio(4, 8);
                            if ($event['duration'] == 8) {
                                echo "<td class='dayCalls' style='background-color: #92d050'>" . $event['type'] . "</td>";
                            } else {
                                echo "<td class='dayCalls' style='background-color: #92d050'>" . $event['type'] . " " . $event['duration'] . "/hrs<br>" . "Number of Calls: " . $callCount['num_calls'] . "</td>";
                            }
                        } elseif ($leaveDuration === null) {
                            // No leave, fetch call count
                            $callCount = fetchCallCount($conn, $userName, $date);
                            $weekTotal += $callCount['num_calls'];
                            $weekTarget += 8;
                            $eachPercent[] = calculateCompletionRatio($callCount['num_calls'], 8);
                            echo "<td class='dayCalls' style='background-color: #92d050'>{$callCount['num_calls']}</td>";
                        } elseif ($leaveDuration == 0.5) {
                            // Half-day leave
                            $weekTotal += (4 + $callCount['num_calls']);
                            $weekTarget += 8;
                            $eachPercent[] = calculateCompletionRatio(4, 8);
                            echo "<td class='dayCalls' style='background-color: #92d050'>Leave Half Day<br>" . "Number of Calls: " . $callCount['num_calls'] ."</td>";
                        } else {
                            // Full-day leave
                            $weekTotal += 8;
                            $weekTarget += 8;
                            $eachPercent[] = calculateCompletionRatio(8, 8);
                            echo "<td class='dayCalls' style='background-color: #92d050'>Leave Full Day</td>";
                        }
                    }
                }
                $sets = array_unique($sets);
                $result2DArray = splitArrayInto2D($eachPercent, count($labels));
                $completionRatio = calculateCompletionRatio($weekTotal, $weekTarget);
                echo "<td>{$weekTotal}</td>";
                echo "<td>{$weekTarget}</td>";
                echo "<td class='achievement' style='background-color: #8db3e2'>" . number_format($completionRatio, 0) . "%</td>";
                $percentages[] = number_format($completionRatio, 0);
            } elseif ($scopeSelect == "weekly") {
                $weekTotalWhole = 0;
                $weekTargetWhole = 0;
                for ($i = 0; $i < (count($thursdaysArray) - 1); $i++) {
                    $j = $i + 1;
                    $date = new DateTime($thursdaysArray[$j]);
                    $date->modify('-1 day');
                    $weekDays = fetchDistinctCallDatesWeekly($conn, $thursdaysArray[$i], $thursdaysArray[$i], $date->format('Y-m-d'));
                    $weekDays = array_map(function ($row) {
                        return $row['callDate'];
                    }, iterator_to_array($weekDays));
                    sort($weekDays);
                    $weekTotal = 0;
                    $weekTarget = 0;
                    foreach ($weekDays as $days) {
                        $holiday = fetchHolidays($conn, $days, $userRow['branch']);
                        if (!empty($holiday)) {

                        } else {
                            $leaveStatus = fetchLeaveStatus($conn, $days, $userName);
                            $leaveDuration = $leaveStatus['leave_duration'] ?? null;
                            $event = fetchEvent($conn, $days, $userRow['name']);
                            $eventDuration = $event['duration'] ?? null;
                            if ($eventDuration != null) {
                                $callCount = fetchCallCount($conn, $userName, $days);
                                $weekTotal += ($event['duration'] + $callCount['num_calls']);
                                $weekTarget += 8;
                            } elseif ($leaveDuration === null) {
                                // No leave, fetch call count
                                $callCount = fetchCallCount($conn, $userName, $days);
                                $weekTotal += $callCount['num_calls'];
                                $weekTarget += 8;
                            } elseif ($leaveDuration == 0.5) {
                                $weekTotal += 4;
                                $weekTarget += 8;
                            } else {
                                $weekTotal += 8;
                                $weekTarget += 8;
                            }
                        }
                    }
                    $weekTotalWhole += $weekTotal;
                    $weekTargetWhole += $weekTarget;
                    $eachPercent[] = calculateCompletionRatio($weekTotalWhole, $weekTargetWhole);
                    echo "<td class='dayCalls' style='background-color: #92d050'>$weekTotal</td>";
                }
                $result2DArray = splitArrayInto2D($eachPercent, count($labels));
                $completionRatio = calculateCompletionRatio($weekTotalWhole, $weekTargetWhole);
                echo "<td>{$weekTotalWhole}</td>";
                echo "<td>{$weekTargetWhole}</td>";
                echo "<td class='achievement' style='background-color: #8db3e2'>" . number_format($completionRatio, 0) . "%</td>";
                $percentages[] = number_format($completionRatio, 0);
            } elseif ($scopeSelect == "monthly") {
                $monthTotalWhole = 0;
                $monthTargetWhole = 0;
                // Define the mapping from month names to their corresponding month numbers
                $monthToNumberMap = [
                    'January' => 1,
                    'February' => 2,
                    'March' => 3,
                    'April' => 4,
                    'May' => 5,
                    'June' => 6,
                    'July' => 7,
                    'August' => 8,
                    'September' => 9,
                    'October' => 10,
                    'November' => 11,
                    'December' => 12,
                ];
                foreach ($filteredMonths as $month) {
                    $monthNumber = $monthToNumberMap[$month];
                    $callDatesResult = fetchDistinctCallDates($conn, $monthNumber, $currentYear);
                    $callDatesResult = array_map(function ($row) {
                        return $row['callDate'];
                    }, iterator_to_array($callDatesResult));
                    $monthTotal = 0;
                    $monthTarget = 0;
                    foreach ($callDatesResult as $days) {
                        $holiday = fetchHolidays($conn, $days, $userRow['branch']);
                        if (!empty($holiday)) {

                        } else {
                            $leaveStatusesResult = fetchLeaveStatus($conn, $days, $userName);
                            $leaveDuration = $leaveStatus['leave_duration'] ?? null;
                            $event = fetchEvent($conn, $days, $userRow['name']);
                            $eventDuration = $event['duration'] ?? null;
                            if ($eventDuration != null) {
                                $callCount = fetchCallCount($conn, $userName, $days);
                                $monthTotal += ($event['duration'] + $callCount['num_calls']);
                                $monthTarget += 8;
                            } elseif ($leaveDuration === null) {
                                $callCount = fetchCallCount($conn, $userName, $days);
                                $monthTotal += $callCount['num_calls'];
                                $monthTarget += 8;
                            } elseif ($leaveDuration == 0.5) {
                                $monthTotal += 4;
                                $monthTarget += 8;
                            } else {
                                $monthTotal += 8;
                                $monthTarget += 8;
                            }
                        }
                    }
                    $monthTotalWhole += $monthTotal;
                    $monthTargetWhole += $monthTarget;
                    $eachPercent[] = calculateCompletionRatio($monthTotalWhole, $monthTargetWhole);
                    echo "<td class='dayCalls' style='background-color: #92d050'>$monthTotal</td>";
                }
                $result2DArray = splitArrayInto2D($eachPercent, count($labels));
                $completionRatio = calculateCompletionRatio($monthTotalWhole, $monthTargetWhole);
                echo "<td>{$monthTotalWhole}</td>";
                echo "<td>{$monthTargetWhole}</td>";
                echo "<td class='achievement' style='background-color: #8db3e2'>" . number_format($completionRatio, 0) . "%</td>";
                $percentages[] = number_format($completionRatio, 0);
            } elseif ($scopeSelect == "yearly") {
                $yearTotalWhole = 0;
                $yearTargetWhole = 0;
                $years = fetchDistinctCallYears($conn);
                foreach ($years as $year) {
                    $monthTotalWhole = 0;
                    $monthTargetWhole = 0;
                    // Define the mapping from month names to their corresponding month numbers
                    $monthToNumberMap = [
                        'January' => 1,
                        'February' => 2,
                        'March' => 3,
                        'April' => 4,
                        'May' => 5,
                        'June' => 6,
                        'July' => 7,
                        'August' => 8,
                        'September' => 9,
                        'October' => 10,
                        'November' => 11,
                        'December' => 12,
                    ];
                    foreach ($filteredMonths as $month) {
                        $monthNumber = $monthToNumberMap[$month];
                        $callDatesResult = fetchDistinctCallDates($conn, $monthNumber, $year['year']);
                        $callDatesResult = array_map(function ($row) {
                            return $row['callDate'];
                        }, iterator_to_array($callDatesResult));
                        $monthTotal = 0;
                        $monthTarget = 0;
                        foreach ($callDatesResult as $days) {
                            $holiday = fetchHolidays($conn, $days, $userRow['branch']);
                            if (!empty($holiday)) {

                            } else {
                                $leaveStatusesResult = fetchLeaveStatus($conn, $days, $userName);
                                $leaveDuration = $leaveStatus['leave_duration'] ?? null;
                                $event = fetchEvent($conn, $days, $userRow['name']);
                                $eventDuration = $event['duration'] ?? null;
                                if ($eventDuration != null) {
                                    $callCount = fetchCallCount($conn, $userName, $days);
                                    $monthTotal += ($event['duration'] + $callCount['num_calls']);
                                    $monthTarget += 8;
                                } elseif ($leaveDuration === null) {
                                    $callCount = fetchCallCount($conn, $userName, $days);
                                    $monthTotal += $callCount['num_calls'];
                                    $monthTarget += 8;
                                } elseif ($leaveDuration == 0.5) {
                                    $monthTotal += 4;
                                    $monthTarget += 8;
                                } else {
                                    $monthTotal += 8;
                                    $monthTarget += 8;
                                }
                            }
                        }
                        $monthTotalWhole += $monthTotal;
                        $monthTargetWhole += $monthTarget;
                    }
                    $yearTotalWhole += $monthTotalWhole;
                    $yearTargetWhole += $monthTargetWhole;
                    $eachPercent[] = calculateCompletionRatio($yearTotalWhole, $yearTargetWhole);
                    echo "<td class='dayCalls' style='background-color: #92d050'>$yearTotalWhole</td>";
                }
                $result2DArray = splitArrayInto2D($eachPercent, count($labels));
                $completionRatio = calculateCompletionRatio($yearTotalWhole, $yearTargetWhole);
                echo "<td>{$yearTotalWhole}</td>";
                echo "<td>{$yearTargetWhole}</td>";
                echo "<td class='achievement' style='background-color: #8db3e2'>" . number_format($completionRatio, 0) . "%</td>";
                $percentages[] = number_format($completionRatio, 0);
            }


            // Calculate completion ratio
            if ($scopeSelect == "daily") {
                // Calculate monthly data and achievement
                $monthActual = 0;
                $monthTarget = 0;
                $dayMonthDates = fetchDistinctCallDates($conn, $currentMonth, $currentYear);
                foreach ($dayMonthDates as $callDate) {
                    $callDateValue = $callDate['callDate'];
                    $leaveStatus = fetchLeaveStatus($conn, $callDateValue, $userName);
                    $leaveDuration = $leaveStatus['leave_duration'] ?? null;

                    $monthTarget += 8;

                    if ($leaveDuration === null) {
                        // No leave, fetch call count
                        $count = fetchCallCount($conn, $userName, $callDateValue);
                        $monthActual += $count['num_calls'];
                    } else {
                        // Calculate based on leave duration
                        $monthActual += 8 * $leaveDuration;
                    }
                }
                if (isset($_POST['callDateStart']) && isset($_POST['callDateEnd'])) {

                } else {
                    // Display monthly data and achievement
                    echo "<td class='actual' style='color: #4f81bd'>{$monthActual}</td>";
                    echo "<td>{$monthTarget}</td>";
                    $achievement = calculateCompletionRatio($monthActual, $monthTarget);
                    echo "<td class='achievement' style='background-color: #8db3e2'>" . number_format($achievement, 0) . "%</td>";
                }
            } else {
                echo "";
            }

            echo "</tr>";
        }
    } else {
        break;
    }
}
?>
</tbody>
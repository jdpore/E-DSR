<thead class='bg-white text-white sticky-header'>
    <tr>
        <?php
        if ($table === 'leave' || $table === 'event') {
            echo "<th scope='col'>AccountExecutive</th>";
        }
        if ($table === 'holiday') {
            echo "<th scope='col'>Branch</th>";
        }
        foreach ($datesArray as $date) {
            echo "<th scope='col'>$date</th>";
        }
        ?>
    </tr>
</thead>
<tbody>

    <?php
    if ($table === 'leave') {
        foreach ($employee_list_result as $employee_list_row) {
            echo "<tr><th>" . $employee_list_row['name'] . "</th>";
            foreach ($datesArray as $date) {
                $leaveData = "SELECT * FROM leave_status WHERE employee_name = '" . $employee_list_row['name'] . "' AND leave_date = '$date'";
                $leaveDataResult = mysqli_query($conn, $leaveData);
                $leaveDataRow = mysqli_fetch_assoc($leaveDataResult);
                echo "<td>";
                if (empty($leaveDataRow['leave_duration'])) {
                    echo 'No Leave';
                } elseif ($leaveDataRow['leave_duration'] == 1) {
                    echo 'Full Day';
                } elseif ($leaveDataRow['leave_duration'] == 0.5) {
                    echo 'Half Day';
                }
                echo "</td>";
            }
            echo "</tr>";
        }
    }
    if ($table === 'event') {
        foreach ($employee_list_result as $employee_list_row) {
            echo "<tr><th>" . $employee_list_row['name'] . "</th>";
            foreach ($datesArray as $date) {
                $eventData = "SELECT * FROM event WHERE employee_name = '" . $employee_list_row['name'] . "' AND date = '$date'";
                $eventDataResult = mysqli_query($conn, $eventData);
                $eventDataRow = mysqli_fetch_assoc($eventDataResult);
                echo "<td>";
                if (empty($eventDataRow['duration'])) {
                    echo "No Activity";
                } else {
                    echo $eventDataRow['type'], ' ', $eventDataRow['duration'], 'hrs';
                }
                echo "</td>";
            }
            echo "</tr>";
        }
    }
    if ($table === 'holiday') {
        foreach ($branchResult as $branchRow) {
            echo "<tr><th>" . $branchRow['branch'] . "</th>";
            foreach ($datesArray as $date) {
                $holidayData = "SELECT * FROM holidays WHERE (branch = '" . $branchRow['branch'] . "' OR branch = 'All') AND date = '$date'";
                $holidayDataResult = mysqli_query($conn, $holidayData);
                $holidayDataRow = mysqli_fetch_assoc($holidayDataResult);
                echo "<td>";
                if (empty($holidayDataRow['branch'])) {
                    echo 'Regular';
                } else {
                    echo 'Holiday';
                }
                echo "</td>";
            }
            echo "</tr>";
        }
        echo "</tr>";
    }

    ?>
</tbody>
<?php 
include 'db.php';
$attendanceRecords = null;
$dateFrom = '';
$dateTo = '';
mysqli_report(MYSQLI_REPORT_OFF);

// --- DELETE ATTENDANCE RECORD ---
if (isset($_GET['del'])){
    $attRN = mysqli_real_escape_string($conn, $_GET['del']);
    mysqli_query($conn, "DELETE FROM attendance WHERE attRN = '$attRN'");
    header("Location: attendance_monitoring_range.php");
    exit();
}

// --- FILTER BY DATE RANGE ---
if (isset($_POST['filter'])){
    $dateFrom = mysqli_real_escape_string($conn, $_POST['dateFrom']);
    $dateTo = mysqli_real_escape_string($conn, $_POST['dateTo']);

    if ($dateFrom && $dateTo) {
        $attendanceRecords = mysqli_query($conn, "
            SELECT * FROM attendance 
            WHERE attDate BETWEEN '$dateFrom' AND '$dateTo'
            ORDER BY attDate, attTimeIn
        ");
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Attendance Monitoring by Date Range</title>
</head>
<body>
    <div class="container">
        <h1 class="page-title">Attendance Monitoring by Date Range</h1>
        <hr>

        <form method="POST" class="form-row">
            <div class="form-group">
                <input type="date" name="dateFrom" class="form-input" value="<?php echo $dateFrom; ?>" required>
            </div>
            <div class="form-group">
                <input type="date" name="dateTo" class="form-input" value="<?php echo $dateTo; ?>" required>
            </div>
            <div class="form-actions">
                <button type="submit" name="filter" class="btn btn-primary">Filter</button>
                <a href="index.php" class="btn btn-secondary">Back to Home</a>
            </div>
        </form>

        <?php if (isset($_POST['filter'])): ?>
            <table class="data-table">
                <tr>
                    <th>Record #</th><th>Emp. ID</th><th>Date/Time In</th><th>Date/Time Out</th><th>Total Hours</th><th>Action</th>
                </tr>
                <?php 
                if ($attendanceRecords && mysqli_num_rows($attendanceRecords) > 0) {
                    while($row = mysqli_fetch_assoc($attendanceRecords)) {
                        $dateTimeIn = $row['attDate'] . ' ' . $row['attTimeIn'];
                        $dateTimeOut = $row['attTimeOut'] ? $row['attDate'] . ' ' . $row['attTimeOut'] : '-';

                        if ($row['attTimeIn'] && $row['attTimeOut']) {
                            $timeIn = new DateTime($row['attTimeIn']);
                            $timeOut = new DateTime($row['attTimeOut']);
                            $diff = $timeIn->diff($timeOut);
                            $hours = $diff->h + ($diff->i / 60);
                            $totalHours = number_format($hours, 2);
                        } else {
                            $totalHours = '-';
                        }

                        echo "<tr>
                            <td>{$row['attRN']}</td>
                            <td>{$row['empID']}</td>
                            <td>{$dateTimeIn}</td>
                            <td>{$dateTimeOut}</td>
                            <td>{$totalHours}</td>
                            <td>
                                <a href='?del={$row['attRN']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                            </td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' class='text-center'>No records found for the selected date range.</td></tr>";
                }
                ?>
            </table>

            <p><strong>Date Generated:</strong> <?php echo date('Y-m-d'); ?></p>
        <?php endif; ?>
    </div>
</body>
</html>

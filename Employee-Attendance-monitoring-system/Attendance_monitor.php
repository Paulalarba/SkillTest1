<?php 
include 'db.php';
$editData = null;
$employee = null;
$attendanceRecords = null;
$searchEmpID = '';
mysqli_report(MYSQLI_REPORT_OFF);

// --- DELETE ATTENDANCE RECORD ---
if (isset($_GET['del'])){
    $attRN = mysqli_real_escape_string($conn, $_GET['del']);
    mysqli_query($conn, "DELETE FROM attendance WHERE attRN = '$attRN'");
    header("Location: Attendance_monitor.php");
    exit();
}

// --- SEARCH EMPLOYEE ---
if (isset($_POST['search'])){
    $searchEmpID = mysqli_real_escape_string($conn, $_POST['empID']);

    $empResult = mysqli_query($conn, "
        SELECT e.*, d.depName 
        FROM employees e 
        LEFT JOIN departments d ON e.depCode = d.depCode 
        WHERE e.empID = '$searchEmpID'
    ");
    $employee = mysqli_fetch_assoc($empResult);

    if ($employee) {
        $attendanceRecords = mysqli_query($conn, "
            SELECT * FROM attendance 
            WHERE empID = '$searchEmpID' 
            ORDER BY attRN
        ");
    } else {
        echo "<script>alert('Error: Employee ID $searchEmpID does not exist!');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Attendance Monitoring</title>
</head>
<body>
    <div class="container">
        <h1 class="page-title">Attendance Monitoring</h1>
        <hr>

        <form method="POST" class="form-row">
            <div class="form-group">
                <input type="number" name="empID" class="form-input" placeholder="Employee #"
                    value="<?php echo $searchEmpID; ?>" required>
            </div>
            <div class="form-actions">
                <button type="submit" name="search" class="btn btn-primary">Search</button>
                <a href="index.php" class="btn btn-secondary">Back to Home</a>
            </div>
        </form>

        <?php if ($employee): ?>
            <div class="info-card">
                <h3><?php echo $employee['empFName'] . ' ' . $employee['empLName']; ?></h3>
                <p><strong>Department:</strong> <?php echo $employee['depName'] ?? 'N/A'; ?></p>
            </div>

            <table class="data-table">
                <tr>
                    <th>Record #</th><th>Emp. ID</th><th>Date/Time In</th><th>Date/Time Out</th><th>Total Hours</th><th>Action</th>
                </tr>
                <?php 
                $totalHoursWorked = 0;
                while($row = mysqli_fetch_assoc($attendanceRecords)) {
                    $dateTimeIn = $row['attDate'] . ' ' . $row['attTimeIn'];
                    $dateTimeOut = $row['attTimeOut'] ? $row['attDate'] . ' ' . $row['attTimeOut'] : '-';

                    if ($row['attTimeIn'] && $row['attTimeOut']) {
                        $timeIn = new DateTime($row['attTimeIn']);
                        $timeOut = new DateTime($row['attTimeOut']);
                        $diff = $timeIn->diff($timeOut);
                        $hours = $diff->h + ($diff->i / 60);
                        $totalHoursWorked += $hours;
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
                ?>
            </table>

            <div class="info-card">
                <h3>Summary</h3>
                <p><strong>Rate Per Hour:</strong> <?php echo number_format($employee['empRPH'], 2); ?></p>
                <p><strong>Total Hours Worked:</strong> <?php echo number_format($totalHoursWorked, 2); ?></p>
                <p><strong>Salary:</strong> <?php echo number_format($employee['empRPH'] * $totalHoursWorked, 2); ?></p>
                <p><strong>Date Generated:</strong> <?php echo date('Y-m-d'); ?></p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>

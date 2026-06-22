<?php 
include 'db.php';
$editData = null;
mysqli_report(MYSQLI_REPORT_OFF);

// --- 1. FETCH DATA FOR EDITING ---
if (isset($_GET['edit'])) {
    $editRN = mysqli_real_escape_string($conn, $_GET['edit']);
    $result = mysqli_query($conn, "SELECT * FROM attendance WHERE attRN = '$editRN'");
    $editData = mysqli_fetch_assoc($result);
}

// --- 2. ADD NEW ATTENDANCE RECORD ---
if(isset($_POST['Save'])){
    $empID = mysqli_real_escape_string($conn, $_POST['empID']);
    $dateTimeIn = mysqli_real_escape_string($conn, $_POST['dateTimeIn']);
    $dateTimeOut = mysqli_real_escape_string($conn, $_POST['dateTimeOut']);

    $empCheck = mysqli_query($conn, "SELECT COUNT(*) AS cnt FROM employees WHERE empID = '$empID'");
    $empRow = mysqli_fetch_assoc($empCheck);

    if ($empRow['cnt'] == 0) {
        echo "<script>alert('Error: Employee ID $empID does not exist!');</script>";
    } elseif (!preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}(:\d{2})?$/', $dateTimeIn)) {
        echo "<script>alert('Error: Invalid Date/Time In format. Use YYYY-MM-DD HH:MM');</script>";
    } elseif ($dateTimeOut !== '' && !preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}(:\d{2})?$/', $dateTimeOut)) {
        echo "<script>alert('Error: Invalid Date/Time Out format. Use YYYY-MM-DD HH:MM');</script>";
    } else {
        $attDate = substr($dateTimeIn, 0, 10);
        $attTimeIn = substr($dateTimeIn, 11);
        $attTimeOut = $dateTimeOut !== '' ? "'" . substr($dateTimeOut, 11) . "'" : 'NULL';

        $sql = "INSERT INTO attendance (empID, attDate, attTimeIn, attTimeOut) 
                VALUES ('$empID', '$attDate', '$attTimeIn', $attTimeOut)";
                
        if (mysqli_query($conn, $sql)) {
            header("Location: Attendance_record.php"); 
            exit();
        } else {
            echo "<script>alert('Error: Unable to save attendance record!');</script>";
        }
    }
}

// --- 3. UPDATE EXISTING ATTENDANCE RECORD ---
if (isset($_POST['update'])){
    $attRN = mysqli_real_escape_string($conn, $_POST['attRN']);
    $empID = mysqli_real_escape_string($conn, $_POST['empID']);
    $dateTimeIn = mysqli_real_escape_string($conn, $_POST['dateTimeIn']);
    $dateTimeOut = mysqli_real_escape_string($conn, $_POST['dateTimeOut']);

    $empCheck = mysqli_query($conn, "SELECT COUNT(*) AS cnt FROM employees WHERE empID = '$empID'");
    $empRow = mysqli_fetch_assoc($empCheck);

    if ($empRow['cnt'] == 0) {
        echo "<script>alert('Error: Employee ID $empID does not exist!');</script>";
    } elseif (!preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}(:\d{2})?$/', $dateTimeIn)) {
        echo "<script>alert('Error: Invalid Date/Time In format. Use YYYY-MM-DD HH:MM');</script>";
    } elseif ($dateTimeOut !== '' && !preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}(:\d{2})?$/', $dateTimeOut)) {
        echo "<script>alert('Error: Invalid Date/Time Out format. Use YYYY-MM-DD HH:MM');</script>";
    } else {
        $attDate = substr($dateTimeIn, 0, 10);
        $attTimeIn = substr($dateTimeIn, 11);
        $attTimeOut = $dateTimeOut !== '' ? "'" . substr($dateTimeOut, 11) . "'" : 'NULL';

        $sql = "UPDATE attendance 
                SET empID = '$empID', 
                attDate = '$attDate', 
                attTimeIn = '$attTimeIn', 
                attTimeOut = $attTimeOut 
                WHERE attRN = '$attRN'";
        mysqli_query($conn, $sql);
        header("Location: Attendance_record.php");
        exit();
    }
}

// --- 4. DELETE ATTENDANCE RECORD ---
if (isset($_GET['del'])){
    $attRN = mysqli_real_escape_string($conn, $_GET['del']);
    mysqli_query($conn, "DELETE FROM attendance WHERE attRN = '$attRN'");
    header("Location: Attendance_record.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Attendance Recording</title>
</head>
<body>
    <div class="container">
        <h1 class="page-title">Attendance Recording</h1>
        <hr>
        
        <form method="POST" class="form-row">
            <div class="form-group">
                <input type="number" name="empID" class="form-input" placeholder="Emp. ID"
                    value="<?php echo $editData['empID'] ?? ''; ?>" required>
            </div>
            <div class="form-group">
                <input type="text" name="dateTimeIn" class="form-input" placeholder="YYYY-MM-DD HH:MM"
                    value="<?php 
                        if ($editData) {
                            echo $editData['attDate'] . ' ' . $editData['attTimeIn'];
                        }
                    ?>" required>
            </div>
            <div class="form-group">
                <input type="text" name="dateTimeOut" class="form-input" placeholder="YYYY-MM-DD HH:MM"
                    value="<?php 
                        if ($editData && $editData['attTimeOut']) {
                            echo $editData['attDate'] . ' ' . $editData['attTimeOut'];
                        }
                    ?>">
            </div>
            <div class="form-actions">
                <?php if (isset($editData)): ?>
                    <input type="hidden" name="attRN" value="<?php echo $editData['attRN']; ?>">
                    <button type="submit" name="update" class="btn btn-success">Update Attendance</button>
                    <a href="Attendance_record.php" class="btn btn-secondary">Cancel</a>
                <?php else: ?>
                    <button type="submit" name="Save" class="btn btn-primary">Add Attendance</button>
                <?php endif; ?>
                <a href="index.php" class="btn btn-secondary">Back to Home</a>
            </div>
        </form>

        <table class="data-table">
            <tr>
                <th>Record #</th><th>Emp. ID</th><th>Date/Time In</th><th>Date/Time Out</th><th>Action</th>
            </tr>
            <?php 
            $res = mysqli_query($conn, "SELECT * FROM attendance ORDER BY attRN");
            while($row = mysqli_fetch_assoc($res)) {
                $dateTimeIn = $row['attDate'] . ' ' . $row['attTimeIn'];
                $dateTimeOut = $row['attTimeOut'] ? $row['attDate'] . ' ' . $row['attTimeOut'] : '-';
                echo "<tr>
                    <td>{$row['attRN']}</td>
                    <td>{$row['empID']}</td>
                    <td>{$dateTimeIn}</td>
                    <td>{$dateTimeOut}</td>
                    <td>
                        <a href='?del={$row['attRN']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                        <a href='?edit={$row['attRN']}' class='btn btn-warning btn-sm'>Edit</a>
                    </td>
                </tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>

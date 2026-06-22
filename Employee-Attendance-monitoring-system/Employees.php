<?php
include 'db.php';
$editData = null;
mysqli_report(MYSQLI_REPORT_OFF);

// --- 1. EDIT DATA ---
if (isset($_GET['edit'])){
    $editId = mysqli_real_escape_string($conn, $_GET['edit']);
    $result = mysqli_query($conn, "SELECT * FROM employees WHERE empID = '$editId'");
    $editData = mysqli_fetch_assoc($result);
}

// --- 2. ADD EMPLOYEES LOGIC ---
if(isset($_POST['save'])){
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $code = mysqli_real_escape_string($conn, $_POST['code']);
    $FName = mysqli_real_escape_string($conn, $_POST['FName']);
    $LName = mysqli_real_escape_string($conn, $_POST['LName']);
    $empRatePH = mysqli_real_escape_string($conn, $_POST['empRatePH']);

    $sql = "INSERT INTO employees(empID, depCode, empFName, empLName, empRPH) 
            VALUES('$id', '$code', '$FName', '$LName', '$empRatePH')";

    if (mysqli_query($conn, $sql)) {
        header("location: Employees.php");
        exit();
    } else {
        echo "<script>alert('Error: This Employee ID already exists!');</script>";
    }
}

// --- 3. UPDATE EMPLOYEES LOGIC ---
if(isset($_POST['update'])){
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $code = mysqli_real_escape_string($conn, $_POST['code']);
    $FName = mysqli_real_escape_string($conn, $_POST['FName']);
    $LName = mysqli_real_escape_string($conn, $_POST['LName']);
    $empRatePH = mysqli_real_escape_string($conn, $_POST['empRatePH']);

    $sql = "UPDATE employees
            SET depCode = '$code',
            empFName = '$FName',
            empLName = '$LName',
            empRPH = '$empRatePH'
            WHERE empID = '$id'";
            
    mysqli_query($conn, $sql);
    header("location: Employees.php");
    exit();
}   

// --- 4. DELETE EMPLOYEE LOGIC ---
if (isset($_GET['del'])){
    $id = mysqli_real_escape_string($conn, $_GET['del']);
    mysqli_query($conn, "DELETE FROM employees WHERE empID = '$id'");
    header("location: Employees.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Employees Management</title>
</head>
<body>
    <div class="container">
        <h1 class="page-title">Employees Management</h1>
        <hr>

        <form method="POST" class="form-row">
            <div class="form-group">
                <input type="number" name="id" class="form-input" placeholder="Employee ID"
                    value="<?php echo $editData['empID'] ?? ''; ?>" 
                    <?php echo $editData ? 'readonly' : ''; ?> required>
            </div>
            <div class="form-group">
                <select name="code" class="form-input" required>
                    <option value="">-- Select Department --</option>
                    <?php
                    $deptRes = mysqli_query($conn, "SELECT depCode, depName FROM departments");
                    while ($dept = mysqli_fetch_assoc($deptRes)) {
                        $selected = ($editData && $editData['depCode'] == $dept['depCode']) ? "selected" : "";
                        echo "<option value='{$dept['depCode']}' $selected>{$dept['depName']} ({$dept['depCode']})</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <input type="text" name="FName" class="form-input" placeholder="First Name"
                value="<?php echo $editData['empFName'] ?? ''; ?>" required>
            </div>
            <div class="form-group">
                <input type="text" name="LName" class="form-input" placeholder="Last Name"
                value="<?php echo $editData['empLName'] ?? ''; ?>" required>
            </div>
            <div class="form-group">
                <input type="text" name="empRatePH" class="form-input" placeholder="Rate per hour"
                value="<?php echo $editData['empRPH'] ?? ''; ?>" required>
            </div>
            <div class="form-actions">
                <?php if (isset($editData)): ?>
                    <button type="submit" name="update" class="btn btn-success">Update Employee</button>
                    <a href="Employees.php" class="btn btn-secondary">Cancel</a>
                <?php else: ?>
                    <button type="submit" name="save" class="btn btn-primary">Add Employee</button>
                <?php endif; ?>
                <a href="index.php" class="btn btn-secondary">Back to Home</a>
            </div>
        </form>

        <table class="data-table">
            <tr>
                <th>Id</th><th>Code</th><th>First Name</th><th>Last Name</th><th>Rate per hour</th><th>Action</th>
            </tr>
            <?php 
            $res = mysqli_query($conn, "SELECT * FROM employees");
            while($row = mysqli_fetch_assoc($res)){
                echo "<tr>
                    <td>{$row['empID']}</td>
                    <td>{$row['depCode']}</td>
                    <td>{$row['empFName']}</td>
                    <td>{$row['empLName']}</td>
                    <td>{$row['empRPH']}</td>
                    <td>
                        <a href='?del={$row['empID']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                        <a href='?edit={$row['empID']}' class='btn btn-warning btn-sm'>Edit</a>
                    </td>
                </tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>

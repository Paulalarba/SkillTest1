<!--create php function-->
<?php
include 'db.php';
$editData = null;

//edit data
if (isset($_GET['edit'])){
    $editId = $_GET['edit'];
    $result = mysqli_query($conn, "SELECT * FROM employees WHERE empID = '$editId'");
    $editData = mysqli_fetch_assoc($result);
}
//Add Employees logic
if(isset($_POST['save'])){
    $id = $_POST['id'];
    $code = $_POST['code'];
    $FName = $_POST['FName'];
    $LName = $_POST['LName'];
    $empRatePH = $_POST['empRatePH'];
    $sql = "INSERT INTO employees(empID, depCode, empFName, empLName, empRPH)
            VALUES('$id', '$code', '$FName', '$LName', '$empRatePH')";

    mysqli_query($conn, $sql);
    header("location: Employees.php");
}
//Update Employees logic
if(isset($_POST['update'])){
    $id = $_POST['id'];
    $code = $_POST['code'];
    $FName = $_POST['FName'];
    $LName = $_POST['LName'];
    $empRatePH = $_POST['empRatePH'];

    $sql = "UPDATE employees
            SET depCode = '$code',
            empFName = '$FName',
            empLName = '$LName',
            empRPH = '$empRatePH'
            WHERE empID = '$id'";
        mysqli_query($conn, $sql);
        header("location: Employees.php");
}   


//Delete employee logic
if (isset($_GET['del'])){
    $id = $_GET['del'];
    mysqli_query($conn, "DELETE FROM employees WHERE empID = '$id'");
    header("location: Employees.php");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <title>Employees Management</title>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Employees Management</h1>
        <hr>

        <form method="POST" class="row g-3 mb-4">
            <div class="col-md-3">
                <input type="number" name="id" class="form-control" placeholder="Employee ID"
                    value="<?php echo $editData['empID'] ?? ''; ?>" 
                    <?php echo $editData ? 'readonly' : ''; ?> required>
            </div>
            <div class="col-md-3">
                <select name="code" class="form-control" required>
                    <?php
                    $deptRes = mysqli_query($conn, "SELECT depCode, depName FROM departments");
                    while ($dept = mysqli_fetch_assoc($deptRes)) {
                        $selected = ($editData && $editData['depCode'] == $dept['depCode']) ? "selected" : "";
                        echo "<option value='{$dept['depCode']}' $selected>{$dept['depName']} ({$dept['depCode']})</option>";
                    }
                    ?>
                </select>

            </div>
            <div class="col-md-3">
                <input type="text" name="FName" class="form-control" placeholder="Last Name"
                value="<?php echo $editData['empFName'] ?? ''; ?>" required>
            </div>
            <div class="col-md-3">
                <input type="text" name="LName" class="form-control" placeholder="Employee lastName"
                value="<?php echo $editData['empLName'] ?? ''; ?>" required>
            </div>
            <div class="col-md-3">
                <input type="text" name="empRatePH" class="form-control" placeholder="Employee Rate per hour"
                value="<?php echo $editData['empRPH'] ?? ''; ?>" required>
            </div>
            <div class="col-12">
                <?php if (isset($editData)): ?>
                    <button type="submit" name="update" class="btn btn-success">Update Employee</button>
                    <a href="Employees.php" class="btn btn-secondary">cancel</a>

                <?php else: ?>
                    <button type="submit" name="save" class="btn btn-primary">Add Employee</button>
                <?php endif; ?>
                <a href="index.php" class="btn btn-secondary">Back to Home</a>
            </div>
        </form>
        <table class="table table-bordered">
            <tr class="table-dark">
                <th>Id</th>
                <th>Code</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Rate per hour</th>
                <th>Action</th>
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
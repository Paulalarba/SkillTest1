<?php
//Add Departmentl logic
include 'db.php';
if(isset($_POST ['Save'])){
    $code = $_POST['code'];
    $name = $_POST['name'];
    $head = $_POST['head'];
    $telNo = $_POST['telNo'];
    
    $sql = "INSERT INTO departments (depCode, depName, depHead, depTellNo) 
            VALUES ('$code', '$name', '$head', '$telNo')";
    mysqli_query($conn, $sql);

}
if (isset($_GET['del'])){
    $id = $_GET['id'];
    mysqli_query($conn, "DELETE FROM departments WHERE depCode = $id");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <title>Departments Management</title>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Departments Management</h1>
        <hr>
        <form method="POST" class="row g-3 mb-4">
            <div class="col-md-3">
                <input type="text" name="code" class="form-control" placeholder="Department Code" required>
            </div>
            <div class="col-md-3">
                <input type="text" name="name" class="form-control" placeholder="Department Name" required>
            </div>
            <div class="col-md-3">
                <input type="text" name="head" class="form-control" placeholder="Department Head" required>
            </div>
            <div class="col-md-3">
                <input type="text" name="telNo" class="form-control" placeholder="Department Tel No." required>
            </div>
            <div class="col-12">
                <button type="submit" name="Save" class="btn btn-primary">Add Department</button>
            </div>

        </form>

        <table class="table table-bordered">
        <tr class="table-dark"><th>Code</th><th>Name</th><th>Head</th><th>Tel</th><th>Action</th></tr>
        <?php 
        // Select all records from the Departments table
        $res = mysqli_query($conn, "SELECT * FROM Departments");
        // while loop fetches each row one by one as an associative array
        while($row = mysqli_fetch_assoc($res)) {
            echo "<tr>
                <td>{$row['depCode']}</td>
                <td>{$row['depName']}</td>
                <td>{$row['depHead']}</td>
                <td>{$row['depTelNo']}</td>
                <td><a href='?del={$row['depCode']}' class='btn btn-danger btn-sm'>Delete</a></td>
            </tr>";
        }
        ?>
    </table>
    </div>
</body>
</html>

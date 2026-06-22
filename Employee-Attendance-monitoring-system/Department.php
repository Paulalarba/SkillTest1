<?php
include 'db.php';
mysqli_report(MYSQLI_REPORT_OFF);
$editData = null;

// --- 1. FETCH DATA FOR EDITING ---
if (isset($_GET['edit'])) {
    $editCode = mysqli_real_escape_string($conn, $_GET['edit']);
    $result = mysqli_query($conn, "SELECT * FROM departments WHERE depCode = '$editCode'");
    $editData = mysqli_fetch_assoc($result);
}

// --- 2. ADD NEW DEPARTMENT ---
if(isset($_POST['Save'])){
    $code = mysqli_real_escape_string($conn, $_POST['code']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $head = mysqli_real_escape_string($conn, $_POST['head']);
    $tellNo = mysqli_real_escape_string($conn, $_POST['tellNo']);
    
    $sql = "INSERT INTO departments (depCode, depName, depHead, depTellNo) 
            VALUES ('$code', '$name', '$head', '$tellNo')";
            
    if (mysqli_query($conn, $sql)) {
        header("Location: Department.php"); 
        exit();
    } else {
        echo "<script>alert('Error: This Department Code already exists!');</script>";
    }
}

// --- 3. UPDATE EXISTING DEPARTMENT ---
if (isset($_POST['update'])){
    $code = mysqli_real_escape_string($conn, $_POST['code']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $head = mysqli_real_escape_string($conn, $_POST['head']);
    $tellNo = mysqli_real_escape_string($conn, $_POST['tellNo']);

    $sql = "UPDATE departments 
            SET depName = '$name', 
            depHead = '$head', 
            depTellNo = '$tellNo' 
            WHERE depCode = '$code'";
    mysqli_query($conn, $sql);
    header("Location: Department.php");
    exit();
}

// --- 4. DELETE DEPARTMENT ---
if (isset($_GET['del'])){
    $code = mysqli_real_escape_string($conn, $_GET['del']);
    mysqli_query($conn, "DELETE FROM departments WHERE depCode = '$code'");
    header("Location: Department.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Departments Management</title>
</head>
<body>
    <div class="container">
        <h1 class="page-title">Departments Management</h1>
        <hr>
        
        <form method="POST" class="form-row">
            <div class="form-group">
                <input type="text" name="code" class="form-input" placeholder="Department Code"
                    value="<?php echo $editData['depCode'] ?? ''; ?>"
                    <?php echo $editData ? 'readonly' : ''; ?> required>
            </div>
            <div class="form-group">
                <input type="text" name="name" class="form-input" placeholder="Department Name" 
                value="<?php echo $editData['depName'] ?? ''; ?>" required>
            </div>
            <div class="form-group">
                <input type="text" name="head" class="form-input" placeholder="Department Head" 
                value="<?php echo $editData['depHead'] ?? ''; ?>" required>
            </div>
            <div class="form-group">
                <input type="text" name="tellNo" class="form-input" placeholder="Department Tell No." 
                value="<?php echo $editData['depTellNo'] ?? ''; ?>" required>
            </div>
            <div class="form-actions">
                <?php if (isset($editData)): ?>
                    <button type="submit" name="update" class="btn btn-success">Update Department</button>
                    <a href="Department.php" class="btn btn-secondary">Cancel</a>
                <?php else: ?>
                    <button type="submit" name="Save" class="btn btn-primary">Add Department</button>
                <?php endif; ?>
                <a href="index.php" class="btn btn-secondary">Back to Home</a>
            </div>
        </form>

        <table class="data-table">
            <tr>
                <th>Code</th><th>Name</th><th>Head</th><th>Tell No.</th><th>Action</th>
            </tr>
            <?php 
            $res = mysqli_query($conn, "SELECT * FROM departments");
            while($row = mysqli_fetch_assoc($res)) {
                echo "<tr>
                    <td>{$row['depCode']}</td>
                    <td>{$row['depName']}</td>
                    <td>{$row['depHead']}</td>
                    <td>{$row['depTellNo']}</td>
                    <td>
                        <a href='?del={$row['depCode']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                        <a href='?edit={$row['depCode']}' class='btn btn-warning btn-sm'>Edit</a>
                    </td>
                </tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>

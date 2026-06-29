<?php
require_once 'dp.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idNum = $_POST['idNum'];
    $campus = $_POST['campus'];
    $fName = $_POST['studFName'];
    $lName = $_POST['studLName'];
    $amount = $_POST['amountPaid'];
    $attended = ($_POST['attended'] == 'yes') ? 1 : 0;

    $sql = "UPDATE registration SET campus='$campus', studFName='$fName', studLName='$lName', amountPaid='$amount', attended='$attended' WHERE idNum='$idNum'";

    if (mysqli_query($conn, $sql)) {
        $message = "Record updated successfully!";
    } else {
        $message = "Error: " . mysqli_error($conn);
    }
}

$id = $_GET['id'] ?? '';
$result = mysqli_query($conn, "SELECT * FROM registration WHERE idNum='$id'");
$row = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html>
<head><title>Edit Student</title></head>
<body style="font-family: sans-serif;">
    <div style="text-align: right;">
        <a href="index.php" style="text-decoration: none; background: #eee; padding: 5px 10px; border: 1px solid #ccc; border-radius: 3px; color: black;">Back to Home</a>
    </div>
    <h2>Edit Student</h2>
    <p><?php echo $message; ?></p>
    <form method="POST">
        ID Number (Read-Only): <input type="text" name="idNum" value="<?php echo $row['idNum']; ?>" readonly><br><br>
        Campus: <input type="text" name="campus" value="<?php echo $row['campus']; ?>" required><br><br>
        First Name: <input type="text" name="studFName" value="<?php echo $row['studFName']; ?>" required><br><br>
        Last Name: <input type="text" name="studLName" value="<?php echo $row['studLName']; ?>" required><br><br>
        Amount Paid: <input type="number" step="0.01" name="amountPaid" value="<?php echo $row['amountPaid']; ?>" required><br><br>
        Attended: 
        <select name="attended">
            <option value="yes" <?php if($row['attended']) echo 'selected'; ?>>Yes</option>
            <option value="no" <?php if(!$row['attended']) echo 'selected'; ?>>No</option>
        </select><br><br>
        <input type="submit" value="Update">
        <a href="Registration.php">Cancel</a>
    </form>
</body>
</html>

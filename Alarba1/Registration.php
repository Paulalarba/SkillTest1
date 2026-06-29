<?php
require_once 'dp.php';

$message = "";

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM registration WHERE idNum = '$id'";
    if (mysqli_query($conn, $sql)) {
        $message = "Record deleted successfully!";
    } else {
        $message = "Error deleting record: " . mysqli_error($conn);
    }
}

// Handle Insert
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $idNum = $_POST['idNum'];
    $campus = $_POST['campus'];
    $fName = $_POST['studFName'];
    $lName = $_POST['studLName'];
    $amount = $_POST['amountPaid'];
    $attended = ($_POST['attended'] == 'yes') ? 1 : 0;

    $sql = "INSERT INTO registration (idNum, campus, studFName, studLName, amountPaid, attended) 
            VALUES ('$idNum', '$campus', '$fName', '$lName', '$amount', '$attended')";

    if (mysqli_query($conn, $sql)) {
        $message = "Student registered successfully!";
    } else {
        $message = "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registration</title>
</head>
<body>
    <div style="text-align: right;">
        <a href="index.php" style="text-decoration: none; background: #eee; padding: 5px 10px; border: 1px solid #ccc; border-radius: 3px; color: black;">Back to Home</a>
    </div>

    <p style="color: green;"><?php echo $message; ?></p>
    
    <form method="POST">
        <input type="hidden" name="register" value="1">
        ID Number: <input type="text" name="idNum" required><br><br>
        Campus: <input type="text" name="campus" required><br><br>
        First Name: <input type="text" name="studFName" required><br><br>
        Last Name: <input type="text" name="studLName" required><br><br>
        Amount Paid: <input type="number" step="0.01" name="amountPaid" required><br><br>
        Attended: 
        <select name="attended">
            <option value="yes">Yes</option>
            <option value="no">No</option>
        </select><br><br>
        <input type="submit" name="register" value="Register">
    </form>

    <hr>
    <h2>Registered Students</h2>
    <table border="1" cellpadding="10">
        <tr>
            <th>ID</th>
            <th>Campus</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Paid</th>
            <th>Attended</th>
            <th>Actions</th>
        </tr>
        <?php
        $result = mysqli_query($conn, "SELECT * FROM registration");
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                <td>{$row['idNum']}</td>
                <td>{$row['campus']}</td>
                <td>{$row['studFName']}</td>
                <td>{$row['studLName']}</td>
                <td>{$row['amountPaid']}</td>
                <td>" . ($row['attended'] ? 'Yes' : 'No') . "</td>
                <td>
                    <a href='edit.php?id={$row['idNum']}'>Edit</a> | 
                    <a href='Registration.php?delete={$row['idNum']}' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                </td>
            </tr>";
        }
        ?>
    </table>
</body>
</html>

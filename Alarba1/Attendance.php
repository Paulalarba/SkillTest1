<?php
require_once 'dp.php';

$message = "";
$student = null;

// Handle Attendance Action
if (isset($_POST['mark_attended'])) {
    $idNum = $_POST['idNum'];
    
    // Check if already attended
    $check = mysqli_query($conn, "SELECT attended FROM registration WHERE idNum = '$idNum'");
    $row = mysqli_fetch_assoc($check);
    
    if ($row && $row['attended'] == 1) {
        $message = "Student's Attendance RECORD ALREADY EXISTS";
    } else {
        $sql = "UPDATE registration SET attended = 1 WHERE idNum = '$idNum'";
        if (mysqli_query($conn, $sql)) {
            $message = "Student Attendance to SUCCESSFULLY RECORDED";
        } else {
            $message = "Error updating record: " . mysqli_error($conn);
        }
    }
}

// Handle ID Lookup
if (isset($_POST['search'])) {
    $idNum = $_POST['idNum'];
    $result = mysqli_query($conn, "SELECT * FROM registration WHERE idNum = '$idNum'");
    
    if (mysqli_num_rows($result) > 0) {
        $student = mysqli_fetch_assoc($result);
    } else {
        $message = "ID # IS NOT YET REGISTERED";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Attendance</title>
</head>
<body>
    <div style="border: 1px solid black; padding: 20px; width: 600px;">
        <form method="POST">
            Input ID #: <input type="text" name="idNum" value="<?php echo isset($_POST['idNum']) ? $_POST['idNum'] : ''; ?>" required>
            <input type="submit" name="search" value="Search">
        <div style="text-align: right;">
            <a href="index.php" style="text-decoration: none; background: #eee; padding: 5px 10px; border: 1px solid #ccc; border-radius: 3px; color: black;">Back to Home</a>
        </div>
        <form method="POST">

        <p><strong><?php echo $message; ?></strong></p>

        <?php if ($student): ?>
            <table border="1" cellpadding="10" style="width: 100%; text-align: left; border-collapse: collapse;">
                <thead>
                    <tr>
                        <th>ID #</th>
                        <th>Name</th>
                        <th>Campus</th>
                        <th>Amount</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo $student['idNum']; ?></td>
                        <td><?php echo $student['studLName'] . ", " . $student['studFName']; ?></td>
                        <td><?php echo $student['campus']; ?></td>
                        <td><?php echo number_format($student['amountPaid'], 2); ?></td>
                        <td>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="idNum" value="<?php echo $student['idNum']; ?>">
                                <input type="submit" name="mark_attended" value="Attended">
                            </form>
                        </td>
                    </tr>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>

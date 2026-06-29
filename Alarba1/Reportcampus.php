<?php
require_once 'dp.php';

$campus_filter = "";
$students = [];
$total_registrants = 0;
$total_collection = 0;
$total_attendees = 0;
$attendee_collection = 0;

if (isset($_POST['generate'])) {
    $campus_filter = $_POST['campus'];
    
    $sql = "SELECT * FROM registration WHERE campus = '$campus_filter'";
    $result = mysqli_query($conn, $sql);
    
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $students[] = $row;
            $total_registrants++;
            $total_collection += $row['amountPaid'];
            
            if ($row['attended'] == 1) {
                $total_attendees++;
                $attendee_collection += $row['amountPaid'];
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Report By Campus</title>
</head>
<body style="font-family: sans-serif;">
    <div style="border: 1px solid black; padding: 20px; width: 600px; margin: 0 auto;">
        <div style="text-align: right;">
            <a href="index.php" style="text-decoration: none; background: #eee; padding: 5px 10px; border: 1px solid #ccc; border-radius: 3px; color: black;">Back to Home</a>
        </div>
        
        <h2 style="text-align: center;">Report (By Campus)</h2>
        
        <form method="POST" style="text-align: center;">
            <p>
                Set filters here: 
                <input type="radio" name="campus" value="Main" <?php if($campus_filter == "Main") echo 'checked'; ?>> Main
                <input type="radio" name="campus" value="Banilad" <?php if($campus_filter == "Banilad") echo 'checked'; ?>> Banilad
                <input type="radio" name="campus" value="LM" <?php if($campus_filter == "LM") echo 'checked'; ?>> LM
            </p>
            <input type="submit" name="generate" value="Generate Report" style="padding: 10px 20px; font-size: 1.1em; cursor: pointer;">
        </form>

        <?php if (isset($_POST['generate'])): ?>
            <table border="1" cellpadding="10" style="width: 100%; border-collapse: collapse; margin-top: 20px;">
                <thead>
                    <tr>
                        <th>ID #</th>
                        <th>Name</th>
                        <th>Campus</th>
                        <th>Amount</th>
                        <th>Attended</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $s): ?>
                        <tr>
                            <td><?php echo $s['idNum']; ?></td>
                            <td><?php echo $s['studLName'] . ", " . $s['studFName']; ?></td>
                            <td><?php echo $s['campus']; ?></td>
                            <td><?php echo number_format($s['amountPaid'], 2); ?></td>
                            <td><?php echo $s['attended'] ? 'Yes' : 'No'; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div style="margin-top: 15px; font-weight: bold;">
                # of Registrants: <?php echo $total_registrants; ?> | Total Collection: <?php echo number_format($total_collection, 2); ?><br>
                # of Attendees: <?php echo $total_attendees; ?> | Total Generated: <?php echo number_format($attendee_collection, 2); ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>

<?php
require_once 'dp.php';

// Define all possible campuses to ensure they appear in the report even with 0 counts
$campuses = ['Main', 'Banilad', 'LM', 'Pardo'];
$summary = [];
$grand_total_registered = 0;
$grand_total_attended = 0;
$grand_total_collection = 0;

foreach ($campuses as $campus) {
    // Count Registered
    $reg_res = mysqli_query($conn, "SELECT COUNT(*) as count FROM registration WHERE campus = '$campus'");
    $reg_data = mysqli_fetch_assoc($reg_res);
    $registered = $reg_data['count'];

    // Count Attended
    $att_res = mysqli_query($conn, "SELECT COUNT(*) as count FROM registration WHERE campus = '$campus' AND attended = 1");
    $att_data = mysqli_fetch_assoc($att_res);
    $attended = $att_data['count'];

    // Sum Collection
    $col_res = mysqli_query($conn, "SELECT SUM(amountPaid) as total FROM registration WHERE campus = '$campus'");
    $col_data = mysqli_fetch_assoc($col_res);
    $collection = $col_data['total'] ?? 0;

    $summary[] = [
        'campus' => $campus,
        'registered' => $registered,
        'attended' => $attended,
        'collection' => $collection
    ];

    $grand_total_registered += $registered;
    $grand_total_attended += $attended;
    $grand_total_collection += $collection;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Summary Report</title>
</head>
<body style="font-family: sans-serif;">
    <div style="border: 1px solid black; padding: 20px; width: 600px; margin: 0 auto;">
        <div style="text-align: right;">
            <a href="index.php" style="text-decoration: none; background: #eee; padding: 5px 10px; border: 1px solid #ccc; border-radius: 3px; color: black;">Back to Home</a>
        </div>

        <div style="text-align: center;">
            <h2>Summary Report</h2>
            <h3>(All Campuses)</h3>
        </div>

        <table border="1" cellpadding="10" style="width: 100%; border-collapse: collapse; text-align: center;">
            <thead>
                <tr style="background-color: #f2f2f2;">
                    <th>Campus</th>
                    <th>Registered</th>
                    <th>Attended</th>
                    <th>Total Collection</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($summary as $row): ?>
                    <tr>
                        <td><?php echo $row['campus']; ?></td>
                        <td><?php echo $row['registered']; ?></td>
                        <td><?php echo $row['attended']; ?></td>
                        <td><?php echo number_format($row['collection'], 2); ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr style="font-weight: bold; background-color: #f2f2f2;">
                    <td>TOTALS</td>
                    <td><?php echo $grand_total_registered; ?></td>
                    <td><?php echo $grand_total_attended; ?></td>
                    <td><?php echo number_format($grand_total_collection, 2); ?></td>
                </tr>
            </tbody>
        </table>

        <div style="text-align: right; margin-top: 20px;">
            Date Generated: <?php echo date("m/d/Y"); ?>
        </div>
    </div>
</body>
</html>

<?php
require_once 'dp.php';

$winner = null;
$filter = "All";

if (isset($_POST['filter'])) {
    $filter = $_POST['filter'];
}

if (isset($_POST['reveal'])) {
    $sql = "SELECT * FROM registration";
    
    if ($filter !== "All") {
        $sql .= " WHERE campus = '$filter'";
    }
    
    $sql .= " ORDER BY RAND() LIMIT 1";
    
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $winner = mysqli_fetch_assoc($result);
    } else {
        $message = "No students found for the selected filter!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Raffle</title>
</head>
<body style="text-align: center; font-family: sans-serif;">
    <div style="border: 1px solid black; padding: 20px; width: 500px; margin: 0 auto;">
        <div style="text-align: right;">
            <a href="index.php" style="text-decoration: none; background: #eee; padding: 5px 10px; border: 1px solid #ccc; border-radius: 3px; color: black;">Back to Home</a>
        </div>
        
        <h2>Raffle</h2>
        
        <form method="POST">
            <p>
                Set filters here: 
                <input type="checkbox" name="filter" value="All" <?php if($filter == "All") echo 'checked'; ?> onchange="this.form.submit()"> All
                <input type="radio" name="filter" value="Main" <?php if($filter == "Main") echo 'checked'; ?> onchange="this.form.submit()"> Main
                <input type="radio" name="filter" value="Banilad" <?php if($filter == "Banilad") echo 'checked'; ?> onchange="this.form.submit()"> Banilad
                <input type="radio" name="filter" value="LM" <?php if($filter == "LM") echo 'checked'; ?> onchange="this.form.submit()"> LM
            </p>
            
            <input type="submit" name="reveal" value="Reveal the Lucky Winner!" style="padding: 10px 20px; font-size: 1.2em; cursor: pointer;">
        </form>

        <?php if ($winner): ?>
            <div style="margin-top: 20px;">
                <table border="1" cellpadding="10" style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <th>ID #</th>
                        <th>Name</th>
                        <th>Campus</th>
                    </tr>
                    <tr>
                        <td><?php echo $winner['idNum']; ?></td>
                        <td><?php echo $winner['studLName'] . ", " . $winner['studFName']; ?></td>
                        <td><?php echo $winner['campus']; ?></td>
                    </tr>
                </table>
                <h3 style="margin-top: 10px;">CONGRATULATIONS!!!</h3>
            </div>
        <?php elseif (isset($message)): ?>
            <p><?php echo $message; ?></p>
        <?php endif; ?>
    </div>
</body>
</html>

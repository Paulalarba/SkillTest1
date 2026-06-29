<!DOCTYPE html>
<html>
<head>
    <title>Candidates Management</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .form-container { margin-bottom: 20px; padding: 15px; background: #f9f9f9; border: 1px solid #ccc; }
    </style>
</head>
<body>
    <h1>Candidates Management</h1>
    <nav><a href="positions.php">Positions</a> | <a href="candidates.php">Candidates</a> | <a href="voters.php">Voters</a> | <a href="results.php">Results</a> | <a href="winners.php">Winners</a> | <a href="login.php">Voting Portal</a></nav>
    <hr>

    <div class="form-container">
        <h3>Add/Update Candidate</h3>
        <form method="POST">
            <input type="hidden" name="candID" id="candID">
            First Name: <input type="text" name="candNName" id="candNName" required>
            Middle Name: <input type="text" name="candMName" id="candMName">
            Last Name: <input type="text" name="candLName" id="candLName" required>
            Position: 
            <select name="posID" id="posID" required>
                <?php
                require 'db.php';
                $posStmt = $pdo->query("SELECT * FROM Positions");
                while ($pos = $posStmt->fetch()) {
                    echo "<option value='{$pos['posID']}'>{$pos['posName']}</option>";
                }
                ?>
            </select>
            Status: <input type="text" name="candStat" id="candStat" placeholder="e.g. Active">
            <button type="submit" name="save">Save Candidate</button>
        </form>
    </div>

    <table>
        <tr>
            <th>ID</th><th>Name</th><th>Position</th><th>Status</th><th>Actions</th>
        </tr>
        <?php
        if (isset($_POST['save'])) {
            $id = $_POST['candID'];
            $fn = $_POST['candNName'];
            $mn = $_POST['candMName'];
            $ln = $_POST['candLName'];
            $pid = $_POST['posID'];
            $stat = $_POST['candStat'];
            if ($id) {
                $stmt = $pdo->prepare("UPDATE Candidates SET candNName=?, candMName=?, candLName=?, posID=?, candStat=? WHERE candID=?");
                $stmt->execute([$fn, $mn, $ln, $pid, $stat, $id]);
            } else {
                $stmt = $pdo->prepare("INSERT INTO Candidates (candNName, candMName, candLName, posID, candStat) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$fn, $mn, $ln, $pid, $stat]);
            }
        }
        if (isset($_GET['deactivate'])) {
            $stmt = $pdo->prepare("UPDATE Candidates SET candStat='inactive' WHERE candID=?");
            $stmt->execute([$_GET['deactivate']]);
        }

        $stmt = $pdo->query("SELECT c.*, p.posName FROM Candidates c JOIN Positions p ON c.posID = p.posID");
        while ($row = $stmt->fetch()) {
            $fullName = "{$row['candNName']} {$row['candMName']} {$row['candLName']}";
            echo "<tr>
                <td>{$row['candID']}</td>
                <td>{$fullName}</td>
                <td>{$row['posName']}</td>
                <td>{$row['candStat']}</td>
                <td>
                    <a href=\"?edit={$row['candID']}\">Edit</a> | 
                    <a href=\"?deactivate={$row['candID']}\">Deactivate</a>
                </td>
            </tr>";
        }
        ?>
    </table>

    <?php
    if (isset($_GET['edit'])) {
        $stmt = $pdo->prepare("SELECT * FROM Candidates WHERE candID=?");
        $stmt->execute([$_GET['edit']]);
        $c = $stmt->fetch();
        echo "<script>
            document.getElementById('candID').value = '{$c['candID']}';
            document.getElementById('candNName').value = '{$c['candNName']}';
            document.getElementById('candMName').value = '{$c['candMName']}';
            document.getElementById('candLName').value = '{$c['candLName']}';
            document.getElementById('posID').value = '{$c['posID']}';
            document.getElementById('candStat').value = '{$c['candStat']}';
        </script>";
    }
    ?>
</body>
</html>

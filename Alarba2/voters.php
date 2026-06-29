<!DOCTYPE html>
<html>
<head>
    <title>Voters Management</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .form-container { margin-bottom: 20px; padding: 15px; background: #f9f9f9; border: 1px solid #ccc; }
    </style>
</head>
<body>
    <h1>Voters Management</h1>
    <nav><a href="positions.php">Positions</a> | <a href="candidates.php">Candidates</a> | <a href="voters.php">Voters</a> | <a href="results.php">Results</a> | <a href="winners.php">Winners</a> | <a href="login.php">Voting Portal</a></nav>
    <hr>

    <div class="form-container">
        <h3>Add/Update Voter</h3>
        <form method="POST">
            <input type="hidden" name="voterID" id="voterID">
            Password: <input type="password" name="voterPass" id="voterPass" required>
            First Name: <input type="text" name="voterFName" id="voterFName" required>
            Middle Name: <input type="text" name="voterMName" id="voterMName">
            Last Name: <input type="text" name="voterLName" id="voterLName" required>
            Status: 
            <select name="voterStat" id="voterStat">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
            Voted: 
            <select name="voted" id="voted">
                <option value="N">No</option>
                <option value="Y">Yes</option>
            </select>
            <button type="submit" name="save">Save Voter</button>
        </form>
    </div>

    <table>
        <tr>
            <th>ID</th><th>Name</th><th>Status</th><th>Voted</th><th>Actions</th>
        </tr>
        <?php
        require 'db.php';
        if (isset($_POST['save'])) {
            $id = $_POST['voterID'];
            $pass = $_POST['voterPass'];
            $fn = $_POST['voterFName'];
            $mn = $_POST['voterMName'];
            $ln = $_POST['voterLName'];
            $stat = $_POST['voterStat'];
            $voted = $_POST['voted'];
            if ($id) {
                $stmt = $pdo->prepare("UPDATE Voters SET voterPass=?, voterFName=?, voterMName=?, voterLName=?, voterStat=?, voted=? WHERE voterID=?");
                $stmt->execute([$pass, $fn, $mn, $ln, $stat, $voted, $id]);
            } else {
                $stmt = $pdo->prepare("INSERT INTO Voters (voterPass, voterFName, voterMName, voterLName, voterStat, voted) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([$pass, $fn, $mn, $ln, $stat, $voted]);
            }
        }
        if (isset($_GET['deactivate'])) {
            $stmt = $pdo->prepare("UPDATE Voters SET voterStat='inactive' WHERE voterID=?");
            $stmt->execute([$_GET['deactivate']]);
        }

        $stmt = $pdo->query("SELECT * FROM Voters");
        while ($row = $stmt->fetch()) {
            $fullName = "{$row['voterFName']} {$row['voterMName']} {$row['voterLName']}";
            echo "<tr>
                <td>{$row['voterID']}</td>
                <td>{$fullName}</td>
                <td>{$row['voterStat']}</td>
                <td>{$row['voted']}</td>
                <td>
                    <a href=\"?edit={$row['voterID']}\">Edit</a> | 
                    <a href=\"?deactivate={$row['voterID']}\">Deactivate</a>
                </td>
            </tr>";
        }
        ?>
    </table>

    <?php
    if (isset($_GET['edit'])) {
        $stmt = $pdo->prepare("SELECT * FROM Voters WHERE voterID=?");
        $stmt->execute([$_GET['edit']]);
        $v = $stmt->fetch();
        echo "<script>
            document.getElementById('voterID').value = '{$v['voterID']}';
            document.getElementById('voterFName').value = '{$v['voterFName']}';
            document.getElementById('voterMName').value = '{$v['voterMName']}';
            document.getElementById('voterLName').value = '{$v['voterLName']}';
            document.getElementById('voterStat').value = '{$v['voterStat']}';
            document.getElementById('voted').value = '{$v['voted']}';
        </script>";
    }
    ?>
</body>
</html>

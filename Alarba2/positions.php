<!DOCTYPE html>
<html>
<head>
    <title>Positions Management</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .form-container { margin-bottom: 20px; padding: 15px; background: #f9f9f9; border: 1px solid #ccc; }
    </style>
</head>
<body>
    <h1>Positions Management</h1>
    <nav><a href="positions.php">Positions</a> | <a href="candidates.php">Candidates</a> | <a href="voters.php">Voters</a> | <a href="results.php">Results</a> | <a href="winners.php">Winners</a> | <a href="login.php">Voting Portal</a></nav>
    <hr>

    <div class="form-container">
        <h3>Add/Update Position</h3>
        <form method="POST">
            <input type="hidden" name="posID" id="posID">
            Name: <input type="text" name="posName" id="posName" required>
            Num of Positions: <input type="number" name="numOfPositions" id="numOfPositions" required>
            Status: 
            <select name="posStat">
                <option value="open">Open</option>
                <option value="closed">Closed</option>
            </select>
            <button type="submit" name="save">Save Position</button>
        </form>
    </div>

    <table>
        <tr>
            <th>ID</th><th>Name</th><th>Limit</th><th>Status</th><th>Actions</th>
        </tr>
        <?php
        require 'db.php';
        if (isset($_POST['save'])) {
            $id = $_POST['posID'];
            $name = $_POST['posName'];
            $num = $_POST['numOfPositions'];
            $stat = $_POST['posStat'];
            if ($id) {
                $stmt = $pdo->prepare("UPDATE Positions SET posName=?, numOfPositions=?, posStat=? WHERE posID=?");
                $stmt->execute([$name, $num, $stat, $id]);
            } else {
                $stmt = $pdo->prepare("INSERT INTO Positions (posName, numOfPositions, posStat) VALUES (?, ?, ?)");
                $stmt->execute([$name, $num, $stat]);
            }
        }
        if (isset($_GET['deactivate'])) {
            $stmt = $pdo->prepare("UPDATE Positions SET posStat='closed' WHERE posID=?");
            $stmt->execute([$_GET['deactivate']]);
        }

        $stmt = $pdo->query("SELECT * FROM Positions");
        while ($row = $stmt->fetch()) {
            echo "<tr>
                <td>{$row['posID']}</td>
                <td>{$row['posName']}</td>
                <td>{$row['numOfPositions']}</td>
                <td>{$row['posStat']}</td>
                <td>
                    <a href=\"?edit={$row['posID']}\">Edit</a> | 
                    <a href=\"?deactivate={$row['posID']}\">Deactivate</a>
                </td>
            </tr>";
        }
        ?>
    </table>

    <?php
    if (isset($_GET['edit'])) {
        $stmt = $pdo->prepare("SELECT * FROM Positions WHERE posID=?");
        $stmt->execute([$_GET['edit']]);
        $p = $stmt->fetch();
        echo "<script>
            document.getElementById('posID').value = '{$p['posID']}';
            document.getElementById('posName').value = '{$p['posName']}';
            document.getElementById('numOfPositions').value = '{$p['numOfPositions']}';
        </script>";
    }
    ?>
</body>
</html>

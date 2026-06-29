<!DOCTYPE html>
<html>
<head>
    <title>Election Winners</title>
    <style>
        body { font-family: Arial; margin: 20px; background: #f4f4f4; }
        table { border-collapse: collapse; width: 100%; background: white; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #eee; }
        nav { margin-bottom: 20px; }
    </style>
</head>
<body>
    <h1>Election Winners</h1>
    <nav>
        <a href="positions.php">Positions</a> | 
        <a href="candidates.php">Candidates</a> | 
        <a href="voters.php">Voters</a> | 
        <a href="results.php">Results</a> | 
        <a href="winners.php">Winners</a>
    </nav>
    <hr>

    <table>
        <tr>
            <th>Elective Position</th>
            <th>Winner</th>
            <th>Total Votes</th>
        </tr>
        <?php
        require 'db.php';

        // Get all positions
        $stmt = $pdo->query("SELECT * FROM Positions");
        while ($pos = $stmt->fetch()) {
            // Get top candidates based on numOfPositions allowed
            $stmtWinners = $pdo->prepare("
                SELECT c.candNName, c.candMName, c.candLName, COUNT(v.candID) as vote_count 
                FROM Candidates c 
                JOIN Votes v ON c.candID = v.candID 
                WHERE c.posID = ? 
                GROUP BY c.candID 
                ORDER BY vote_count DESC, c.candID ASC
                LIMIT ?
            ");
            // Note: MySQL LIMIT doesn't accept parameters in some versions, but PDO handles it if set as integer.
            // We use a workaround by manually concatenating or using bindValue with PDO::PARAM_INT.
            $stmtWinners->bindValue(1, $pos['posID'], PDO::PARAM_INT);
            $stmtWinners->bindValue(2, $pos['numOfPositions'], PDO::PARAM_INT);
            $stmtWinners->execute();

            while ($winner = $stmtWinners->fetch()) {
                $fullName = "{$winner['candNName']} {$winner['candMName']} {$winner['candLName']}";
                echo "<tr>
                        <td>{$pos['posName']}</td>
                        <td>{$fullName}</td>
                        <td>{$winner['vote_count']}</td>
                      </tr>";
            }
        }
        ?>
    </table>
</body>
</html>

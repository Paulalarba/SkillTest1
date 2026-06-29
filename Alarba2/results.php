<!DOCTYPE html>
<html>
<head>
    <title>Election Results</title>
    <style>
        body { font-family: Arial; margin: 20px; background: #f4f4f4; }
        table { border-collapse: collapse; width: 100%; background: white; margin-bottom: 30px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #eee; }
        .pos-header { background-color: #ddd; font-weight: bold; text-align: center; }
        nav { margin-bottom: 20px; }
    </style>
</head>
<body>
    <h1>Election Results</h1>
    <nav>
        <a href="positions.php">Positions</a> | 
        <a href="candidates.php">Candidates</a> | 
        <a href="voters.php">Voters</a> | 
        <a href="results.php">Results</a> | 
        <a href="winners.php">Winners</a>
    </nav>
    <hr>

    <?php
    require 'db.php';

    $stmt = $pdo->query("SELECT * FROM Positions");
    while ($pos = $stmt->fetch()) {
        echo "<table>";
        echo "<tr>
                <th colspan='3' class='pos-header'>{$pos['posName']}</th>
              </tr>";
        echo "<tr>
                <th>Candidate</th>
                <th>Total Votes</th>
                <th>Voting %</th>
              </tr>";

        // Calculate total votes for this position
        $stmtTotal = $pdo->prepare("SELECT COUNT(*) FROM Votes WHERE posID = ?");
        $stmtTotal->execute([$pos['posID']]);
        $totalPosVotes = $stmtTotal->fetchColumn();

        // Get votes per candidate
        $stmtCand = $pdo->prepare("
            SELECT c.candNName, c.candMName, c.candLName, COUNT(v.candID) as vote_count 
            FROM Candidates c 
            LEFT JOIN Votes v ON c.candID = v.candID 
            WHERE c.posID = ? 
            GROUP BY c.candID 
            ORDER BY vote_count DESC
        ");
        $stmtCand->execute([$pos['posID']]);
        
        $hasCandidates = false;
        while ($cand = $stmtCand->fetch()) {
            $hasCandidates = true;
            $fullName = "{$cand['candNName']} {$cand['candMName']} {$cand['candLName']}";
            $percentage = ($totalPosVotes > 0) ? round(($cand['vote_count'] / $totalPosVotes) * 100, 2) : 0;
            
            echo "<tr>
                    <td>{$fullName}</td>
                    <td>{$cand['vote_count']}</td>
                    <td>{$percentage}%</td>
                  </tr>";
        }

        if (!$hasCandidates) {
            echo "<tr><td colspan='3' style='text-align:center;'>No candidates for this position.</td></tr>";
        }
        echo "</table>";
    }
    ?>
</body>
</html>

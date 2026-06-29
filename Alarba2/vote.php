<?php
session_start();
require 'db.php';

if (!isset($_SESSION['voterID'])) {
    header("Location: login.php");
    exit;
}

$voterID = $_SESSION['voterID'];

if (isset($_POST['submit_vote'])) {
    $pdo->beginTransaction();
    try {
        $votes = $_POST['candidates']; // Array of candID per posID
        
        foreach ($votes as $posID => $selectedCandidates) {
            // Check limit (numOfPositions)
            $stmt = $pdo->prepare("SELECT numOfPositions FROM Positions WHERE posID = ?");
            $stmt->execute([$posID]);
            $limit = $stmt->fetchColumn();
            
            if (count($selectedCandidates) > $limit) {
                throw new Exception("You selected too many candidates for position ID $posID (Limit: $limit)");
            }
            
            foreach ($selectedCandidates as $candID) {
                $stmt = $pdo->prepare("INSERT INTO Votes (posID, voterID, candID) VALUES (?, ?, ?)");
                $stmt->execute([$posID, $voterID, $candID]);
            }
        }

        // Mark voter as voted
        $stmt = $pdo->prepare("UPDATE Voters SET voted = 'Y' WHERE voterID = ?");
        $stmt->execute([$voterID]);

        $pdo->commit();
        $_SESSION['voted'] = true;
        echo "<script>alert('Vote submitted successfully!'); window.location.href='logout.php';</script>";
    } catch (Exception $e) {
        $pdo->rollBack();
        $error = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Cast Your Vote</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        .position-block { border: 1px solid #ccc; padding: 15px; margin-bottom: 20px; background: #fff; }
        .position-title { font-weight: bold; font-size: 1.2em; margin-bottom: 10px; }
        .limit-info { font-size: 0.9em; color: #666; }
    </style>
</head>
<body style="background: #f4f4f4;">
    <h1>Voting Ballot</h1>
    <p>Logged in as Voter ID: <?php echo $voterID; ?> | <a href="logout.php">Logout</a> | <a href="login.php"><button type="button">Back to Login</button></a></p>
    <hr>

    <?php if (isset($error)) echo "<p style='color:red;'><b>Error:</b> $error</p>"; ?>

    <form method="POST">
        <?php
        // Only show open positions
        $stmt = $pdo->query("SELECT * FROM Positions WHERE posStat = 'open'");
        while ($pos = $stmt->fetch()) {
            echo "<div class='position-block'>";
            echo "<div class='position-title'>{$pos['posName']}</div>";
            echo "<div class='limit-info'>You can select up to {$pos['numOfPositions']} candidate(s).</div>";
            
            // Candidates for this position
            $cStmt = $pdo->prepare("SELECT * FROM Candidates WHERE posID = ? AND candStat != 'inactive'");
            $cStmt->execute([$pos['posID']]);
            while ($cand = $cStmt->fetch()) {
                $fullName = "{$cand['candNName']} {$cand['candMName']} {$cand['candLName']}";
                echo "<label style='display:block; margin: 5px 0;'>
                        <input type='checkbox' name='candidates[{$pos['posID']}][]' value='{$cand['candID']}'> 
                        $fullName
                      </label>";
            }
            echo "</div>";
        }
        ?>
        <button type="submit" name="submit_vote" style="padding: 10px 20px; font-size: 1.1em; cursor: pointer;">Submit All Votes</button>
    </form>
</body>
</html>

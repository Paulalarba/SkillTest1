<?php
session_start();
require 'db.php';

$error = "";
if (isset($_POST['login'])) {
    $id = $_POST['voterID'];
    $pass = $_POST['voterPass'];

    $stmt = $pdo->prepare("SELECT * FROM Voters WHERE voterID = ? AND voterPass = ?");
    $stmt->execute([$id, $pass]);
    $voter = $stmt->fetch();

    if ($voter) {
        if ($voter['voterStat'] !== 'active') {
            $error = "Your account is inactive.";
        } elseif ($voter['voted'] === 'Y') {
            $error = "You have already voted.";
        } else {
            $_SESSION['voterID'] = $voter['voterID'];
            header("Location: vote.php");
            exit;
        }
    } else {
        $error = "Invalid voter ID or password.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Voter Login</title>
    <style>
        body { font-family: Arial; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; background: #f0f2f5; }
        .login-card { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); width: 300px; }
        input { width: 100%; padding: 10px; margin: 10px 0; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background: #007bff; color: white; border: none; cursor: pointer; }
        .error { color: red; margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="login-card">
        <h2>Voter Login</h2>
        <?php if ($error) echo "<p class='error'>$error</p>"; ?>
        <form method="POST">
            <label>Voter ID</label>
            <input type="text" name="voterID" required>
            <label>Password</label>
            <input type="password" name="voterPass" required>
            <button type="submit" name="login">Login</button>
        </form>
        <p><a href="voters.php">Back to Admin</a></p>
    </div>
</body>
</html>

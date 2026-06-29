<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Election System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <nav class="navbar navbar-dark bg-primary mb-5">
        <div class="container">
            <a class="navbar-brand" href="index.php">Election System</a>
        </div>
    </nav>

    <div class="container text-center">
        <h1 class="mb-4">Welcome to the Voting Portal</h1>
        
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="list-group shadow-sm">
                    <a href="login.php" class="list-group-item list-group-item-action p-3">
                        <strong>Voter Login</strong> - Cast your vote
                    </a>
                    <a href="candidates.php" class="list-group-item list-group-item-action p-3">
                        <strong>Candidates</strong> - View who is running
                    </a>
                    <a href="positions.php" class="list-group-item list-group-item-action p-3">
                        <strong>Positions</strong> - View open roles
                    </a>
                    <a href="results.php" class="list-group-item list-group-item-action p-3">
                        <strong>Results</strong> - Check current standings
                    </a>
                    <a href="winners.php" class="list-group-item list-group-item-action p-3">
                        <strong>Winners</strong> - See the elected winners
                    </a>
                </div>
            </div>
        </div>
    </div>

    <footer class="text-center mt-5 text-muted">
        <p>&copy; <?php echo date("Y"); ?> Election System</p>
    </footer>

</body>
</html>

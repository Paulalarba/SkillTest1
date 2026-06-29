<?php
include 'config.php';

$results = [];
$count = 0;
$title = "";

if (isset($_POST['search_special'])) {
    $stmt = $pdo->prepare("SELECT * FROM Doctor WHERE docSpecial = ?");
    $stmt->execute([$_POST['special']]);
    $results = $stmt->fetchAll();
    $count = count($results);
    $title = "Doctors with specialization: " . htmlspecialchars($_POST['special']);
}

if (isset($_POST['search_age'])) {
    $stmt = $pdo->prepare("SELECT *, TIMESTAMPDIFF(YEAR, patBirhtDay, CURDATE()) AS age FROM Patient WHERE TIMESTAMPDIFF(YEAR, patBirhtDay, CURDATE()) BETWEEN ? AND ?");
    $stmt->execute([$_POST['age_from'], $_POST['age_to']]);
    $results = $stmt->fetchAll();
    $count = count($results);
    $title = "Patients from age " . $_POST['age_from'] . " to " . $_POST['age_to'];
}

if (isset($_POST['search_patid'])) {
    $stmt = $pdo->prepare("SELECT * FROM Consultation WHERE patID = ?");
    $stmt->execute([$_POST['patID']]);
    $results = $stmt->fetchAll();
    $count = count($results);
    $title = "Consultations for Patient ID: " . $_POST['patID'];
}

if (isset($_POST['search_date'])) {
    $stmt = $pdo->prepare("SELECT * FROM Consultation WHERE consultDate BETWEEN ? AND ?");
    $stmt->execute([$_POST['date_from'], $_POST['date_to']]);
    $results = $stmt->fetchAll();
    $count = count($results);
    $title = "Consultations from " . $_POST['date_from'] . " to " . $_POST['date_to'];
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Consultations Inquiry</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav>
        <a href="index.php">Home</a>
        <a href="doctor_mgmt.php">Doctors Management</a>
        <a href="patient_mgmt.php">Patients Management</a>
        <a href="consult_mgmt.php">Consultations Transaction</a>
        <a href="inquiry.php">Consultations Inquiry</a>
    </nav>
    <h2>Consultations Inquiry</h2>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
        <fieldset>
            <legend>Search Doctors by Specialization</legend>
            <form method="POST">
                <input type="text" name="special" placeholder="Specialization" required>
                <button type="submit" name="search_special" class="btn">Search</button>
            </form>
        </fieldset>

        <fieldset>
            <legend>Search Patients by Age</legend>
            <form method="POST">
                <input type="number" name="age_from" placeholder="From Age" required>
                <input type="number" name="age_to" placeholder="To Age" required>
                <button type="submit" name="search_age" class="btn">Search</button>
            </form>
        </fieldset>

        <fieldset>
            <legend>Search Consultations by Patient ID</legend>
            <form method="POST">
                <input type="number" name="patID" placeholder="Patient ID" required>
                <button type="submit" name="search_patid" class="btn">Search</button>
            </form>
        </fieldset>

        <fieldset>
            <legend>Search Consultations by Date Range</legend>
            <form method="POST">
                <input type="date" name="date_from" required>
                <input type="date" name="date_to" required>
                <button type="submit" name="search_date" class="btn">Search</button>
            </form>
        </fieldset>
    </div>

    <hr>

    <?php if ($title): ?>
        <h3><?php echo $title; ?></h3>
        <div class='count-box'>Total Records Found: <?php echo $count; ?></div>
        
        <table>
            <?php if (isset($_POST['search_special'])): ?>
                <tr><th>ID</th><th>First Name</th><th>Last Name</th><th>Address</th><th>Specialization</th></tr>
                <?php foreach ($results as $r): ?>
                    <tr><td><?=$r['docID']?></td><td><?=$r['docFName']?></td><td><?=$r['docLName']?></td><td><?=$r['docAddress']?></td><td><?=$r['docSpecial']?></td></tr>
                <?php endforeach; ?>
            <?php elseif (isset($_POST['search_age'])): ?>
                <tr><th>ID</th><th>First Name</th><th>Last Name</th><th>Birth Day</th><th>Age</th><th>Tel No</th></tr>
                <?php foreach ($results as $r): ?>
                    <tr><td><?=$r['patID']?></td><td><?=$r['patFName']?></td><td><?=$r['patLName']?></td><td><?=$r['patBirhtDay']?></td><td><?=$r['age']?></td><td><?=$r['patTelNo']?></td></tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><th>Consult ID</th><th>Pat ID</th><th>Doc ID</th><th>Date</th><th>Diagnosis</th><th>Prescription</th></tr>
                <?php foreach ($results as $r): ?>
                    <tr><td><?=$r['ConsultID']?></td><td><?=$r['patID']?></td><td><?=$r['docID']?></td><td><?=$r['consultDate']?></td><td><?=$r['diagnosis']?></td><td><?=$r['prescription']?></td></tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </table>
    <?php endif; ?>
</body>
</html>

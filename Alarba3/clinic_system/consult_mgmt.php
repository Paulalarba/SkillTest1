<?php
include 'config.php';

$msg = "";

// Add Consultation
if (isset($_POST['add'])) {
    $stmt = $pdo->prepare("INSERT INTO Consultation (ConsultID, patID, docID, consultDate, diagnosis, prescription) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$_POST['ConsultID'], $_POST['patID'], $_POST['docID'], $_POST['consultDate'], $_POST['diagnosis'], $_POST['prescription']]);
    $msg = "Consultation added successfully!";
}

// Update Consultation
if (isset($_POST['update'])) {
    $stmt = $pdo->prepare("UPDATE Consultation SET patID=?, docID=?, consultDate=?, diagnosis=?, prescription=? WHERE ConsultID=?");
    $stmt->execute([$_POST['patID'], $_POST['docID'], $_POST['consultDate'], $_POST['diagnosis'], $_POST['prescription'], $_POST['ConsultID']]);
    $msg = "Consultation updated successfully!";
}

// Delete Consultation
if (isset($_POST['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM Consultation WHERE ConsultID=?");
    $stmt->execute([$_POST['ConsultID']]);
    $msg = "Consultation deleted successfully!";
}

// Search for update/delete
$search_result = null;
if (isset($_POST['search'])) {
    $stmt = $pdo->prepare("SELECT * FROM Consultation WHERE ConsultID=?");
    $stmt->execute([$_POST['ConsultID']]);
    $search_result = $stmt->fetch();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Consultations Transaction</title>
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
    <h2>Consultations Transaction Management</h2>
    <p><?php echo $msg; ?></p>

    <h3>Add Consultation</h3>
    <form method="POST">
        <div class="form-group"><label>Consult ID:</label><input type="number" name="ConsultID" required></div>
        <div class="form-group"><label>Patient ID:</label><input type="number" name="patID" required></div>
        <div class="form-group"><label>Doctor ID:</label><input type="number" name="docID" required></div>
        <div class="form-group"><label>Date:</label><input type="date" name="consultDate" required></div>
        <div class="form-group"><label>Diagnosis:</label><input type="text" name="diagnosis" required></div>
        <div class="form-group"><label>Prescription:</label><input type="text" name="prescription" required></div>
        <button type="submit" name="add" class="btn btn-add">Add Consultation</button>
    </form>

    <hr>
    <h3>Search/Update/Delete Consultation</h3>
    <form method="POST">
        <label>Consultation ID:</label> <input type="number" name="ConsultID" required>
        <button type="submit" name="search" class="btn">Search</button>
    </form>

    <?php if ($search_result): ?>
        <form method="POST">
            <div class="form-group"><label>Consult ID:</label><input type="number" name="ConsultID" value="<?php echo $search_result['ConsultID']; ?>" readonly></div>
            <div class="form-group"><label>Patient ID:</label><input type="number" name="patID" value="<?php echo $search_result['patID']; ?>"></div>
            <div class="form-group"><label>Doctor ID:</label><input type="number" name="docID" value="<?php echo $search_result['docID']; ?>"></div>
            <div class="form-group"><label>Date:</label><input type="date" name="consultDate" value="<?php echo $search_result['consultDate']; ?>"></div>
            <div class="form-group"><label>Diagnosis:</label><input type="text" name="diagnosis" value="<?php echo $search_result['diagnosis']; ?>"></div>
            <div class="form-group"><label>Prescription:</label><input type="text" name="prescription" value="<?php echo $search_result['prescription']; ?>"></div>
            <button type="submit" name="update" class="btn btn-update">Update</button>
            <button type="submit" name="delete" class="btn btn-delete">Delete</button>
        </form>
    <?php endif; ?>

    <hr>
    <h3>Viewing Consultation Records</h3>
    <?php
    $stmt = $pdo->query("SELECT COUNT(*) FROM Consultation");
    $count = $stmt->fetchColumn();
    echo "<div class='count-box'>Total Consultations: $count</div>";

    $stmt = $pdo->query("SELECT * FROM Consultation");
    $consults = $stmt->fetchAll();
    ?>
    <table>
        <tr><th>ID</th><th>Pat ID</th><th>Doc ID</th><th>Date</th><th>Diagnosis</th><th>Prescription</th></tr>
        <?php foreach ($consults as $con): ?>
            <tr>
                <td><?php echo $con['ConsultID']; ?></td>
                <td><?php echo $con['patID']; ?></td>
                <td><?php echo $con['docID']; ?></td>
                <td><?php echo $con['consultDate']; ?></td>
                <td><?php echo $con['diagnosis']; ?></td>
                <td><?php echo $con['prescription']; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>

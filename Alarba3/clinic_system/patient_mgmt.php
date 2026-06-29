<?php
include 'config.php';

$msg = "";

// Add Patient
if (isset($_POST['add'])) {
    $stmt = $pdo->prepare("INSERT INTO Patient (patID, patFName, patLName, patBirhtDay, patTelNo) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$_POST['patID'], $_POST['patFName'], $_POST['patLName'], $_POST['patBirhtDay'], $_POST['patTelNo']]);
    $msg = "Patient added successfully!";
}

// Update Patient
if (isset($_POST['update'])) {
    $stmt = $pdo->prepare("UPDATE Patient SET patFName=?, patLName=?, patBirhtDay=?, patTelNo=? WHERE patID=?");
    $stmt->execute([$_POST['patFName'], $_POST['patLName'], $_POST['patBirhtDay'], $_POST['patTelNo'], $_POST['patID']]);
    $msg = "Patient updated successfully!";
}

// Delete Patient
if (isset($_POST['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM Patient WHERE patID=?");
    $stmt->execute([$_POST['patID']]);
    $msg = "Patient deleted successfully!";
}

// Search for update/delete
$search_result = null;
if (isset($_POST['search'])) {
    $stmt = $pdo->prepare("SELECT * FROM Patient WHERE patID=?");
    $stmt->execute([$_POST['patID']]);
    $search_result = $stmt->fetch();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Patients Management</title>
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
    <h2>Patients Management</h2>
    <p><?php echo $msg; ?></p>

    <h3>Add Patient</h3>
    <form method="POST">
        <div class="form-group"><label>ID:</label><input type="number" name="patID" required></div>
        <div class="form-group"><label>First Name:</label><input type="text" name="patFName" required></div>
        <div class="form-group"><label>Last Name:</label><input type="text" name="patLName" required></div>
        <div class="form-group"><label>Birth Day:</label><input type="date" name="patBirhtDay" required></div>
        <div class="form-group"><label>Telephone:</label><input type="text" name="patTelNo" required></div>
        <button type="submit" name="add" class="btn btn-add">Add Patient</button>
    </form>

    <hr>
    <h3>Search/Update/Delete Patient</h3>
    <form method="POST">
        <label>Patient ID:</label> <input type="number" name="patID" required>
        <button type="submit" name="search" class="btn">Search</button>
    </form>

    <?php if ($search_result): ?>
        <form method="POST">
            <div class="form-group"><label>ID:</label><input type="number" name="patID" value="<?php echo $search_result['patID']; ?>" readonly></div>
            <div class="form-group"><label>First Name:</label><input type="text" name="patFName" value="<?php echo $search_result['patFName']; ?>"></div>
            <div class="form-group"><label>Last Name:</label><input type="text" name="patLName" value="<?php echo $search_result['patLName']; ?>"></div>
            <div class="form-group"><label>Birth Day:</label><input type="date" name="patBirhtDay" value="<?php echo $search_result['patBirhtDay']; ?>"></div>
            <div class="form-group"><label>Telephone:</label><input type="text" name="patTelNo" value="<?php echo $search_result['patTelNo']; ?>"></div>
            <button type="submit" name="update" class="btn btn-update">Update</button>
            <button type="submit" name="delete" class="btn btn-delete">Delete</button>
        </form>
    <?php endif; ?>

    <hr>
    <h3>Viewing Patient Records</h3>
    <?php
    $stmt = $pdo->query("SELECT COUNT(*) FROM Patient");
    $count = $stmt->fetchColumn();
    echo "<div class='count-box'>Total Patients: $count</div>";

    $stmt = $pdo->query("SELECT * FROM Patient");
    $patients = $stmt->fetchAll();
    ?>
    <table>
        <tr><th>ID</th><th>First Name</th><th>Last Name</th><th>Birth Day</th><th>Tel No</th></tr>
        <?php foreach ($patients as $pat): ?>
            <tr>
                <td><?php echo $pat['patID']; ?></td>
                <td><?php echo $pat['patFName']; ?></td>
                <td><?php echo $pat['patLName']; ?></td>
                <td><?php echo $pat['patBirhtDay']; ?></td>
                <td><?php echo $pat['patTelNo']; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>

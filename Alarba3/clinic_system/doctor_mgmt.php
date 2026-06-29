<?php
include 'config.php';

$msg = "";

// Add Doctor
if (isset($_POST['add'])) {
    $stmt = $pdo->prepare("INSERT INTO Doctor (docID, docFName, docLName, docAddress, docSpecial) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$_POST['docID'], $_POST['docFName'], $_POST['docLName'], $_POST['docAddress'], $_POST['docSpecial']]);
    $msg = "Doctor added successfully!";
}

// Update Doctor
if (isset($_POST['update'])) {
    $stmt = $pdo->prepare("UPDATE Doctor SET docFName=?, docLName=?, docAddress=?, docSpecial=? WHERE docID=?");
    $stmt->execute([$_POST['docFName'], $_POST['docLName'], $_POST['docAddress'], $_POST['docSpecial'], $_POST['docID']]);
    $msg = "Doctor updated successfully!";
}

// Delete Doctor
if (isset($_POST['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM Doctor WHERE docID=?");
    $stmt->execute([$_POST['docID']]);
    $msg = "Doctor deleted successfully!";
}

// Search for update/delete
$search_result = null;
if (isset($_POST['search'])) {
    $stmt = $pdo->prepare("SELECT * FROM Doctor WHERE docID=?");
    $stmt->execute([$_POST['docID']]);
    $search_result = $stmt->fetch();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Doctors Management</title>
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
    <h2>Doctors Management</h2>
    <p><?php echo $msg; ?></p>

    <h3>Add Doctor</h3>
    <form method="POST">
        <div class="form-group"><label>ID:</label><input type="number" name="docID" required></div>
        <div class="form-group"><label>First Name:</label><input type="text" name="docFName" required></div>
        <div class="form-group"><label>Last Name:</label><input type="text" name="docLName" required></div>
        <div class="form-group"><label>Address:</label><input type="text" name="docAddress" required></div>
        <div class="form-group"><label>Specialization:</label><input type="text" name="docSpecial" required></div>
        <button type="submit" name="add" class="btn btn-add">Add Doctor</button>
    </form>

    <hr>
    <h3>Search/Update/Delete Doctor</h3>
    <form method="POST">
        <label>Doctor ID:</label> <input type="number" name="docID" required>
        <button type="submit" name="search" class="btn">Search</button>
    </form>

    <?php if ($search_result): ?>
        <form method="POST">
            <div class="form-group"><label>ID:</label><input type="number" name="docID" value="<?php echo $search_result['docID']; ?>" readonly></div>
            <div class="form-group"><label>First Name:</label><input type="text" name="docFName" value="<?php echo $search_result['docFName']; ?>"></div>
            <div class="form-group"><label>Last Name:</label><input type="text" name="docLName" value="<?php echo $search_result['docLName']; ?>"></div>
            <div class="form-group"><label>Address:</label><input type="text" name="docAddress" value="<?php echo $search_result['docAddress']; ?>"></div>
            <div class="form-group"><label>Specialization:</label><input type="text" name="docSpecial" value="<?php echo $search_result['docSpecial']; ?>"></div>
            <button type="submit" name="update" class="btn btn-update">Update</button>
            <button type="submit" name="delete" class="btn btn-delete">Delete</button>
        </form>
    <?php endif; ?>

    <hr>
    <h3>Viewing Doctor Records</h3>
    <?php
    $stmt = $pdo->query("SELECT COUNT(*) FROM Doctor");
    $count = $stmt->fetchColumn();
    echo "<div class='count-box'>Total Doctors: $count</div>";

    $stmt = $pdo->query("SELECT * FROM Doctor");
    $doctors = $stmt->fetchAll();
    ?>
    <table>
        <tr><th>ID</th><th>First Name</th><th>Last Name</th><th>Address</th><th>Specialization</th></tr>
        <?php foreach ($doctors as $doc): ?>
            <tr>
                <td><?php echo $doc['docID']; ?></td>
                <td><?php echo $doc['docFName']; ?></td>
                <td><?php echo $doc['docLName']; ?></td>
                <td><?php echo $doc['docAddress']; ?></td>
                <td><?php echo $doc['docSpecial']; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>

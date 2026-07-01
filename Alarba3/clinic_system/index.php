<?php 
createStudentTable();
function createStudentTable(){
    $conn = new PDO('sqlite: skilltest.db');
    $sql = "CREATE TABLE IF NOT EXISTS `students`(
        id INT(10) PRIMARY KEY,
        idNo INT (20) UNIQUE,
        lastName VARCHAR(50),
        firstName VARCHAR(50),
        course VARCHAR(20),
        yearLevel VARCHAR(20)
        )";
    $conn -> exec($sql);
    $conn = NULL;
}

function addStudent($idNo, $lastName, $firstName, $course, $yearLevel){
    try{
        $conn = new PDO('sqlite: skilltest.db');
        $sql = "INSERT INTO students (`idNo`, `lastName`, `firstName`, `course`, `yearLevel`)
        VALUES (?,?,?,?,?);";
        
        $stmt = $conn->prepare($sql);
        $stmt-> execute([$idNo, $lastName, $firstName, $course, $yearLevel]);
        $conn = NULL;
    } catch (PDOException $e) {echo $e -> getMessage();}
}

function deleteStudent($idNo){
    try{
        $conn = new PDO('sqlite: skilltest.db');
        $sql = "DELETE FROM students WHERE `idNo` = ?";
        $stmt = $conn -> prepare($sql);
        $stmt ->execute([$idNo]);
        $conn = NULL;
    } catch (PDOException $e) {echo $e-> getMessage();}
}

function updateStudent($idNo, $lastName, $firstName, $course, $yearLevel ){
    try{
        $conn = new PDO ('sqlite: skilltest.db');
        $sql = "UPDATE students SET `lastname`, `firstName`, `course`, `yearLevel` WHERE `idNo` = ?";
        $stmt = $conn -> prepare($sql);
        $stmt -> execute([$lastName, $firstName, $course, $yearLevel, $idNo]);
        $conn = NULL;
    } catch (PDOException $e){echo $e -> getMessage();}
}
function getStudent(){
    try{
        $conn = new PDO('sqlite: skilltest.db');
        $sql = "SELECT * FROM students";
        $stmt = $conn-> query($sql);
        $students = $stmt -> fetchAll(PDO::FETCH_ASSOC);
        $conn = NULL;
    } catch (PDOException $e){echo $e -> getMessage();}
}

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    if (isset($_POST['action'])){
        if($_POST['action'] === 'add'){
            addStudent($_POST['id'], $_POST['lastName'], $_POST['firsttName'],$_POST['course'], $_POST['yearLevel']);
        }elseif($_POST['action'] === 'update'){
            updateStudent($_POST['id'], $_POST['lastName'], $_POST['firsttName'],$_POST['course'], $_POST['yearLevel']);
        }elseif ($_POST['action'] === 'delete'){
            deleteStudent($_POST['idNo']);
        }
    }
    header("Location: index.php");
    exit;
}
$students = getStudent();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>student Register</title>
    <style>
        body {font-family: sans-serif; margin: 20px;}
        table {border-collapse: collapse; width: 100%; margin-top: 20px;}
        th, td {border: 1px solid #add; padding: 8px; text-align: center;}
        th{background-color: #f2f2f2;}
        .form-container {background: #f9f9f9; padding: 15px; border: 1px solid #ccc; width: 300px;}
        input{display: block; margin-bottom: 10px; width: 100%;}
    </style>
</head>
<body>
    <h2>Add Update Student</h2>
    <div class ="form-container">
        <form method="POSt">
            <input type="text" name="idNo" placeholder="Id No" require>
            <input type="text" name="lastname" placeholder="last Name" required>
            <input type="text" name="firstname" placeholder="first name" required>
            <input type="text" name="course" placeholder="Course" required>
            <input type="text" name="yearLevel" placeholder="Year Level" required>
            <button type="submit" name="action" value="add">ADD STUDENT</button>
            <button type="submit" name="action" value="update">UPDATE STUDENT</button>
        </form>
    </div>
    <h2>STUDENT LIST</h2>
    <table>
        <tr>
            <th>ID NO</th>
            <th>Last name</th>
            <th>First Name</th>
            <th>Course</th>
            <th>Level</th>
            <th>Action</th>
        </tr>
        <?php if (!empty($students)): ?>
            <?php foreach($students as $students): ?>
                <tr>
                    <td><?= htmlspecialchars($students['idNo']) ?></td>
                    <td><?= htmlspecialchars($students['lastName']) ?></td>
                    <td><?= htmlspecialchars($students['firstName']) ?></td>
                    <td><?= htmlspecialchars($students['course']) ?></td>
                    <td><?= htmlspecialchars($students['yearLevel']) ?></td>
                    <td>
                        <tr>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="idNo" value="<?= htmlspecialchars($students['idno']) ?>">
                                <button type="submit" name="action" value="delete">Delete</button>
                            </form>
                        </tr>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
                <tr><td colspan="6">No students found.</td></tr>
        <?php endif; ?>
    </table>
</body>
</html>
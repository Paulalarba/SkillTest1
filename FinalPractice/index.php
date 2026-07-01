<?php
createStudentTable();

function createStudentTable(){
	$conn = new PDO('sqlite:skilltest.db');
	$sql = "CREATE TABLE IF NOT EXISTS `students`(
		id INTEGER PRIMARY KEY AUTOINCREMENT,
		idno VARCHAR(10) UNIQUE,
		lastname VARCHAR(50),
		firstname VARCHAR(50),
		course VARCHAR(10),
		level VARCHAR(5)
	)";
	$conn->exec($sql);
	$conn=null;
}


function createTeacherTable(){
	$conn = new PDO('sqlite:skilltest.db');
	$sql = "CREATE TABLE IF NOT EXISTS `teacher`(
		id INTEGER PRIMARY KEY AUTOINCREMENT,
		teacherId INT UNIQUE,
		firstName VARCHAR(50),
		lastName VARCHAR(50),
		department VARCHAR(50),
		ratePerHour VARCHAR(20)
	)";
	$conn->exec($sql);
	$conn=null;
}

function addstudent($idno,$lastname,$firstname,$course,$level){
	try{
		$conn = new PDO('sqlite:skilltest.db');
		$sql="INSERT INTO students(`idno`,`lastname`,`firstname`,`course`,`level`)
			VALUES
			(?,?,?,?,?);";
		$stmt = $conn->prepare($sql);
		$stmt->execute([$idno,$lastname,$firstname,$course,$level]);
		$conn=null;
	}catch(PDOException $e){ echo $e->getMessage();}
}

function updatestudent($idno,$lastname,$firstname,$course,$level){
	try{
		$conn = new PDO('sqlite:skilltest.db');
		$sql="UPDATE students SET `lastname`=?,`firstname`=?,`course`=?,`level`=? WHERE `idno`=?";
		$stmt = $conn->prepare($sql);
		$stmt->execute([$lastname,$firstname,$course,$level,$idno]);
		$conn=null;
	}catch(PDOException $e){ echo $e->getMessage();} 
}

function deletestudent($idno){
	try{
		$conn = new PDO('sqlite:skilltest.db');
		$sql="DELETE FROM students WHERE `idno`=?";
		$stmt = $conn->prepare($sql);
		$stmt->execute([$idno]);
		$conn=null;
	}catch(PDOException $e){ echo $e->getMessage();} 
}

function getallstudents(){
	try{
		$conn = new PDO('sqlite:skilltest.db');
		$sql="SELECT * FROM students";
		$stmt = $conn->query($sql);
		$students = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$conn=null;
		return $students;
	}catch(PDOException $e){ 
		echo $e->getMessage();
		return []; 
	} 
}

// Handle Actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'add') {
            addstudent($_POST['idno'], $_POST['lastname'], $_POST['firstname'], $_POST['course'], $_POST['level']);
        } elseif ($_POST['action'] === 'update') {
            updatestudent($_POST['idno'], $_POST['lastname'], $_POST['firstname'], $_POST['course'], $_POST['level']);
        } elseif ($_POST['action'] === 'delete') {
            deletestudent($_POST['idno']);
        }
    }
    header("Location: index.php");
    exit;
}

$students =  getallstudents();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Management</title>
    <style>
        body { font-family: sans-serif; margin: 20px; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .form-container { background: #f9f9f9; padding: 15px; border: 1px solid #ccc; width: 300px; }
        input { display: block; margin-bottom: 10px; width: 100%; }
    </style>
</head>
<body>
    <h2>Add / Update Student</h2>
    <div class="form-container">
        <form method="POST">
            <input type="text" name="idno" placeholder="ID No" required>
            <input type="text" name="lastname" placeholder="Last Name" required>
            <input type="text" name="firstname" placeholder="First Name" required>
            <input type="text" name="course" placeholder="Course" required>
            <input type="text" name="level" placeholder="Level" required>
            <button type="submit" name="action" value="add">Add Student</button>
            <button type="submit" name="action" value="update">Update Student</button>
        </form>
    </div>

    <h2>Student List</h2>
    <table>
        <tr>
            <th>ID No</th>
            <th>Last Name</th>
            <th>First Name</th>
            <th>Course</th>
            <th>Level</th>
            <th>Action</th>
        </tr>
        <?php if (!empty($students)): ?>
            <?php foreach($students as $student): ?>
                <tr>
                    <td><?= htmlspecialchars($student['idno']) ?></td>
                    <td><?= htmlspecialchars($student['lastname']) ?></td>
                    <td><?= htmlspecialchars($student['firstname']) ?></td>
                    <td><?= htmlspecialchars($student['course']) ?></td>
                    <td><?= htmlspecialchars($student['level']) ?></td>
                    <td>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="idno" value="<?= htmlspecialchars($student['idno']) ?>">
                            <button type="submit" name="action" value="delete">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="6">No students found.</td></tr>
        <?php endif; ?>
    </table>
</body>
</html>
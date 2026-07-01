<?php
function createStudentTable(){
    $conn = new PDO('sqlite: skilltest.db');
    $sql = "CREATE TABLE IF NOT EXIST(
        id INT PRIMARY KEY AUTOINCREMENT,
        idNum INT(10),
        firstName VARCHAR(50),
        lastName VARCHAR(50),
        course VARCHAR(20),
        yearL VARCHAR(10)
    )";
    $conn->exec($sql);
    $conn = NULL;
}

function addStudent($idNum, $firstname, $lastName, $course, $yearl){
    try{
        $conn = new PDO('sqlite: skilltest.db');
        $sql = "INSERT INTO students(`idNum`, `firstName`, `lastName`, `course`, `yearL`)
        VALUES (?,?,?,?,?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$idNum, $firstname, $lastName, $course, $yearl]);
        $conn = NULL;
    } catch(PDOException $e){
        echo $e->getMessage();
    }
}

?>
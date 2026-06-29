<?php
/*
-- DATABASE SCHEMA
CREATE TABLE Departments (
    depCode INT PRIMARY KEY,
    depName VARCHAR(255),
    depHead VARCHAR(255),
    depTelNo VARCHAR(50)
);

CREATE TABLE Employees (
    empID INT PRIMARY KEY,
    depCode INT,
    emp_FName VARCHAR(255),
    emp_LName VARCHAR(255),
    empRPH DECIMAL(10, 2),
    FOREIGN KEY (depCode) REFERENCES Departments(depCode)
);

CREATE TABLE Attendance (
    attRN INT PRIMARY KEY,
    empID INT,
    attDate DATE,
    attTimeIn DATETIME,
    attTimeOut DATETIME,
    attStat VARCHAR(50),
    FOREIGN KEY (empID) REFERENCES Employees(empID)
);
*/
$host = "localhost";
$user= "root";
$pass = "PaulAlarbaDB2004";
$dbname = "attendance_db";

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn){
    die("connection failed:" .mysqli_connect_error());
}
?>
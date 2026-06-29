<?php
/*
CREATE DATABASE registrationdb
USE registrationdb

CREATE TABLE Registration (
    idNum VARCHAR(255) PRIMARY KEY,
    campus VARCHAR(255),
    studFName VARCHAR(255),
    studLName VARCHAR(255),
    amountPaid DECIMAL(10, 2),
    attended VARCHAR(10)
);
*/
$host = "localhost";
$user= "root";
$pass = "PaulAlarbaDB2004";
$dbname = "registrationdb";

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn){
    die("connection failed:" .mysqli_connect_error());
}
?>
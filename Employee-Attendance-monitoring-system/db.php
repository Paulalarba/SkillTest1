<?php
$host = "localhost";
$user= "root";
$pass = "PaulAlarbaDB2004";
$dbname = "attendance_db";

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn){
    die("connection failed:" .mysqli_connect_error());
}
?>
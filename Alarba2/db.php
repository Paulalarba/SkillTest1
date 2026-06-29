<?php
/**
    *CREATE DATABASE IF NOT EXISTS electiondb;
    *USE electiondb;
 * 
 * CREATE TABLE Positions (
 *     posID INT AUTO_INCREMENT PRIMARY KEY,
 *     posName VARCHAR(255) NOT NULL,
 *     numOfPositions INT NOT NULL,
 *     posStat ENUM('open', 'closed') NOT NULL DEFAULT 'open'
 * ) ENGINE=InnoDB;
 * 
 * CREATE TABLE Voters (
 *     voterID INT AUTO_INCREMENT PRIMARY KEY,
 *     voterPass VARCHAR(255) NOT NULL,
 *     voterFName VARCHAR(255) NOT NULL,
 *     voterMName VARCHAR(255),
 *     voterLName VARCHAR(255) NOT NULL,
 *     voterStat ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
 *     voted ENUM('Y', 'N') NOT NULL DEFAULT 'N'
 * ) ENGINE=InnoDB;
 * 
 * CREATE TABLE Candidates (
 *     candID INT AUTO_INCREMENT PRIMARY KEY,
 *     candNName VARCHAR(255) NOT NULL,
 *     candMName VARCHAR(255),
 *     candLName VARCHAR(255) NOT NULL,
 *     posID INT NOT NULL,
 *     candStat VARCHAR(50) DEFAULT 'active',
 *     CONSTRAINT fk_cand_pos FOREIGN KEY (posID) REFERENCES Positions(posID) ON DELETE CASCADE
 * ) ENGINE=InnoDB;
 * 
 * CREATE TABLE Votes (
 *     posID INT NOT NULL,
 *     voterID INT NOT NULL,
 *     candID INT NOT NULL,
 *     PRIMARY KEY (posID, voterID),
 *     CONSTRAINT fk_vote_pos FOREIGN KEY (posID) REFERENCES Positions(posID) ON DELETE CASCADE,
 *     CONSTRAINT fk_vote_voter FOREIGN KEY (voterID) REFERENCES Voters(voterID) ON DELETE CASCADE,
 *     CONSTRAINT fk_vote_cand FOREIGN KEY (candID) REFERENCES Candidates(candID) ON DELETE CASCADE
 * ) ENGINE=InnoDB;
 */

$host = 'localhost';

$db   = 'electiondb'; // Replace with your actual database name
$user = 'root';     // Replace with your db username
$pass = 'PaulAlarbaDB2004';         // Replace with your db password
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
     throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
?>

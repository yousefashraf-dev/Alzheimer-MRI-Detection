<?php
$dbHost = "localhost";
$dbUser = "root";
$dbPass = "";
$dbName = "alzhimar";

try {
    $con = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
} catch (Exception $e) {
    die("Database connection failed.");
}
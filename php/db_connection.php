<?php  
// Config Details
$dbHost     = "localhost:3306";  
$dbUsername = "root";  
$dbPassword = "";  
$dbName     = "medalert";  
  
$db = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);  
  
// Checks the connection
if ($db->connect_error) {  
    die("Connection failed: " . $db->connect_error);  
} 
 
?>
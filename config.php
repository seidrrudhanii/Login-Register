<?php
/* Database credentials. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
$servername="localhost";
$dbusername="root";
$dbname="test";
$password="";
$port=3306;
 
try {
    $conn = new PDO("mysql:host=$servername;port=$port;dbname=$dbname", $dbusername, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   
  } catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
  }
?>
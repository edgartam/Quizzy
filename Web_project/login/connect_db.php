<?php
$host = 'sql203.infinityfree.com';
$db_name = 'if0_38778302_quiz_db';
$username = 'if0_38778302';
$password = '0SZsBQLNeh';
$port = 3308;

try {
 
    $pdo = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8", $username, $password);
    
  
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
   
    $pdo->exec("set names utf8");

} catch (PDOException $e) {
  
    die("Connection failed: " . $e->getMessage());
}
?>
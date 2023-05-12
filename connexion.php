<?php
$server = "localhost";
$db = "upload";
$user = "root";
$password= "";

try {
    $conn = new PDO("mysql:host=$server;dbname=$db",$user, $password);
}catch(PDOException $e) {
    echo "erreur lors de la tentative de connexion".$e->getCode();
}    
echo "connecté. ";
?>
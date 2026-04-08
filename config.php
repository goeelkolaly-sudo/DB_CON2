<?php 
$host = "localhost";
$database = "school2";
$user = "root";
$password = "";

try{
$connection = new PDO("mysql:host=$host;dbname=$database;charset=utf8",$user,$password);
$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}


catch(PDOExceeption $e){
    
 die("connection failed : ". $e->getMessage());

}


?>
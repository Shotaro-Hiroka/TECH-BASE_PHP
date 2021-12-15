<?php
    try{
        $dsn = 'mysql:dbname=tb230778db;host=localhost';
        $user = 'tb-230778';
        $password = 'V7Gx4nFc4b';
        $db = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    }catch(PDOException $e){
        echo "データベースエラー:".$e->getMessage();
    }
?>
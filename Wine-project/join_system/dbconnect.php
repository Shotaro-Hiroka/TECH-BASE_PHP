<?php
    try{
        $dsn = '＃＃＃＃＃＃＃＃＃＃＃＃＃＃';
        $user = '＃＃＃＃＃＃＃＃＃＃＃＃';
        $password = '＃＃＃＃＃＃＃＃＃＃＃＃＃';
        $db = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    }catch(PDOException $e){
        echo "データベースエラー:".$e->getMessage();
    }
?>
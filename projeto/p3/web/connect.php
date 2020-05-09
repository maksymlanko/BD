<?php
    include 'info.php'; 
    try{
        $db = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);     #open DB
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch (PDOException $e) {
        echo("<p>ERROR: {$e->getMessage()}</p>");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Local</title>

</head>
<body> 
<?php include 'connect.php'; ?><!--DB connection-->
<?php
try{
    $lat=$_REQUEST['latitude'];
    $lon=$_REQUEST['longitude'];
    if(!empty($lat)){
        $sql = "DELETE FROM local_publico WHERE latitude=".$lat." and longitude=".$lon.";";
        $result = $db->prepare($sql);
        $result->execute();
        $db=null;
        header('Location: index.php');
        exit();
    }
}
catch (PDOException $e) {
    echo("<p>ERROR: {$e->getMessage()}</p>");
    exit();
}
?>


<?php $db = null; ?>
</body>
</html>

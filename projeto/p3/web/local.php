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
    if(empty($_GET)){
        $func=0;
    }
    else{
        $func = $_REQUEST['func'];
    }
    if($func==0){
        $header = "Novo ";
    }
    if($func==1){
        $lat=$_REQUEST['latitude'];
        $lon=$_REQUEST['longitude'];
        $sql = "DELETE FROM local_publico WHERE latitude=:lat and longitude=:lon;";
        $result = $db->prepare($sql);
        $result->execute([':lat' => $lat, ':lon' =>  $lon]);
        $db=null;
        header('Location: index.php');
        exit();
    }
}
catch (PDOException $e) {
    $e;
    exit();
}
?>
<?php $db = null; ?>

<h3><?php echo $header; ?>Item</h3>
<form action="update.php" method="post">
    <p><input type="hidden" name="table" value="local"/></p>
    <p><input type="hidden" name="func" value="<?=$func?>"/></p>
    <p>Latitude: <input value="" required placeholder="00.000000" step="0.000001" type="number" name="lat" min="-90.000000" max="90.000000" size="10px"/></p>
    <p>Longitude: <input value="" required placeholder="000.000000" step="0.000001" type="number" name="lon" min="-180.000000" max="180.000000" size="10px"/></p>
    <p>Nome: <input required value="" type="text" name="nome"/></p>
    <p><input type="reset"><input type="submit" value="Submit"/></p>
</form>


</body>
</html>

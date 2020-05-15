<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Local</title>
    <link rel="stylesheet" href="style.css">
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
    error_log($e);
    echo '<h3 class="error">Não é possivel remover este local publico dado que ainda têm itens atribuidos:</h3>';
    $sql = "SELECT id, descricao FROM item WHERE latitude=:lat and longitude=:lon;";
    $result = $db->prepare($sql);
    $result->execute([':lat' => $lat, ':lon' =>  $lon]);
    echo '<center><table><tr><th>ID</th><th>Descricao</th></tr>';
    foreach($result as $row){
        echo "<tr><td>".$row['id']."</td><td>".$row['descricao']."</td></tr>";
    }
    echo "</table></center>";
    echo "<a href='index.html'>Pagina Inicial</a>";
    header('Refresh: 10; URL=index.php');
    exit();
} catch (PDOException $e) {
    error_log($e);
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

<?php include "footer.php" ?>
</body>
</html>

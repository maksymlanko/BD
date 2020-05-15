<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Item</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="style.css">

</head>
<body> 
<?php include 'connect.php'; ?><!--DB connection-->
<?php
try{
    $id=0;
    if(empty($_GET)){
        $func=0;
    }
    else{
        $func = $_REQUEST['func'];
    }
    if($func==0){
        $header = "Novo ";
        $sql = "SELECT * FROM local_publico;";
        $result = $db->prepare($sql);
        $result->execute();
    }
    if($func==1){
        $id = $_REQUEST['id'];
        $sql = "DELETE FROM item WHERE id=:id ;";
        $result = $db->prepare($sql);
        $result->execute([':id' => $id]);
        $db=null;
        header('Location: index.php');
        exit();
    }
}
catch (PDOException $e) {
    error_log($e);
    echo '<h3 class="error">Não é possivel remover este  Item dado que está referenciado pelas incidências:</h3>';
    $sql = "SELECT item_id, descricao FROM incidencia LEFT JOIN anomalia ON anomalia_id=id WHERE item_id=:id;";
    $result = $db->prepare($sql);
    $result->execute([':id' => $id]);
    echo '<center><table><tr><th>ID</th><th>Descricao</th></tr>';
    foreach($result as $row){
        echo "<tr><td>".$row['item_id']."</td><td>".$row['descricao']."</td></tr>";
    }
    echo "</table></center>";
    echo "<a href='index.php'>Pagina Inicial</a>";
    header('Refresh: 10; URL=index.php');
    exit();
} catch (PDOException $e) {
    error_log($e);
    exit();
}
?>
<?php $db = null; ?>

<script>
$( document ).ready(function() {
    $('select[name="local"]').change(function(){
        var v = $(this).val();
        var coors = v.split(';');
        $('#lat').val(coors[0]);
        $('#lon').val(coors[1]);    
    });
});
    
</script>

<h3><?php echo $header; ?>Item</h3>
<form action="update.php" method="post">
    <p><input type="hidden" name="table" value="item"/></p>
    <p><input type="hidden" name="func" value="<?=$func?>"/></p>
    <p><input type="hidden" name="id" value="<?=$id?>"/></p>
    <p>Descricao:<input required value="" type="text" name="descricao"/></p>
    <p>Localizacao: <input required value="" type="text" name="localizacao"/></p>
    
    <p>Coordenadas: <select id="local" name="local" required>
        <option disabled selected value> -- select an option -- </option>
        <?php
            foreach($result as $row){
                echo '<option value="'.$row['latitude'].';'.$row['longitude'].'">'.$row['nome'].' - Lat='.$row['latitude'].' Lon='.$row['longitude'].'</option>';
            }
        ?>
    </select></p>
    
    
    
    
    <p style="display: none;">Latitude: <input value="" required placeholder="00.000000" step="0.000001" type="number" name="lat" id="lat" min="-90.000000" max="90.000000" size="10px"/></p>
    <p style="display: none;">Longitude: <input value="" required placeholder="000.000000" step="0.000001" type="number" name="lon" id="lon" min="-180.000000" max="180.000000" size="10px"/></p>
    
    <p><input type="reset"><input type="submit" value="Submit"/></p>
</form>
<?php include "footer.php" ?>
</body>
</html>

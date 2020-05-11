<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Item</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

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
    echo $e;
    exit();
}
?>
<?php $db = null; ?>

<script>
$( document ).ready(function() {
    var v = $('#local').val();
    var coors = v.split(';');
    $('#lat').val(coors[0]);
    $('#lon').val(coors[1]); 
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

</body>
</html>

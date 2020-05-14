<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Anomalias por Local</title>
</head>
<body> 
<?php include 'connect.php'; ?>
<?php
try{
    $sql = "SELECT * FROM local_publico;";
    $result = $db->prepare($sql);
    $result->execute();
}
catch (PDOException $e) {
    echo $e;
    exit();
}
?>


<h3>Listar anomalias entre dois locais publicos</h3>
<form action="display.php" method="post">
    <p><input type="hidden" name="al" value="e"/></p>
    <p>Local Público 1: <select id="local1" name="local1" required>
        <option disabled selected value> -- select an option -- </option>
        <?php
            foreach($result as $row){
                echo '<option value="'.$row['latitude'].';'.$row['longitude'].'">'.$row['nome'].' - Lat='.$row['latitude'].' Lon='.$row['longitude'].'</option>';
            }
        ?>
    </select></p>
    <p>Local Público 2: <select id="local2" name="local2" required>
        <option disabled selected value> -- select an option -- </option>
        <?php
            $result->execute();
            foreach($result as $row){
                echo '<option value="'.$row['latitude'].';'.$row['longitude'].'">'.$row['nome'].' - Lat='.$row['latitude'].' Lon='.$row['longitude'].'</option>';
            }
        ?>
    </select></p>
    <p><input type="reset"><input type="submit" value="Submit"/></p>
</form>
<?php $db = null; ?>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Updater</title>

</head>
<body> 
<?php include 'connect.php'; #DB connection ?>
<?php
try{
    #func: 
    #       0 new
    #       2 edit

    if(empty($_POST)){
        header('Location: index.php');
    }
    $func = $_REQUEST['func'];
    $table = $_REQUEST['table'];
    $fields='';
    $values='';
    if($func==0){
        if($table=="anomalia"){
            $fields='zona, imagem, lingua, descricao, tem_anomalia_traducao';
            $values=':zona, :imagem, :lingua, :descricao, :tem_anomalia_traducao';
            
            $zona='('.$_REQUEST['x1'].','.$_REQUEST['y1'].','.$_REQUEST['x2'].','.$_REQUEST['y2'].')';
            $imagem=$_REQUEST['imagem'];
            $lingua=$_REQUEST['lingua'];
            $descricao=$_REQUEST['descricao'];
            $tem_anomalia_traducao=$_REQUEST['traducao'];
            
            /*if(!empty($_POST['time'])){
                $fields='zona, imagem, lingua, descricao, tem_anomalia_traducao, ts';
                $values=':zona, :imagem, :lingua, :descricao, :tem_anomalia_traducao, :ts';
                $ts=$_REQUEST['date']." ".$_REQUEST['time'].'.000000';
                $sql = "INSERT INTO :table ($fields)VALUES($values);";
                $result = $db->prepare($sql);
                $result->execute([':table' => $table, ':zona' => $zona, ':imagem' => $imagem, ':lingua' => $lingua, ':ts' => $ts, ':descricao' => $descricao, ':tem_anomalia_traducao' => $tem_anomalia_traducao]);
            }*/

            $sql = "INSERT INTO anomalia ($fields)VALUES($values) RETURNING id;";
            $result = $db->prepare($sql);
            $result->execute([':zona' => $zona, ':imagem' => $imagem, ':lingua' => $lingua, ':descricao' => $descricao, ':tem_anomalia_traducao' => $tem_anomalia_traducao]);
            if($tem_anomalia_traducao=="True"){
                foreach ($result as $row) {
                    $id=$row['id'];
                    break;
                }
                $fields='id, zona2, lingua2';
                $values=':id, :zona2, :lingua2';
                $sql = "INSERT INTO anomalia_traducao ($fields)VALUES($values);";

                $zona2='('.$_REQUEST['bx1'].','.$_REQUEST['by1'].','.$_REQUEST['bx2'].','.$_REQUEST['by2'].')';
                $lingua2=$_REQUEST['lingua2'];
                $result = $db->prepare($sql);
                $result->execute([':id' => $id,':zona2' => $zona2,':lingua2' => $lingua2]);
            }
            header('Location: index.php');
        }
        elseif($table=="item"){
            $fields='descricao, localizacao, latitude, longitude';
            $values=':descricao, :localizacao, :latitude, :longitude';
            
            $descricao=$_REQUEST['descricao'];
            $localizacao=$_REQUEST['localizacao'];
            $latitude=$_REQUEST['lat'];
            $longitude=$_REQUEST['lon'];

            $sql = "INSERT INTO item ($fields)VALUES($values);-- RETURNING id;";
            $result = $db->prepare($sql);
            $result->execute([':descricao' => $descricao, ':localizacao' => $localizacao, ':latitude' => $latitude, ':longitude' => $longitude]);
            header('Location: index.php');
        }
        elseif($table=="local"){
            $fields='nome, latitude, longitude';
            $values=':nome, :latitude, :longitude';
            
            $nome=$_REQUEST['nome'];
            $latitude=$_REQUEST['lat'];
            $longitude=$_REQUEST['lon'];

            $sql = "INSERT INTO local_publico ($fields)VALUES($values);";
            $result = $db->prepare($sql);
            $result->execute([':nome' => $nome, ':latitude' => $latitude, ':longitude' => $longitude]);
            header('Location: index.php');
        }

    }
    elseif ($func==2) {
       
    }
}
catch (PDOException $e){
    echo $e;
    exit();
}
?>


<?php $db = null; ?>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Anomalias entre 2 locais</title>
</head>
<body> 
<?php include 'connect.php'; ?>
<?php
try{
    if(empty($_POST)){
        $db = null;
        header('Location: index.php');
        exit();
    }
    $table=$_REQUEST['al'];
    if($table=='e'){
        $tmp=explode(';',$_POST['local1']);
        $lat1=$tmp[0];
        $lon1=$tmp[1];
        $tmp=explode(';',$_POST['local2']);
        $lat2=$tmp[0];
        $lon2=$tmp[1];
        if($lat1 > $lat2){
            $tmp=$lat1;
            $lat1=$lat2;
            $lat2=$lat1;
        }
        if($lon1 > $lon2){
            $tmp=$lon1;
            $lon1=$lon2;
            $lon2=$lon1;
        }

        $sql = "SELECT anomalia.id, zona, lingua, ts, anomalia.descricao, lingua2, zona2
        FROM incidencia JOIN anomalia on anomalia_id=anomalia.id 
        LEFT JOIN anomalia_traducao on anomalia.id=anomalia_traducao.id 
        JOIN item on item_id=item.id
        WHERE latitude BETWEEN :lat1 and :lat2
        AND longitude BETWEEN :lon1 and :lon2;";

        $result = $db->prepare($sql);
        $result->execute([':lat1'=>$lat1,':lat2'=>$lat2,':lon1'=>$lon1,':lon2'=>$lon2]);
    }
    elseif($table=='f'){
        $lat=$_POST['lat'];
        $lon=$_POST['lon'];
        $dlat=$_POST['dlat'];
        $dlon=$_POST['dlon'];



        #https://stackoverflow.com/questions/45093912/how-to-get-data-from-last-x-months-postgres-sql-query-where-date-field-is-a-time
        $sql = "SELECT anomalia.id, zona, lingua, ts, anomalia.descricao, lingua2, zona2
        FROM incidencia JOIN anomalia on anomalia_id=anomalia.id 
        LEFT JOIN anomalia_traducao on anomalia.id=anomalia_traducao.id 
        JOIN item on item_id=item.id
        WHERE latitude BETWEEN :lat::decimal - :dlat::decimal and :lat::decimal + :dlat::decimal
        AND longitude BETWEEN :lon::decimal - :dlon::decimal and :lon::decimal + :dlon::decimal
        AND ts >= date_trunc('month', now()) - interval '3 month';";

        $result = $db->prepare($sql);
        $result->execute([':lat'=>$lat,':dlat'=>$dlat,':lon'=>$lon,':dlon'=>$dlon]);
    }
    else{
        $db = null;
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

<table>
    <tr><th colspan="8">Anomalias</th></tr>
    <tr><th class='PrimaryKey'>id</th><th>Zona</th><th>Imagem</th><th>Língua</th><th>TimeStamp</th><th>Descrição</th><th>Segunda Língua</th><th>Segunda Zona</th></tr>
    <?php 
        foreach($result as $row){
            echo("<tr>"); 
            echo("<td>".$row['id']."</td>");
            echo("<td>".$row['zona']."</td>");
            echo("<td><a href='viewImage.php?id=".$row['id']."'>Open Image</a></td>");
            echo("<td>".$row['lingua']."</td>");
            echo("<td>".substr($row['ts'], 0, 19)."</td>");
            echo("<td>".$row['descricao']."</td>");
            echo("<td>".$row['lingua2']."</td>");
            echo("<td>".$row['zona2']."</td>");
            echo("</tr>\n");
        }
    ?>
</table>

<a href="index.php">Voltar ao ecrã inicial</a>

</body>
</html>
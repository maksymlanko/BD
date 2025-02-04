<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Updater</title>
    <link rel="stylesheet" href="style.css">


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
    $error='';
    if($func==0){
        if($table=="anomalia"){
            $fields='zona, imagem, lingua, descricao, tem_anomalia_redacao';
            $values=':zona, :imagem, :lingua, :descricao, :tem_anomalia_redacao';
            
            $zona='('.$_REQUEST['x1'].','.$_REQUEST['y1'].','.$_REQUEST['x2'].','.$_REQUEST['y2'].')';
            $imagem=$_REQUEST['imagem'];
            $lingua=$_REQUEST['lingua'];
            $descricao=$_REQUEST['descricao'];
            $tem_anomalia_redacao=$_REQUEST['traducao'];
            
            /*if(!empty($_POST['time'])){
                $fields='zona, imagem, lingua, descricao, tem_anomalia_redacao, ts';
                $values=':zona, :imagem, :lingua, :descricao, :tem_anomalia_redacao, :ts';
                $ts=$_REQUEST['date']." ".$_REQUEST['time'].'.000000';
                $sql = "INSERT INTO :table ($fields)VALUES($values);";
                $result = $db->prepare($sql);
                $result->execute([':table' => $table, ':zona' => $zona, ':imagem' => $imagem, ':lingua' => $lingua, ':ts' => $ts, ':descricao' => $descricao, ':tem_anomalia_redacao' => $tem_anomalia_redacao]);
            }*/

            $sql = "INSERT INTO anomalia ($fields)VALUES($values) RETURNING id;";
            $result = $db->prepare($sql);
            $result->execute([':zona' => $zona, ':imagem' => $imagem, ':lingua' => $lingua, ':descricao' => $descricao, ':tem_anomalia_redacao' => $tem_anomalia_redacao]);
            if($tem_anomalia_redacao=="False"){
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
            $db = null;
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
            $db = null;
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
            $db = null;
            header('Location: index.php');
        }
        elseif($table=="proposta"){
            $email=$_REQUEST['email'];
            $texto=$_REQUEST['texto'];
            $password=$_REQUEST['password'];
            $sql = "SELECT password FROM utilizador_qualificado NATURAL JOIN utilizador WHERE email=:email;";
            $result = $db->prepare($sql);
            $result->execute([':email' => $email]);
            $check=0;
            foreach($result as $row){
                if($row['password']==$password){
                    $check=1;
                }
                break;
            }
            if($check==0){
                $error = 'Email ou Password incorrectos';
            }
            else{
                $sql = "select insert_proposta(:email, :texto);";
                $result = $db->prepare($sql);
                $result->execute([':email' => $email, ':texto' => $texto]);
                $db = null;
                header('Location: index.php');
            }
        }
        elseif($table=="correcao"){
            $fields='anomalia_id, email, nro';
            $values=':id, :email, :nro';
            $id=$_REQUEST['incidencia'];
            $sql = "INSERT INTO correcao ($fields)VALUES($values);";
            $proposta=explode(';',$_REQUEST['proposta']);
            $email=$proposta[0];
            $nro=$proposta[1];
            $result = $db->prepare($sql);
            $result->execute([':email' => $email, ':nro' => $nro, ':id' => $id]);
            $db = null;
            header('Location: index.php');
        }
        elseif($table=="incidencia"){
            $fields='anomalia_id, email, item_id';
            $values=':aid, :email, :iid';
            $email=$_POST['utilizador'];
            $item=$_POST['item'];
            $anomalia=$_POST['anomalia'];
            $sql = "INSERT INTO incidencia ($fields)VALUES($values);";
            $result = $db->prepare($sql);
            $result->execute([':email' => $email, ':aid' => $anomalia, ':iid' => $item]);
            $db = null;
            header('Location: index.php');
        }
        elseif($table=="duplicado"){
            $fields='item1, item2';
            $values=':id1, :id2';
            $id1=$_POST['item1'];
            $id2=$_POST['item2'];
            if($id1>$id2){
                $error='ID Item 1 deve ser menor que ID Item 2';
            }
            else{
                $sql = "INSERT INTO duplicado ($fields)VALUES($values);";
                $result = $db->prepare($sql);
                $result->execute([':id1' => $id1, ':id2' => $id2]);
                $db = null;
                header('Location: index.php');
            }
        }
    }
    elseif ($func==2) {
        if($table=="proposta"){
            $fields='email, nro, data_hora, texto';
            $values=':email, :nro, :data_hora, :texto';
            $email=$_REQUEST['email'];
            $mail=$_REQUEST['mail'];
            $texto=$_REQUEST['texto'];
            $nro=$_REQUEST['nro'];
            $password=$_REQUEST['password'];
            $sql = "SELECT password FROM utilizador_qualificado NATURAL JOIN utilizador WHERE email=:email;";
            $result = $db->prepare($sql);
            $result->execute([':email' => $email]);
            $check=0;
            foreach($result as $row){
                if($row['password']==$password){
                    $check=1;
                }
                break;
            }
            if($check==0){
                $error = 'Email ou Password incorrectos';
            }
            else{
                $sql = "UPDATE proposta_de_correcao SET data_hora=now(), texto=:texto WHERE email=:email and nro=:nro;";
                $result = $db->prepare($sql);
                $result->execute([':email' => $mail, ':nro' => $nro, ':texto' => $texto]);
                $db = null;
                header('Location: index.php');
            }
        }
        elseif($table=="correcao"){
            $fields='email, nro, data_hora, texto';
            $values=':email, :nro, :data_hora, :texto';
            $texto=$_POST['propostaText'];

            $email=$_POST['email'];
            $nro=$_POST['nro'];
            
            $sql = "UPDATE proposta_de_correcao SET data_hora=now(), texto=:texto WHERE email=:email and nro=:nro;";

            $result = $db->prepare($sql);
            $result->execute([':email' => $email, ':nro' => $nro, ':texto' => $texto]);
            $db = null;
            header('Location: index.php');
            
        }

    }
}
catch (PDOException $e){
    echo $e->getMessage();
    exit();
}
?>
<?php $db = null; ?>
<h3 class="error"><?php echo $error;?></h3>
<?php header('Refresh: 10; URL=index.php'); ?>
<?php include "footer.php" ?>
</body>
</html>

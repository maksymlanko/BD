<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Proposta de Correção</title>
</head>
<body>
<!--debug-->
<?php include 'connect.php'; ?>
<?php

    #func: 
    #       0 new
    #       1 delete
    #       2 edit
?>

<?php
$email='';
$nro='';
try{
    if(empty($_GET)){
        $func=0;
    }
    else{
        $func = $_REQUEST['func'];
    }
    if($func==0){
        $header = "Nova ";
    }
    if($func==1){
        $email = $_REQUEST['email'];
        $nro = $_REQUEST['nro'];
        $sql = "DELETE FROM proposta_de_correcao WHERE email=:email and nro=:nro;";
        $result = $db->prepare($sql);
        $result->execute([':email' => $email, ':nro' => $nro]);
        $db=null;
        header('Location: index.php');
        exit();
    }
    elseif ($func==2) {
        $header = "Editar ";
        $email = $_REQUEST['email'];
        $nro = $_REQUEST['nro'];
        $sql = "SELECT * FROM proposta_de_correcao WHERE email=:email and nro=:nro;";
        $result = $db->prepare($sql);
        $result->execute([':email' => $email, ':nro' => $nro]);
        foreach ($result as $row) {
            $data_hora=$row['data_hora'];
            $texto=$row['texto'];
            break;
        }
        $data=substr($data_hora, 0, 10);
        $hora=substr($data_hora, 11, 8);

    }

}
catch (PDOException $e) {
    echo $e;
    exit();
}
?>
<!--end debug-->



<!--checkar later se houve tempo: https://stackoverflow.com/questions/36181765/set-max-value-based-on-another-input-->
<h3><?php echo $header; ?>Proposta de Correção</h3>
<form action="update.php" method="post">
    <p><input type="hidden" name="table" value="proposta"/></p>
    <p><input type="hidden" name="func" value="<?=$func?>"/></p>
    <p>Email: <input required type="email" name="email" id="email" value="<?=$email?>"/></p>
    <p>Número: <input required type="number" name="nro" value="<?=$nro?>"/></p>
    <!--<p>Date: <input required value="<?php #echo $data;?>" type="date" name="date"/></p>
    <p>Time: <input required value="<?php #echo $hora;?>" type="time" name="time"/></p>-->
    <p>Texto: <input required value="<?=$texto?>" type="text" name="texto"/></p>
    <p><input type="reset"><input type="submit" value="Submit"/></p>
</form>

<?php $db = null; ?>
</body>
</html>

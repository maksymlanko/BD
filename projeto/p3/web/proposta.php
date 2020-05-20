<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Proposta de Correção</title>
    <link rel="stylesheet" href="style.css">

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
    error_log($e);
    echo '<h3 class="error">Não é possivel remover esta Proposta de Correção dado que está em uso como Correção</h3>';
    echo "<a href='index.php'>Pagina Inicial</a>";
    header('Refresh: 10; URL=index.php');
    exit();
} catch (PDOException $e) {
    error_log($e);
    exit();
}
?>
<!--end debug-->



<!--checkar later se houve tempo: https://stackoverflow.com/questions/36181765/set-max-value-based-on-another-input-->
<h3><?php echo $header; ?>Proposta de Correção</h3>
<form action="update.php" method="post">
    <p><input type="hidden" name="table" value="proposta"/></p>
    <p><input type="hidden" name="func" value="<?=$func?>"/></p>
    <p><input type="hidden" name="mail" value="<?=$email?>"/></p>
    <p><input type="hidden" name="nro" value="<?=$nro?>"/></p>
    <p>Email: <input required type="email" name="email" id="email" value="<?=$email?>"/></p>
    <p>Password: <input required type="password" name="password" id="email" value=""/></p>
    <p style="display: none;">Número: <input disabled type="number" name="nro" value="<?=$nro?>"/></p>

    <p>Texto: <input id="texto" name="texto" value="<?=$texto?>"/></p>

    
    <p><input type="submit" value="Submit"/></p>
</form>

<?php $db = null; ?>
<?php include "footer.php" ?>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Correção</title>
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
$email='email@domain.com';
$nro='000';
$aid='';
$exists=0;
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
        $aid = $_REQUEST['anomalia_id'];
        $sql = "DELETE FROM correcao WHERE email=:email and nro=:nro and anomalia_id=:aid;";
        $result = $db->prepare($sql);
        $result->execute([':email' => $email, ':nro' => $nro, ':aid' => $aid]);
        $db=null;
        header('Location: index.php');
        exit();
    }
    elseif ($func==2) {
        $header = "Editar ";
        $email = $_REQUEST['email'];
        $nro = $_REQUEST['nro'];
        $aid = $_REQUEST['anomalia_id'];
        $sql='SELECT email, nro, texto, descricao, imagem FROM
        proposta_de_correcao NATURAL JOIN correcao NATURAL JOIN incidencia LEFT JOIN anomalia
        ON anomalia_id = anomalia.id;
        WHERE correcao.email=:email and nro=:nro';

        #$sql = "SELECT * FROM proposta_de_correcao WHERE email=:email and nro=:nro and anomalia_id=:aid;";
        $result = $db->prepare($sql);
        $result->execute([':email' => $email, ':nro' => $nro, ':aid' => $aid]);
        foreach ($result as $row) {
            $exists=1;
            break;
        }
        if($exists==0){
            header('Location: index.php');
        }
    }

}
catch (PDOException $e) {
    echo $e;
    exit();
}
?>
<!--end debug-->



<!--checkar later se houve tempo: https://stackoverflow.com/questions/36181765/set-max-value-based-on-another-input-->
<h3><?php echo $header; ?>Correção</h3>
<form action="update.php" method="post">
    <p><input type="hidden" name="table" value="correcao"/></p>
    <p><input type="hidden" name="func" value="<?=$func?>"/></p>

    <p>Incidência:
        <select id="local" name="local" required>
            <?php
                $sql='SELECT descricao, id FROM incidencia JOIN anomalia on anomalia.id=incidencia.anomalia_id;';
                $result = $db->prepare($sql);
                $result->execute();
                foreach($result as $row){
                    echo '<option value="'.$row['id'].'">'.$row['id'].' - '.$row['descricao'].'</option>';
                }
            ?>
        </select>
    </p>
    <p>Propostas de correção:
        <select id="proposta" name="proposta" required>
            <?php
                $sql='SELECT * FROM proposta_de_correcao;';
                $result = $db->prepare($sql);
                $result->execute();
                foreach($result as $row){
                    echo '<option value="'.$row['email'].';'.$row['nro'].'">'.$row['texto'].' - '.$row['email'].':'.$row['nro'].'</option>';
                }
            ?>
        </select>
    </p>

    <p><input type="reset"><input type="submit" value="Submit"/></p>
</form>

<?php $db = null; ?>
</body>
</html>

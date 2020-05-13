<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Correção</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

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

<script>
    $( document ).ready(function() {
        $('select[name="incidencia"]').change(function(){
            var a = $(this).val();
            var res;
            jQuery.ajax({
                type: "POST",
                url: 'functions.php',
                dataType: 'json',
                data: {functionname: 'incidencia', arguments: [a]},
                success: function (obj, textstatus) {
                            if( !('error' in obj) ) {
                                res=obj;
                            }
                            else {
                                console.error(obj.error);
                            }
                },
                async: false
            });

            //console.log(res);
            $('#aid').val(res.aid);
            $('#zona').val(res.zona);
            $('#lingua').val(res.lingua);
            $('#a_descricao').val(res.a_descricao);
            $('#localizacao').val(res.localizacao);
            $('#it_descri').val(res.it_descri);
            $('#imagem').attr("src", res.imagem);
        });

        $('select[name="proposta"]').change(function(){
            var a = $(this).val();
            var res;
            jQuery.ajax({
                type: "POST",
                url: 'functions.php',
                dataType: 'json',
                data: {functionname: 'proposta', arguments: [a]},
                success: function (obj, textstatus) {
                            if( !('error' in obj) ) {
                                res=obj;
                            }
                            else {
                                console.error(obj.error);
                            }
                },
                async: false
            });
            $('#propostaText').val(res.texto);
        });
    });
</script>

<h3><?php echo $header; ?>Correção</h3>
<form action="update.php" method="post">
    <p><input type="hidden" name="table" value="correcao"/></p>
    <p><input type="hidden" name="func" value="<?=$func?>"/></p>

    <p>Incidência:
        <select id="incidencia" name="incidencia" required>
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
    <table>
        <tr><th colspan="6">Incidência</th></tr>
        <tr><th>ID</th><th>Zona</th><th>Lingua</th><th>Descrição Anomalia</th><th>Item</th><th>Localização Item</th></tr>
        <tr>
            <td><input value="" id="aid"/></td>
            <td><input value="" id="zona"/></td>
            <td><input value="" id="lingua"/></td>
            <td><input value="" id="a_descricao"/></td>
            <td><input value="" id="localizacao"/></td>
            <td><input value="" id="it_descri"/></td>

        </tr>
    </table>
    <img src="" id="imagem"/>



    <p>Propostas de correção:
        <select id="proposta" name="proposta" required>
            <?php
                $sql='SELECT * FROM proposta_de_correcao;';
                $result = $db->prepare($sql);
                $result->execute();
                foreach($result as $row){
                    echo '<option value="'.$row['email'].';'.$row['nro'].'">'.$row['email'].':'.$row['nro'].'</option>';
                }
            ?>
        </select>
    </p>
    <textarea id="propostaText" name="propostaText" cols="40" rows="5" value=""></textarea>


    <p><input type="reset"><input type="submit" value="Submit"/></p>
</form>

<?php $db = null; ?>
</body>
</html>

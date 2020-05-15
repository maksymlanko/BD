<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Registar Incidencia</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="style.css">


</head>
<body>
<?php include 'connect.php'; ?>


<script>
    function reload_anomalia(){
        var a = $('#anomalia').val();
        var res;
        jQuery.ajax({
            type: "POST",
            url: 'functions.php',
            dataType: 'json',
            data: {functionname: 'anomalia', arguments: [a]},
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

        $('#zona').val(res.zona);
        $('#lingua').val(res.lingua);
        $('#ts').val(res.ts);
        $('#descricao').val(res.descricao);
        $('#zona2').val(res.zona2);
        $('#lingua2').val(res.lingua2);
        $('#imagem').attr("src", res.imagem);
    }
    $( document ).ready(function() {
        $('select[name="anomalia"]').change(function(){
            reload_anomalia();
        });
    });
</script>

<h3><?php echo $header; ?>Registar Incidência</h3>
<form action="update.php" method="post">
    <p><input type="hidden" name="table" value="incidencia"/></p>
    <p><input type="hidden" name="func" value="0"/></p>

    <p>Utilizador:
        <select id="utilizador" name="utilizador" required>
            <option disabled selected value> -- select an option -- </option>
            <?php
                $sql='SELECT email FROM utilizador;';
                $result = $db->prepare($sql);
                $result->execute();
                foreach($result as $row){
                    echo '<option value="'.$row['email'].'">'.$row['email'].'</option>';
                }
            ?>
        </select>
    </p>
    <p>Item:
        <select id="item" name="item" required>
            <option disabled selected value> -- select an option -- </option>
            <?php
                $sql='SELECT id, descricao, localizacao FROM item;';
                $result = $db->prepare($sql);
                $result->execute();
                foreach($result as $row){
                    echo '<option value="'.$row['id'].'">'.$row['id'].' - '.$row['descricao'].' - '.$row['localizacao'].'</option>';
                }
            ?>
        </select>
    </p>
    <p>Anomalia:
        <select id="anomalia" name="anomalia" required>
            <option disabled selected value> -- select an option -- </option>
            <?php
                $sql='SELECT * FROM anomalia;';
                $result = $db->prepare($sql);
                $result->execute();
                foreach($result as $row){
                    echo '<option value="'.$row['id'].'" >'.$row['id'].' - '.$row['descricao'].'</option>';
                }
            ?>
        </select>
    </p>

    <table>
        <tr><th colspan="6">Incidência</th></tr>
        <tr><th>Descrição</th><th>Lingua</th><th>Zona</th><th>Lingua 2</th><th>Zona 2</th><th>Timestamp</th></tr>
        <tr>
            <td><input disabled value="" id="descricao"/></td>
            <td><input disabled value="" id="lingua"/></td>
            <td><input disabled value="" id="zona"/></td>
            <td><input disabled value="" id="lingua2"/></td>
            <td><input disabled value="" id="zona2"/></td>
            <td><input disabled value="" id="ts"/></td>
        </tr>
    </table>
    <img src="" id="imagem"/>

    <p><input type="reset"><input type="submit" value="Submit"/></p>
</form>

<?php $db = null; ?>
<?php include "footer.php" ?>
</body>
</html>

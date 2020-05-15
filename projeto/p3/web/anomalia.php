<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Anomalia</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
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

    $comp='20px';
    $id='';
    $hidden='';
    $zona='';
    $imagem='';
    $lingua='';
    $ts='';
    $descricao='';
    $tem_anomaila_traducao='';
    $zona2='';
    $lingua2='';
    $inFalse='';
    $inTrue='';
    $ax1='';
    $ax2='';
    $ay1='';
    $ay2='';
    $bx1='';
    $bx2='';
    $by1='';
    $by2='';
    $ta='';
    $tb='';
try{
    if(empty($_GET)){
        $func=0;
    }
    else{
        $func = $_REQUEST['func'];
    }
    $inTrue='';
    $inFalse='checked';

    if($func==0){
        $header = "Nova ";
    }
    if($func==1){
        $id = $_REQUEST['id'];
        $sql = "DELETE FROM anomalia WHERE id=:id ;";
        $result = $db->prepare($sql);
        $result->execute([':id' => $id]);
        $sql = "DELETE FROM anomalia_traducao WHERE id=:id;";
        $result = $db->prepare($sql);
        $result->execute([':id' => $id]);
        $db=null;
        header('Location: index.php');
        exit();
    }
    elseif ($func==2) {
        $header = "Editar ";
        $id = $_REQUEST['id'];
        $sql = "SELECT * FROM anomalia WHERE id=$id;";
        $result = $db->prepare($sql);
        $result->execute();
        foreach ($result as $row) {
            $zona=$row['zona'];
            $imagem=$row['imagem'];
            $lingua=$row['lingua'];
            $ts=$row['ts'];
            $descricao=$row['descricao'];
            $tem_anomaila_traducao=$row['tem_anomaila_traducao'];        
            break;
        }
        $zona=substr($zona, 1);
        $arr = explode(",", $zona, 21);
        $ax1=$arr[0];
        $ay1=$arr[1];
        $ax2=$arr[2];
        $ay2=substr($arr[3], 0, -1);
        $ta=substr($ts, 0, 10);
        $tb=substr($ts, 11, 8);
        if($tem_anomaila_traducao==1){
            $inFalse='';
            $inTrue='checked';

            $sql = "SELECT * FROM anomalia_traducao WHERE id=$id;";
            $result = $db->prepare($sql);
            $result->execute();
            foreach ($result as $row) {
                $zona2=$row['zona2'];
                $lingua2=$row['lingua2'];
                break;
            }
            $zona2=substr($zona2, 1);
            $arr = explode(",", $zona2, 21);
            $bx1=$arr[0];
            $by1=$arr[1];
            $bx2=$arr[2];
            $by2=substr($arr[3], 0, -1);
        }
        else{
            $inFalse='checked';
            $inTrue='';
            $tem_anomaila_traducao=0;
        }

    }

}
catch (PDOException $e) {
    error_log($e);
    echo '<h3 class="error">Não é possivel remover esta Anomalia dado que está referenciado por uma Incidência</h3>';
    echo "<a href='index.php'>Pagina Inicial</a>";
    header('Refresh: 10; URL=index.php');
    exit();
} catch (PDOException $e) {
    error_log($e);
    exit();
}
?>
<!--end debug-->

<script type="text/javascript">
$(document).ready(function(){ 
    $("input[name$='traducao']").click(function() {
        var test = $(this).val();
        $(".traducao").hide();
        $(".traducao"+test).show();
        $("input.in").prop('required',false);
        $("input.in"+test).prop('required',true);
    }); 
});

</script>


<!--checkar later se houve tempo: https://stackoverflow.com/questions/36181765/set-max-value-based-on-another-input-->
<h3><?php echo $header; ?>Anomalia</h3>
<form action="update.php" method="post">
    <p><input required type="hidden" name="table" value="anomalia"/></p>
    <p><input required type="hidden" name="func" value="<?=$func?>"/></p>
    <p><input required type="hidden" name="id" value="<?=$id?>"/></p>
    <p>Zona: (<input value="<?=$ax1?>" required type="number" name="x1" min="0" max="9999" size="2px"/>,<input value="<?=$ay1?>" required type="number" name="y1" min="0" max="9999" size="2px"/>,<input value="<?=$ax2?>" required type="number" name="x2" min="0" max="9999" size="2px"/>,<input value="<?=$ay2?>" required type="number" name="y2" min="0" max="9999" size="2px"/>)</p>
    <p>Imagem: <input value="<?=$imagem?>" required type="url" name="imagem"/></p>
    <p>Lingua: <input required value="<?=$lingua?>" type="text" name="lingua"/></p>
    <p style="display: none;">Date: <input value="<?=$ta?>" type="date" name="date"/></p>
    <p style="display: none;">Time: <input value="<?=$tb?>" type="time" name="time"/></p>
    <p>Descricao: <input required value="<?=$descricao?>" type="text" name="descricao"/></p>
    <p>Anomalia de Traducao: 
        <br>
        <input type="radio" id="false" name="traducao" value="False" <?=$inFalse?>>
        <label for="False">Não</label><br>
        <input type="radio" id="true" name="traducao" value="True" <?=$inTrue?>>
        <label for="True">Sim</label><br>
    </p>
    <p class="traducaoTrue traducao" style="display: none;">Zona2:  (<input class="inTrue in" value="<?=$bx1?>" type="number" name="bx1" min="0" max="9999" size="2px"/>,<input class="inTrue in" value="<?=$by1?>" type="number" name="by1" min="0" max="9999" size="2px"/>,<input class="inTrue in" value="<?=$bx2?>" type="number" name="bx2" min="0" max="9999" size="2px"/>,<input class="inTrue in" value="<?=$by2?>" type="number" name="by2" min="0" max="9999" size="2px"/>)</p>
    <p class="traducaoTrue traducao" style="display: none;">Lingua2: <input class="inTrue in" value="<?=$lingua2?>" type="text" name="lingua2" id="lingua2"/></p>
    <p><input type="reset"><input type="submit" value="Submit"/></p>
</form>

<?php $db = null; ?>
<?php include "footer.php" ?>
</body>
</html>

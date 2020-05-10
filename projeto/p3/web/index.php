<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>App BD</title>

    <style>
        body {
            /*background-color:*/
            font-family: "Arial Black", Gadget, sans-serif;
        }
        .debuggerTable{
            border: 1px solid orange;
            margin-bottom: 20px;
        }
        .debuggerTable tr th{
            border: 1px solid orange;
        }
        .debuggerTable tr td{
            border: 1px solid orange;
        }
        .PrimaryKey{
            /*color: red;*/
        }
        table{
            border: 1px solid orange;
            margin-bottom: 20px;
        }
        th, td{
            border: 1px solid orange;
        }
        .PrimaryKey{
            /*color: red;*/
        }
        .new{
            margin-top: 5px;
            text-align: center;
        }
        .new a{
            text-decoration: none;
        }
        .new a:hover{
            color:red;
        }
    </style>
</head>
<body> 
<?php include 'connect.php'; #DB connection ?>
<?php
    function process_boolean($bool){
        if($bool=='1')
            return 'True';
        else
            return 'False';
    }
?>
<?php #include 'debug.php'; ?>
<?php
    #func: 
    #       0 new
    #       1 delete
    #       2 edit
?>

<table id="local_publico">
    <tr><th colspan="2">Local Público</th></tr>
    <tr><th class='PrimaryKey'>Latitude, Longitude</th><th>Nome</th></tr>
    <?php 
        $sql = "SELECT * FROM local_publico;";
        $result = $db->prepare($sql);
        $result->execute();
        foreach($result as $row){
            echo("<tr>"); 
            echo("<td>".'('.$row['latitude'].', '.$row['longitude'].')'."</td>");
            echo("<td>".$row['nome']."</td>");
            echo("<td><a href='local.php?func='1'&latitude=".$row['latitude']."&longitude=".$row['longitude']."'>Delete Entry</a></td>");
            echo("</tr>\n");
        }
    ?>
    <tr></tr>
    <tr><td colspan="2" class="new"><a href='local.php'>Incerir novo local</a></td></tr>
</table>
<table class="debuggerTable" id="item">
    <tr><th colspan="4">Item</th></tr>
    <tr><th class='PrimaryKey'>id</th><th>Descrição</th><th>localização</th><th>latitude, longitude</th></tr>
    <?php 
        $sql = "SELECT * FROM item;";
        $result = $db->prepare($sql);
        $result->execute();
        foreach($result as $row){
            echo("<tr>"); 
            echo("<td>".$row['id']."</td>");
            echo("<td>".$row['descricao']."</td>");
            echo("<td>".$row['localizacao']."</td>");
            echo("<td>".'('.$row['latitude'].', '.$row['longitude'].')'."</td>");
            echo("<td><a href='item.php?func='1'&id=".$row['id']."'>Delete Entry</a></td>");
            echo("</tr>\n");
        }
    ?>
    <tr></tr>
    <tr><td colspan="4" class="new"><a href='item.php'>Incerir novo Item</a></td></tr>
</table>
<table class="debuggerTable" id="anomalia">
    <tr><th colspan="7">Anomalias</th></tr>
    <tr><th class='PrimaryKey'>id</th><th>Zona</th><th>Imagem</th><th>Língua</th><th>TimeStamp</th><th>Descrição</th><th>Redação</th></tr>
    <?php 
        $sql = "SELECT * FROM anomalia;";
        $result = $db->prepare($sql);
        $result->execute();
        foreach($result as $row){
            echo("<tr>"); 
            echo("<td>".$row['id']."</td>");
            echo("<td>".$row['zona']."</td>");
            echo("<td><a href='viewImage.php?id=".$row['id']."'>Open Image</a></td>");
            echo("<td>".$row['lingua']."</td>");
            echo("<td>".$row['ts']."</td>");
            echo("<td>".$row['descricao']."</td>");
            echo("<td>".process_boolean($row['tem_anomaila_traducao'])."</td>");
            echo("<td><a href='anomalia.php?func='1'&id=".$row['id']."'>Delete Entry</a></td>");
            echo("</tr>\n");
        }
    ?>
    <tr></tr>
    <tr><td colspan="7" class="new"><a href='anomalia.php'>Incerir nova Anomalia</a></td></tr>
</table>

<?php $db = null; ?>
</body>
</html>

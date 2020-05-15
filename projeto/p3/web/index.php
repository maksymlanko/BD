<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>App BD</title>
    <link rel="stylesheet" href="style.css">

    <!--<style>
        
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
        
    </style>-->
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
<h1 id="title">App 3ª parte projeto BD</h1>
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
            echo("<td><a href='local.php?func=1&latitude=".$row['latitude']."&longitude=".$row['longitude']."'>Delete Entry</a></td>");
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
            echo("<td><a href='item.php?func=1&id=".$row['id']."'>Delete Entry</a></td>");
            echo("</tr>\n");
        }
    ?>
    <tr></tr>
    <tr><td colspan="4" class="new"><a href='item.php'>Incerir novo Item</a></td></tr>
</table>
<table class="debuggerTable" id="anomalia">
    <tr><th colspan="8">Anomalias</th></tr>
    <tr><th class='PrimaryKey'>id</th><th>Descrição</th><th>Imagem</th><th>TimeStamp</th><th>Língua</th><th>Zona</th><th>Segunda Língua</th><th>Segunda Zona</th></tr>
    <?php 
        $sql = "SELECT anomalia.id, zona,imagem,lingua,ts,descricao,zona2,lingua2 FROM anomalia LEFT JOIN anomalia_traducao ON anomalia_traducao.id=anomalia.id;";
        $result = $db->prepare($sql);
        $result->execute();
        foreach($result as $row){
            echo("<tr>"); 
            echo("<td>".$row['id']."</td>");
            echo("<td>".$row['descricao']."</td>");
            echo("<td><a href='viewImage.php?id=".$row['id']."'>Open Image</a></td>");
            echo("<td>".substr($row['ts'], 0, 19)."</td>");
            echo("<td>".$row['lingua']."</td>");
            echo("<td>".$row['zona']."</td>");
            echo("<td>".$row['lingua2']."</td>");
            echo("<td>".$row['zona2']."</td>");
            echo("<td><a href='anomalia.php?func=1&id=".$row['id']."'>Delete Entry</a></td>");
            echo("</tr>\n");
        }
    ?>
    <tr></tr>
    <tr><td colspan="8" class="new"><a href='anomalia.php'>Incerir nova Anomalia</a></td></tr>
</table>
<table class="debuggerTable" id="correcao">
    <tr><th colspan="3">Correção</th></tr>
    <tr><th class='PrimaryKey'>Utilizador</th><th class='PrimaryKey'>Número</th><th class='PrimaryKey'>Anomalia</th></tr>
    <?php 
        $sql = "SELECT * FROM correcao;";
        $result = $db->prepare($sql);
        $result->execute();
        foreach($result as $row){
            echo("<tr>"); 
            echo("<td>".$row['email']."</td>");
            echo("<td>".$row['nro']."</td>");
            echo("<td>".$row['anomalia_id']."</td>");
            echo("<td><a href='correcao.php?func=2&email=".$row['email']."&nro=".$row['nro']."&aid=".$row['anomalia_id']."'>Edit Entry</a></td>");
            echo("<td><a href='correcao.php?func=1&email=".$row['email']."&nro=".$row['nro']."&aid=".$row['anomalia_id']."'>Delete Entry</a></td>");
            echo("</tr>\n");
        }
    ?>
    <tr></tr>
    <tr><td colspan="3" class="new"><a href='correcao.php'>Incerir nova Correção</a></td></tr>
</table>
<table class="debuggerTable" id="proposta_de_correcao">
    <tr><th colspan="4">Propósta de Correção</th></tr>
    <tr><th class='PrimaryKey'>Utilizador</th><th class='PrimaryKey'>Número</th><th>Data e Hora</th><th>Comentário</th></tr>
    <?php 
        $sql = "SELECT * FROM proposta_de_correcao;";
        $result = $db->prepare($sql);
        $result->execute();
        foreach($result as $row){
            echo("<tr>"); 
            echo("<td>".$row['email']."</td>");
            echo("<td>".$row['nro']."</td>");
            echo("<td>".substr($row['data_hora'], 0, 19)."</td>");
            echo("<td>".$row['texto']."</td>");
            echo("<td><a href='proposta.php?func=2&email=".$row['email']."&nro=".$row['nro']."'>Edit Entry</a></td>");
            echo("<td><a href='proposta.php?func=1&email=".$row['email']."&nro=".$row['nro']."'>Delete Entry</a></td>");
            echo("</tr>\n");
        }
    ?>
    <tr></tr>
    <tr><td colspan="4" class="new"><a href='proposta.php'>Incerir nova Proposta de Correção</a></td></tr>
</table>

<table class="debuggerTable" id="utilizador">
    <tr><th colspan="2">Utilizadores</th></tr>
    <tr><th class='PrimaryKey'>Email</th><th>Qualificado</th></tr>
    <?php 
        $sql = "SELECT * FROM Utilizador;";
        $result = $db->prepare($sql);
        $result->execute();
        $sql = "SELECT email FROM Utilizador_Qualificado WHERE email=:email;";
        $result2 = $db->prepare($sql);
        $qual='';
        foreach($result as $row){
            echo("<tr>"); 
            echo("<td>".$row['email']."</td>");
            #echo("<td>".$row['password']."</td>");    
            
            $result2->execute([':email' => $row['email']]);
            foreach($result2 as $row){
                $qual='X';
                break;
            }
            echo("<td style='text-align: center;'>$qual</td>");
            $qual='';
            
            echo("</tr>\n");
        }
    ?>
</table>
<?php $db = null; ?>

<br>
<center>
<p><a href="incidencia.php" class="alinea">Registar Incidencias</a></p>
<p><a href="duplicados.php" class="alinea">Registar Duplicados</a></p>
<p><a href="e.php" class="alinea">Listar anomalias entre 2 locais públicos</a></p>
<p><a href="f.php" class="alinea">Procurar anomalias por coordenadas</a></p>
</center>
<?php include "footer.php" ?>
</body>
</html>

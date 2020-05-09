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
    </style>
</head>
<body> 
<?php include 'info.php'; ?><!--DB params-->
<?php
try{
    $db = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);     #open DB
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $e) {
    echo("<p>ERROR: {$e->getMessage()}</p>");
}
?>
<center>
    <table class="debuggerTable" id="local_publico">
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
                echo("</tr>\n");
            }
        ?>
    </table>
    <table class="debuggerTable" id="item">
        <tr><th colspan="4">Item</th></tr>
        <tr><th class='PrimaryKey'>id</th><th>Descrição</th><th>localização</th><th>latitude, longitude</th></tr>
        <?php 
            $sql = "SELECT * FROM anomalia;";
            $result = $db->prepare($sql);
            $result->execute();
            foreach($result as $row){
                echo("<tr>"); 
                echo("<td>".$row['id']."</td>");
                echo("<td>".$row['descricao']."</td>");
                echo("<td>".$row['localizacao']."</td>");
                echo("<td>".'('.$row['latitude'].', '.$row['longitude'].')'."</td>");
                echo("</tr>\n");
            }
        ?>
    </table>
    <table class="debuggerTable" id="anomalia">
        <tr><th colspan="7">Anomalias</th></tr>
        <tr><th class='PrimaryKey'>id</th><th>Zona</th><th>Imagem</th><th>Língua</th><th>TimeStamp</th><th>Descrição</th><th>Redação</th></tr>
        <?php 
            #https://stackoverflow.com/questions/22210612/display-image-from-postgresql-database-in-php
            $sql = "SELECT * FROM anomalia;";
            $result = $db->prepare($sql);
            $result->execute();
            foreach($result as $row){
                echo("<tr>"); 
                echo("<td>".$row['id']."</td>");
                echo("<td>".$row['zona']."</td>");
                echo("<td>".$row['imagem']."</td>");
                echo("<td>".$row['lingua']."</td>");
                echo("<td>".$row['ts']."</td>");
                echo("<td>".$row['descricao']."</td>");
                echo("<td>".$row['redacao']."</td>");

                echo("</tr>\n");
            }
        ?>
    </table>
    <table class="debuggerTable" id="anomalia_traducao">
        <tr><th colspan="3">Anomalias de Tradução</th></tr>
        <tr><th class='PrimaryKey'>id</th><th>Zona 2</th><th>Língua 2</th></tr>
        <?php 
            $sql = "SELECT * FROM anomalia_traducao;";
            $result = $db->prepare($sql);
            $result->execute();
            foreach($result as $row){
                echo("<tr>"); 
                echo("<td>".$row['id']."</td>");
                echo("<td>".$row['zona2']."</td>");
                echo("<td>".$row['lingua2']."</td>");
                echo("</tr>\n");
            }
        ?>
    </table>    
    <table class="debuggerTable" id="duplicado">
        <tr><th colspan="2">Itens Duplicados</th></tr>
        <tr><th class='PrimaryKey'>Item 1</th><th class='PrimaryKey'>Item 2</th></tr>
        <?php 
            $sql = "SELECT * FROM duplicado;";
            $result = $db->prepare($sql);
            $result->execute();
            foreach($result as $row){
                echo("<tr>"); 
                echo("<td>".$row['item1']."</td>");
                echo("<td>".$row['item2']."</td>");
                echo("</tr>\n");
            }
        ?>
    </table>
    <table class="debuggerTable" id="utilizador">
        <tr><th colspan="2">Utilizadores</th></tr>
        <tr><th class='PrimaryKey'>Email</th><th>Password</th></tr>
        <?php 
            $sql = "SELECT * FROM Utilizador;";
            $result = $db->prepare($sql);
            $result->execute();
            foreach($result as $row){
                echo("<tr>"); 
                echo("<td>".$row['email']."</td>");
                echo("<td>".$row['password']."</td>");
                echo("</tr>\n");
            }
        ?>
    </table>
    <table class="debuggerTable" id="utilizador_qualificado">
        <tr><th colspan="1">Utilizadores Qualificados</th></tr>
        <tr><th class='PrimaryKey'>Email</th></tr>
        <?php 
            $sql = "SELECT * FROM Utilizador_qualificado;";
            $result = $db->prepare($sql);
            $result->execute();
            foreach($result as $row){
                echo("<tr>"); 
                echo("<td>".$row['email']."</td>");
                echo("</tr>\n");
            }
        ?>
    </table>
    <table class="debuggerTable" id="utilizador_regular">
        <tr><th colspan="1">Utilizadores Regulares</th></tr>
        <tr><th class='PrimaryKey'>Email</th></tr>
        <?php 
            $sql = "SELECT * FROM Utilizador_regular;";
            $result = $db->prepare($sql);
            $result->execute();
            foreach($result as $row){
                echo("<tr>"); 
                echo("<td>".$row['email']."</td>");
                echo("</tr>\n");
            }
        ?>
    </table>
    <table class="debuggerTable" id="incidencia">
        <tr><th colspan="3">Incidências</th></tr>
        <tr><th class='PrimaryKey'>Anomalia</th><th>Item</th><th>Utilizador</th></tr>
        <?php 
            $sql = "SELECT * FROM incidencia;";
            $result = $db->prepare($sql);
            $result->execute();
            foreach($result as $row){
                echo("<tr>"); 
                echo("<td>".$row['anomalia_id']."</td>");
                echo("<td>".$row['item_id']."</td>");
                echo("<td>".$row['email']."</td>");
                echo("</tr>\n");
            }
        ?>
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
                echo("<td>".$row['data_hora']."</td>");
                echo("<td>".$row['texto']."</td>");
                echo("</tr>\n");
            }
        ?>
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
                echo("</tr>\n");
            }
        ?>
    </table>
</center>
<?php $db = null; ?>
</body>
</html>

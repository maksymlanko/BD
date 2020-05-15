<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Anomalias por Coordenadas</title>
    <link rel="stylesheet" href="style.css">

</head>
<body> 

<h3>Listar anomalias por coordenadas</h3>
<form action="display.php" method="post">    
    <p><input type="hidden" name="al" value="f"/></p>
    <p>Latitude: <input value="" required placeholder="00.000000" step="0.000001" type="number" name="lat" min="-90.000000" max="90.000000" size="10px"/></p>
    <p>Longitude: <input value="" required placeholder="000.000000" step="0.000001" type="number" name="lon" min="-180.000000" max="180.000000" size="10px"/></p>
    <br><br>
    <p>Variação de Latitude: <input value="" required placeholder="00.000000" step="0.000001" type="number" name="dlat" min="0" max="90.000000" size="10px"/></p>
    <p>Variação de Longitude: <input value="" required placeholder="000.000000" step="0.000001" type="number" name="dlon" min="0" max="180.000000" size="10px"/></p>
    

    <p><input type="reset"><input type="submit" value="Submit"/></p>
</form>
<?php include "footer.php" ?>
</body>
</html>

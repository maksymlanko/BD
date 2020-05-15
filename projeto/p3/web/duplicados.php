<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Itens Duplicados</title>
    <link rel="stylesheet" href="style.css">

</head>
<body>
<!--debug-->
<?php include 'connect.php'; ?>
<?php
    $sql="SELECT id, descricao FROM item;";
    $result = $db->prepare($sql);
    $result->execute();
?>
<!--end debug-->

<table class="debuggerTable" id="duplicados">
    <tr><th colspan="2">Duplicados</th></tr>
    <tr><th>item1</th><th>item2</th></tr>
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


<h3>Duplicados</h3>
<form action="update.php" method="post">
    <p><input required type="hidden" name="table" value="duplicado"/></p>
    <p><input required type="hidden" name="func" value="0"/></p>
    <p style="display:none;">Item 1: <input type="number" min="0" step="1" name="item1" value="0"/></p>
    <p style="display:none;">Item 2: <input type="number" min="0" step="1" name="item2" value="0"/></p>

    <p>Item 1: <select id="item1" name="item1" required>
        <option disabled selected value> -- select an option -- </option>
        <?php
            $sql="SELECT id, descricao FROM item;";
            $result = $db->prepare($sql);
            $result->execute();
            foreach($result as $row){
                echo '<option value="'.$row['id'].'">'.$row['id'].' - '.$row['descricao'].'</option>';
            }
        ?>
    </select></p>
    <p>Item 2: <select id="item2" name="item2" required>
        <option disabled selected value> -- select an option -- </option>
        <?php
            $result->execute();
            foreach($result as $row){
                echo '<option value="'.$row['id'].'">'.$row['id'].' - '.$row['descricao'].'</option>';
            }
        ?>
    </select></p>



    <p><input type="reset"><input type="submit" value="Submit"/></p>
</form>

<?php $db = null; ?>
<?php include "footer.php" ?>
</body>
</html>

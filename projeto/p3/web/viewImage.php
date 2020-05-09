<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>App BD</title>

</head>
<body> 
<?php include 'connect.php'; ?><!--DB connection-->

<?php 
try{
    $id = $_REQUEST['id'];
    $sql = "SELECT zona, imagem FROM anomalia WHERE id=".$id.";";
    $result = $db->prepare($sql);
    $result->execute();
    foreach($result as $row){
        $img=$row['imagem'];
        $zona=$row['zona'];
        break;
    }
    $db = null;
}
catch (PDOException $e) {
    echo("<p>ERROR: {$e->getMessage()}</p>");
    exit();
}
?>


<img src="<?php echo $img; ?>"/>


</body>
</html>
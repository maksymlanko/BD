<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Item</title>

</head>
<body> 
<?php include 'connect.php'; ?><!--DB connection-->
<?php
try{
    $id = $_REQUEST['id'];
    if(!empty($id)){
        $sql = "DELETE FROM item WHERE id=$id ;";
        $result = $db->prepare($sql);
        $result->execute();
        $db=null;
        header('Location: index.php');
        exit();
    }
}
catch (PDOException $e) {
    echo("<p>ERROR: {$e->getMessage()}</p>");
    exit();
}
?>



<?php $db = null; ?>
</body>
</html>

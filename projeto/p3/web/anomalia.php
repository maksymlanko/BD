<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Snomslis</title>

</head>
<body> 
<?php include 'connect.php'; ?><!--DB connection-->
<?php $id = $_REQUEST['id'];
    if(!empty($id)){
        $sql = "DELETE FROM anomalia WHERE id=$id ;";
        $result = $db->prepare($sql);
        $result->execute();
        $db=null;
        header('Location: index.php');
        exit();
    }
?>







</h3> <form action="update.php" method="post">
    <p><input type="hidden" name="account_number" value="<?=$_REQUEST['account_number']?>"/></p>
<p>New balance: <input type="text" name="balance"/></p>
<p><input type="submit" value="Submit"/></p> </form>
    </body>


<?php $db = null; ?>
</body>
</html>

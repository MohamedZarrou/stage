<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="" method="POST">
        <input type="submit" name="detail" value="detail">
        <input type="submit" name="diploms" value="diploms">
        <input type="submit" name="historique" value="historique">
        <input type="submit" name="us" value="us">
    </form>
</body>
</html>
<?php 
$PPR=$_GET["PPR"];
if(isset($_POST["detail"])){
    header("location:admin/details.php?PPR=$PPR");
    exit();
}
if(isset($_POST["us"])){
    header("location:admin/us.php?PPR={$PPR}");
    exit();
}
if(isset($_POST["diploms"])){
    header("location:admin/diploms.php?PPR={$PPR}");
    exit();
}
if(isset($_POST["historique"])){
    header("location:admin/historique.php?PPR={$PPR}");
    exit();
}

?>
<?php
include "../sql/db.php";
$PPR = $_GET["PPR"];
if($_SERVER["REQUEST_METHOD"]=="POST"){
$db = Database::getInstance()->getConnection();
$sql="INSERT INTO diplome (Lib,niveau,etablissement,annee,type,PPR) VALUES(:lib,:niveau,:etablissement,:annee,:type,:PPR)";
$stmt=$db->prepare($sql);
$stmt->bindparam(":lib",$_POST["lib"]);
$stmt->bindparam(":niveau",$_POST["niveau"]);
$stmt->bindparam(":etablissement",$_POST["etablissement"]);
$stmt->bindparam(":annee",$_POST["annee"]);
$stmt->bindparam(":type",$_POST["type"]);
$stmt->bindparam(":PPR",$PPR);
$stmt->execute();
}
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="" method="POST">
    <label for="">libele</label>
    <input type="text" name="lib" >
    <br>
    <label for="">niveau</label>
    <input type="text" name="niveau" >
    <br>
    <label for="">etablissement</label>
    <input type="text" name="etablissement" >
    <label for="">annee</label>
    <input type="date" name="annee" >
    <label for="">type</label>
    <input type="text" name="type" >

    <input type="submit"value="add">
    </form>
</body>
</html>
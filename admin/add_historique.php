<?php
include "../sql/db.php";
$PPR = $_GET["PPR"];
if($_SERVER["REQUEST_METHOD"]=="POST"){
$db = Database::getInstance()->getConnection();
$sql="INSERT INTO hist_affectation (Code,date_debut,date_fin,PPR) VALUES(:code,:dd,:df,:PPR)";
$stmt=$db->prepare($sql);
$stmt->bindparam(":code",$_POST["Code"]);
$stmt->bindparam(":dd",$_POST["date_debut"]);
$stmt->bindparam(":df",$_POST["date_fin"]);
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
    <label for="">Code</label>
    <input type="text" name="Code" >
    <br>
    <label for="">date de debut</label>
    <input type="date" name="date_debut" >
    <br>
    <label for="">date de fin</label>
    <input type="date" name="date_fin" >
    <input type="submit"value="add">
    </form>
</body>
</html>
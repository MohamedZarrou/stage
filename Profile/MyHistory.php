<?php 
include "../sql/db.php";
$PPR=$_COOKIE["PPR"];
if(isset($_POST["add"])){
    header("location:add_historique.php?PPR=$PPR");
    exit();

}


$db = Database::getInstance()->getConnection();
$sql="SELECT * FROM hist_affectation WHERE PPR=:PPR";
$stmt=$db->prepare($sql);
$stmt->bindparam(":PPR",$PPR);
$stmt->execute();
$historiques= $stmt->fetchall(PDO::FETCH_ASSOC);
if(isset($_POST["supprimer"])){
    $id=$_POST["id"];
    $sql1=" DELETE from hist_affectation WHERE id=:ID";
    $stmt1=$db->prepare($sql1);
    $stmt1->bindparam(":ID",$id);
    $stmt1->execute();
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
    <table border="1">
        <tr>
            <th>US</th>
            <th>Date du début</th>
            <th>Date de la fin</th>
           
        </tr>
        
        <?php foreach($historiques as $historique):?>
            <tr>
            <td><?= htmlspecialchars($historique["Code"]) ?></td>
            <td><?= htmlspecialchars($historique["date_debut"]) ?></td>
            <td><?= htmlspecialchars($historique["date_fin"]) ?></td>
            
            

            </tr>
        <?php  endforeach;?>
        
    </table>
  
</body>
</html>
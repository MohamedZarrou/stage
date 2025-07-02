<?php 
include "../sql/db.php";
$PPR=$_GET["PPR"];

$db = Database::getInstance()->getConnection();
$sql="SELECT * FROM diplome WHERE PPR=:PPR";
$stmt=$db->prepare($sql);
$stmt->bindparam(":PPR",$PPR);
$stmt->execute();
$diploms = $stmt->fetchall(PDO::FETCH_ASSOC);
if(isset($_POST["supprimer"])){
    $id=$_POST["id"];
    $sql1=" DELETE from diplome WHERE id=:ID";
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
            <th>id</th>
            <th>libele</th>
            <th>niveau</th>
            <th>etablisement</th>
            <th>annee</th>
            <th>type</th>
            <th>action</th>
        </tr>
        
        <?php foreach($diploms as $diplom):?>
            <tr>
            <td><?= htmlspecialchars($diplom["id"]) ?></td>
            <td><?= htmlspecialchars($diplom["Lib"]) ?></td>
            <td><?= htmlspecialchars($diplom["niveau"]) ?></td>
            <td><?= htmlspecialchars($diplom["etablissement"]) ?></td>
            <td><?= htmlspecialchars($diplom["annee"]) ?></td>
            <td><?= htmlspecialchars($diplom["type"]) ?></td>
            <td><a href="diploms_edit.php?ID=<?= htmlspecialchars($diplom["id"]) ?>"> <button>modifier</button></a>
            <form action="" method="POST"><input type="submit"name="supprimer" value="supprimer">
        <input type="hidden"  name="id"value="<?= htmlspecialchars($diplom["id"]) ?>"></form> </td>

            </tr>
        <?php  endforeach;?>
        <a href="diploms_add.php?PPR=<?= htmlspecialchars($PPR)?>"> <button>add diplom</button></a>
    </table>
</body>
</html>
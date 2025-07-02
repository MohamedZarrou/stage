<?php 
include "../sql/db.php";

$id = $_GET["ID"];

$db = Database::getInstance()->getConnection();
$sql = "SELECT * FROM hist_affectation WHERE id = :id";
$stmt = $db->prepare($sql);
$stmt->bindParam(":id", $id);
$stmt->execute();
$historique = $stmt->fetch(PDO::FETCH_ASSOC);
if(isset($_POST["retour"])){
    header("location:historique.php?PPR={$historique["PPR"]})");
    exit();

}


if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["save"])) {
    $sqlUpdate = "UPDATE hist_affectation SET 
        Code=:Code,
        date_debut=:date_debut,
        date_fin=:date_fin
        WHERE id = :id";

    $stmtUpdate = $db->prepare($sqlUpdate);
    $stmtUpdate->bindParam(":Code", $_POST["Code"]);
    $stmtUpdate->bindParam(":date_debut", $_POST["date_debut"]);
    $stmtUpdate->bindParam(":date_fin", $_POST["date_fin"]);
    $stmtUpdate->bindParam(":id", $id) ;
    $stmtUpdate->execute();
    header("location:historique.php?PPR={$historique["PPR"]})");
    exit();

    
}


?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Historique D'affectation</title>
    <style>
        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }
        input[type="text"] {
            width: 300px;
            padding: 5px;
        }
        .buttons {
            margin-top: 20px;
        }
    </style>
</head>
<body>

<h2>Mod</h2>

<form method="POST" id="profileForm">
    <label for="Code">Code</label>
    <input type="text" name="Code" value="<?= htmlspecialchars($historique["Code"]) ?>" >

    <label for="date_debut">Date du début</label>
    <input type="date" name="date_debut" value="<?= htmlspecialchars($historique["date_debut"]) ?>" >

    <label for="date_fin">Date de la fin</label>
    <input type="date" name="date_fin" value="<?= htmlspecialchars($historique["date_fin"]) ?>" >
    <input type="submit" value="save" name="save">
    <input type="submit" value="retour" name="retour">
   
</form>


</body>
</html>
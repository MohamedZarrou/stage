<?php 
include "../sql/db.php";

$id = $_GET["ID"];

$db = Database::getInstance()->getConnection();
$sql = "SELECT * FROM diplome WHERE id = :id";
$stmt = $db->prepare($sql);
$stmt->bindParam(":id", $id);
$stmt->execute();
$diploms = $stmt->fetch(PDO::FETCH_ASSOC);
if(isset($_POST["retour"])){
    header("location:diploms.php?PPR={$diploms["PPR"]})");
    exit();

}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["save"])) {
    $sqlUpdate = "UPDATE diplome SET 
        Lib=:lib,
        niveau=:niveau,
        etablissement=:eta,
        annee=:annee,
        type=:type
      

        WHERE id = :id";

    $stmtUpdate = $db->prepare($sqlUpdate);
    $stmtUpdate->bindParam(":lib", $_POST["lib"]);
    $stmtUpdate->bindParam(":niveau", $_POST["niveau"]);
    $stmtUpdate->bindParam(":eta", $_POST["etablissement"]);
    $stmtUpdate->bindParam(":type", $_POST["Type"]);
    $stmtUpdate->bindParam(":annee", $_POST["annee"]);
    $stmtUpdate->bindParam(":id", $id) ;
    
    $stmtUpdate->execute();
    header("location:diploms.php?PPR={$diploms["PPR"]})");
    exit();

    
}


?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Profil Utilisateur</title>
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

<h2>Profil de l'utilisateur</h2>

<form method="POST" id="profileForm">
    <label for="libelee">libelee</label>
    <input type="text" name="lib" value="<?= htmlspecialchars($diploms["Lib"]) ?>" >

    <label for="prenom">niveau</label>
    <input type="text" id="niveau" name="niveau" value="<?= htmlspecialchars($diploms["niveau"]) ?>" >

    <label for="Cin">etablissement</label>
    <input type="text" name="etablissement" value="<?= htmlspecialchars($diploms["etablissement"]) ?>" >

    <label for="">annee</label>
    <input type="annee" name="annee" value="<?= htmlspecialchars($diploms["annee"]) ?>" >

    <label for="Type">Type</label>
    <input type="Type" name="Type" value="<?= htmlspecialchars($diploms["type"]) ?>" >
    <input type="submit" value="save" name="save">
    <input type="submit" value="retour" name="retour">
</form>


</body>
</html>

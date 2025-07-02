<?php 
include "../sql/db.php";

$PPR = $_GET["PPR"];
$db = Database::getInstance()->getConnection();

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["save"])) {
    $sqlUpdate = "UPDATE us SET 
        lib = :lib,
        cellule_mere = :cellule_mere,
        type = :type,
        Batiment = :Batiment
        WHERE PPR = :ppr";

    $stmtUpdate = $db->prepare($sqlUpdate);
    $stmtUpdate->bindParam(":lib", $_POST["lib"]);
    $stmtUpdate->bindParam(":cellule_mere", $_POST["cellule_mere"]);
    $stmtUpdate->bindParam(":type", $_POST["type"]);
    $stmtUpdate->bindParam(":Batiment", $_POST["batiment"]);
    $stmtUpdate->bindParam(":ppr", $PPR);
    $stmtUpdate->execute();

    
    header("Location: " . $_SERVER['PHP_SELF'] . "?PPR=" . urlencode($PPR));
    exit;
}


$sql = "SELECT * FROM us WHERE PPR = :ppr";
$stmt = $db->prepare($sql);
$stmt->bindParam(":ppr", $PPR);
$stmt->execute();
$us = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Profil US</title>
    <style>
        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }
        input[type="text"],
        input[type="date"] {
            width: 300px;
            padding: 5px;
        }
        .buttons {
            margin-top: 20px;
        }
    </style>
</head>
<body>

<h2>Profil de l'US</h2>

<form method="POST" id="profileForm">
    <label for="lib">Libellé</label>
    <input type="text" name="lib" value="<?= htmlspecialchars($us["lib"]) ?>" disabled>

    <label for="cellule_mere">Cellule mère</label>
    <input type="text" name="cellule_mere" value="<?= htmlspecialchars($us["cellule_mere"]) ?>" disabled>

    <label for="type">Type</label>
    <input type="text" name="type" value="<?= htmlspecialchars($us["type"]) ?>" disabled>

    <label for="batiment">Bâtiment</label>
    <input type="text" name="batiment" value="<?= htmlspecialchars($us["Batiment"]) ?>" disabled>

    <div class="buttons">
        <button type="button" id="editBtn">Modifier</button>
        <button type="submit" name="save" id="saveBtn" style="display:none;">Enregistrer</button>
    </div>
</form>

<script>
    const editBtn = document.getElementById('editBtn');
    const saveBtn = document.getElementById('saveBtn');
    const inputs = document.querySelectorAll('#profileForm input');

    editBtn.addEventListener('click', function () {
        inputs.forEach(input => input.disabled = false);
        editBtn.style.display = 'none';
        saveBtn.style.display = 'inline';
    });
</script>

</body>
</html>

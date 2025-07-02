<?php 
include "../sql/db.php";

$PPR = $_GET["PPR"];
$db = Database::getInstance()->getConnection();

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["save"])) {
    $sqlUpdate = "UPDATE utilisateurs SET 
        nom = :nom,
        prenom = :prenom,
        Cin = :Cin,
        d_naiss = :d_naiss,
        d_recrutement = :d_recrutement,
        sit_familliale = :sit_familliale,
        genre = :genre,
        role = :role,
        email = :email,
        fonction = :fonction,
        grade = :grade
        WHERE PPR = :ppr";

    $stmtUpdate = $db->prepare($sqlUpdate);
    $stmtUpdate->bindParam(":nom", $_POST["nom"]);
    $stmtUpdate->bindParam(":prenom", $_POST["prenom"]);
    $stmtUpdate->bindParam(":Cin", $_POST["Cin"]);
    $stmtUpdate->bindParam(":d_naiss", $_POST["d_naiss"]);
    $stmtUpdate->bindParam(":d_recrutement", $_POST["d_recrutement"]);
    $stmtUpdate->bindParam(":sit_familliale", $_POST["sit_familliale"]);
    $stmtUpdate->bindParam(":genre", $_POST["genre"]);
    $stmtUpdate->bindParam(":role", $_POST["role"]);
    $stmtUpdate->bindParam(":email", $_POST["email"]);
    $stmtUpdate->bindParam(":fonction", $_POST["fonction"]);
    $stmtUpdate->bindParam(":grade", $_POST["grade"]);
    $stmtUpdate->bindParam(":ppr", $PPR);
    $stmtUpdate->execute();
}

$sql = "SELECT * FROM utilisateurs WHERE PPR = :ppr";
$stmt = $db->prepare($sql);
$stmt->bindParam(":ppr", $PPR);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);
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
    <label for="nom">Nom</label>
    <input type="text" name="nom" value="<?= htmlspecialchars($user["nom"]) ?>" disabled>

    <label for="prenom">Prénom</label>
    <input type="text" id="prenom" name="prenom" value="<?= htmlspecialchars($user["prenom"]) ?>" disabled>

    <label for="Cin">CIN</label>
    <input type="text" name="Cin" value="<?= htmlspecialchars($user["Cin"]) ?>" disabled>

    <label for="d_naiss">Date de naissance</label>
    <input type="date" name="d_naiss" value="<?= htmlspecialchars($user["d_naiss"]) ?>" disabled>

    <label for="d_recrutement">Date de recrutement</label>
    <input type="date" name="d_recrutement" value="<?= htmlspecialchars($user["d_recrutement"]) ?>" disabled>

    <label for="sit_familliale">Situation familiale</label>
    <input type="text" name="sit_familliale" value="<?= htmlspecialchars($user["sit_familliale"]) ?>" disabled>

    <label for="genre">Genre</label>
    <input type="text"  name="genre" value="<?= htmlspecialchars($user["genre"]) ?>" disabled>

    <label for="role">Rôle</label>
    <input type="text"  name="role" value="<?= htmlspecialchars($user["role"]) ?>" disabled>

    <label for="email">Email</label>
    <input type="text"  name="email" value="<?= htmlspecialchars($user["email"]) ?>" disabled>

    <label for="fonction">Fonction</label>
    <input type="text"  name="fonction" value="<?= htmlspecialchars($user["fonction"]) ?>" disabled>

    <label for="grade">Grade</label>
    <input type="text" name="grade" value="<?= htmlspecialchars($user["grade"]) ?>" disabled>

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

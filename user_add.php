<?php
include "sql/db.php";
$db = Database::getInstance()->getConnection();

if (isset($_POST["save"])) {
    $imgData = null;
    $mimeType = null;

    // Handle image if uploaded
    if (isset($_FILES["img"]) && $_FILES["img"]["error"] === UPLOAD_ERR_OK) {
        $imgData = file_get_contents($_FILES["img"]["tmp_name"]);
        $mimeType = $_FILES["img"]["type"];
    }

    $sql = "INSERT INTO utilisateurs (
        nom, prenom, Cin, d_naiss, d_recrutement, sit_familliale, genre,
        role, email, fonction, grade, img, mime_type
    ) VALUES (
        :nom, :prenom, :Cin, :d_naiss, :d_recrutement, :sit_familiale, :genre,
        :role, :email, :fonction, :grade, :img, :mime_type
    )";

    $stmt = $db->prepare($sql);
    $stmt->bindParam(":nom", $_POST["nom"]);
    $stmt->bindParam(":prenom", $_POST["prenom"]);
    $stmt->bindParam(":Cin", $_POST["Cin"]);
    $stmt->bindParam(":d_naiss", $_POST["d_naiss"]);
    $stmt->bindParam(":d_recrutement", $_POST["d_recrutement"]);
    $stmt->bindParam(":sit_familiale", $_POST["sit_familliale"]);
    $stmt->bindParam(":genre", $_POST["genre"]);
    $stmt->bindParam(":role", $_POST["role"]);
    $stmt->bindParam(":email", $_POST["email"]);
    $stmt->bindParam(":fonction", $_POST["fonction"]);
    $stmt->bindParam(":grade", $_POST["grade"]);
    $stmt->bindParam(":img", $imgData, PDO::PARAM_LOB);
    $stmt->bindParam(":mime_type", $mimeType);
    $stmt->execute();

    echo "<p style='color: green;'>Utilisateur ajouté avec succès !</p>";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter Utilisateur</title>
    <style>
        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }
        input[type="text"], input[type="date"], input[type="email"], input[type="file"] {
            width: 300px;
            padding: 5px;
        }
        .buttons {
            margin-top: 20px;
        }
    </style>
</head>
<body>

<h2>Ajouter un utilisateur</h2>

<form method="POST" enctype="multipart/form-data">
    <label for="nom">Nom</label>
    <input type="text" name="nom" required>

    <label for="prenom">Prénom</label>
    <input type="text" name="prenom" required>

    <label for="Cin">CIN</label>
    <input type="text" name="Cin" required>

    <label for="d_naiss">Date de naissance</label>
    <input type="date" name="d_naiss" required>

    <label for="d_recrutement">Date de recrutement</label>
    <input type="date" name="d_recrutement" required>

    <label for="sit_familliale">Situation familiale</label>
    <input type="text" name="sit_familliale">

    <label for="genre">Genre</label>
    <input type="text" name="genre">

    <label for="role">Rôle</label>
    <input type="text" name="role">

    <label for="email">Email</label>
    <input type="email" name="email">

    <label for="fonction">Fonction</label>
    <input type="text" name="fonction">

    <label for="grade">Grade</label>
    <input type="text" name="grade">

    <label for="img">Image de profil</label>
    <input type="file" name="img" accept="image/*">

    <div class="buttons">
        <input type="submit" name="save" value="Enregistrer">
    </div>
</form>

</body>
</html>

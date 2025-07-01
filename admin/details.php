 
<?php 
include "../sql/db.php";

$PPR = $_GET["PPR"];
$db = Database::getInstance()->getConnection();

$sql = "SELECT * FROM utilisateurs WHERE PPR = :ppr";
$stmt = $db->prepare($sql);
$stmt->bindParam(":ppr", $PPR);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="" method ="POST">
        <input type="text" name="nom" value="<?= htmlspecialchars($users["nom"]) ?>">
        <input type="text" name="premom" value="<?= htmlspecialchars($users["prenom"]) ?>">
        <input type="text" name="cin" value="<?= htmlspecialchars($users["cin"]) ?>">
        <input type="text" name="d_naiss" value="<?= htmlspecialchars($users["d_naiss"]) ?>">
        <input type="text" name="d_recrutement" value="<?= htmlspecialchars($users["d_recrutement"]) ?>">
        <input type="text" name="sit_familliale" value="<?= htmlspecialchars($users["sit_familliale"]) ?>">
        <input type="text" name="genre" value="<?= htmlspecialchars($users["genre"]) ?>">
        <input type="text" name="role" value="<?= htmlspecialchars($users["role"]) ?>">
        <input type="text" name="email" value="<?= htmlspecialchars($users["email"]) ?>">
        <input type="text" name="fonction" value="<?= htmlspecialchars($users["fonction"]) ?>">
        <input type="text" name="grade" value="<?= htmlspecialchars($users["grade"]) ?>">
    
    </form>
</body>
</html>
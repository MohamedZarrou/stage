<?php 
include "../sql/db.php";

$PPR = $_GET["PPR"];
$db = Database::getInstance()->getConnection();

$imgData = null;
$mimeType = null;
$updateImage = false;

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["save"])) {
    if (isset($_FILES["img"]) && $_FILES["img"]["error"] === UPLOAD_ERR_OK) {
        $imgData = file_get_contents($_FILES["img"]["tmp_name"]);
        $mimeType = $_FILES["img"]["type"];
        $updateImage = true;
    }

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
        grade = :grade";

    if ($updateImage) {
        $sqlUpdate .= ", img = :img, mime_type = :mime_type";
    }

    $sqlUpdate .= " WHERE PPR = :ppr";

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
    if ($updateImage) {
        $stmtUpdate->bindParam(":img", $imgData, PDO::PARAM_LOB);
        $stmtUpdate->bindParam(":mime_type", $mimeType);
    }
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil - <?php echo htmlspecialchars($user['prenom'] . ' ' . $user['nom']); ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-blue: #5b9bd5;
            --secondary-blue: #9dc3e6;
            --light-bg: #f0f7ff;
            --table-bg: #ffffff;
            --text-dark: #2e3a4d;
            --text-light: #ffffff;
            --border-color: #c5e0ff;
            --highlight-blue: #e6f2ff;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--light-bg);
            margin: 0;
            padding: 20px;
            color: var(--text-dark);
        }
        
        .dashboard-container {
            max-width: 800px;
            margin: 0 auto;
            background-color: var(--table-bg);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            border: 1px solid var(--border-color);
        }
        
        h1 {
            color: var(--primary-blue);
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid var(--primary-blue);
            padding-bottom: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        h2 {
            color: var(--primary-blue);
            margin-bottom: 20px;
        }
        
        .profile-form {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            color: var(--text-dark);
        }
        
        .form-control {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid var(--border-color);
            border-radius: 5px;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: var(--primary-blue);
            outline: none;
            box-shadow: 0 0 0 3px rgba(91, 155, 213, 0.2);
        }
        
        .form-control:disabled {
            background-color: #f9f9f9;
            color: #777;
        }
        
        .profile-image {
            grid-column: span 2;
            text-align: center;
            margin-bottom: 20px;
        }
        
        .profile-image img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--primary-blue);
            margin-bottom: 10px;
        }
        
        .file-input {
            display: none;
        }
        
        .file-label {
            display: inline-block;
            padding: 8px 15px;
            background-color: var(--primary-blue);
            color: white;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .file-label:hover {
            background-color: #4a8bc9;
        }
        
        .button-group {
            grid-column: span 2;
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 20px;
        }
        
        .btn {
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: all 0.3s ease;
            border: none;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-primary {
            background-color: var(--primary-blue);
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #4a8bc9;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(91, 155, 213, 0.3);
        }
        
        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }
        
        .btn-secondary:hover {
            background-color: #5a6268;
        }
        
        .user-info {
            background-color: var(--highlight-blue);
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
            border-left: 4px solid var(--primary-blue);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        
        @media (max-width: 768px) {
            .profile-form {
                grid-template-columns: 1fr;
            }
            
            .profile-image, .button-group {
                grid-column: span 1;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h1><i class="fas fa-user-edit"></i> Profil</h1>
        
        <div class="user-info">
            <i class="fas fa-user-tie"></i>
            <p>Profil de: <strong><?php echo htmlspecialchars($user['prenom'] . ' ' . htmlspecialchars($user['nom'])) ?></strong></p>
        </div>
        
        <form method="POST" enctype="multipart/form-data" id="profileForm" class="profile-form">
            <div class="profile-image">
                <img src="../image.php?PPR=<?php echo urlencode($user['PPR']); ?>" alt="Photo de profil" id="profileImg">
                <input type="file" name="img" id="img" class="file-input" accept="image/*" disabled>
                <label for="img" class="file-label" id="fileLabel" style="display:none;">
                    <i class="fas fa-camera"></i> Changer la photo
                </label>
            </div>
            
            <div class="form-group">
                <label for="nom">Nom</label>
                <input type="text" class="form-control" id="nom" name="nom" 
                       value="<?php echo htmlspecialchars($user['nom']); ?>" disabled>
            </div>
            
            <div class="form-group">
                <label for="prenom">Prénom</label>
                <input type="text" class="form-control" id="prenom" name="prenom" 
                       value="<?php echo htmlspecialchars($user['prenom']); ?>" disabled>
            </div>
            
            <div class="form-group">
                <label for="Cin">CIN</label>
                <input type="text" class="form-control" id="Cin" name="Cin" 
                       value="<?php echo htmlspecialchars($user['Cin']); ?>" disabled>
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" 
                       value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
            </div>
            
            <div class="form-group">
                <label for="d_naiss">Date de naissance</label>
                <input type="date" class="form-control" id="d_naiss" name="d_naiss" 
                       value="<?php echo htmlspecialchars($user['d_naiss']); ?>" disabled>
            </div>
            
            <div class="form-group">
                <label for="d_recrutement">Date de recrutement</label>
                <input type="date" class="form-control" id="d_recrutement" name="d_recrutement" 
                       value="<?php echo htmlspecialchars($user['d_recrutement']); ?>" disabled>
            </div>
            
            <div class="form-group">
                <label for="sit_familliale">Situation familiale</label>
                <input type="text" class="form-control" id="sit_familliale" name="sit_familliale" 
                       value="<?php echo htmlspecialchars($user['sit_familliale']); ?>" disabled>
            </div>
            
            <div class="form-group">
                <label for="genre">Genre</label>
                <input type="text" class="form-control" id="genre" name="genre" 
                       value="<?php echo htmlspecialchars($user['genre']); ?>" disabled>
            </div>
            
            <div class="form-group">
                <label for="role">Rôle</label>
                <input type="text" class="form-control" id="role" name="role" 
                       value="<?php echo htmlspecialchars($user['role']); ?>" disabled>
            </div>
            
            <div class="form-group">
                <label for="fonction">Fonction</label>
                <input type="text" class="form-control" id="fonction" name="fonction" 
                       value="<?php echo htmlspecialchars($user['fonction']); ?>" disabled>
            </div>
            
            <div class="form-group">
                <label for="grade">Grade</label>
                <input type="text" class="form-control" id="grade" name="grade" 
                       value="<?php echo htmlspecialchars($user['grade']); ?>" disabled>
            </div>
            
            <div class="button-group">
                <button type="button" class="btn btn-primary" id="editBtn">
                    <i class="fas fa-edit"></i> Modifier
                </button>
                <button type="submit" class="btn btn-primary" id="saveBtn" name="save" style="display:none">
                    <i class="fas fa-save"></i> Enregistrer
                </button>
               <a href="javascript:history.back()" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
            </div>
        </form>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const editBtn = document.getElementById('editBtn');
        const saveBtn = document.getElementById('saveBtn');
        const fileLabel = document.getElementById('fileLabel');
        const inputs = document.querySelectorAll('#profileForm input:not([type="file"])');
        const fileInput = document.getElementById('img');
        const profileImg = document.getElementById('profileImg');
        
        editBtn.addEventListener('click', () => {
            inputs.forEach(input => input.disabled = false);
            fileLabel.style.display = 'inline-block';
            fileInput.disabled = false;
            editBtn.style.display = 'none';
            saveBtn.style.display = 'inline-block';
        });
        
        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(evt) {
                    profileImg.src = evt.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    });
    </script>
</body>
</html>
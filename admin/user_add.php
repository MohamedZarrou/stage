<?php 
include "../sql/db.php";
$db = Database::getInstance()->getConnection();

$success_message = '';
$error_message = '';

if(isset($_POST["save"])) {
    try {
        // Handle file upload
        $imgData = null;
        $mimeType = null;
        
        if(isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            // Check if the file is an image
            $fileInfo = getimagesize($_FILES['photo']['tmp_name']);
            if($fileInfo !== false) {
                $imgData = file_get_contents($_FILES['photo']['tmp_name']);
                $mimeType = $_FILES['photo']['type'];
            } else {
                throw new Exception("Le fichier téléchargé n'est pas une image valide.");
            }
        }

        $sql = "INSERT INTO utilisateurs 
                (nom, prenom, Cin, d_naiss, d_recrutement, sit_familliale, genre, role, email, fonction, grade, img_profile, mime_type) 
                VALUES 
                (:nom, :prenom, :Cin, :d_naiss, :d_recrutement, :sit_familiale, :genre, :role, :email, :fonction, :grade, :img, :mime)";
        
        $stmt = $db->prepare($sql);
        $stmt->bindparam(":nom", $_POST["nom"]);
        $stmt->bindparam(":prenom", $_POST["prenom"]);
        $stmt->bindparam(":Cin", $_POST["Cin"]);
        $stmt->bindparam(":d_naiss", $_POST["d_naiss"]);
        $stmt->bindparam(":d_recrutement", $_POST["d_recrutement"]);
        $stmt->bindparam(":sit_familiale", $_POST["sit_familliale"]);
        $stmt->bindparam(":genre", $_POST["genre"]);
        $stmt->bindparam(":role", $_POST["role"]);
        $stmt->bindparam(":email", $_POST["email"]);
        $stmt->bindparam(":fonction", $_POST["fonction"]);
        $stmt->bindparam(":grade", $_POST["grade"]);
        $stmt->bindparam(":img", $imgData, PDO::PARAM_LOB);
        $stmt->bindparam(":mime", $mimeType);
        
        if($stmt->execute()) {
            $success_message = "Utilisateur créé avec succès!";
        } else {
            $error_message = "Erreur lors de la création de l'utilisateur.";
        }
    } catch (Exception $e) {
        $error_message = "Erreur: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouveau Utilisateur</title>
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
            --error-color: #e74c3c;
            --success-color: #2ecc71;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--light-bg);
            margin: 0;
            padding: 20px;
            color: var(--text-dark);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        
        .form-container {
            max-width: 800px;
            width: 100%;
            background-color: var(--table-bg);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            border: 1px solid var(--border-color);
        }
        
        h2 {
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
        
        .form-group {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .form-field {
            margin-bottom: 15px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--text-dark);
        }
        
        input[type="text"],
        input[type="date"],
        input[type="email"],
        select {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid var(--border-color);
            border-radius: 5px;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        
        input:focus,
        select:focus {
            border-color: var(--primary-blue);
            outline: none;
            box-shadow: 0 0 0 3px rgba(91, 155, 213, 0.2);
        }
        
        .photo-upload {
            grid-column: 1 / -1;
            text-align: center;
            margin-bottom: 20px;
        }
        
        .photo-preview {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--border-color);
            margin-bottom: 15px;
            background-color: #f5f5f5;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        
        .photo-preview i {
            font-size: 3rem;
            color: var(--primary-blue);
        }
        
        .photo-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .file-upload-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background-color: var(--secondary-blue);
            color: var(--text-dark);
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .file-upload-btn:hover {
            background-color: #8ab5e0;
        }
        
        .file-upload-input {
            display: none;
        }
        
        .buttons {
            display: flex;
            justify-content: flex-end;
            gap: 15px;
            margin-top: 30px;
        }
        
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 12px 25px;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            text-decoration: none;
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
            transform: translateY(-2px);
        }
        
        .alert {
            padding: 15px;
            margin-bottom: 25px;
            border-radius: 5px;
            font-weight: 500;
        }
        
        .alert-success {
            background-color: rgba(46, 204, 113, 0.1);
            color: var(--success-color);
            border-left: 4px solid var(--success-color);
        }
        
        .alert-error {
            background-color: rgba(231, 76, 60, 0.1);
            color: var(--error-color);
            border-left: 4px solid var(--error-color);
        }
        
        @media (max-width: 600px) {
            .form-group {
                grid-template-columns: 1fr;
            }
            
            .buttons {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2><i class="fas fa-user-plus"></i> Nouveau Utilisateur</h2>
        
        <?php if($success_message): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($success_message); ?>
            </div>
        <?php endif; ?>
        
        <?php if($error_message): ?>
            <div class="alert alert-error">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" enctype="multipart/form-data">
            <div class="photo-upload">
                <div class="photo-preview" id="photoPreview">
                    <i class="fas fa-user"></i>
                    <img id="previewImage" style="display: none;">
                </div>
                <button type="button" class="file-upload-btn" onclick="document.getElementById('photoInput').click()">
                    <i class="fas fa-camera"></i> Choisir une photo
                </button>
                <input type="file" id="photoInput" name="photo" class="file-upload-input" accept="image/*">
            </div>
            
            <div class="form-group">
                <div class="form-field">
                    <label for="nom">Nom</label>
                    <input type="text" name="nom" id="nom" required>
                </div>
                
                <div class="form-field">
                    <label for="prenom">Prénom</label>
                    <input type="text" id="prenom" name="prenom" required>
                </div>
                
                <div class="form-field">
                    <label for="Cin">CIN</label>
                    <input type="text" name="Cin" id="Cin" required>
                </div>
                
                <div class="form-field">
                    <label for="d_naiss">Date de naissance</label>
                    <input type="date" name="d_naiss" id="d_naiss" required>
                </div>
                
                <div class="form-field">
                    <label for="d_recrutement">Date de recrutement</label>
                    <input type="date" name="d_recrutement" id="d_recrutement" required>
                </div>
                
                <div class="form-field">
                    <label for="sit_familliale">Situation familiale</label>
                    <select name="sit_familliale" id="sit_familliale" required>
                        <option value="">Sélectionner</option>
                        <option value="Célibataire">Célibataire</option>
                        <option value="Marié(e)">Marié(e)</option>
                        <option value="Divorcé(e)">Divorcé(e)</option>
                        <option value="Veuf/Veuve">Veuf/Veuve</option>
                    </select>
                </div>
                
                <div class="form-field">
                    <label for="genre">Genre</label>
                    <select name="genre" id="genre" required>
                        <option value="">Sélectionner</option>
                        <option value="Homme">Homme</option>
                        <option value="Femme">Femme</option>
                    </select>
                </div>
                
                <div class="form-field">
                    <label for="role">Rôle</label>
                    <select name="role" id="role" required>
                        <option value="">Sélectionner</option>
                        <option value="Admin">Admin</option>
                        <option value="User">User</option>
                    </select>
                </div>
                
                <div class="form-field">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" >
                </div>
                
                <div class="form-field">
                    <label for="fonction">Fonction</label>
                    <input type="text" name="fonction" id="fonction" >
                </div>
                
                <div class="form-field">
                    <label for="grade">Grade</label>
                    <input type="text" name="grade" id="grade" >
                </div>
            </div>
            
            <div class="buttons">
                <a href="javascript:history.back()" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
                <button type="submit" name="save" class="btn btn-primary">
                    <i class="fas fa-save"></i> Enregistrer
                </button>
            </div>
        </form>
    </div>

    <script>
        // Photo preview functionality
        document.getElementById('photoInput').addEventListener('change', function(e) {
            const preview = document.getElementById('photoPreview');
            const previewImage = document.getElementById('previewImage');
            const icon = preview.querySelector('i');
            
            if (e.target.files && e.target.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(event) {
                    previewImage.src = event.target.result;
                    previewImage.style.display = 'block';
                    icon.style.display = 'none';
                }
                
                reader.readAsDataURL(e.target.files[0]);
            }
        });
    </script>
</body>
</html>
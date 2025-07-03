<?php
include "sql/db.php";
$db = Database::getInstance()->getConnection();

// Check if we're viewing employees for a specific US
$view_employees = isset($_GET['view_employees']);
$current_us_ppr = $_GET['ppr'] ?? null;

// Get all US units
$us_list = $db->query("SELECT * FROM us ORDER BY lib")->fetchAll(PDO::FETCH_ASSOC);

// If viewing employees, get the US info and its employees
$current_us = null;
$employees = [];
if ($view_employees && $current_us_ppr) {
    // Get the current US info
    $stmt = $db->prepare("SELECT * FROM us WHERE PPR = ?");
    $stmt->execute([$current_us_ppr]);
    $current_us = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Get employees working in this US
    $stmt = $db->prepare("
        SELECT u.* 
        FROM utilisateurs u
        JOIN us ue ON u.PPR = ue.PPR
        WHERE ue.PPR = ?
        ORDER BY u.nom, u.prenom
    ");
    $stmt->execute([$current_us_ppr]);
    $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unités de Service</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #5b9bd5;
            --secondary-blue: #9dc3e6;
            --light-bg: #f0f7ff;
            --table-bg: #ffffff;
            --text-dark: #2e3a4d;
            --text-light: #ffffff;
            --border-color: #c5e0ff;
            --error-color: #e74c3c;
            --success-color: #2ecc71;
        }
        
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: var(--light-bg);
            color: var(--text-dark);
            line-height: 1.6;
            padding: 20px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        h1 {
            color: var(--primary-blue);
            margin-bottom: 30px;
            text-align: center;
            font-size: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
            color: var(--primary-blue);
            text-decoration: none;
            font-weight: 500;
        }
        
        .back-button:hover {
            text-decoration: underline;
        }
        
        /* US Cards Grid */
        .us-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }
        
        .us-card {
            background-color: var(--table-bg);
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            border: 1px solid var(--border-color);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .us-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }
        
        .us-card h3 {
            color: var(--primary-blue);
            margin-bottom: 15px;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 10px;
        }
        
        .us-info {
            margin-bottom: 15px;
        }
        
        .us-info p {
            margin-bottom: 8px;
            display: flex;
        }
        
        .us-info strong {
            min-width: 120px;
            display: inline-block;
        }
        
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 15px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            border: none;
        }
        
        .btn-primary {
            background-color: var(--primary-blue);
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #4a8bc9;
        }
        
        /* Employees List */
        .employees-section {
            margin-top: 40px;
            background-color: var(--table-bg);
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 25px;
            border: 1px solid var(--border-color);
        }
        
        .employees-list {
            margin-top: 20px;
        }
        
        .employee-card {
            display: flex;
            align-items: center;
            padding: 15px;
            border-bottom: 1px solid var(--border-color);
        }
        
        .employee-card:last-child {
            border-bottom: none;
        }
        
        .employee-photo {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: var(--light-bg);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            overflow: hidden;
        }
        
        .employee-photo i {
            font-size: 20px;
            color: var(--primary-blue);
        }
        
        .employee-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .employee-info {
            flex: 1;
        }
        
        .employee-name {
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .employee-position {
            color: #666;
            font-size: 14px;
        }
        
        .no-employees {
            text-align: center;
            padding: 20px;
            color: #666;
            font-style: italic;
        }
        
        @media (max-width: 768px) {
            .us-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>
            <i class="fas fa-building"></i>
            <?= $view_employees ? "Employés de l'Unité de Service" : "Unités de Service" ?>
        </h1>
        
        <?php if ($view_employees && $current_us): ?>
            <!-- Back button when viewing employees -->
            <a href="?" class="back-button">
                <i class="fas fa-arrow-left"></i> Retour à la liste des unités
            </a>
            
            <!-- Current US Info -->
            <div class="us-card">
                <h3><?= htmlspecialchars($current_us['lib']) ?></h3>
                <div class="us-info">
                    <p><strong>Type:</strong> <?= htmlspecialchars($current_us['type']) ?></p>
                    <p><strong>Cellule mère:</strong> <?= htmlspecialchars($current_us['cellule_mere']) ?></p>
                    <p><strong>Bâtiment:</strong> <?= htmlspecialchars($current_us['Batiment']) ?></p>
                </div>
            </div>
            
            <!-- Employees List -->
            <div class="employees-section">
                <h2><i class="fas fa-users"></i> Employés</h2>
                
                <?php if (count($employees) > 0): ?>
                    <div class="employees-list">
                        <?php foreach ($employees as $employee): ?>
                            <div class="employee-card">
                                <div class="employee-photo">
                                    <?php if (!empty($employee['img_profile'])): ?>
                                        <img src="data:<?= htmlspecialchars($employee['mime_type']) ?>;base64,<?= base64_encode($employee['img_profile']) ?>" alt="Photo de profil">
                                    <?php else: ?>
                                        <i class="fas fa-user"></i>
                                    <?php endif; ?>
                                </div>
                                <div class="employee-info">
                                    <div class="employee-name">
                                        <?= htmlspecialchars($employee['prenom']) ?> <?= htmlspecialchars($employee['nom']) ?>
                                    </div>
                                    <div class="employee-position">
                                        <?= htmlspecialchars($employee['fonction']) ?> - <?= htmlspecialchars($employee['grade']) ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="no-employees">
                        <i class="fas fa-info-circle"></i> Aucun employé trouvé pour cette unité de service
                    </div>
                <?php endif; ?>
            </div>
            
        <?php else: ?>
            <!-- Main US Listing -->
            <div class="us-grid">
                <?php foreach ($us_list as $us): ?>
                    <div class="us-card">
                        <h3><?= htmlspecialchars($us['lib']) ?></h3>
                        <div class="us-info">
                            <p><strong>Type:</strong> <?= htmlspecialchars($us['type']) ?></p>
                            <p><strong>Cellule mère:</strong> <?= htmlspecialchars($us['cellule_mere']) ?></p>
                            <p><strong>Bâtiment:</strong> <?= htmlspecialchars($us['Batiment']) ?></p>
                        </div>
                        <a href="?view_employees=true&ppr=<?= urlencode($us['PPR']) ?>" class="btn btn-primary">
                            <i class="fas fa-users"></i> Voir les employés
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
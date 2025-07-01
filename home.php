<?php
include("sql/db.php");
$db = Database::getInstance()->getConnection();
$sql = "SELECT * FROM utilisateurs";
$stmt = $db->prepare($sql);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Utilisateurs</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    :root {
      --primary-color: #4361ee;
      --secondary-color: #3f37c9;
      --accent-color: #4895ef;
      --light-color: #f8f9fa;
      --dark-color: #212529;
      --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    }
    
    body {
      background-color: #f5f7ff;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    .container {
      max-width: 1200px;
    }
    
    h1 {
      color: var(--dark-color);
      font-weight: 700;
      margin-bottom: 2rem;
      position: relative;
      display: inline-block;
    }
    
    h1::after {
      content: '';
      position: absolute;
      bottom: -10px;
      left: 50%;
      transform: translateX(-50%);
      width: 80px;
      height: 4px;
      background: var(--accent-color);
      border-radius: 2px;
    }
    
    .user-card {
      transition: var(--transition);
      cursor: pointer;
      border-radius: 16px;
      overflow: hidden;
      border: none;
      background: white;
      box-shadow: var(--shadow);
      height: 100%;
      display: flex;
      flex-direction: column;
    }
    
    .user-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
    }
    
    .user-image {
      width: 100%;
      height: 220px;
      object-fit: cover;
      border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    }
    
    .user-info {
      padding: 20px;
      flex-grow: 1;
      display: flex;
      flex-direction: column;
    }
    
    .user-info h5 {
      color: var(--dark-color);
      font-weight: 600;
      margin-bottom: 0.5rem;
    }
    
    .user-info p {
      color: #6c757d;
      font-size: 0.9rem;
      margin-bottom: 0;
    }
    
    .text-decoration-none:hover {
      text-decoration: none !important;
    }
    
    .card-body {
      padding: 0;
    }
    
    @media (max-width: 768px) {
      .col-sm-6 {
        flex: 0 0 100%;
        max-width: 100%;
      }
      
      .user-image {
        height: 250px;
      }
    }
  </style>
</head>
<body>
  <div class="container py-5">
    <div class="text-center mb-5">
      <h1>Liste des Utilisateurs</h1>
    </div>
    <div class="row g-4">
      <?php foreach ($users as $user): ?>
        <div class="col-sm-6 col-md-4 col-lg-3">
          <a href="gestion.php?PPR=<?= urlencode($user['PPR']) ?>" class="text-decoration-none">
            <div class="card user-card">
              <img src="image.php?PPR=<?= urlencode($user['PPR']) ?>" class="user-image" alt="Image de <?= htmlspecialchars($user['nom']) ?>">
              <div class="user-info">
                <h5><?= htmlspecialchars($user['nom']) . ' ' . htmlspecialchars($user['prenom']) ?></h5>
                <p><?= htmlspecialchars($user['fonction']) ?></p>
              </div>
            </div>
          </a>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</body>
</html>
<?php
include("sql/db.php");

// Initialize filter variables
$filters = [
    'nom' => $_GET['nom'] ?? '',
    'prenom' => $_GET['prenom'] ?? '',
    'Cin' => $_GET['Cin'] ?? '',
    'd_naiss' => $_GET['d_naiss'] ?? '',
    'd_recrutement' => $_GET['d_recrutement'] ?? '',
    'sit_familliale' => $_GET['sit_familliale'] ?? '',
    'genre' => $_GET['genre'] ?? '',
    'role' => $_GET['role'] ?? '',
    'email' => $_GET['email'] ?? '',
    'fonction' => $_GET['fonction'] ?? '',
    'grade' => $_GET['grade'] ?? ''
];

// Build the SQL query with filters
$sql = "SELECT * FROM utilisateurs WHERE 1=1";
$params = [];

foreach ($filters as $field => $value) {
    if (!empty($value)) {
        $sql .= " AND $field LIKE :$field";
        $params[":$field"] = "%$value%";
    }
}

// Add sorting if specified
$sort = $_GET['sort'] ?? '';
$order = $_GET['order'] ?? 'ASC';
if (!empty($sort) && in_array(strtoupper($order), ['ASC', 'DESC'])) {
    $sql .= " ORDER BY $sort $order";
}

try {
    $db = Database::getInstance()->getConnection();
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Get distinct values for filter dropdowns
    $distinctValues = [
        'sit_familliale' => $db->query("SELECT DISTINCT sit_familliale FROM utilisateurs")->fetchAll(PDO::FETCH_COLUMN),
        'genre' => $db->query("SELECT DISTINCT genre FROM utilisateurs")->fetchAll(PDO::FETCH_COLUMN),
        'role' => $db->query("SELECT DISTINCT role FROM utilisateurs")->fetchAll(PDO::FETCH_COLUMN),
        'fonction' => $db->query("SELECT DISTINCT fonction FROM utilisateurs")->fetchAll(PDO::FETCH_COLUMN),
        'grade' => $db->query("SELECT DISTINCT grade FROM utilisateurs")->fetchAll(PDO::FETCH_COLUMN)
    ];
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}


$db = Database::getInstance()->getConnection();

try {
    // Count employees
    $userCountStmt = $db->query("SELECT COUNT(*) AS count FROM utilisateurs");
    $userCount = $userCountStmt->fetch(PDO::FETCH_ASSOC)['count'];

    // Count diplomas
    $diplomeCountStmt = $db->query("SELECT COUNT(*) AS count FROM diplome");
    $diplomeCount = $diplomeCountStmt->fetch(PDO::FETCH_ASSOC)['count'];

    // Count unities (assuming 'us' is the table name for unities)
    $usCountStmt = $db->query("SELECT COUNT(*) AS count FROM us");
    $usCount = $usCountStmt->fetch(PDO::FETCH_ASSOC)['count'];
} catch (PDOException $e) {
    die("Error fetching statistics: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
  <style>
    :root {
      --primary-color: #4361ee;
      --secondary-color: #3f37c9;
      --accent-color: #4895ef;
      --light-color: #f8f9fa;
      --dark-color: #212529;
      --sidebar-width: 300px;
      --navbar-height: 70px;
      --transition: all 0.3s ease;
      --diploma-color: #4cc9f0;
      --unity-color: #f72585;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f8fafc;
      overflow-x: hidden;
    }

    /* Sidebar Styles */
    .sidebar {
      width: var(--sidebar-width);
      height: 100vh;
      position: fixed;
      left: 0;
      top: 0;
      background: white;
      color: var(--dark-color);
      padding-top: var(--navbar-height);
      transition: var(--transition);
      z-index: 1000;
      box-shadow: 2px 0 15px rgba(0, 0, 0, 0.1);
      display: flex;
      flex-direction: column;
    }

    .sidebar-profile {
      padding: 20px;
      border-bottom: 1px solid #eee;
      text-align: center;
      background: linear-gradient(to right, #f8f9fa, #e9ecef);
    }

    .profile-avatar {
      width: 80px;
      height: 80px;
      border-radius: 50%;
      object-fit: cover;
      border: 3px solid white;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      margin-bottom: 10px;
    }

    .profile-name {
      font-weight: 600;
      margin-bottom: 5px;
    }

    .profile-role {
      font-size: 0.8rem;
      color: #6c757d;
      background-color: #e9ecef;
      padding: 3px 10px;
      border-radius: 20px;
      display: inline-block;
    }

    .sidebar-menu {
      padding: 20px 0;
      flex-grow: 1;
      overflow-y: auto;
    }

    .sidebar-menu .nav-link {
      color: var(--dark-color);
      padding: 12px 25px;
      margin: 3px 0;
      border-radius: 0;
      transition: var(--transition);
      display: flex;
      align-items: center;
      font-weight: 500;
    }

    .sidebar-menu .nav-link:hover,
    .sidebar-menu .nav-link.active {
      color: var(--primary-color);
      background-color: rgba(67, 97, 238, 0.1);
      border-left: 3px solid var(--primary-color);
    }

    .sidebar-menu .nav-link i {
      margin-right: 12px;
      font-size: 1.1rem;
      width: 20px;
      text-align: center;
    }

    .sidebar-section {
      margin-top: 20px;
      padding: 0 25px;
    }

    .sidebar-section-title {
      font-size: 0.85rem;
      text-transform: uppercase;
      font-weight: 600;
      color: #6c757d;
      margin-bottom: 15px;
      letter-spacing: 0.5px;
    }

    .diploma-badge {
      background-color: rgba(76, 201, 240, 0.1);
      color: var(--diploma-color);
      padding: 5px 10px;
      border-radius: 4px;
      font-size: 0.8rem;
      margin-bottom: 8px;
      display: inline-block;
    }

    .unity-badge {
      background-color: rgba(247, 37, 133, 0.1);
      color: var(--unity-color);
      padding: 5px 10px;
      border-radius: 4px;
      font-size: 0.8rem;
      margin-bottom: 8px;
      display: inline-block;
    }

    /* Navbar Styles */
    .navbar {
      height: var(--navbar-height);
      background-color: white;
      box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
      position: fixed;
      top: 0;
      left: var(--sidebar-width);
      right: 0;
      z-index: 1000;
      padding: 0 25px;
    }

    .navbar-brand {
      font-weight: 700;
      color: var(--dark-color);
      font-size: 1.2rem;
    }

    .search-bar {
      width: 350px;
      position: relative;
    }

    .search-bar input {
      padding-left: 40px;
      border-radius: 20px;
      border: 1px solid #dee2e6;
      height: 40px;
    }

    .search-bar i {
      position: absolute;
      left: 15px;
      top: 50%;
      transform: translateY(-50%);
      color: #6c757d;
    }

    .user-avatar {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      object-fit: cover;
      border: 2px solid #e9ecef;
    }

    /* Main Content Styles */
    .main-content {
      margin-left: var(--sidebar-width);
      padding: 25px;
      padding-top: calc(var(--navbar-height) + 25px);
      min-height: 100vh;
      transition: var(--transition);
      background-color: #f8fafc;
    }

    /* Filter Panel Styles */
    .filter-panel {
      background-color: white;
      border-radius: 12px;
      padding: 25px;
      margin-bottom: 25px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
      border: 1px solid #eee;
    }

    .filter-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
    }

    .filter-title {
      font-weight: 700;
      color: var(--dark-color);
      margin: 0;
      font-size: 1.2rem;
    }

    .filter-group {
      margin-bottom: 20px;
    }

    .filter-group label {
      font-weight: 600;
      font-size: 0.9rem;
      margin-bottom: 8px;
      color: #495057;
      display: block;
    }

    /* User Card Styles */
    .user-card {
      transition: var(--transition);
      cursor: pointer;
      border-radius: 12px;
      overflow: hidden;
      border: none;
      background: white;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
      height: 100%;
      display: flex;
      flex-direction: column;
      border: 1px solid #eee;
    }

    .user-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
      border-color: var(--primary-color);
    }

    .user-image-container {
      position: relative;
      height: 180px;
      overflow: hidden;
    }

    .user-image {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.5s ease;
    }

    .user-card:hover .user-image {
      transform: scale(1.05);
    }

    .user-badge {
      position: absolute;
      top: 10px;
      right: 10px;
      background-color: rgba(0, 0, 0, 0.7);
      color: white;
      padding: 3px 8px;
      border-radius: 4px;
      font-size: 0.7rem;
      font-weight: 500;
    }

    .user-info {
      padding: 20px;
      flex-grow: 1;
      display: flex;
      flex-direction: column;
    }

    .user-info h5 {
      color: var(--dark-color);
      font-weight: 700;
      margin-bottom: 0.5rem;
    }

    .user-info p {
      color: #6c757d;
      font-size: 0.9rem;
      margin-bottom: 0.5rem;
      display: flex;
      align-items: center;
    }

    .user-info p i {
      margin-right: 8px;
      width: 16px;
      color: var(--primary-color);
    }

    .user-role {
      display: inline-block;
      padding: 4px 10px;
      border-radius: 20px;
      font-size: 0.75rem;
      font-weight: 600;
      margin-top: 10px;
      align-self: flex-start;
    }

    .role-admin {
      background-color: rgba(220, 53, 69, 0.1);
      color: #dc3545;
    }

    .role-user {
      background-color: rgba(13, 110, 253, 0.1);
      color: #0d6efd;
    }

    .role-manager {
      background-color: rgba(25, 135, 84, 0.1);
      color: #198754;
    }

    /* Stats Cards */
    .stats-card {
      border-radius: 12px;
      padding: 20px;
      margin-bottom: 20px;
      color: white;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .stats-card.users {
      background: linear-gradient(135deg, #4361ee, #3a0ca3);
    }

    .stats-card.diplomas {
      background: linear-gradient(135deg, #4cc9f0, #4895ef);
    }

    .stats-card.unities {
      background: linear-gradient(135deg, #f72585, #b5179e);
    }

    .stats-card i {
      font-size: 2rem;
      margin-bottom: 15px;
    }

    .stats-card .count {
      font-size: 1.8rem;
      font-weight: 700;
      margin-bottom: 5px;
    }

    .stats-card .label {
      font-size: 0.9rem;
      opacity: 0.9;
    }

    /* Responsive Styles */
    @media (max-width: 992px) {
      .sidebar {
        transform: translateX(-100%);
      }

      .sidebar.active {
        transform: translateX(0);
      }

      .navbar {
        left: 0;
      }

      .main-content {
        margin-left: 0;
      }

      .search-bar {
        width: 100%;
        margin-top: 10px;
      }
    }

    /* Toggle Button for Mobile */
    .sidebar-toggle {
      display: none;
      background: none;
      border: none;
      font-size: 1.5rem;
      color: var(--dark-color);
    }

    @media (max-width: 992px) {
      .sidebar-toggle {
        display: block;
      }
    }
  </style>
</head>
<body>
  <!-- Sidebar -->
  <div class="sidebar">
  <?php
$nom = $_COOKIE['nom'] ?? 'Unknown';
$prenom = $_COOKIE['prenom'] ?? 'User';
$role = $_COOKIE['role'];
$fullName = htmlspecialchars("$prenom $nom"); 
?>

<!-- User Profile Section -->
<div class="sidebar-profile">
  <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($fullName); ?>&background=4361ee&color=fff" class="profile-avatar" alt="<?php echo $fullName; ?>">
  <h5 class="profile-name"><?php echo $fullName; ?></h5>
  <span class="profile-role"><?php echo $role; ?></span>
</div>
    
    <!-- Main Menu -->
    <div class="sidebar-menu">
      <ul class="nav flex-column">
        <li class="nav-item">
          <a class="nav-link active" href="#">
            <i class="bi bi-people-fill"></i> Users Management
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="unities.php">
            <i class="bi bi-building"></i> Unities
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="diploms.php">
            <i class="bi bi-file-earmark-text"></i> Diplomas
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="">
            <i class="bi bi-gear-fill"></i> Settings
          </a>
        </li>
      </ul>
      
    
    </div>
    
    <!-- Footer -->
    <div class="sidebar-footer p-3 text-center border-top">
      <a href="logout.php" class="btn btn-sm btn-outline-danger">
        <i class="bi bi-box-arrow-right"></i> Logout
      </a>
    </div>
  </div>

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-light bg-white">
    <div class="container-fluid">
      <button class="sidebar-toggle" id="sidebarToggle">
        <i class="bi bi-list"></i>
      </button>
      <a class="navbar-brand ms-3" href="#">
        <i class="bi bi-people-fill me-2"></i>User Management System
      </a>
      
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <form class="d-flex ms-auto search-bar" method="GET" action="">
          <i class="bi bi-search"></i>
          <input class="form-control me-2" type="search" name="global_search" placeholder="Search users..." 
                 value="<?= htmlspecialchars($_GET['global_search'] ?? '') ?>">
        </form>
        
        <ul class="navbar-nav ms-3">
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
  <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($fullName); ?>&background=4361ee&color=fff" class="user-avatar" alt="<?php echo $fullName; ?>">
              <span class="ms-2 d-none d-lg-inline"><?php echo $fullName; ?></span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i> My Profile</a></li>
              <li><a class="dropdown-item" href="#"><i class="bi bi-file-earmark-text me-2"></i> My Diplomas</a></li>
              <li><a class="dropdown-item" href="#"><i class="bi bi-building me-2"></i> My Unities</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item" href="#"><i class="bi bi-box-arrow-right me-2"></i> Logout</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  


  <!-- Main Content -->
  <div class="main-content">
    <div class="container-fluid">
      <!-- Stats Cards -->
      <div class="row mb-4">
        <div class="col-md-4">
          <div class="stats-card users">
            <i class="bi bi-people-fill"></i>
            <div class="count"><?= $userCount ?></div>
            <div class="label">Total Users</div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="stats-card diplomas">
            <i class="bi bi-file-earmark-text"></i>
            <div class="count"><?= $diplomeCount ?></div>
            <div class="label">Diplomas</div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="stats-card unities">
            <i class="bi bi-building"></i>
            <div class="count"><?= $usCount ?></div>
            <div class="label">Unities</div>
          </div>
        </div>
      </div>
      
      <div class="row mb-4">
        <div class="col-12">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <h2 class="mb-0">User Management</h2>
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="#"><i class="bi bi-house-door"></i> Dashboard</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Users</li>
                </ol>
              </nav>
            </div>
            <a href="admin/user_add.php" class="btn btn-primary">
              <i class="bi bi-plus-circle"></i> Add New User
            </a>
          </div>
        </div>
      </div>

      <!-- Filter Panel -->
      <div class="filter-panel">
        <div class="filter-header">
          <h3 class="filter-title"><i class="bi bi-funnel me-2"></i>Filter Users</h3>
          <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse">
            <i class="bi bi-funnel"></i> Toggle Filters
          </button>
        </div>
        
        <div class="collapse show" id="filterCollapse">
          <form method="GET" action="">
            <div class="row">
              <div class="col-md-4">
                <div class="filter-group">
                  <label for="nom"><i class="bi bi-person"></i> Last Name</label>
                  <input type="text" class="form-control" id="nom" name="nom" 
                         value="<?= htmlspecialchars($filters['nom']) ?>">
                </div>
              </div>
              <div class="col-md-4">
                <div class="filter-group">
                  <label for="prenom"><i class="bi bi-person"></i> First Name</label>
                  <input type="text" class="form-control" id="prenom" name="prenom" 
                         value="<?= htmlspecialchars($filters['prenom']) ?>">
                </div>
              </div>
              <div class="col-md-4">
                <div class="filter-group">
                  <label for="Cin"><i class="bi bi-credit-card"></i> CIN</label>
                  <input type="text" class="form-control" id="Cin" name="Cin" 
                         value="<?= htmlspecialchars($filters['Cin']) ?>">
                </div>
              </div>
            </div>
            
            <div class="row mt-3">
              <div class="col-md-3">
                <div class="filter-group">
                  <label for="role"><i class="bi bi-person-badge"></i> Role</label>
                  <select class="form-select" id="role" name="role">
                    <option value="">All Roles</option>
                    <?php foreach ($distinctValues['role'] as $role): ?>
                      <option value="<?= htmlspecialchars($role) ?>" <?= $filters['role'] === $role ? 'selected' : '' ?>>
                        <?= htmlspecialchars($role) ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
              <div class="col-md-3">
                <div class="filter-group">
                  <label for="genre"><i class="bi bi-gender-ambiguous"></i> Gender</label>
                  <select class="form-select" id="genre" name="genre">
                    <option value="">All Genders</option>
                    <?php foreach ($distinctValues['genre'] as $genre): ?>
                      <option value="<?= htmlspecialchars($genre) ?>" <?= $filters['genre'] === $genre ? 'selected' : '' ?>>
                        <?= htmlspecialchars($genre) ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
              <div class="col-md-3">
                <div class="filter-group">
                  <label for="fonction"><i class="bi bi-briefcase"></i> Function</label>
                  <select class="form-select" id="fonction" name="fonction">
                    <option value="">All Functions</option>
                    <?php foreach ($distinctValues['fonction'] as $fonction): ?>
                      <option value="<?= htmlspecialchars($fonction) ?>" <?= $filters['fonction'] === $fonction ? 'selected' : '' ?>>
                        <?= htmlspecialchars($fonction) ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
              <div class="col-md-3">
                <div class="filter-group">
                  <label for="grade"><i class="bi bi-award"></i> Grade</label>
                  <select class="form-select" id="grade" name="grade">
                    <option value="">All Grades</option>
                    <?php foreach ($distinctValues['grade'] as $grade): ?>
                      <option value="<?= htmlspecialchars($grade) ?>" <?= $filters['grade'] === $grade ? 'selected' : '' ?>>
                        <?= htmlspecialchars($grade) ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
            </div>
            
            <div class="row mt-3">
              <div class="col-12 text-end">
                <button type="submit" class="btn btn-primary">
                  <i class="bi bi-filter"></i> Apply Filters
                </button>
                <a href="?" class="btn btn-outline-secondary ms-2">
                  <i class="bi bi-arrow-counterclockwise"></i> Reset
                </a>
              </div>
            </div>
          </form>
        </div>
      </div>

      <!-- Users Grid -->
      <div class="row g-4">
        <?php foreach ($users as $user): ?>
          <div class="col-sm-6 col-md-4 col-lg-3">
            <a href="gestion2.php?PPR=<?= urlencode($user['PPR']) ?>" class="text-decoration-none">
              <div class="card user-card">
                <div class="user-image-container">
                  <img src="image.php?PPR=<?= urlencode($user['PPR']) ?>" class="user-image" alt="Image de <?= htmlspecialchars($user['nom']) ?>">
                  <span class="user-badge"><?= htmlspecialchars($user['grade']) ?></span>
                </div>
                <div class="user-info">
                  <h5><?= htmlspecialchars($user['nom']) . ' ' . htmlspecialchars($user['prenom']) ?></h5>
                  <p><i class="bi bi-envelope"></i> <?= htmlspecialchars($user['email']) ?></p>
                  <p><i class="bi bi-briefcase"></i> <?= htmlspecialchars($user['fonction']) ?></p>
                  <p><i class="bi bi-building"></i> <?= htmlspecialchars($user['sit_familliale']) ?></p>
                  <span class="user-role <?= 
                    $user['role'] === 'admin' ? 'role-admin' : 
                    ($user['role'] === 'manager' ? 'role-manager' : 'role-user') 
                  ?>">
                    <?= htmlspecialchars($user['role']) ?>
                  </span>
                </div>
              </div>
            </a>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
  <script>
    // Toggle sidebar on mobile
    document.getElementById('sidebarToggle').addEventListener('click', function() {
      document.querySelector('.sidebar').classList.toggle('active');
    });

    // Handle filter toggling
    document.querySelectorAll('.filter-group select').forEach(select => {
      select.addEventListener('change', function() {
        this.form.submit();
      });
    });
  </script>
</body>
</html>
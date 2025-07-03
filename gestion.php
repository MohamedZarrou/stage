<?php
ob_start(); // Start output buffering at the very beginning

include "sql/db.php";

// Handle form submissions first
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $PPR = isset($_GET["PPR"]) ? $_GET["PPR"] : '';
    
    if(isset($_POST["detail"])){
        header("location:admin/details.php?PPR=$PPR");
        ob_end_flush();
        exit();
    }
    if(isset($_POST["us"])){
        header("location:admin/us.php?PPR={$PPR}");
        ob_end_flush();
        exit();
    }
    if(isset($_POST["diploms"])){
        header("location:admin/diploms.php?PPR={$PPR}");
        ob_end_flush();
        exit();
    }
    if(isset($_POST["historique"])){
        header("location:admin/historique.php?PPR={$PPR}");
        ob_end_flush();
        exit();
    }
}

$PPR = isset($_GET["PPR"]) ? $_GET["PPR"] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard</title>
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
        
        .button-group {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            justify-content: center;
            margin-bottom: 15px;
        }
        
        .nav-button {
            background-color: var(--primary-blue);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: all 0.3s ease;
            min-width: 150px;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            text-decoration: none;
        }
        
        .nav-button:hover {
            background-color: #4a8bc9;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(91, 155, 213, 0.3);
        }
        
        .return-button {
            background-color: #6c757d;
            width: 100%;
            max-width: 200px;
            margin: 0 auto;
        }
        
        .return-button:hover {
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
        
        .footer {
            text-align: center;
            margin-top: 30px;
            color: var(--primary-blue);
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        
        .button-container {
            display: flex;
            justify-content: center;
            margin-top: 15px;
        }
        
        @media (max-width: 600px) {
            .button-group {
                flex-direction: column;
            }
            
            .nav-button {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h1><i class="fas fa-tachometer-alt"></i> Employee Dashboard</h1>
        
        <?php if($PPR): ?>
        <div class="user-info">
            <i class="fas fa-user-tie"></i>
            <p>Currently viewing employee with PPR: <strong><?php echo htmlspecialchars($PPR); ?></strong></p>
        </div>
        <?php endif; ?>
        
        <form action="" method="POST">
            <div class="button-group">
                <button type="submit" name="detail" class="nav-button">
                    <i class="fas fa-id-card"></i> Personal Details
                </button>
                <button type="submit" name="diploms" class="nav-button">
                    <i class="fas fa-graduation-cap"></i> Diplomas
                </button>
                <button type="submit" name="historique" class="nav-button">
                    <i class="fas fa-briefcase"></i> Work History
                </button>
                <button type="submit" name="us" class="nav-button">
                    <i class="fas fa-cog"></i> Us
                </button>
            </div>
        </form>
        
        <div class="button-container">
            <a href="dash.php" class="nav-button return-button">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>
        
        <div class="footer">
            <i class="far fa-copyright"></i>
            <p>Employee Management System <?php echo date('Y'); ?></p>
        </div>
    </div>
</body>
</html>
<?php
ob_end_flush(); // Send the output buffer
?>
</html>
<?php 
if(isset($_POST["detail"])){
    header("location:admin/details.php?PPR=$PPR");
    exit();
}
if(isset($_POST["us"])){
    header("location:admin/us.php?PPR={$PPR}");
    exit();
}
if(isset($_POST["diploms"])){
    header("location:admin/diploms.php?PPR={$PPR}");
    exit();
}
if(isset($_POST["historique"])){
    header("location:admin/historique.php?PPR={$PPR}");
    exit();
}
?>
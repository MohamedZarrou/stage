<?php 
include "../sql/db.php";
$PPR=$_COOKIE["PPR"];

$db = Database::getInstance()->getConnection();
$sql="SELECT * FROM hist_affectation WHERE PPR=:PPR";
$stmt=$db->prepare($sql);
$stmt->bindparam(":PPR",$PPR);
$stmt->execute();
$historiques= $stmt->fetchall(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assignment History</title>
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
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: var(--light-bg);
            padding: 30px;
            color: var(--text-dark);
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: var(--table-bg);
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
            padding: 30px;
            border: 1px solid var(--border-color);
        }
        
        h1 {
            color: var(--primary-blue);
            margin-bottom: 25px;
            text-align: center;
            font-size: 28px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-top: 20px;
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid var(--border-color);
        }
        
        th, td {
            padding: 14px 16px;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
        }
        
        th {
            background-color: var(--primary-blue);
            color: var(--text-light);
            font-weight: 500;
            font-size: 0.9rem;
        }
        
        tr {
            background-color: var(--table-bg);
            transition: all 0.2s ease;
        }
        
        tr:nth-child(even) {
            background-color: #f8fbff;
        }
        
        tr:hover {
            background-color: #e6f2ff;
        }
        
        @media (max-width: 768px) {
            body {
                padding: 15px;
            }
            
            .container {
                padding: 20px;
            }
            
            table {
                display: block;
                overflow-x: auto;
            }
            
            th, td {
                padding: 10px 12px;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><i class="fas fa-history"></i> Assignment History</h1>
        
        <table>
            <thead>
                <tr>
                    <th><i class="fas fa-building"></i> Unit</th>
                    <th><i class="fas fa-calendar-start"></i> Start Date</th>
                    <th><i class="fas fa-calendar-end"></i> End Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($historiques as $historique): ?>
                    <tr>
                        <td><?= htmlspecialchars($historique["Code"]) ?></td>
                        <td><?= htmlspecialchars($historique["date_debut"]) ?></td>
                        <td><?= htmlspecialchars($historique["date_fin"]) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
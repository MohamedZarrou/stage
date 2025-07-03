<?php 
include "../sql/db.php";
$PPR = $_GET["PPR"];

$db = Database::getInstance()->getConnection();
$sql = "SELECT * FROM diplome WHERE PPR = :PPR";
$stmt = $db->prepare($sql);
$stmt->bindparam(":PPR", $PPR);
$stmt->execute();
$diploms = $stmt->fetchall(PDO::FETCH_ASSOC);

if(isset($_POST["supprimer"])) {
    $id = $_POST["id"];
    $sql1 = "DELETE FROM diplome WHERE id = :ID";
    $stmt1 = $db->prepare($sql1);
    $stmt1->bindparam(":ID", $id);
    $stmt1->execute();
    header("Location: diploms.php?PPR=$PPR");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diplomas Management</title>
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
            position: relative;
            border: 1px solid var(--border-color);
        }
        
        h1 {
            color: var(--primary-blue);
            margin-bottom: 25px;
            text-align: center;
            font-weight: 600;
            font-size: 28px;
        }
        
        .add-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background-color: var(--primary-blue);
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 8px;
            margin-bottom: 25px;
            transition: all 0.3s ease;
            font-weight: 500;
            border: none;
            cursor: pointer;
        }
        
        .add-btn:hover {
            background-color: #4a8bc9;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(91, 155, 213, 0.3);
        }
        
        .return-btn {
            position: absolute;
            top: 30px;
            right: 30px;
            background-color: var(--secondary-blue);
            color: var(--text-dark);
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        
        .return-btn:hover {
            background-color: #8ab5e0;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(157, 195, 230, 0.3);
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
        
        .action-cell {
            display: flex;
            gap: 10px;
        }
        
        .action-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 14px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.2s ease;
            font-weight: 500;
        }
        
        .edit-btn {
            background-color: var(--secondary-blue);
            color: var(--text-dark);
            border: none;
            cursor: pointer;
        }
        
        .edit-btn:hover {
            background-color: #8ab5e0;
        }
        
        .delete-form {
            display: inline;
        }
        
        .delete-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background-color: #ff6b6b;
            color: white;
            border: none;
            padding: 8px 14px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        
        .delete-btn:hover {
            background-color: #ff5252;
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
            
            .return-btn {
                position: static;
                margin-bottom: 15px;
                display: inline-flex;
                width: auto;
            }
            
            th, td {
                padding: 10px 12px;
                font-size: 0.9rem;
            }
            
            .action-cell {
                flex-direction: column;
                gap: 8px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><i class="fas fa-graduation-cap"></i> Diplomas Management</h1>
        <a href="../gestion.php?PPR=<?= htmlspecialchars($PPR) ?>" class="return-btn">
            <i class="fas fa-arrow-left"></i> Return Back
        </a>
        
        <a href="diploms_add.php?PPR=<?= htmlspecialchars($PPR) ?>" class="add-btn">
            <i class="fas fa-plus-circle"></i> Add Diploma
        </a>
        
        <table>
            <thead>
                <tr>
                    <th><i class="fas fa-id-card"></i> ID</th>
                    <th><i class="fas fa-book"></i> Title</th>
                    <th><i class="fas fa-layer-group"></i> Level</th>
                    <th><i class="fas fa-university"></i> Institution</th>
                    <th><i class="fas fa-calendar-alt"></i> Year</th>
                    <th><i class="fas fa-tag"></i> Type</th>
                    <th><i class="fas fa-cog"></i> Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($diploms as $diplom): ?>
                    <tr>
                        <td><?= htmlspecialchars($diplom["id"]) ?></td>
                        <td><?= htmlspecialchars($diplom["Lib"]) ?></td>
                        <td><?= htmlspecialchars($diplom["niveau"]) ?></td>
                        <td><?= htmlspecialchars($diplom["etablissement"]) ?></td>
                        <td><?= htmlspecialchars($diplom["annee"]) ?></td>
                        <td><?= htmlspecialchars($diplom["type"]) ?></td>
                        <td class="action-cell">
                            <a href="diploms_edit.php?ID=<?= htmlspecialchars($diplom["id"]) ?>" class="action-btn edit-btn">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form method="POST" class="delete-form" onsubmit="return confirm('Are you sure you want to delete this diploma?');">
                                <input type="hidden" name="id" value="<?= htmlspecialchars($diplom["id"]) ?>">
                                <button type="submit" name="supprimer" class="delete-btn">
                                    <i class="fas fa-trash-alt"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
<?php 
include "sql/db.php";

$db = Database::getInstance()->getConnection();

// Fetch all diplômes
$sql = "SELECT * FROM diplome";
$stmt = $db->prepare($sql);
$stmt->execute();
$diploms = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle delete
if (isset($_POST["supprimer"])) {
    $id = $_POST["id"];
    $sql1 = "DELETE FROM diplome WHERE id = :ID";
    $stmt1 = $db->prepare($sql1);
    $stmt1->bindParam(":ID", $id);
    $stmt1->execute();
    
    // Refresh the page to reflect changes
    header("Location: " . $_SERVER['REQUEST_URI']);
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Liste des Diplômes</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <style>
        :root {
            --primary-blue: #5b9bd5;
            --secondary-blue: #9dc3e6;
            --light-bg: #f0f7ff;
            --table-bg: #ffffff;
            --text-dark: #2e3a4d;
            --text-light: #ffffff;
            --border-color: #c5e0ff;
            --error-red: #ff6b6b;
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
            min-height: 100vh;
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

        h2 {
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

        a.add-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background-color: var(--primary-blue);
            color: var(--text-light);
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 8px;
            margin-bottom: 25px;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            user-select: none;
        }

        a.add-btn:hover {
            background-color: #4a8bc9;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(91, 155, 213, 0.3);
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid var(--border-color);
            margin-top: 10px;
        }

        th, td {
            padding: 14px 16px;
            text-align: center;
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
            justify-content: center;
        }

        .action-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 14px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s ease;
            text-decoration: none;
            cursor: pointer;
            border: none;
        }

        .modifier-btn {
            background-color: var(--secondary-blue);
            color: var(--text-dark);
        }

        .modifier-btn:hover {
            background-color: #8ab5e0;
        }

        .supprimer-btn {
            background-color: var(--error-red);
            color: white;
            border-radius: 6px;
            padding: 8px 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            border: none;
        }

        .supprimer-btn:hover {
            background-color: #ff5252;
        }

        form.delete-form {
            display: inline;
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

            .action-cell {
                flex-direction: column;
                gap: 8px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2><i class="fas fa-graduation-cap"></i> Liste des Diplômes</h2>

        <a href="admin/diploms_add.php" class="add-btn">
            <i class="fas fa-plus-circle"></i> Ajouter un Diplôme
        </a>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>PPR</th>
                    <th>Libellé</th>
                    <th>Niveau</th>
                    <th>Établissement</th>
                    <th>Année</th>
                    <th>Type</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($diploms as $diplom): ?>
                    <tr>
                        <td><?= htmlspecialchars($diplom["id"]) ?></td>
                        <td><?= htmlspecialchars($diplom["PPR"]) ?></td>
                        <td><?= htmlspecialchars($diplom["Lib"]) ?></td>
                        <td><?= htmlspecialchars($diplom["niveau"]) ?></td>
                        <td><?= htmlspecialchars($diplom["etablissement"]) ?></td>
                        <td><?= htmlspecialchars($diplom["annee"]) ?></td>
                        <td><?= htmlspecialchars($diplom["type"]) ?></td>
                        <td class="action-cell">
                            <a href="admin/diploms_edit.php?ID=<?= htmlspecialchars($diplom["id"]) ?>" class="action-btn modifier-btn">
                                <i class="fas fa-edit"></i> Modifier
                            </a>
                            <form method="POST" class="delete-form" onsubmit="return confirm('Supprimer ce diplôme ?');">
                                <input type="hidden" name="id" value="<?= htmlspecialchars($diplom["id"]) ?>">
                                <button type="submit" name="supprimer" class="supprimer-btn">
                                    <i class="fas fa-trash-alt"></i> Supprimer
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
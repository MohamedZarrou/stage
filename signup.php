<?php
include "sql/db.php";

$error = '';
$success = '';

if (isset($_POST['signup'])) {
    $Cin = $_POST['Cin'];
    
    // Check if employee exists
    $db = Database::getInstance()->getConnection();
    $stmt = $db->prepare("SELECT * FROM utilisateurs WHERE Cin = :Cin");
    $stmt->bindParam(":Cin", $Cin);
    $stmt->execute();
    $employee = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$employee) {
        $error = "You are not registered as an employee. Please contact HR.";
    } else {
        // Employee exists, proceed with registration
        $email = $_POST['email'];
        $password = $_POST['password'];
        
        // Check if email exists for a different CIN
        $stmt = $db->prepare("SELECT * FROM utilisateurs WHERE email = :email AND Cin != :Cin");
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":Cin", $Cin);
        $stmt->execute();
        
        if ($stmt->fetch()) {
            $error = "Email already registered with a different employee. Please use another email.";
        } else {
            // Update the employee record with email and password
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $db->prepare("UPDATE utilisateurs SET email = :email, password = :password WHERE PPR = :ppr");
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":password", $hash);
            $stmt->bindParam(":ppr", $employee['PPR']);
            
            if ($stmt->execute()) {
                $success = "Registration successful! Redirecting to login...";
                header("refresh:2;url=login.php");
            } else {
                $error = "Registration failed. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up | Employee Portal</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --primary-color: #3498db;
            --primary-light: #5dade2;
            --primary-dark: #2980b9;
            --secondary-color: #f8f9fa;
            --accent-color: #e74c3c;
            --text-color: #2c3e50;
            --text-light: #7f8c8d;
            --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
            color: var(--text-color);
            line-height: 1.6;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(135deg, var(--primary-light), var(--primary-dark));
            background-attachment: fixed;
        }

        .signup-container {
            width: 100%;
            max-width: 450px;
            margin: 2rem;
        }

        .signup-card {
            background: white;
            border-radius: 10px;
            box-shadow: var(--shadow);
            overflow: hidden;
            transition: var(--transition);
        }

        .signup-header {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            padding: 1.5rem;
            text-align: center;
        }

        .signup-header h2 {
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .signup-header p {
            opacity: 0.9;
            font-size: 0.9rem;
        }

        .signup-body {
            padding: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--text-color);
        }

        .input-icon {
            position: absolute;
            left: 15px;
            top: 40px;
            color: var(--text-light);
        }

        .form-control {
            width: 100%;
            padding: 12px 15px 12px 40px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            transition: var(--transition);
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
            outline: none;
        }

        .btn {
            display: block;
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            text-align: center;
        }

        .btn-primary {
            background: var(--primary-color);
            color: white;
            box-shadow: var(--shadow);
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .signup-footer {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #eee;
        }

        .signup-footer a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
        }

        .signup-footer a:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        .alert {
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
        }

        .alert-error {
            background: #fdecea;
            color: var(--accent-color);
        }

        .alert-success {
            background: #e8f5e9;
            color: #2e7d32;
        }

        .alert i {
            margin-right: 8px;
        }

        .name-fields {
            display: flex;
            gap: 1rem;
        }

        .name-fields .form-group {
            flex: 1;
        }

        /* Responsive */
        @media (max-width: 480px) {
            .signup-container {
                margin: 1rem;
            }
            
            .signup-body {
                padding: 1.5rem;
            }
            
            .name-fields {
                flex-direction: column;
                gap: 0;
            }
        }
    </style>
</head>
<body>
    <div class="signup-container">
        <div class="signup-card">
            <div class="signup-header">
                <h2><i class="fas fa-user-plus"></i> Employee Registration</h2>
                <p>Create your account to access the portal</p>
            </div>
            
            <div class="signup-body">
                <?php if ($error): ?>
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i>
                        <span><?php echo $error; ?></span>
                    </div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        <span><?php echo $success; ?></span>
                    </div>
                <?php endif; ?>
                
                <form action="" method="POST">
                    <div class="form-group">
                        <label for="Cin">CIN</label>
                        <i class="fas fa-id-card input-icon"></i>
                        <input type="text" id="Cin" name="Cin" class="form-control" placeholder="Enter your CIN" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <i class="fas fa-envelope input-icon"></i>
                        <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Password</label>
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" id="password" name="password" class="form-control" placeholder="Create a password" required>
                    </div>
                    
                    <button type="submit" name="signup" class="btn btn-primary">
                        <i class="fas fa-user-plus"></i> Register Account
                    </button>
                </form>
                
                <div class="signup-footer">
                    Already registered? <a href="login.php">Sign in here</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
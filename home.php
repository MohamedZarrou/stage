<?php
session_start();
include "sql/db.php";
$db = Database::getInstance()->getConnection();

// Check if user is logged in
$loggedIn = isset($_SESSION['user']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Portal</title>
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
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            padding: 5rem 0;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiPjxkZWZzPjxwYXR0ZXJuIGlkPSJwYXR0ZXJuIiB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHBhdHRlcm5Vbml0cz0idXNlclNwYWNlT25Vc2UiIHBhdHRlcm5UcmFuc2Zvcm09InJvdGF0ZSg0NSkiPjxyZWN0IHdpZHRoPSIyMCIgaGVpZ2h0PSIyMCIgZmlsbD0icmdiYSgyNTUsMjU1LDI1NSwwLjA1KSIvPjwvcGF0dGVybj48L2RlZnM+PHJlY3QgZmlsbD0idXJsKCNwYXR0ZXJuKSIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIvPjwvc3ZnPg==');
        }

        .hero-content {
            position: relative;
            z-index: 1;
        }

        .hero h1 {
            font-size: 3rem;
            margin-bottom: 1rem;
            font-weight: 700;
        }

        .hero p {
            font-size: 1.2rem;
            max-width: 700px;
            margin: 0 auto 2rem;
            opacity: 0.9;
        }

        /* Auth Buttons */
        .auth-buttons {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-top: 2rem;
        }

        .btn {
            display: inline-block;
            padding: 0.8rem 1.8rem;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
            cursor: pointer;
            font-size: 1rem;
        }

        .btn-primary {
            background: white;
            color: var(--primary-color);
            box-shadow: var(--shadow);
        }

        .btn-primary:hover {
            background: var(--secondary-color);
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .btn-outline {
            background: transparent;
            border: 2px solid white;
            color: white;
        }

        .btn-outline:hover {
            background: white;
            color: var(--primary-color);
            transform: translateY(-3px);
        }

        /* Footer */
        footer {
            background: var(--text-color);
            color: white;
            padding: 2rem 0;
            text-align: center;
        }

        .footer-content {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
        }

        .footer-section {
            flex: 1;
            min-width: 250px;
            margin-bottom: 1rem;
        }

        .footer-section h3 {
            margin-bottom: 1rem;
            font-size: 1.2rem;
        }

        .footer-section p {
            opacity: 0.8;
        }

        .social-icons {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-top: 1rem;
        }

        .social-icons a {
            color: white;
            font-size: 1.2rem;
            transition: var(--transition);
        }

        .social-icons a:hover {
            color: var(--primary-light);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2.2rem;
            }
            
            .hero p {
                font-size: 1rem;
            }
            
            .auth-buttons {
                flex-direction: column;
                align-items: center;
            }
            
            .btn {
                width: 100%;
                max-width: 250px;
            }
        }
    </style>
</head>
<body>
    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content container">
            <h1>Welcome to the Employee Portal</h1>
            <p>Access comprehensive employee statistics, manage organizational units, and track professional development all in one place.</p>
            
            <?php if (!$loggedIn): ?>
                <div class="auth-buttons">
                    <a href="login.php" class="btn btn-primary">
                        <i class="fas fa-sign-in-alt"></i> Login
                    </a>
                    <a href="signup.php" class="btn btn-outline">
                        <i class="fas fa-user-plus"></i> Sign Up
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container footer-content">
            <div class="footer-section">
                <h3>About Us</h3>
                <p>Providing comprehensive employee management solutions since 2023.</p>
            </div>
            <div class="footer-section">
                <h3>Quick Links</h3>
                <p>
                    <a href="home.php" style="color: white; text-decoration: none;">Home</a> | 
                    <a href="login.php" style="color: white; text-decoration: none;">Login</a> | 
                    <a href="signup.php" style="color: white; text-decoration: none;">Register</a>
                </p>
            </div>
            <div class="footer-section">
                <h3>Connect With Us</h3>
                <div class="social-icons">
                    <a href="#"><i class="fab fa-facebook"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-linkedin"></i></a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
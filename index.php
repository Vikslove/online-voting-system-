<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>College Voting System - Home</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&family=Lobster&display=swap" rel="stylesheet">
    <style>
        /* Root Theme Variables */
        :root {
            --primary-bg: linear-gradient(to right, #ff7e5f, #feb47b);
            --secondary-bg: #ffffff;
            --accent-color: #ff6f61;
            --text-light: #ffffff;
            --text-dark: #333333;
            --button-bg: #ff6f61;
            --button-hover: #ff8a75;
            --header-gradient: linear-gradient(to right, #4facfe, #00f2fe);
        }

        /* Global Styles */
        body {
            font-family: 'Poppins', Arial, sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            background: var(--secondary-bg);
            color: var(--text-dark);
            overflow-x: hidden;
            transition: all 0.3s ease;
        }

        /* Navbar Styles */
        .navbar {
            background: var(--header-gradient);
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: var(--text-light);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 16px;
            margin: 20px;
            animation: fadeIn 1s ease-out;
        }

        .navbar .logo h1 {
            font-size: 2rem;
            margin: 0;
            font-family: 'Lobster', cursive;
            color: var(--text-light);
            text-shadow: 0 3px 8px rgba(0, 0, 0, 0.2);
        }

        .navbar .links {
            display: flex;
            gap: 20px;
        }

        .navbar .links a {
            color: var(--text-light);
            text-decoration: none;
            font-size: 16px;
            font-weight: 600;
            padding: 10px 20px;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.1);
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .navbar .links a:hover {
            background-color: var(--accent-color);
            color: var(--text-light);
            transform: scale(1.1);
        }

        .cta-button {
            background-color: var(--button-bg);
            color: var(--text-light);
            padding: 12px 25px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 25px;
            text-decoration: none;
            transition: background-color 0.3s ease, transform 0.3s ease;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
        }

        .cta-button:hover {
            background-color: var(--button-hover);
            transform: scale(1.1);
        }

        /* Hero Section */
        .hero {
            text-align: left;
            padding: 100px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: var(--primary-bg);
            border-radius: 16px;
            margin: 20px;
            opacity: 0;
            animation: slideIn 1s forwards;
        }

        .hero-text {
            max-width: 50%;
        }

        .hero h1 {
            font-size: 4rem;
            margin-bottom: 20px;
            font-family: 'Lobster', cursive;
            color: var(--text-light);
            animation: bounceIn 1s ease-out;
        }

        .hero p {
            font-size: 1.5rem;
            color: var(--text-light);
            margin-bottom: 40px;
            animation: fadeInText 2s ease-out;
        }

        .hero img {
            max-width: 40%;
            border-radius: 16px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
            opacity: 0;
            animation: fadeInImage 2s ease-out forwards;
        }

        /* Info Section */
        .info-section {
            background: linear-gradient(to right, #4facfe, #00f2fe);
            padding: 80px 20px;
            text-align: center;
            color: var(--text-light);
            border-radius: 16px;
            margin: 20px;
            animation: fadeIn 1.5s ease-out;
        }

        .info-section h2 {
            font-size: 3rem;
            font-family: 'Lobster', cursive;
            margin-bottom: 30px;
        }

        .info-section ul {
            list-style: none;
            padding: 0;
            font-size: 1.2rem;
        }

        .info-section ul li {
            margin: 10px 0;
            position: relative;
            padding-left: 30px;
        }

        .info-section ul li:before {
            content: "\2714";
            position: absolute;
            left: 0;
            color: var(--accent-color);
            font-size: 1.5rem;
        }

        /* Footer */
        .footer {
            background-color: rgba(0, 0, 0, 0.9);
            color: white;
            padding: 20px;
            text-align: center;
        }

        .footer p a {
            color: var(--accent-color);
            text-decoration: none;
        }

        .footer p a:hover {
            text-decoration: underline;
        }

        /* Animations */
        @keyframes fadeIn {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }

        @keyframes fadeInText {
            0% { opacity: 0; transform: translateY(20px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeInImage {
            0% { opacity: 0; transform: scale(0.8); }
            100% { opacity: 1; transform: scale(1); }
        }

        @keyframes slideIn {
            0% { opacity: 0; transform: translateX(-100%); }
            100% { opacity: 1; transform: translateX(0); }
        }

        @keyframes bounceIn {
            0% { opacity: 0; transform: translateY(20px); }
            60% { opacity: 1; transform: translateY(-10px); }
            100% { transform: translateY(0); }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2.5rem;
            }

            .voting-section {
                width: 90%;
            }

            .voting-category label {
                padding: 12px;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <div class="navbar">
        <div class="logo">
            <h1>College Voting System</h1>
        </div>
        <div class="links">
            <a href="index.php">Home</a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="results.php">Results</a>
                <a href="feedback.php">Feedback</a>
                <a href="logout.php">Logout</a>
                
            <?php else: ?>
                <a href="register.php">Register</a>
                <a href="login.php">Login</a>
                <a href="admin_login.php">Admin Login</a>
                <a href="about.php">About</a>
            <?php endif; ?>
        </div>
        <a href="register.php" class="cta-button">Get Started</a>
    </div>

    <!-- Hero Section -->
    <div class="hero">
        <div class="hero-text">
            <?php if (isset($_SESSION['user_id'])): ?>
                <h1>Hii, <?php echo htmlspecialchars
                ($_SESSION['user_name']); ?>! Welcome to the Online Voting System</h1>
            <?php else: ?>
                <h1>Welcome to the Online Voting System</h1>
            <?php endif; ?>
            <p>Empowering students to select their leaders with transparency and efficiency.</p>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="vote.php" class="cta-button">Start Voting</a>
            <?php else: ?>
                <a href="login.php" class="cta-button">Login to Vote</a>
            <?php endif; ?>
        </div>
        <img src="3d-illustration.png" alt="3D Illustration">
    </div>

    <!-- Info Section -->
    <div class="info-section">
        <h2>Why Choose Online Voting?</h2>
        <ul>
            <li>Secure and Transparent Voting Process</li>
            <li>Real-Time Results Tracking</li>
            <li>User-Friendly Interface</li>
            <li>Accessible Anytime, Anywhere</li>
            <li>Environmentally Friendly - Paperless Voting</li>
        </ul>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>&copy; <?php echo date("Y"); ?> Voting_System_Developers. All rights reserved. | <a href="#">Privacy Policy</a> | <a href="#">Terms of Service</a></p>
    </div>
</body>
</html>

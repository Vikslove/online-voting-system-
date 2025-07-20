<?php
session_start();
require 'db2.php'; // Database connection (if needed)
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About - College Voting System</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet"> <!-- Google Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"> <!-- Font Awesome -->
    <style>
        /* General Styles */
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f5f8fa;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Navbar */
        .navbar {
            background: linear-gradient(135deg, #6a82fb, #fc5c7d);
            color: white;
            padding: 20px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
        }

        .navbar h2 {
            margin: 0;
            font-size: 30px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
            flex-grow: 1;
            text-align: center;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
            font-size: 18px;
            font-weight: bold;
            transition: 0.3s ease-in-out;
        }

        .navbar a:hover {
            color: #c1e8ff;
        }

        /* Hero Section */
        .hero {
            text-align: center;
            padding: 150px 20px;
            background: linear-gradient(to right, #6a82fb, #fc5c7d);
            color: white;
            margin-top: 90px; /* Adjust for fixed navbar */
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            animation: fadeIn 1.5s ease-in-out;
        }

        .hero h1 {
            font-size: 3.5rem;
            margin-bottom: 10px;
            animation: slideIn 1s ease-in-out;
        }

        .hero p {
            font-size: 1.2rem;
            margin-bottom: 30px;
            animation: slideIn 1.2s ease-in-out;
        }

        /* About Section */
        .about-section {
            margin: 40px auto;
            padding: 30px;
            max-width: 800px;
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
            animation: fadeIn 1.5s ease-in-out;
        }

        .about-section h2 {
            color: #6a82fb;
            text-align: center;
            margin-bottom: 20px;
            font-size: 2rem;
        }

        .about-section p {
            font-size: 1.1rem;
            line-height: 1.8;
            color: #333;
            margin-bottom: 20px;
        }

        .about-section ul {
            list-style-type: none;
            padding: 0;
        }

        .about-section ul li {
            background: #eef4fb;
            margin: 10px 0;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
            transition: 0.3s ease;
            cursor: pointer;
        }

        .about-section ul li:hover {
            background-color: #d9ecff;
            transform: translateY(-5px);
        }

        /* Team Section */
        .team-section {
            margin: 40px auto;
            padding: 30px;
            max-width: 800px;
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
            animation: fadeIn 1.5s ease-in-out;
        }

        .team-section h2 {
            color: #6a82fb;
            text-align: center;
            margin-bottom: 20px;
            font-size: 2rem;
        }

        .team-member {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .team-member img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin-right: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .team-member h3 {
            margin: 0;
            font-size: 1.5rem;
            color: #333;
        }

        .team-member p {
            margin: 5px 0;
            color: #777;
        }

        /* Footer */
        .footer {
            text-align: center;
            padding: 20px;
            background-color: #333;
            color: white;
            margin-top: 40px;
            animation: fadeIn 1.5s ease-in-out;
        }

        .footer p {
            font-size: 1rem;
        }

        .footer p a {
            color: #fc5c7d;
            text-decoration: none;
            font-weight: bold;
            transition: 0.3s ease;
        }

        .footer p a:hover {
            text-decoration: underline;
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideIn {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2.5rem;
            }

            .about-section, .team-section {
                width: 90%;
            }
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <div class="navbar">
        <h2>College Voting System</h2>
        <a href="index.php">Home</a>
        <a href="about.php">About</a>
        <a href="logout.php">Logout</a>
    </div>

    <!-- Hero Section -->
    <div class="hero">
        <h1>About Us</h1>
        <p>Learn more about our online voting system and the team behind it.</p>
    </div>

    <!-- About Section -->
    <div class="about-section">
        <h2>About the Online Voting System</h2>
        <p>
            Welcome to the College Voting System, a secure and efficient platform designed to streamline the voting process for college elections, events, and more. Our system ensures transparency, fairness, and ease of use for both voters and administrators.
        </p>
        <p>
            Key features of our system include:
        </p>
        <ul>
            <li>Secure user authentication and role-based access control.</li>
            <li>Real-time voting with instant results.</li>
            <li>Ability to revote if the admin makes changes to the voting process.</li>
            <li>User-friendly interface for seamless navigation.</li>
            <li>Responsive design for access on any device.</li>
        </ul>
    </div>

    <!-- Team Section -->
    <div class="team-section">
        <h2>Our Developer Team</h2>
        <div class="team-member">
            <img src="viks.jpg" alt="Team Member 1">
            <div>
                <h3>Vivek Pandit</h3>
                <p>Lead Developer and UI Designer</p>
            </div>
        </div>
        <div class="team-member">
            <img src="vishal.jpg" alt="Team Member 2">
            <div>
                <h3>Vishal Korde</h3>
                <p>UI/UX Designer and  Developer</p>
            </div>
        </div>
        <div class="team-member">
            <img src="ritesh.jpg" alt="Team Member 3">
            <div>
                <h3>Ritesh Mokle </h3>
                <p>Project Manager and Representative</p>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>&copy; <?php echo date("Y"); ?> Voting_System_Developers.All rights reserved.</p>
    </div>

</body>
</html>
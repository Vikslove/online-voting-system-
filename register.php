<?php
session_start();
require 'db.php'; // Connect to the database

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars(trim($_POST['name']));
    $prn = htmlspecialchars(trim($_POST['prn']));
    $password = htmlspecialchars($_POST['password']);
    $confirm_password = htmlspecialchars($_POST['confirm_password']);

    // Server-side validation
    if (!preg_match("/^[A-Za-z\s]+$/", $name)) {
        echo "<script>alert(' Please enter a valid full name (letters and spaces only).'); window.location.href='register.php';</script>";
        exit();
    } elseif (!preg_match("/^[0-9]{10}$/", $prn)) {
        echo "<script>alert(' PRN must be exactly 10 digits.'); window.location.href='register.php';</script>";
        exit();
    } elseif (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/", $password)) {
        echo "<script>alert(' Password must be at least 8 characters long, including 1 uppercase, 1 lowercase, 1 number, and 1 special character.'); window.location.href='register.php';</script>";
        exit();
    } elseif ($password !== $confirm_password) {
        echo "<script>alert(' Passwords do not match.'); window.location.href='register.php';</script>";
        exit();
    } else {
        // Check if PRN already exists
        $check_sql = "SELECT * FROM users WHERE prn = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("s", $prn);
        $check_stmt->execute();
        $result = $check_stmt->get_result();

        if ($result->num_rows > 0) {
            echo "<script>alert(' PRN already exists!'); window.location.href='register.php';</script>";
            exit();
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert user into the database
            $sql = "INSERT INTO users (name, prn, password) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $name, $prn, $hashed_password);

            if ($stmt->execute()) {
                echo "<script>alert(' Registration successful! Redirecting to login...'); window.location.href='login.php';</script>";
                exit();
            } else {
                echo "<script>alert(' Something went wrong! Try again.'); window.location.href='register.php';</script>";
                exit();
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
    <title>Register - College Voting System</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <!-- FontAwesome for the eye icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* General Styling */
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            background: url('regeback.png') no-repeat center center/cover;
            color: #333;
            justify-content: center;
            align-items: center;
            transition: background 0.3s ease-in-out; /* Smooth background transition */
        }

        .container {
            width: 90%;
            max-width: 1000px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            animation: fadeIn 1.5s ease-out; /* Fade-in effect */
        }

        .left-section {
            flex: 1;
            padding: 20px;
            color: black;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: flex-start;
            opacity: 0;
            animation: slideIn 1.5s ease-out forwards; /* Smooth slide-in animation */
        }

        .left-section h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
            animation: slideUp 1s ease-out; /* Animation for the heading */
        }

        .left-section p {
            font-size: 1rem;
            line-height: 1.5;
            animation: fadeInText 1s ease-in; /* Text fade-in effect */
        }

        .form-container {
            flex: 1;
            max-width: 400px;
            padding: 30px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 10px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            transform: scale(0.95); /* Initial scale for animation */
            animation: zoomIn 0.5s ease-in-out forwards; /* Zoom-in effect */
            position: relative;
        }

        .form-container h2 {
            font-size: 1.8rem;
            color: #1e3c72;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .form-container label {
            display: block;
            font-size: 0.9rem;
            margin-bottom: 5px;
            color: #333;
        }

        .form-container input {
            width: 100%;
            padding: 12px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
            transition: all 0.3s ease;
            box-sizing: border-box; /* Ensures proper fitting */
        }

        .form-container input:focus {
            border-color: #2a5298;
            box-shadow: 0 0 5px rgba(42, 82, 152, 0.5);
        }

        .form-container button {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            color: white;
            font-size: 1.2rem;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: transform 0.2s ease;
        }

        .form-container button:hover {
            transform: scale(1.05); /* Slight scale effect on hover */
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2); /* Hover shadow effect */
        }

        .login-link {
            text-align: center;
            margin-top: 15px;
            font-size: 0.9rem;
        }

        .login-link a {
            color: #1e3c72;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        .login-link a:hover {
            color: #2a5298; /* Color change on hover */
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @keyframes slideIn {
            from {
                transform: translateX(-100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideUp {
            from {
                transform: translateY(30px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes fadeInText {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @keyframes zoomIn {
            from {
                transform: scale(0.95);
                opacity: 0;
            }
            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        /* Style for the toggle button */
        .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 1.5rem;
            color: #2a5298;
            transition: color 0.3s ease;
        }

        .toggle-password:hover {
            color: #1e3c72; /* Change color on hover */
        }

        /* Additional styles for a smooth transition */
        input[type="password"] {
            padding-right: 40px; /* Space for the icon */
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="left-section">
            <h1>Welcome to Online Voting</h1>
            <p>Experience a transparent and secure voting system for college elections.</p>
        </div>
        <div class="form-container">
            <h2>Create an Account</h2>
            <form method="POST" action="" onsubmit="return validateForm()">
                <label for="name">Full Name:</label>
                <input type="text" id="name" name="name" required>

                <label for="prn">PRN Number:</label>
                <input type="text" id="prn" name="prn" required>

                <label for="password">Password:</label>
                <div style="position: relative;">
                    <input type="password" id="password" name="password" required>
                    <span class="toggle-password" onclick="togglePassword('password')"><i class="fas fa-eye"></i></span>
                </div>

                <label for="confirm_password">Confirm Password:</label>
                <div style="position: relative;">
                    <input type="password" id="confirm_password" name="confirm_password" required>
                    <span class="toggle-password" onclick="togglePassword('confirm_password')"><i class="fas fa-eye"></i></span>
                </div>

                <button type="submit">Register</button>
            </form>
            <div class="login-link">
                Already have an account? <a href="login.php">Login here</a>
            </div>
        </div>
    </div>

    <script>
    function validateForm() {
        const name = document.getElementById("name").value.trim();
        const prn = document.getElementById("prn").value.trim();
        const password = document.getElementById("password").value;
        const confirmPassword = document.getElementById("confirm_password").value;

        const nameRegex = /^[A-Za-z\s]+$/;
        const prnRegex = /^[0-9]{10}$/;
        const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

        if (!nameRegex.test(name)) {
            alert(" Please enter a valid full name (letters and spaces only).");
            return false;
        }
        if (!prnRegex.test(prn)) {
            alert(" PRN must be exactly 10 digits.");
            return false;
        }
        if (!passwordRegex.test(password)) {
            alert(" Password must be at least 8 characters long, including 1 uppercase, 1 lowercase, 1 number, and 1 special character.");
            return false;
        }
        if (password !== confirmPassword) {
            alert(" Passwords do not match. Please enter the same password.");
            return false;
        }

        return true;
    }

    function togglePassword(id) {
        const passwordField = document.getElementById(id);
        const type = passwordField.type === 'password' ? 'text' : 'password';
        passwordField.type = type;

        // Toggle the icon (eye open/close)
        const icon = document.querySelector(`#${id} + .toggle-password i`);
        if (icon.classList.contains('fa-eye')) {
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }
    </script>
</body>
</html>

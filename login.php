<?php
session_start();
require 'db.php'; // Include the database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prn = trim($_POST['prn']);
    $password = trim($_POST['password']);

    // Server-side validation
    if (empty($prn) || empty($password)) {
        echo "<script>alert('❌ All fields are required!');</script>";
    } elseif (!preg_match("/^[0-9]{10}$/", $prn)) {
        echo "<script>alert('❌ PRN must be exactly 10 digits.');</script>";
    } else {
        // Check if PRN exists in the database
        $sql = "SELECT * FROM users WHERE prn = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $prn);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            // Verify the password
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];

                // Save login activity
                $user_id = $user['id'];
                $username = $user['name'];
                $login_time = date("Y-m-d H:i:s");
                $ip_address = $_SERVER['REMOTE_ADDR'];

                $log_sql = "INSERT INTO login_activity (user_id, username, login_time, ip_address) VALUES (?, ?, ?, ?)";
                $log_stmt = $conn->prepare($log_sql);
                $log_stmt->bind_param("isss", $user_id, $username, $login_time, $ip_address);
                $log_stmt->execute();

                // Redirect after successful login
                echo "<script>alert('✅ Login successful!'); window.location.href='index.php';</script>";
            } else {
                echo "<script>alert('❌ Invalid password!');</script>";
            }
        } else {
            echo "<script>alert('❌ PRN not found!');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - College Voting System</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* General Styles */
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(135deg, #74ebd5, #acb6e5);
            overflow: hidden;
        }

        /* Animated Background Bubbles */
        body::before {
            content: '';
            position: absolute;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
            z-index: -1;
        }

        body::after {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
            animation: float 8s ease-in-out infinite;
            bottom: -50px;
            right: -50px;
            z-index: -1;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-30px);
            }
        }

        /* Container */
        .container {
            display: flex;
            width: 90%;
            max-width: 1000px;
            height: auto;
            min-height: 500px;
            background: #fff;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.1);
        }

        /* Left Panel */
        .left-panel {
            flex: 1;
            background: linear-gradient(135deg, #0072ff, #00c6ff);
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 40px;
        }

        .left-panel h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
            animation: fadeIn 1s ease;
        }

        .left-panel p {
            font-size: 1.2rem;
            animation: fadeIn 1.5s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Right Panel */
        .right-panel {
            flex: 1;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .right-panel h2 {
            font-size: 2rem;
            color: #333;
            margin-bottom: 20px;
            animation: slideIn 1s ease;
        }

        .right-panel form {
            display: flex;
            flex-direction: column;
        }

        .right-panel input {
            padding: 15px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: box-shadow 0.3s ease, transform 0.3s ease;
        }

        .right-panel input:focus {
            box-shadow: 0 0 8px rgba(0, 114, 255, 0.5);
            transform: scale(1.02);
            border-color: #0072ff;
            outline: none;
        }

        .right-panel button {
            padding: 15px;
            border: none;
            border-radius: 8px;
            background: linear-gradient(135deg, #0072ff, #00c6ff);
            color: white;
            font-size: 1.2rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            animation: bounce 2s infinite;
        }

        .right-panel button:hover {
            transform: translateY(-3px);
            box-shadow: 0px 5px 15px rgba(0, 114, 255, 0.5);
        }

        @keyframes bounce {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-5px);
            }
        }

        .right-panel .register {
            text-align: center;
            margin-top: 20px;
        }

        .right-panel .register a {
            color: #0072ff;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        .right-panel .register a:hover {
            color: #00c6ff;
        }

        /* Password Container with Eye Button */
        .password-container {
            position: relative;
        }

        .password-container input {
            padding-right: 15px; /* Space for the eye button */
            width: 100%;
        }

        .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #666;
            transition: color 0.3s ease;
        }

        .toggle-password:hover {
            color: #0072ff;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                flex-direction: column;
                width: 95%;
                height: auto;
            }

            .left-panel, .right-panel {
                padding: 20px;
            }

            .left-panel {
                height: 200px;
            }

            .right-panel {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Left Panel -->
        <div class="left-panel">
            <h1>Welcome Back!</h1>
            <p>Log in to cast your vote and make your voice heard.</p>
        </div>

        <!-- Right Panel -->
        <div class="right-panel">
            <h2>Login</h2>
            <form method="POST" action="">
                <input type="text" name="prn" placeholder="Enter your PRN number" required>
                
                <!-- Password field with eye button -->
                <div class="password-container">
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                    <i class="toggle-password fas fa-eye" onclick="togglePasswordVisibility()"></i>
                </div>

                <button type="submit">Login</button>
            </form>
            <div class="register">
                Don't have an account? <a href="register.php">Sign up</a>
            </div>
            <div class="register">
    <a href="forgot_password.php">Forgot Password?</a>
</div>

        </div>
    </div>

    <script>
        // Function to toggle password visibility
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.querySelector('.toggle-password');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }

        // Your existing validation function
        function validateLogin() {
            const prn = document.querySelector("input[name='prn']").value.trim();
            const password = document.getElementById("password").value.trim();

            const prnRegex = /^[0-9]{10}$/;

            if (prn === "" || password === "") {
                alert("❌ All fields are required.");
                return false;
            }
            if (!prnRegex.test(prn)) {
                alert("❌ PRN must be exactly 10 digits.");
                return false;
            }
            if (password.length < 8) {
                alert("❌ Password must be at least 8 characters long.");
                return false;
            }

            return true;
        }
    </script>
</body>
</html>
<?php
session_start();
require 'db.php';

if (!isset($_SESSION['reset_prn'])) {
    echo "<script>alert('❌ Unauthorized access!'); window.location.href='login.php';</script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    if (empty($new_password) || empty($confirm_password)) {
        echo "<script>alert('❌ All fields are required!');</script>";
    } elseif ($new_password !== $confirm_password) {
        echo "<script>alert('❌ Passwords do not match!');</script>";
    } elseif (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $new_password)) {
        echo "<script>alert('❌ Password must be at least 8 characters long, with one uppercase, one lowercase, one number, and one special character!');</script>";
    } else {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $prn = $_SESSION['reset_prn'];

        $sql = "UPDATE users SET password = ? WHERE prn = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $hashed_password, $prn);
        $stmt->execute();

        unset($_SESSION['reset_prn']);

        echo "<script>alert('✅ Password reset successful!'); window.location.href='login.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(to right, #A1FFCE, #FAFFD1);
        }
        .container {
            background: white;
            padding: 30px;
            width: 380px;
            text-align: center;
            border-radius: 12px;
            box-shadow: 0px 5px 20px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #333;
            margin-bottom: 20px;
            font-size: 24px;
        }
        .input-box {
            position: relative;
            width: 100%;
            margin-bottom: 15px;
        }
        input {
            width: 100%;
            padding: 12px 40px 12px 10px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: 0.3s ease;
        }
        input:focus {
            border-color: #00C6FF;
            outline: none;
        }
        .toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #777;
            font-size: 16px;
        }
        .error-msg {
            color: red;
            font-size: 14px;
            display: none;
            text-align: left;
            margin-top: 5px;
        }
        button {
            width: 100%;
            padding: 12px;
            font-size: 18px;
            border: none;
            background: #00C6FF;
            color: white;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s ease;
            font-weight: bold;
            margin-top: 10px;
        }
        button:hover {
            background: #0072ff;
        }
        .back-to-login {
            margin-top: 10px;
            color: #333;
            font-size: 14px;
        }
        .back-to-login a {
            color: #00C6FF;
            text-decoration: none;
            font-weight: bold;
        }
        .back-to-login a:hover {
            color: #0072ff;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Reset Password</h2>
        <form method="POST" onsubmit="return validateForm()">
            <div class="input-box">
                <input type="password" name="new_password" id="new_password" placeholder="Enter new password" required>
                <i class="fas fa-eye toggle-password" onclick="togglePassword('new_password')"></i>
                <p class="error-msg" id="password-error">Password must be at least 8 characters, including uppercase, lowercase, number, and special character.</p>
            </div>
            <div class="input-box">
                <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm new password" required>
                <i class="fas fa-eye toggle-password" onclick="togglePassword('confirm_password')"></i>
                <p class="error-msg" id="match-error">Passwords do not match!</p>
            </div>
            <button type="submit">Reset Password</button>
        </form>
        <p class="back-to-login">Back to <a href="login.php">Login</a></p>
    </div>

    <script>
        function togglePassword(fieldId) {
            var field = document.getElementById(fieldId);
            var icon = field.nextElementSibling;
            if (field.type === "password") {
                field.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                field.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        }

        function validateForm() {
            var password = document.getElementById("new_password").value;
            var confirmPassword = document.getElementById("confirm_password").value;
            var passwordError = document.getElementById("password-error");
            var matchError = document.getElementById("match-error");
            var passwordPattern = /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

            passwordError.style.display = "none";
            matchError.style.display = "none";

            if (!passwordPattern.test(password)) {
                passwordError.style.display = "block";
                return false;
            }

            if (password !== confirmPassword) {
                matchError.style.display = "block";
                return false;
            }

            return true;
        }
    </script>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Toggle Fix</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&family=Lobster&display=swap" rel="stylesheet">
    <style>
        /* Global Styles */
        :root {
            --primary-bg: #141e30;
            --secondary-bg: #243b55;
            --primary-text: white;
            --secondary-text: #ffdd57;
            --button-bg: #ffdd57;
            --button-text: #141e30;
            --input-bg: rgba(255, 255, 255, 0.2);
            --input-focus-bg: rgba(255, 255, 255, 0.3);
            --error-text: red;
        }

        [data-theme="light"] {
            --primary-bg: #f4f4f4;
            --secondary-bg: #ffffff;
            --primary-text: #141e30;
            --secondary-text: #4caf50;
            --button-bg: #4caf50;
            --button-text: white;
            --input-bg: #e0e0e0;
            --input-focus-bg: #ffffff;
            --error-text: darkred;
        }

        body {
            font-family: 'Poppins', Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to right, var(--primary-bg), var(--secondary-bg));
            color: var(--primary-text);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            transition: all 0.3s ease;
        }

        .login-container {
            background: rgba(0, 0, 0, 0.8);
            padding: 40px 30px;
            border-radius: 12px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.5);
            width: 350px;
            text-align: center;
            animation: fadeIn 1s ease-in-out;
            background: var(--secondary-bg);
            color: var(--primary-text);
        }

        .login-container h1 {
            margin-bottom: 25px;
            font-size: 2rem;
            font-family: 'Lobster', cursive;
            color: var(--secondary-text);
        }

        .login-container input {
            width: 100%;
            padding: 12px 15px;
            margin-bottom: 20px;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            background: var(--input-bg);
            color: var(--primary-text);
            outline: none;
            transition: background 0.3s ease, transform 0.3s ease;
        }

        .login-container input:focus {
            background: var(--input-focus-bg);
            transform: scale(1.03);
        }

        .login-container button {
            background: var(--button-bg);
            color: var(--button-text);
            padding: 12px;
            font-size: 1rem;
            font-weight: bold;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s ease, color 0.3s ease, transform 0.2s ease;
            width: 100%;
        }

        .login-container button:hover {
            background: var(--secondary-text);
            color: var(--primary-text);
            transform: translateY(-3px);
        }

        .error {
            color: var(--error-text);
            font-size: 0.9rem;
            margin-bottom: 15px;
        }

        /* Toggle Switch */
        .theme-toggle {
            position: absolute;
            top: 20px;
            right: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .toggle-button {
            position: relative;
            width: 50px;
            height: 25px;
            background: var(--secondary-bg);
            border-radius: 25px;
            cursor: pointer;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: background 0.3s ease;
        }

        .toggle-button::before {
            content: '';
            position: absolute;
            top: 3px;
            left: 3px;
            width: 20px;
            height: 20px;
            background: var(--button-bg);
            border-radius: 50%;
            transition: transform 0.3s ease;
        }

        .toggle-button.active::before {
            transform: translateX(25px);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <!-- Toggle Button -->
    <div class="theme-toggle">
        <span>Light Mode</span>
        <div class="toggle-button" id="theme-toggle"></div>
    </div>

    <!-- Login Container -->
    <div class="login-container">
        <h1>Admin Login</h1>
        <?php
        if (isset($_SESSION['error'])) {
            echo '<p class="error">' . htmlspecialchars($_SESSION['error']) . '</p>';
            unset($_SESSION['error']);
        }
        ?>
        <form action="admin_login_handler.php" method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
    </div>

    <!-- JavaScript for Toggle Button -->
    <script>
        const toggleButton = document.getElementById('theme-toggle');
        const body = document.body;

        // Check and set the default theme
        if (!localStorage.getItem('theme')) {
            localStorage.setItem('theme', 'dark');
        }
        body.setAttribute('data-theme', localStorage.getItem('theme'));

        // Toggle theme on button click
        toggleButton.addEventListener('click', () => {
            const currentTheme = body.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            body.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            toggleButton.classList.toggle('active', newTheme === 'light');
        });

        // Set initial toggle state
        if (localStorage.getItem('theme') === 'light') {
            toggleButton.classList.add('active');
        }
    </script>
</body>
</html>

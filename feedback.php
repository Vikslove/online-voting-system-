<?php
session_start();
require 'db2.php'; // Database connection

// Redirect if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Handle feedback submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $feedback = isset($_POST['feedback']) ? trim($_POST['feedback']) : '';
    $rating = isset($_POST['rating']) ? intval($_POST['rating']) : 0;

    if (empty($feedback) || $rating < 1 || $rating > 5) {
        echo "<script>alert('Please provide valid feedback and a rating between 1 and 5.');</script>";
    } else {
        // Insert feedback into the database
        $insert_feedback_query = "INSERT INTO feedback (user_id, feedback, rating) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($insert_feedback_query);
        $stmt->bind_param("isi", $user_id, $feedback, $rating);

        if ($stmt->execute()) {
            echo "<script>alert('Thank you for your feedback!'); window.location.href='results.php';</script>";
        } else {
            echo "<script>alert('Failed to submit feedback. Please try again.');</script>";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback - College Voting System</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f5f8fa;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        .navbar {
            background: linear-gradient(135deg, #6a82fb, #fc5c7d);
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .navbar h2 {
            margin: 0;
            font-size: 30px;
            letter-spacing: 1px;
        }
        .navbar a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
            font-size: 16px;
            transition: color 0.3s;
        }
        .navbar a:hover {
            color: #c1e8ff;
        }
        .hero {
            text-align: center;
            padding: 150px 20px;
            background: linear-gradient(to right, #6a82fb, #fc5c7d);
            color: white;
            margin-top: 70px;
            border-radius: 12px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        }
        .hero h1 {
            font-size: 3rem;
            margin-bottom: 20px;
        }
        .hero p {
            font-size: 1.2rem;
        }
        .feedback-section {
            margin: 40px auto;
            padding: 30px;
            max-width: 800px;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .feedback-section h2 {
            color: #6a82fb;
            text-align: center;
            font-size: 2rem;
            margin-bottom: 20px;
        }
        .feedback-section form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        .feedback-section label {
            font-size: 1.1rem;
            font-weight: bold;
        }
        .feedback-section textarea {
            width: 100%;
            height: 150px;
            padding: 10px;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 8px;
            resize: vertical;
        }
        .stars {
            display: flex;
            gap: 8px;
            justify-content: center;
            font-size: 2rem;
            cursor: pointer;
        }
        .stars i {
            color: #d3d3d3;
            transition: color 0.3s;
        }
        .stars i.selected,
        .stars i:hover,
        .stars i:hover ~ i {
            color: #f1c40f;
        }
        .feedback-section button {
            padding: 12px;
            background-color: #6a82fb;
            color: white;
            font-size: 1.2rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
        }
        .feedback-section button:hover {
            background-color: #fc5c7d;
            transform: scale(1.05);
        }
        .footer {
            text-align: center;
            padding: 20px;
            background-color: #333;
            color: white;
            margin-top: 40px;
            font-size: 14px;
        }
    </style>
</head>
<body>

    <div class="navbar">
        <h2>College Voting System</h2>
        <div>
            <a href="index.php">Home</a>
            <a href="feedback.php">Feedback</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <div class="hero">
        <h1>Feedback</h1>
        <p>We value your feedback! Let us know how we can improve our voting system.</p>
    </div>

    <div class="feedback-section">
        <h2>Share Your Feedback</h2>
        <form method="POST">
            <label for="feedback">Your Feedback:</label>
            <textarea id="feedback" name="feedback" placeholder="Enter your feedback here..." required></textarea>

            <div class="rating">
                <label>Your Rating:</label>
                <div class="stars">
                    <i class="fas fa-star" data-value="1"></i>
                    <i class="fas fa-star" data-value="2"></i>
                    <i class="fas fa-star" data-value="3"></i>
                    <i class="fas fa-star" data-value="4"></i>
                    <i class="fas fa-star" data-value="5"></i>
                </div>
                <input type="hidden" id="rating" name="rating" required>
            </div>

            <button type="submit">Submit Feedback</button>
        </form>
    </div>

    <div class="footer">
        <p>&copy; <?php echo date("Y"); ?> College Voting System. All rights reserved.</p>
    </div>

    <script>
        const stars = document.querySelectorAll('.stars i');
        const ratingInput = document.getElementById('rating');

        stars.forEach(star => {
            star.addEventListener('click', () => {
                ratingInput.value = star.getAttribute('data-value');
                stars.forEach(s => s.classList.remove('selected'));
                star.classList.add('selected');
                let prev = star.previousElementSibling;
                while (prev) {
                    prev.classList.add('selected');
                    prev = prev.previousElementSibling;
                }
            });
        });
    </script>
</body>
</html>

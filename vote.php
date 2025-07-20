<?php
session_start();
require 'db2.php'; // Database connection

// Redirect if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch the latest update timestamp from the candidates table
$last_updated_query = "SELECT MAX(last_updated) AS last_updated FROM candidates";
$last_updated_result = $conn->query($last_updated_query);
$last_updated = $last_updated_result->fetch_assoc()['last_updated'];

// Fetch the user's last vote timestamp
$last_vote_query = "SELECT last_voted_at FROM votes WHERE user_id = ?";
$stmt = $conn->prepare($last_vote_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($last_voted_at);
$stmt->fetch();
$stmt->close();

// Handle vote submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $candidate_id = isset($_POST['candidate']) ? intval($_POST['candidate']) : 0;

    if ($candidate_id === 0) {
        echo "<script>alert('Invalid vote! Please select a candidate.');</script>";
    } else {
        // Check if the user can revote (admin has made changes since the last vote)
        if ($last_voted_at && $last_updated <= $last_voted_at) {
            echo "<script>alert('You cannot revote until the admin makes changes.');</script>";
        } else {
            // Check if the user has already voted
            $check_vote_query = "SELECT id FROM votes WHERE user_id = ?";
            $stmt = $conn->prepare($check_vote_query);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                // Update the existing vote
                $update_vote_query = "UPDATE votes SET candidate_id = ?, last_voted_at = CURRENT_TIMESTAMP WHERE user_id = ?";
                $stmt = $conn->prepare($update_vote_query);
                $stmt->bind_param("ii", $candidate_id, $user_id);
            } else {
                // Insert a new vote
                $insert_vote_query = "INSERT INTO votes (candidate_id, user_id, last_voted_at) VALUES (?, ?, CURRENT_TIMESTAMP)";
                $stmt = $conn->prepare($insert_vote_query);
                $stmt->bind_param("ii", $candidate_id, $user_id);
            }

            if ($stmt->execute()) {
                echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        showSuccessModal();
                        setTimeout(function() {
                            window.location.href='results.php';
                        }, 2000);
                    });
                </script>";
            } else {
                echo "<script>alert('Failed to submit vote. Please try again.');</script>";
            }
            $stmt->close();
        }
    }
}

// Fetch candidates from the database
$candidates_query = "SELECT id, name, voting_type FROM candidates";
$candidates_result = $conn->query($candidates_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vote - College Voting System</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #6366f1, #ec4899);
            --secondary-gradient: linear-gradient(135deg, #f472b6, #f59e0b);
            --background-gradient: linear-gradient(135deg, #f3f4f6, #fce7f3);
            --card-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: var(--background-gradient);
            min-height: 100vh;
        }

        /* Navbar */
        .navbar {
            background: var(--primary-gradient);
            padding: 1rem 2rem;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .navbar h2 {
            color: white;
            font-size: 1.8rem;
            text-align: center;
            margin: 0;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .navbar a:hover {
            opacity: 0.8;
            transform: translateY(-2px);
        }

        /* Side Menu */
        .side-menu {
            position: fixed;
            top: 90px;
            left: -250px;
            width: 250px;
            height: calc(100% - 90px);
            background: var(--primary-gradient);
            transition: left 0.3s ease;
            z-index: 999;
            box-shadow: var(--card-shadow);
        }

        .side-menu.active {
            left: 0;
        }

        .side-menu ul {
            list-style: none;
            padding: 20px;
        }

        .side-menu ul li {
            margin-bottom: 15px;
        }

        .side-menu ul li a {
            color: white;
            text-decoration: none;
            font-size: 1.1rem;
            font-weight: 500;
            display: block;
            padding: 10px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .side-menu ul li a:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateX(5px);
        }

        /* Menu Toggle */
        .menu-toggle {
            position: fixed;
            top: 25px;
            left: 20px;
            font-size: 24px;
            color: white;
            cursor: pointer;
            z-index: 1001;
            transition: all 0.3s ease;
        }

        .menu-toggle:hover {
            transform: scale(1.1);
        }

        /* Hero Section */
        .hero {
            text-align: center;
            padding: 150px 20px 100px;
            background: var(--secondary-gradient);
            color: white;
            margin-bottom: 40px;
            clip-path: polygon(0 0, 100% 0, 100% 85%, 0 100%);
        }

        .hero h1 {
            font-size: 3rem;
            margin-bottom: 1rem;
            animation: fadeInUp 0.8s ease;
        }

        .hero p {
            font-size: 1.2rem;
            opacity: 0.9;
            animation: fadeInUp 0.8s ease 0.2s;
        }

        /* Voting Section */
        .voting-section {
            max-width: 1000px;
            margin: 40px auto;
            padding: 20px;
            background: white;
            border-radius: 20px;
            box-shadow: var(--card-shadow);
        }

        .voting-section h2 {
            color: #1f2937;
            text-align: center;
            margin-bottom: 30px;
            font-size: 2rem;
        }

        /* Voting Categories */
        .voting-category {
            margin-bottom: 40px;
            padding: 20px;
            border-radius: 12px;
            background: white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .voting-category h3 {
            color: #4f46e5;
            font-size: 1.5rem;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e5e7eb;
        }

        .voting-category label {
            display: flex;
            align-items: center;
            padding: 15px;
            margin-bottom: 15px;
            background: #f9fafb;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .voting-category label:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            background: #f3f4f6;
        }

        .voting-category input[type="radio"] {
            margin-right: 15px;
            transform: scale(1.2);
        }

        .voting-category button {
            width: 100%;
            padding: 12px;
            background: var(--primary-gradient);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 20px;
        }

        .voting-category button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.4);
        }

        /* Success Modal */
        .success-modal {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
            text-align: center;
            z-index: 1100;
            display: none;
        }

        .success-modal.show {
            display: block;
            animation: modalFadeIn 0.3s ease;
        }

        .success-modal i {
            font-size: 3rem;
            color: #10b981;
            margin-bottom: 15px;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes modalFadeIn {
            from {
                opacity: 0;
                transform: translate(-50%, -40%);
            }
            to {
                opacity: 1;
                transform: translate(-50%, -50%);
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .navbar h2 {
                font-size: 1.5rem;
            }

            .hero h1 {
                font-size: 2.5rem;
            }

            .voting-section {
                margin: 20px;
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
        <h2>College Voting System</h2>
        <a href="index.php">Home</a>
        <a href="logout.php">Logout</a>
    </div>

    <!-- Side Menu Bar -->
    <div class="side-menu" id="sideMenu">
        <ul>
            <li><a href="#" data-target="class-representative">Class Representative Voting</a></li>
            <li><a href="#" data-target="event-voting">College Event Voting</a></li>
            <li><a href="#" data-target="cultural-voting">Cultural Festival Voting</a></li>
        </ul>
    </div>

    <!-- Menu Toggle Button -->
    <div class="menu-toggle" id="menuToggle">
        <i class="fas fa-bars"></i>
    </div>

    <!-- Hero Section -->
    <div class="hero">
        <h1>Vote Now!</h1>
        <p>Make your voice heard by casting your votes for different categories below.</p>
    </div>

    <!-- Voting Section -->
    <div class="voting-section">
        <h2>Voting Categories</h2>

        <!-- Class Representative Voting -->
        <div class="voting-category" id="class-representative">
            <h3>Class Representative Voting</h3>
            <form method="POST">
                <?php if ($candidates_result && $candidates_result->num_rows > 0): ?>
                    <?php while ($candidate = $candidates_result->fetch_assoc()): ?>
                        <?php if ($candidate['voting_type'] === 'Class Representative Voting'): ?>
                            <label>
                                <input type="radio" name="candidate" value="<?php echo $candidate['id']; ?>">
                                <?php echo htmlspecialchars($candidate['name']); ?>
                                <i class="fas fa-check-circle"></i>
                            </label>
                        <?php endif; ?>
                    <?php endwhile; ?>
                    <button type="submit">Submit Vote</button>
                <?php else: ?>
                    <p>No candidates available.</p>
                <?php endif; ?>
            </form>
        </div>

        <!-- Event Voting -->
        <div class="voting-category" id="event-voting" style="display: none;">
            <h3>College Event Voting</h3>
            <form method="POST">
                <?php
                $candidates_result->data_seek(0); // Reset result pointer
                while ($candidate = $candidates_result->fetch_assoc()): ?>
                    <?php if ($candidate['voting_type'] === 'College Event Voting'): ?>
                        <label>
                            <input type="radio" name="candidate" value="<?php echo $candidate['id']; ?>">
                            <?php echo htmlspecialchars($candidate['name']); ?>
                            <i class="fas fa-check-circle"></i>
                        </label>
                    <?php endif; ?>
                <?php endwhile; ?>
                <button type="submit">Submit Vote</button>
            </form>
        </div>

        <!-- Cultural Festival Voting -->
        <div class="voting-category" id="cultural-voting" style="display: none;">
            <h3>Cultural Festival Voting</h3>
            <form method="POST">
                <?php
                $candidates_result->data_seek(0); // Reset result pointer
                while ($candidate = $candidates_result->fetch_assoc()): ?>
                    <?php if ($candidate['voting_type'] === 'Cultural Festival Voting'): ?>
                        <label>
                            <input type="radio" name="candidate" value="<?php echo $candidate['id']; ?>">
                            <?php echo htmlspecialchars($candidate['name']); ?>
                            <i class="fas fa-check-circle"></i>
                        </label>
                    <?php endif; ?>
                <?php endwhile; ?>
                <button type="submit">Submit Vote</button>
            </form>
        </div>
    </div>

    <!-- Success Modal -->
    <div class="success-modal" id="successModal">
        <i class="fas fa-check-circle"></i>
        <h3>Vote Submitted Successfully!</h3>
        <p>Thank you for participating in the voting process.</p>
    </div>

    <script>
        // Toggle side menu
        const menuToggle = document.getElementById('menuToggle');
        const sideMenu = document.getElementById('sideMenu');

        menuToggle.addEventListener('click', () => {
            sideMenu.classList.toggle('active');
        });

        // Show voting categories based on menu selection
        const menuItems = document.querySelectorAll('.side-menu ul li a');
        const votingCategories = document.querySelectorAll('.voting-category');

        menuItems.forEach(item => {
            item.addEventListener('click', (e) => {
                e.preventDefault();
                const targetId = item.getAttribute('data-target');

                // Hide all voting categories
                votingCategories.forEach(category => {
                    category.style.display = 'none';
                });

                // Show the selected voting category
                const targetCategory = document.getElementById(targetId);
                if (targetCategory) {
                    targetCategory.style.display = 'block';
                }

                // Close side menu on mobile after selection
                if (window.innerWidth <= 768) {
                    sideMenu.classList.remove('active');
                }
            });
        });

        // Success Modal Function
        function showSuccessModal() {
            const modal = document.getElementById('successModal');
            modal.classList.add('show');
            setTimeout(() => {
                modal.classList.remove('show');
            }, 2000);
        }
    </script>
</body>
</html>
<?php
session_start();
require 'db_connection.php'; // Include database connection

// Redirect non-admin users
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php"); // Redirect to login page if not logged in
    exit;
}

// Fetch candidates and their vote counts
$query = "SELECT 
            candidates.id, 
            candidates.name, 
            candidates.voting_type, 
            COUNT(votes.id) AS vote_count
          FROM candidates
          LEFT JOIN votes ON candidates.id = votes.candidate_id
          GROUP BY candidates.id, candidates.name, candidates.voting_type";
$result = $conn->query($query);

if (!$result) {
    die("Query Failed: " . $conn->error); // Debugging in case of query failure
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <style>
        /* Global Variables */
        :root {
            --primary-bg: linear-gradient(135deg, #6a11cb, #2575fc);
            --secondary-bg: #ffffff;
            --primary-text: #333;
            --button-bg: #2575fc;
            --button-hover: #6a11cb;
            --form-bg: #f4f4f4;
            --table-border: #ddd;
            --danger-bg: #ff4d4f;
            --success-bg: #28a745;
        }

        [data-theme="dark"] {
            --primary-bg: linear-gradient(135deg, #1c1e26, #2a2c3c);
            --secondary-bg: #2a2c3c;
            --primary-text: #f8f8f8;
            --button-bg: #4CAF50;
            --button-hover: #6a11cb;
            --form-bg: #353745;
            --table-border: #444;
            --danger-bg: #ff4d4f;
            --success-bg: #28a745;
        }

        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background: var(--primary-bg);
            color: var(--primary-text);
            min-height: 100vh;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
            background: var(--secondary-bg);
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            animation: fadeIn 1s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        h1, h2 {
            text-align: center;
            color: var(--primary-text);
            animation: slideIn 1s ease-in-out;
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateX(-20px); }
            to { opacity: 1; transform: translateX(0); }
        }

        /* Header Actions */
        .header-actions {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            animation: slideIn 1s ease-in-out;
        }

        .header-actions a, .theme-toggle {
            padding: 10px 15px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 1rem;
            cursor: pointer;
            background: var(--button-bg);
            color: white;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .header-actions a:hover, .theme-toggle:hover {
            background: var(--button-hover);
            transform: scale(1.05);
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            animation: fadeIn 1.5s ease-in-out;
        }

        table th, table td {
            border: 1px solid var(--table-border);
            padding: 15px;
            text-align: center;
        }

        table th {
            background: var(--button-bg);
            color: white;
        }

        table tr:nth-child(even) {
            background: var(--form-bg);
        }

        table tr:hover {
            background: var(--button-hover);
            color: white;
            transform: scale(1.02);
            transition: transform 0.3s ease;
        }

        /* Buttons in Table */
        .actions button {
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            font-size: 0.9rem;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .actions .edit {
            background: var(--success-bg);
            color: white;
        }

        .actions .delete {
            background: var(--danger-bg);
            color: white;
        }

        .actions button:hover {
            opacity: 0.8;
            transform: scale(1.1);
        }

        /* Add Candidate Form */
        form {
            text-align: center;
            margin-top: 30px;
            animation: slideIn 1s ease-in-out;
        }

        form input, form select, form button {
            margin: 10px 5px;
            padding: 12px;
            border: 1px solid var(--table-border);
            border-radius: 5px;
            font-size: 1rem;
            width: calc(30% - 20px);
            transition: all 0.3s ease;
        }

        form button {
            background: var(--button-bg);
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        form button:hover {
            background: var(--button-hover);
            transform: scale(1.05);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                width: 95%;
                padding: 15px;
            }

            table th, table td {
                padding: 10px;
                font-size: 0.9rem;
            }

            form input, form select, form button {
                width: 100%;
                margin-bottom: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header-actions">
            <a href="logout.php">Logout</a>
            <button class="theme-toggle" id="theme-toggle">Toggle Theme</button>
        </div>
        <h1>Admin Dashboard</h1>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Candidate Name</th>
                    <th>Voting Type</th>
                    <th>Vote Count</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['voting_type']); ?></td>
                    <td><?php echo htmlspecialchars($row['vote_count']); ?></td>
                    <td class="actions">
                        <form method="POST" action="edit_candidate.php" style="display:inline;">
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
                            <button type="submit" class="edit">Edit</button>
                        </form>
                        <form method="POST" action="delete_candidate.php" style="display:inline;">
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
                            <button type="submit" class="delete" onclick="return confirm('Are you sure you want to delete this candidate?');">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <h2>Add Candidate</h2>
        <form method="POST" action="add_candidate.php">
            <input type="text" name="name" placeholder="Candidate Name" required>
            <select name="voting_type" required>
                <option value="Class Representative Voting">Class Representative Voting</option>
                <option value="College Event Voting">College Event Voting</option>
                <option value="Cultural Festival Voting">Cultural Festival Voting</option>
            </select>
            <button type="submit">Add Candidate</button>
        </form>
    </div>

    <!-- JavaScript -->
    <script>
        const themeToggle = document.getElementById('theme-toggle');
        const body = document.body;

        if (!localStorage.getItem('theme')) {
            localStorage.setItem('theme', 'light');
        }
        body.setAttribute('data-theme', localStorage.getItem('theme'));

        themeToggle.addEventListener('click', () => {
            const currentTheme = body.getAttribute('data-theme');
            const newTheme = currentTheme === 'light' ? 'dark' : 'light';
            body.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
        });
    </script>
</body>
</html>
<?php
session_start();
require 'db2.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $query = "SELECT * FROM candidates WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $candidate = $result->fetch_assoc();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Candidate</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .form-container {
            background: #fff;
            color: #333;
            max-width: 400px;
            width: 100%;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.3);
            text-align: center;
        }

        .form-container h2 {
            font-size: 2rem;
            margin-bottom: 20px;
            color: #6a11cb;
        }

        .form-container input[type="text"],
        .form-container select {
            width: 100%;
            padding: 12px 15px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 1rem;
            outline: none;
            transition: 0.3s ease;
        }

        .form-container input[type="text"]:focus,
        .form-container select:focus {
            border-color: #6a11cb;
            box-shadow: 0px 0px 8px rgba(106, 17, 203, 0.4);
        }

        .form-container button {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            color: #fff;
            font-size: 1.2rem;
            font-weight: bold;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s ease;
        }

        .form-container button:hover {
            background: linear-gradient(135deg, #2575fc, #6a11cb);
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        }

        .form-container select {
            cursor: pointer;
        }

        .form-container small {
            display: block;
            margin-top: 20px;
            font-size: 0.9rem;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Edit Candidate</h2>
        <form method="POST" action="update_candidate.php">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($candidate['id']); ?>">
            <input type="text" name="name" value="<?php echo htmlspecialchars($candidate['name']); ?>" placeholder="Candidate Name" required>
            
            <select name="voting_type" required>
                <option value="" disabled>Select Voting Type</option>
                <option value="Class Representative Voting" <?php if ($candidate['voting_type'] === 'Class Representative Voting') echo 'selected'; ?>>Class Representative Voting</option>
                <option value="College Event Voting" <?php if ($candidate['voting_type'] === 'College Event Voting') echo 'selected'; ?>>College Event Voting</option>
                <option value="Cultural Festival Voting" <?php if ($candidate['voting_type'] === 'Cultural Festival Voting') echo 'selected'; ?>>Cultural Festival Voting</option>
            </select>
            
            <button type="submit">Update Candidate</button>
        </form>
        <small>Make sure the candidate information is correct before submitting.</small>
    </div>
</body>
</html>

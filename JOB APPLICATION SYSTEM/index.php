<?php
require_once 'models.php';

$searchResults = [];
$searching = false;

session_start();  // Start the session to access messages

// Check if there's a message to display
$message = isset($_SESSION['message']) ? $_SESSION['message'] : '';  // Retrieve message from session
$status = isset($_SESSION['statusCode']) ? $_SESSION['statusCode'] : '';  // Retrieve status from session

// Clear the message from session after displaying
unset($_SESSION['message']);
unset($_SESSION['statusCode']);

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $searching = true;
    $searchResults = searchApplicants($_GET['search']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Application System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #007bff;
            color: white;
            padding: 1em 2em;
        }
        main {
            padding: 2em;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: #ffffff;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1, h2 {
            color: #343a40;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        input[type="text"], input[type="email"], input[type="number"], textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            font-size: 14px;
        }
        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            font-size: 14px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th, table td {
            border: 1px solid #dee2e6;
            padding: 10px;
            text-align: left;
        }
        table th {
            background-color: #f1f1f1;
        }
        .search-form {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        .message {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
            font-size: 14px;
        }
        .message-success {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }
        .message-error {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }
    </style>
</head>
<body>
    <header>
        <h1>Tacher Job Application System</h1>
    </header>
    <main>
        <div class="container">
            <!-- Display Messages -->
            <?php if ($message): ?>
                <div class="message <?= $status === 200 ? 'message-success' : 'message-error' ?>">
                    <?= $message ?>
                </div>
            <?php endif; ?>

            <!-- Add Applicant Form -->
            <h2>Add New Applicant</h2>
            <form action="handleForm.php" method="POST">
                <input type="hidden" name="action" value="add" />
                <label for="full_name">Full Name:</label>
                <input type="text" name="full_name" required />
    
                <label for="email">Email:</label>
                <input type="email" name="email" required />
    
                <label for="phone">Phone:</label>
                <input type="text" name="phone" required />
    
                <label for="qualification">Qualification:</label>
                <input type="text" name="qualification" required />
    
                <label for="experience">Experience:</label>
                <input type="text" name="experience" required />
    
                <button type="submit">Add Applicant</button>
            </form>

            <!-- Search Form -->
            <h2>Search Applicants</h2>
            <form method="GET" class="search-form">
                <input type="text" name="search" placeholder="Search by name, email, phone, qualification, or experience" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                <button type="submit">Search</button>
            </form>

            <!-- Applicants Table -->
            <?php if ($searching && $searchResults['statusCode'] === 200): ?>
                <h2>Search Results</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Qualification</th>
                            <th>Experience</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($searchResults['querySet'] as $applicant): ?>
                        <tr>
                            <td><?= htmlspecialchars($applicant['full_name']) ?></td>
                            <td><?= htmlspecialchars($applicant['email']) ?></td>
                            <td><?= htmlspecialchars($applicant['phone']) ?></td>
                            <td><?= htmlspecialchars($applicant['qualification']) ?></td>
                            <td><?= htmlspecialchars($applicant['experience']) ?> years</td>
                            <td>
                                <a href="editApplicant.php?id=<?= $applicant['id'] ?>">Edit</a>
                                <form method="POST" action="handleForm.php" style="display:inline;">
                                    <input type="hidden" name="id" value="<?= $applicant['id'] ?>">
                                    <input type="hidden" name="action" value="delete">
                                    <button type="submit" onclick="return confirm('Are you sure you want to delete this applicant?');">Delete</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php elseif ($searching): ?>
                <p>No applicants found for the given search.</p>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>

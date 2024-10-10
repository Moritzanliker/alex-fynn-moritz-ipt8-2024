<?php
session_start();
include("../php/connection.php");
include("../php/functions.php");

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $project_name = $_POST['project_name'];
    $description = $_POST['description'];
    $owner_id = $_SESSION['user_id']; // Assuming user_id is stored in session
    $assigned_usernames = $_POST['assigned_usernames']; // Get the usernames input
    $status = $_POST['status']; // Get the selected status
    $due_date = $_POST['due_date']; // Get the due date input

    // Prepare and execute the SQL statement to insert the project
    $stmt_project = $con->prepare("INSERT INTO project (project_name, description, owner_id, status, due_date) VALUES (?, ?, ?, ?, ?)");
    $stmt_project->bind_param("ssiss", $project_name, $description, $owner_id, $status, $due_date);

    if ($stmt_project->execute()) {
        echo "<p>Project created successfully!</p>";

        // Get the last inserted project ID
        $project_id = $con->insert_id;

        // If usernames are provided, insert them into the project_users table
        if (!empty($assigned_usernames)) {
            // Split usernames by comma and trim whitespace
            $usernames = array_map('trim', explode(',', $assigned_usernames));

            // Insert each user into the project_users table
            foreach ($usernames as $username) {
                // Retrieve user ID based on the assigned username
                $stmt_user = $con->prepare("SELECT user_id FROM user WHERE username = ?");
                $stmt_user->bind_param("s", $username);
                $stmt_user->execute();
                $stmt_user->store_result();

                if ($stmt_user->num_rows > 0) {
                    $stmt_user->bind_result($assigned_user_id);
                    $stmt_user->fetch();

                    // Insert into project_users (you must create this table)
                    $stmt_project_user = $con->prepare("INSERT INTO project_users (project_id, user_id) VALUES (?, ?)");
                    $stmt_project_user->bind_param("ii", $project_id, $assigned_user_id);
                    $stmt_project_user->execute();
                    $stmt_project_user->close();
                } else {
                    echo "<p>User '$username' not found.</p>";
                }

                $stmt_user->close();
            }
        }
    } else {
        echo "<p>Error: " . $stmt_project->error . "</p>";
    }

    $stmt_project->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Project</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 50px;
        }
        .container {
            max-width: 500px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"],
        textarea,
        input[type="date"],
        select {
            width: 95%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }
        input[type="submit"] {
            width: 99%;
            padding: 10px;
            background-color: #28a745;
            border: none;
            border-radius: 3px;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #218838;
        }
        a {
            text-decoration: none;
            font-size: 20px;
        }
    </style>
</head>
<body>
    <a href="../pages/dashboard.php">Go Back</a>
    <div class="container">
        <h2>Create a New Project</h2>
        <form method="POST" action="">
            <label for="project_name">Project Name:</label>
            <input type="text" id="project_name" name="project_name" required>
            
            <label for="description">Project Description:</label>
            <textarea id="description" name="description" rows="4" required></textarea>

            <label for="status">Status:</label>
            <select id="status" name="status" required>
                <option value="pending">Pending</option>
                <option value="in_progress">In Progress</option>
                <option value="completed">Completed</option>
            </select>

            <label for="due_date">Due Date:</label>
            <input type="date" id="due_date" name="due_date" required>

            <label for="assigned_usernames">Assign Users (Usernames, comma separated, optional):</label>
            <input type="text" id="assigned_usernames" name="assigned_usernames">
            
            <input type="submit" value="Create Project">
        </form>
    </div>
</body>
</html>

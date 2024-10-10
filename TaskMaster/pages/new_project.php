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
    $assigned_username = $_POST['assigned_username'];

    // Retrieve user ID based on the assigned username
    $stmt_user = $con->prepare("SELECT user_id FROM user WHERE username = ?");
    $stmt_user->bind_param("s", $assigned_username);
    $stmt_user->execute();
    $stmt_user->store_result();

    if ($stmt_user->num_rows > 0) {
        $stmt_user->bind_result($assigned_user_id);
        $stmt_user->fetch();

        // Prepare and execute the SQL statement to insert the project
        $stmt_project = $con->prepare("INSERT INTO project (project_name, description, owner_id) VALUES (?, ?, ?)");
        $stmt_project->bind_param("ssi", $project_name, $description, $owner_id);

        if ($stmt_project->execute()) {
            echo "<p>Project created successfully!</p>";
        } else {
            echo "<p>Error: " . $stmt_project->error . "</p>";
        }

        // Optionally, you can link the project to the assigned user in a project_users table if needed

        $stmt_project->close();
    } else {
        echo "<p>User not found.</p>";
    }

    $stmt_user->close();
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
            padding: 20px;
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
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }
        input[type="submit"] {
            width: 100%;
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
    </style>
</head>
<body>
    <div class="container">
        <h2>Create a New Project</h2>
        <form method="POST" action="">
            <label for="project_name">Project Name:</label>
            <input type="text" id="project_name" name="project_name" required>
            
            <label for="project_description">Project Description:</label>
            <textarea id="project_description" name="description" rows="4" required></textarea>

            <label for="assigned_username">Assign User (Username):</label>
            <input type="text" id="assigned_username" name="assigned_username" >
            
            <input type="submit" value="Create Project">
        </form>
    </div>
</body>
</html>

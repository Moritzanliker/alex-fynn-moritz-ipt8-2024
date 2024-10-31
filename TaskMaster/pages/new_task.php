<?php
session_start();
include("../php/connection.php");
include("../php/functions.php");

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../pages/login.php"); // Redirect to login if not logged in
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $task_name = $_POST['task_name'];
    $description = $_POST['description'];
    $project_id = $_POST['project_id']; // Selected project
    $assigned_username = $_POST['assigned_username']; // Assigned user
    $status = $_POST['status'];
    $due_date = $_POST['due_date'];

    // Prepare and execute the SQL statement to insert the task
    $stmt_task = $con->prepare("INSERT INTO task (task_name, description, status, project_id, assigned_user_id, due_date) VALUES (?, ?, ?, ?, ?, ?)");
    
    // Default assigned_user_id to null
    $assigned_user_id = null;

    // Retrieve user ID based on the assigned username if provided
    if (!empty($assigned_username)) {
        $stmt_user = $con->prepare("SELECT user_id FROM user WHERE username = ?");
        $stmt_user->bind_param("s", $assigned_username);
        $stmt_user->execute();
        $stmt_user->store_result();

        if ($stmt_user->num_rows > 0) {
            $stmt_user->bind_result($assigned_user_id);
            $stmt_user->fetch();
        } else {
            echo "<p>User '$assigned_username' not found. Task will be created without assigned user.</p>";
        }

        $stmt_user->close();
    }

    // Bind parameters and execute the task insert statement
    $stmt_task->bind_param("sssiis", $task_name, $description, $status, $project_id, $assigned_user_id, $due_date);

    if ($stmt_task->execute()) {
        // Redirect to the project page after successful task creation
        header("Location: project_view.php?project_id=" . $project_id);
        exit();
    } else {
        echo "<p>Error: " . $stmt_task->error . "</p>";
    }

    $stmt_task->close();
}

// Fetch available projects for the dropdown
$projects = [];
$stmt_projects = $con->prepare("SELECT project_id, project_name FROM project");
$stmt_projects->execute();
$stmt_projects->bind_result($project_id, $project_name);
while ($stmt_projects->fetch()) {
    $projects[] = ['id' => $project_id, 'name' => $project_name];
}
$stmt_projects->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Task</title>
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
            padding: 30px;
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
        select,
        input[type="date"] {
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
        a {
            text-decoration: none;
            font-size: 20px;
        }
    </style>
</head>
<body>
<a href="../pages/dashboard.php"> Go Back</a>
    <div class="container">
        <h2>Create a New Task</h2>
        <form method="POST" action="">
            <label for="task_name">Task Name:</label>
            <input type="text" id="task_name" name="task_name" required>

            <label for="description">Task Description:</label>
            <textarea id="description" name="description" rows="4"></textarea>

            <label for="project_id">Select Project:</label>
            <select id="project_id" name="project_id" required>
                <option value="">Select a project</option>
                <?php foreach ($projects as $project): ?>
                    <option value="<?= $project['id'] ?>"><?= htmlspecialchars($project['name']) ?></option>
                <?php endforeach; ?>
            </select>

            <label for="assigned_username">Assign User (Username, optional):</label>
            <input type="text" id="assigned_username" name="assigned_username">

            <label for="status">Task Status:</label>
            <select id="status" name="status" required>
                <option value="pending">Pending</option>
                <option value="in_progress">In Progress</option>
                <option value="completed">Completed</option>
            </select>

            <label for="due_date">Due Date:</label>
            <input type="date" id="due_date" name="due_date">

            <input type="submit" value="Create Task">
        </form>
    </div>
</body>
</html>

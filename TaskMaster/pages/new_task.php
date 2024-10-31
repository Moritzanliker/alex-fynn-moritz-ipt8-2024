<?php
session_start();
include("../php/connection.php");
include("../php/functions.php");

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../pages/login.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $task_name = $_POST['task_name'];
    $description = $_POST['description'];
    $project_id = $_POST['project_id'];
    $assigned_user_id = $_POST['assigned_user_id']; // selected user ID
    $status = $_POST['status'];
    $due_date = $_POST['due_date'];

    // Insert the task with assigned_user_id
    $stmt_task = $con->prepare("INSERT INTO task (task_name, description, status, project_id, due_date, assigned_user_id) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt_task->bind_param("sssisi", $task_name, $description, $status, $project_id, $due_date, $assigned_user_id);

    if ($stmt_task->execute()) {
        echo "<p>Task created successfully and user assigned!</p>";
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

// Fetch available users for the dropdown
$users = [];
$stmt_users = $con->prepare("SELECT user_id, username FROM user");
$stmt_users->execute();
$stmt_users->bind_result($user_id, $username);
while ($stmt_users->fetch()) {
    $users[] = ['id' => $user_id, 'username' => $username];
}
$stmt_users->close();
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
<a href="../pages/dashboard.php">Go Back</a>
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

            <label for="assigned_user_id">Assign User:</label>
            <select id="assigned_user_id" name="assigned_user_id" required>
                <option value="">Select a user</option>
                <?php foreach ($users as $user): ?>
                    <option value="<?= $user['id'] ?>"><?= htmlspecialchars($user['username']) ?></option>
                <?php endforeach; ?>
            </select>

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

<?php
session_start();
include("../php/connection.php");
include("../php/functions.php");

$user_data = check_login($con);
$user_id = $_SESSION['user_id']; // Get the logged-in user's ID

// Fetch projects where the user is the owner
$owner_query = "SELECT p.project_id, p.project_name, p.description, p.created_at 
                FROM project p
                JOIN project_users pu ON p.project_id = pu.project_id
                WHERE pu.user_id = '$user_id' AND pu.role = 'owner'";
$owner_result = mysqli_query($con, $owner_query);

// Fetch projects where the user is a member
$member_query = "SELECT p.project_id, p.project_name, p.description, p.created_at 
                 FROM project p
                 JOIN project_users pu ON p.project_id = pu.project_id 
                 WHERE pu.user_id = '$user_id' AND pu.role = 'member'";
$member_result = mysqli_query($con, $member_query);

// Fetch tasks for a specific project
function fetch_tasks($con, $project_id) {
    $task_query = "SELECT t.task_name, t.description, t.status, t.due_date, u.username AS assigned_user
                   FROM task t
                   LEFT JOIN user u ON t.assigned_user_id = u.user_id
                   WHERE t.project_id = '$project_id'";
    return mysqli_query($con, $task_query);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaskMaster Dashboard</title>
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        /* General styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7f9;
            color: #333;
            margin: 0;
        }

        /* Button styling */
        .btn {
            display: inline-block;
            background-color: #e53e3e;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            margin: 20px 0;
            transition: background-color 0.3s ease;
        }
        .btn:hover {
            background-color: #c53030;
        }

        /* Main container styling */
        .container {
            width: 90%;
            max-width: 1200px;
            margin: auto;
            padding: 20px;
        }

        /* Project and Task List styling */
        .section-header h2 {
            font-size: 2em;
            margin-bottom: 10px;
            border-bottom: 3px solid #e53e3e;
            display: inline-block;
            padding-bottom: 5px;
        }

        .list-projects-tasks {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        /* Project Card styling */
        .project-card {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: box-shadow 0.3s ease;
        }
        .project-card:hover {
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }
        .project-card h3 {
            font-size: 1.5em;
            color: #333;
            margin-bottom: 10px;
        }
        .project-card p {
            color: #666;
            margin-bottom: 10px;
        }
        .project-card .created-at {
            font-size: 0.9em;
            color: #888;
        }

        /* Task List styling */
        .task-list {
            list-style: none;
            padding: 0;
            margin-top: 10px;
        }
        .task-list li {
            background-color: #f7f8fa;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 10px;
            transition: background-color 0.2s ease;
        }
        .task-list li:hover {
            background-color: #eef0f2;
        }
        .task-list li strong {
            color: #e53e3e;
        }
        .task-status {
            font-weight: bold;
            color: #4caf50;
        }
        .task-info {
            margin-top: 5px;
            color: #555;
        }

    </style>
</head>
<body>

<header class="header">
    <center>
        <div class="header-content">
            <a href="../pages/dashboard.php" class="logo">
                <svg class="icon-logo" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m8 3 4 8 5-5 5 15H2L8 3z"></path>
                </svg>
                <span class="title">TaskMaster</span>
            </a>
            <nav class="nav">
                <a href="../pages/dashboard.php" class="nav-link">Dashboard</a>
                <a href="../pages/project.php" class="nav-link">Projects</a>
                <a href="../pages/task.php" class="nav-link">Tasks</a>
            </nav>
            <div class="header-actions">
                Hello, <?php echo htmlspecialchars($user_data['username']); ?>
                <label class="switch">
                        <input type="checkbox" id="toggle-darkmode">
                        <span class="slider"></span>
                    </label>
                <a href="../php/logout.php" class="nav-link">Logout</a>
            </div>
        </div>
    </center>
</header>

<div class="container">
    <center>
        <div class="actions">
            <a href="../pages/new_task.php" class="btn">+ New Task</a>
        </div>
    </center>

    <main class="main-content">
        <section class="recent-section">
            <div class="section-header">
                <h2>All Projects and Tasks</h2>
            </div>
            <div class="list-projects-tasks">

                <!-- Projects Owned by User -->
                <h3>Projects Owned</h3>
                <?php while ($owner_project = mysqli_fetch_assoc($owner_result)): ?>
                    <div class="project-card">
                        <h3><?php echo htmlspecialchars($owner_project['project_name']); ?></h3>
                        <p><?php echo htmlspecialchars($owner_project['description']); ?></p>
                        <p class="created-at">Created At: <?php echo htmlspecialchars($owner_project['created_at']); ?></p>
                        <ul class="task-list">
                            <?php
                            $tasks_result = fetch_tasks($con, $owner_project['project_id']);
                            if (mysqli_num_rows($tasks_result) > 0):
                                while ($task = mysqli_fetch_assoc($tasks_result)): ?>
                                    <li>
                                        <strong>Task:</strong> <?php echo htmlspecialchars($task['task_name']); ?>
                                        <div class="task-info">
                                            <?php echo htmlspecialchars($task['description']); ?> 
                                            <br><span class="task-status">Status:</span> <?php echo htmlspecialchars($task['status']); ?>
                                            <br><strong>Assigned to:</strong> <?php echo htmlspecialchars($task['assigned_user'] ?? 'Unassigned'); ?>
                                            <br><strong>Due Date:</strong> <?php echo htmlspecialchars($task['due_date']); ?>
                                        </div>
                                    </li>
                                <?php endwhile;
                            else: ?>
                                <li>No tasks assigned to this project yet.</li>
                            <?php endif; ?>
                        </ul>
                    </div>
                <?php endwhile; ?>

                <!-- Projects Member of -->
                <h3>Projects I'm a Member Of</h3>
                <?php while ($member_project = mysqli_fetch_assoc($member_result)): ?>
                    <div class="project-card">
                        <h3><?php echo htmlspecialchars($member_project['project_name']); ?></h3>
                        <p><?php echo htmlspecialchars($member_project['description']); ?></p>
                        <p class="created-at">Created At: <?php echo htmlspecialchars($member_project['created_at']); ?></p>
                        <ul class="task-list">
                            <?php
                            $tasks_result = fetch_tasks($con, $member_project['project_id']);
                            if (mysqli_num_rows($tasks_result) > 0):
                                while ($task = mysqli_fetch_assoc($tasks_result)): ?>
                                    <li>
                                        <strong>Task:</strong> <?php echo htmlspecialchars($task['task_name']); ?>
                                        <div class="task-info">
                                            <?php echo htmlspecialchars($task['description']); ?> 
                                            <br><span class="task-status">Status:</span> <?php echo htmlspecialchars($task['status']); ?>
                                            <br><strong>Assigned to:</strong> <?php echo htmlspecialchars($task['assigned_user'] ?? 'Unassigned'); ?>
                                            <br><strong>Due Date:</strong> <?php echo htmlspecialchars($task['due_date']); ?>
                                        </div>
                                    </li>
                                <?php endwhile;
                            else: ?>
                                <li>No tasks assigned to this project yet.</li>
                            <?php endif; ?>
                        </ul>
                    </div>
                <?php endwhile; ?>
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="container">
            <p>© 2024 TaskMaster. All rights reserved.</p>
            <nav class="footer-nav">
                <a href="#">Terms</a>
                <a href="#">Privacy</a>
                <a href="#">Support</a>
            </nav>
        </div>
    </footer>
</div>

<script src="../js/script.js"></script>
</body>
</html>

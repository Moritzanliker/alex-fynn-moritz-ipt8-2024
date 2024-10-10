<?php
session_start();
include("../php/connection.php");
include("../php/functions.php");

$user_data = check_login($con);
$user_id = $_SESSION['user_id']; // Get the logged-in user's ID

// Fetch projects where the user is the owner
$owner_query = "SELECT p.project_id, p.project_name, p.description, p.created_at 
                FROM project p 
                WHERE p.owner_id = '$user_id'";
$owner_result = mysqli_query($con, $owner_query);

// Fetch projects where the user is a member
$member_query = "SELECT p.project_id, p.project_name, p.description, p.created_at 
                 FROM project p 
                 JOIN project_users pu ON p.project_id = pu.project_id 
                 WHERE pu.user_id = '$user_id'";
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
</head>
<body>
<style>

.project-list {
    margin: 10px 0;
    background-color: #fff;
    padding: 15px;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
}

.project-list strong {
    font-size: 18px;
    color: black;
}

ul {
    list-style-type: none;
    padding-left: 20px;
}

ul li {
    margin-bottom: 10px;
}

ul li strong {
    font-size: 16px;
    color: #4CAF50;
}



.section-header h2 {
    color: #333;
    border-bottom: 2px solid #e53e3e;
    padding-bottom: 5px;
    margin-bottom: 20px;
}

/* Task List */
.list-projects-tasks h3 {
    color: #e53e3e;
    font-size: 22px;
}

.list-projects-tasks ul li {
    background-color: #d4d3d3;
    padding: 15px;
    border-radius: 5px;
    margin-bottom: 15px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    display: block;
}

hr {
    border: none;
    height: 1px;
    background-color: #eee;
}
</style>

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
                <a href="../pages/profile.php" class="nav-link">Profile</a>
            </nav>
            <div class="header-actions">
                Hello, <?php echo $user_data['username']; ?>
                <label class="switch">
                    <input type="checkbox" id="toggle-darkmode">
                    <span class="slider"></span>
                </label>
                <nav class="nav">
                    <a href="../php/logout.php" class="nav-link">Logout</a>
                </nav>
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
        <!-- Project and Task List Section -->
        <section class="recent-section">
            <div class="section-header">
                <h2>All Projects and Tasks</h2>
            </div>
            <div class="list-projects-tasks">

                <!-- Projects Owned by User -->
                <h3>Projects Owned</h3>
                <?php while ($owner_project = mysqli_fetch_assoc($owner_result)): ?>
                <div class="project-list">
                    <strong><?php echo htmlspecialchars($owner_project['project_name']); ?></strong> <br>
                    <span><?php echo htmlspecialchars($owner_project['description']); ?></span><br>
                    <ul>
                        <?php
                        $tasks_result = fetch_tasks($con, $owner_project['project_id']);
                        if (mysqli_num_rows($tasks_result) > 0):
                            while ($task = mysqli_fetch_assoc($tasks_result)): ?>
                                <li>
                                    <strong>Task:</strong> <?php echo htmlspecialchars($task['task_name']); ?>
                                    <br><?php echo htmlspecialchars($task['description']); ?>
                                    <br><strong>Status: </strong> <?php echo htmlspecialchars($task['status']); ?>
                                    <br><strong>Assigned to:</strong> <?php echo htmlspecialchars($task['assigned_user'] ?? 'Unassigned'); ?>
                                    <br><strong>Due Date:</strong> <?php echo htmlspecialchars($task['due_date']); ?>
                                </li>
                            <?php endwhile;
                        else: ?>
                            <li>No tasks assigned to this project yet.</li>
                        <?php endif; ?>
                    </ul>
                </div>
                <hr>
                <?php endwhile; ?>

                <!-- Projects Member of -->
                <h3>Projects I'm a Member Of</h3>
                <?php while ($member_project = mysqli_fetch_assoc($member_result)): ?>
                <div class="project-list">
                    <strong>Project:</strong> <?php echo htmlspecialchars($member_project['project_name']); ?> <br>
                    <span><?php echo htmlspecialchars($member_project['description']); ?></span><br>
                    <span><strong>Created At:</strong> <?php echo htmlspecialchars($member_project['created_at']); ?></span>

                    <!-- Fetch and Display Tasks for This Project -->
                    <ul>
                        <?php
                        $tasks_result = fetch_tasks($con, $member_project['project_id']);
                        if (mysqli_num_rows($tasks_result) > 0):
                            while ($task = mysqli_fetch_assoc($tasks_result)): ?>
                                <li>
                                    <strong>Task:</strong> <?php echo htmlspecialchars($task['task_name']); ?>
                                    <br><span><?php echo htmlspecialchars($task['description']); ?> (Status: <?php echo htmlspecialchars($task['status']); ?>)</span>
                                    <br><strong>Assigned to:</strong> <?php echo htmlspecialchars($task['assigned_user'] ?? 'Unassigned'); ?>
                                    <br><strong>Due Date:</strong> <?php echo htmlspecialchars($task['due_date']); ?>
                                </li>
                            <?php endwhile;
                        else: ?>
                            <li>No tasks assigned to this project yet.</li>
                        <?php endif; ?>
                    </ul>
                </div>
                <hr>
                <?php endwhile; ?>
                
            </div>
        </section>

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

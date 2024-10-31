<?php
session_start();
include("../php/connection.php");
include("../php/functions.php");

$user_data = check_login($con);
$user_id = $user_data['user_id'];

// Query for the number of ongoing projects
$ongoing_projects_query = "SELECT COUNT(*) AS count FROM project WHERE owner_id = $user_id AND status IN ('in_progress', 'pending')";
$ongoing_projects_result = mysqli_query($con, $ongoing_projects_query);
$ongoing_projects_count = mysqli_fetch_assoc($ongoing_projects_result)['count'];

// Query for the number of completed tasks
$completed_tasks_query = "SELECT COUNT(*) AS count FROM task WHERE assigned_user_id = $user_id AND status = 'completed'";
$completed_tasks_result = mysqli_query($con, $completed_tasks_query);
$completed_tasks_count = mysqli_fetch_assoc($completed_tasks_result)['count'];

// Query for the number of team members
$team_members_query = "SELECT COUNT(DISTINCT user_id) AS count FROM project_users WHERE project_id IN (SELECT project_id FROM project WHERE owner_id = $user_id)";
$team_members_result = mysqli_query($con, $team_members_query);
$team_members_count = mysqli_fetch_assoc($team_members_result)['count'];

// Query for the number of upcoming deadlines (tasks with due dates within the next 7 days)
$upcoming_deadlines_query = "SELECT COUNT(*) AS count FROM project WHERE owner_id = $user_id AND due_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)";
$upcoming_deadlines_result = mysqli_query($con, $upcoming_deadlines_query);
$upcoming_deadlines_count = mysqli_fetch_assoc($upcoming_deadlines_result)['count'];

// Query for the 3 most recent projects
$recent_projects_query = "SELECT project_id, project_name, description, due_date FROM project WHERE owner_id = $user_id ORDER BY created_at DESC LIMIT 3";

$recent_projects_result = mysqli_query($con, $recent_projects_query);

// Query for the 3 most recent tasks
$recent_tasks_query = "SELECT task_name, description, due_date FROM task WHERE assigned_user_id = $user_id ORDER BY created_at DESC LIMIT 3";
$recent_tasks_result = mysqli_query($con, $recent_tasks_query);
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
        <main class="main-content">
            <section class="dashboard">
                <div class="section-header">
                    <h1>Dashboard</h1>
                    <div class="actions">
                        <a href="../pages/new_project.php" class="btn" style=" text-decoration: none; color: black;">+ New Project</a>
                        <a href="../pages/new_task.php"class="btn" style=" text-decoration: none; color: black;"> + New Task</a>
                     </div>
                </div>
                <div class="card-grid">
                    <div class="card">
                        <div class="card-header">
                            <h2>Ongoing Projects</h2>
                            <p><?php echo $ongoing_projects_count; ?> active projects</p>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h2>Completed Tasks</h2>
                            <p><?php echo $completed_tasks_count; ?> tasks completed</p>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h2>Team Members</h2>
                            <p><?php echo $team_members_count; ?> team members</p>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h2>Upcoming Deadlines</h2>
                            <p><?php echo $upcoming_deadlines_count; ?> deadlines this week</p>
                        </div>
                    </div>
                </div>
            </section>
            <!-- Recent Projects Section -->
            <section class="recent-section">
                <div class="section-header">
                    <h2>Recent Projects</h2>
                    <a href="../pages/project.php" class="view-all">View all</a>
                </div>
                <div class="card-grid-projects-tasks">
                    <?php while ($project = mysqli_fetch_assoc($recent_projects_result)) { ?>
                    <div class="card">
                        <div class="card-header">
                            <h2><?php echo $project['project_name']; ?></h2>
                            <p><?php echo $project['description']; ?></p>
                        </div>
                        <div class="card-footer">
                            <p>Deadline: <?php echo $project['due_date']; ?></p>
                            <a href="../pages/project_view.php?project_id=<?php echo $project['project_id']; ?>" class="view-link">View Project</a>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </section>
            <!-- Recent Tasks Section -->
            <section class="recent-section">
                <div class="section-header">
                    <h2>Recent Tasks</h2>
                    <a href="../pages/task.php" class="view-all">View all</a>
                </div>
                <div class="card-grid-projects-tasks">
                    <?php while ($task = mysqli_fetch_assoc($recent_tasks_result)) { ?>
                    <div class="card">
                        <div class="card-header">
                            <h2><?php echo $task['task_name']; ?></h2>
                            <p><?php echo $task['description']; ?></p>
                        </div>
                        <div class="card-footer">
                            <p>Due: <?php echo $task['due_date']; ?></p>
                        </div>
                    </div>
                    <?php } ?>
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

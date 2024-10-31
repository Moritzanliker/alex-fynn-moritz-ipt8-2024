<?php
session_start();
include("../php/connection.php");
include("../php/functions.php");
$user_data = check_login($con);

// Assuming you have the user's ID stored in the session after login
$user_id = $_SESSION['user_id']; // Replace with your actual session variable for user ID

// Fetch projects where the user is the owner
$owner_query = "SELECT p.project_id, p.project_name, p.description, p.created_at 
                FROM project p 
                JOIN project_users pu ON p.project_id = pu.project_id 
                WHERE pu.user_id = '$user_id' AND pu.role = 'owner'"; // Adjust 'owner' if necessary

$owner_result = mysqli_query($con, $owner_query);

// Fetch projects where the user is a member
$member_query = "SELECT p.project_id, p.project_name, p.description, p.created_at 
                 FROM project p 
                 JOIN project_users pu ON p.project_id = pu.project_id 
                 WHERE pu.user_id = '$user_id' AND pu.role = 'member'"; // Adjust 'member' if necessary

$member_result = mysqli_query($con, $member_query);

// Function to calculate progress based on tasks
function calculate_project_progress($project_id, $con) {
   $task_query = "SELECT status FROM task WHERE project_id = ?";
   $stmt = $con->prepare($task_query);
   $stmt->bind_param("i", $project_id);
   $stmt->execute();
   $result = $stmt->get_result();

   $total_tasks = 0;
   $progress_points = 0;

   while ($task = $result->fetch_assoc()) {
       $total_tasks++;
       if ($task['status'] === 'completed') {
           $progress_points += 2;
       } elseif ($task['status'] === 'in_progress') {
           $progress_points += 1;
       }
   }

   // Calculate progress as a percentage
   return $total_tasks > 0 ? ($progress_points / ($total_tasks * 2)) * 100 : 0;
}

// Fetch project progress for owned projects
$owned_projects = [];
while ($project = mysqli_fetch_assoc($owner_result)) {
   $project['progress'] = calculate_project_progress($project['project_id'], $con);
   $owned_projects[] = $project;
}

// Fetch project progress for member projects
$member_projects = [];
while ($project = mysqli_fetch_assoc($member_result)) {
   $project['progress'] = calculate_project_progress($project['project_id'], $con);
   $member_projects[] = $project;
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
      /* Grid layout to ensure cards appear under titles */
      .section-title {
         margin-top: 40px;
         font-size: 1.8em;
         color: #333;
         text-align: left;
         width: 100%;
      }

      .card-grid {
         display: flex;
         flex-wrap: wrap;
         gap: 20px;
         justify-content: flex-start;
         margin-top: 10px;
      }

      .card {
         width: calc(33.33% - 20px); /* Makes three cards fit per row */
         border: 1px solid #ddd;
         border-radius: 8px;
         padding: 15px;
         background-color: #fff;
         box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      }

      .card-header h2 {
         margin: 0;
         font-size: 1.3em;
         color: #333;
      }

      .progress-fill {
         background-color: #4caf50;
         height: 100%;
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
         <a href="../pages/new_project.php" class="btn" style="text-decoration: none; color: black;">+ New Project</a>
      </div>
   </center>
   <main class="main-content">
      <section class="recent-section">
         <!-- Projects Owned by User -->
         <h2 class="section-title">Projects Owned</h2>
         <div class="card-grid">
            <?php foreach ($owned_projects as $owner_project): ?>
               <div class="card">
                     <div class="card-header">
                        <h2><?php echo htmlspecialchars($owner_project['project_name']); ?></h2>
                        <p><?php echo htmlspecialchars($owner_project['description']); ?></p>
                     </div>
                     <div class="card-content">
                        <div class="progress">
                           <span>Progress</span>
                           <div class="progress-bar">
                                 <div class="progress-fill" style="width: <?php echo $owner_project['progress']; ?>%;"></div>
                           </div>
                        </div>
                        <div class="card-footer">
                           <p>Created At: <?php echo htmlspecialchars($owner_project['created_at']); ?></p>
                           <a href="../pages/project_view.php?project_id=<?php echo $owner_project['project_id']; ?>" class="view-link">View Project</a>
                        </div>
                     </div>
               </div>
            <?php endforeach; ?>
         </div>

         <!-- Projects Member of -->
         <h2 class="section-title">Projects I'm a Member Of</h2>
         <div class="card-grid">
            <?php foreach ($member_projects as $member_project): ?>
               <div class="card">
                     <div class="card-header">
                        <h2><?php echo htmlspecialchars($member_project['project_name']); ?></h2>
                        <p><?php echo htmlspecialchars($member_project['description']); ?></p>
                     </div>
                     <div class="card-content">
                        <div class="progress">
                           <span>Progress</span>
                           <div class="progress-bar">
                                 <div class="progress-fill" style="width: <?php echo $member_project['progress']; ?>%;"></div>
                           </div>
                        </div>
                        <div class="card-footer">
                           <p>Created At: <?php echo htmlspecialchars($member_project['created_at']); ?></p>
                           <a href="../pages/project_view.php?project_id=<?php echo $member_project['project_id']; ?>" class="view-link">View Project</a>
                        </div>
                     </div>
               </div>
            <?php endforeach; ?>
         </div>
      </section>
   </main>
</div>

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
<script src="../js/script.js">
   function updateProgressBar() {
    // Calculate total points and max points
    const totalTasks = <?php echo array_sum(array_map('count', $tasks)); ?>;
    const maxPoints = totalTasks * 2;
    let currentPoints = 0;

    // Calculate points for each task based on status
    document.querySelectorAll('.task-item').forEach(task => {
        const status = task.closest('.frame').id.replace('frame-', '');
        if (status === 'in_progress') currentPoints += 1;
        else if (status === 'completed') currentPoints += 2;
    });

    // Calculate progress percentage
    const progressPercentage = Math.round((currentPoints / maxPoints) * 100);

    // Update progress bar
    const progressBar = document.getElementById("progress-bar");
    progressBar.style.width = `${progressPercentage}%`;
    progressBar.setAttribute("aria-valuenow", progressPercentage);
    progressBar.textContent = `${progressPercentage}%`;
}
</script>
</body>
</html>

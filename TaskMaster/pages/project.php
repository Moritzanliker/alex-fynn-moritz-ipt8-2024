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
                WHERE p.owner_id = '$user_id'";

$owner_result = mysqli_query($con, $owner_query);

// Fetch projects where the user is a member
$member_query = "SELECT p.project_id, p.project_name, p.description, p.created_at 
                 FROM project p 
                 JOIN project_users pu ON p.project_id = pu.project_id 
                 WHERE pu.user_id = '$user_id'";

$member_result = mysqli_query($con, $member_query);
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
         <center>
         <div class="actions">
               <a href="../pages/new_project.php" class="btn" style=" text-decoration: none; color: black;">+ New Project</a>
         </div>
         </center>
         <main class="main-content">
         <!-- Recent Projects Section -->
         <section class="recent-section">
            <div class="section-header">
               <h2>All Projects</h2>
            </div>
            <div class="card-grid-projects-tasks">

               <!-- Projects Owned by User -->
               <h3>Projects Owned</h3>
               <?php while ($owner_project = mysqli_fetch_assoc($owner_result)): ?>
               <div class="card">
                  <div class="card-header">
                     <h2><?php echo htmlspecialchars($owner_project['project_name']); ?></h2>
                     <p><?php echo htmlspecialchars($owner_project['description']); ?></p>
                  </div>
                  <div class="card-content">
                     <div class="progress">
                        <span>Progress</span>
                        <div class="progress-bar">
                           <div class="progress-fill" style="width: 75%;"></div>
                        </div>
                     </div>
                     <div class="card-footer">
                        <p>Created At: <?php echo htmlspecialchars($owner_project['created_at']); ?></p>
                        <a href="../pages/project_view.php?project_id=<?php echo $owner_project['project_id']; ?>" class="view-link">View Project</a>
                     </div>
                  </div>
               </div>
               <?php endwhile; ?>

               <!-- Projects Member of -->
               <h3>Projects I'm a Member Of</h3>
               <?php while ($member_project = mysqli_fetch_assoc($member_result)): ?>
               <div class="card">
                  <div class="card-header">
                     <h2><?php echo htmlspecialchars($member_project['project_name']); ?></h2>
                     <p><?php echo htmlspecialchars($member_project['description']); ?></p>
                  </div>
                  <div class="card-content">
                     <div class="progress">
                        <span>Progress</span>
                        <div class="progress-bar">
                           <div class="progress-fill" style="width: 75%;"></div>
                        </div>
                     </div>
                     <div class="card-footer">
                        <p>Created At: <?php echo htmlspecialchars($member_project['created_at']); ?></p>
                        <a href="../pages/project_view.php?project_id=<?php echo $member_project['project_id']; ?>" class="view-link">View Project</a>
                     </div>
                  </div>
               </div>
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

<?php
session_start();
include("../php/connection.php");
include("../php/functions.php");

	$user_data = check_login($con);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TastMaster Dashboard</title>
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
                 <a href="../pages/profile.php" class="nav-link">Profile</a>
              </nav>
              <div class="header-actions">
              Hello, <?php echo $user_data['username']; ?>
                 </button>
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

    <h1>Profile</h1>


    <footer class="footer">
        <div class="container">
            <p>© 2024 TastMaster. All rights reserved.</p>
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
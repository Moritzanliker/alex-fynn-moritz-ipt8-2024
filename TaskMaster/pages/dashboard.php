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
               <a href="#" class="logo">
                  <svg class="icon-logo" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                     <path d="m8 3 4 8 5-5 5 15H2L8 3z"></path>
                  </svg>
                  <span class="title">TaskMaster</span>
               </a>
               <nav class="nav">
                  <a href="../pages/dashboard.html" class="nav-link">Dashboard</a>
                  <a href="../pages/project.html" class="nav-link">Projects</a>
                  <a href="../pages/task.html" class="nav-link">Tasks</a>
                  <a href="../pages/profile.html" class="nav-link">Profile</a>
               </nav>
               <div class="header-actions">
                  <button class="icon-button">
                     <svg class="icon" width="24px" height="auto" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"></path>
                        <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"></path>
                     </svg>
                  </button>
                  <label class="switch">
                  <input type="checkbox" id="toggle-darkmode">
                  <span class="slider"></span>
                  </label>
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
                     <button class="btn">+ New Project</button>
                     <button class="btn">+ New Task</button>
                  </div>
               </div>
               <div class="card-grid">
                  <div class="card">
                     <div class="card-header">
                        <h2>Ongoing Projects</h2>
                        <p>12 active projects</p>
                     </div>
                     <div class="card-content">
                        <div class="stats">
                           <span class="stats-number">12</span>
                           <svg class="icon-dashboard" xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                              <path d="M16 20V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                              <rect width="20" height="14" x="2" y="6" rx="2"></rect>
                           </svg>
                        </div>
                     </div>
                  </div>
                  <div class="card">
                     <div class="card-header">
                        <h2>Completed Tasks</h2>
                        <p>145 tasks completed</p>
                     </div>
                     <div class="card-content">
                        <div class="stats">
                           <span class="stats-number">145</span>
                           <svg class="icon-dashboard" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                              <circle cx="12" cy="12" r="10"></circle>
                              <path d="m9 12 2 2 4-4"></path>
                           </svg>
                        </div>
                     </div>
                  </div>
                  <div class="card">
                     <div class="card-header">
                        <h2>Team Members</h2>
                        <p>24 team members</p>
                     </div>
                     <div class="card-content">
                        <div class="stats">
                           <span class="stats-number">24</span>
                           <svg class="icon-dashboard" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                              <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                              <circle cx="9" cy="7" r="4"></circle>
                              <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                              <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                           </svg>
                        </div>
                     </div>
                  </div>
                  <div class="card">
                     <div class="card-header">
                        <h2>Upcoming Deadlines</h2>
                        <p>3 deadlines this week</p>
                     </div>
                     <div class="card-content">
                        <div class="stats">
                           <span class="stats-number">3</span>
                           <svg class="icon-dashboard" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                              <path d="M8 2v4"></path>
                              <path d="M16 2v4"></path>
                              <rect width="18" height="18" x="3" y="4" rx="2"></rect>
                              <path d="M3 10h18"></path>
                           </svg>
                        </div>
                     </div>
                  </div>
               </div>
            </section>
            <!-- Recent Projects Section -->
            <section class="recent-section">
               <div class="section-header">
                  <h2>Recent Projects</h2>
                  <a href="#" class="view-all">View all</a>
               </div>
               <div class="card-grid-projects-tasks">
                  <div class="card">
                     <div class="card-header">
                        <h2>Website Redesign</h2>
                        <p>Redesign the company website</p>
                     </div>
                     <div class="card-content">
                        <div class="progress">
                           <span>Progress</span>
                           <div class="progress-bar">
                              <div class="progress-fill" style="width: 75%;"></div>
                           </div>
                        </div>
                        <div class="card-footer">
                           <p>Deadline: 2023-12-31</p>
                           <a href="#" class="view-link">View Project</a>
                        </div>
                     </div>
                  </div>
                  <div class="card">
                     <div class="card-header">
                        <h2>Mobile App Development</h2>
                        <p>Build a new mobile app for the company</p>
                     </div>
                     <div class="card-content">
                        <div class="progress">
                           <span>Progress</span>
                           <div class="progress-bar">
                              <div class="progress-fill" style="width: 45%;"></div>
                           </div>
                        </div>
                        <div class="card-footer">
                           <p>Deadline: 2024-03-31</p>
                           <a href="#" class="view-link">View Project</a>
                        </div>
                     </div>
                  </div>
                  <div class="card">
                     <div class="card-header">
                        <h2>ERP System Implementation</h2>
                        <p>Implement a new ERP system for the company</p>
                     </div>
                     <div class="card-content">
                        <div class="progress">
                           <span>Progress</span>
                           <div class="progress-bar">
                              <div class="progress-fill" style="width: 65%;"></div>
                           </div>
                        </div>
                        <div class="card-footer">
                           <p>Deadline: 2024-06-30</p>
                           <a href="#" class="view-link">View Project</a>
                        </div>
                     </div>
                  </div>
               </div>
            </section>
            <!-- Recent Tasks Section -->
            <section class="recent-section">
               <div class="section-header">
                  <h2>Recent Tasks</h2>
                  <a href="#" class="view-all">View all</a>
               </div>
               <div class="card-grid-projects-tasks">
                  <div class="card">
                     <div class="card-header">
                        <h2>Design homepage wireframe</h2>
                        <p>Create a wireframe for the homepage</p>
                     </div>
                     <div class="card-content">
                        <div class="progress">
                           <span>Progress</span>
                           <div class="progress-bar">
                              <div class="progress-fill" style="width: 90%;"></div>
                           </div>
                        </div>
                        <div class="card-footer">
                           <p>Due: 2023-11-15</p>
                           <a href="#" class="view-link">View Task</a>
                        </div>
                     </div>
                  </div>
                  <div class="card">
                     <div class="card-header">
                        <h2>Implement login functionality</h2>
                        <p>Integrate the login feature with the backend</p>
                     </div>
                     <div class="card-content">
                        <div class="progress">
                           <span>Progress</span>
                           <div class="progress-bar">
                              <div class="progress-fill" style="width: 75%;"></div>
                           </div>
                        </div>
                        <div class="card-footer">
                           <p>Due: 2023-12-01</p>
                           <a href="#" class="view-link">View Task</a>
                        </div>
                     </div>
                  </div>
                  <div class="card">
                     <div class="card-header">
                        <h2>Write technical documentation</h2>
                        <p>Document the API endpoints and data models</p>
                     </div>
                     <div class="card-content">
                        <div class="progress">
                           <span>Progress</span>
                           <div class="progress-bar">
                              <div class="progress-fill" style="width: 60%;"></div>
                           </div>
                        </div>
                        <div class="card-footer">
                           <p>Due: 2024-01-15</p>
                           <a href="#" class="view-link">View Task</a>
                        </div>
                     </div>
                  </div>
               </div>
            </section>
         </main>
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
      <script src="/alex-fynn-moritz-ipt8-2024/TaskMaster/js/script.js"></script>
   </body>
</html>
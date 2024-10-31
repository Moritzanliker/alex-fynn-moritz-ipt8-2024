<?php
session_start();
include("../php/connection.php");
include("../php/functions.php");

$user_data = check_login($con);
$user_id = $user_data['user_id'];
$project_id = $_GET['project_id'] ?? null;

if (!$project_id) {
    die("Project not found.");
}

// Fetch project details
$project_query = "SELECT project_name, description, status, due_date, created_at FROM project WHERE project_id = ?";
$stmt = $con->prepare($project_query);
$stmt->bind_param("i", $project_id);
$stmt->execute();
$project = $stmt->get_result()->fetch_assoc();

// Check if the current user is the owner of the project
$owner_query = "SELECT role FROM project_users WHERE project_id = ? AND user_id = ?";
$stmt = $con->prepare($owner_query);
$stmt->bind_param("ii", $project_id, $user_id);
$stmt->execute();
$stmt->bind_result($user_role);
$stmt->fetch();
$is_owner = ($user_role === 'owner'); // True if the user is the owner
$stmt->close();

// Fetch project members
$members_query = "SELECT u.user_id, u.username, pu.role FROM project_users pu
JOIN user u ON pu.user_id = u.user_id
WHERE pu.project_id = ?";
$stmt = $con->prepare($members_query);
$stmt->bind_param("i", $project_id);
$stmt->execute();
$members_result = $stmt->get_result();

// Fetch tasks for the project along with creation date, due date, and assigned user
$tasks_query = "
    SELECT 
        t.task_id, t.task_name, t.status, t.created_at, t.due_date, u.username AS assigned_user 
    FROM 
        task t 
    LEFT JOIN 
        user u ON t.assigned_user_id = u.user_id
    WHERE 
        t.project_id = ?";
$stmt = $con->prepare($tasks_query);
$stmt->bind_param("i", $project_id);
$stmt->execute();
$tasks_result = $stmt->get_result();


$tasks = [
    'pending' => [],
    'in_progress' => [],
    'completed' => []
];

while ($task = $tasks_result->fetch_assoc()) {
    $tasks[$task['status']][] = [
        'task_id' => $task['task_id'],
        'task_name' => $task['task_name'],
        'created_at' => $task['created_at'],
        'due_date' => $task['due_date'],
        'assigned_user' => $task['assigned_user'],
    ];
}


// Handle adding new members
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['assigned_usernames'])) {
    $usernames = explode(',', $_POST['assigned_usernames']);
    
    foreach ($usernames as $username) {
        $username = trim($username);
        
        // Find user ID based on username
        $user_query = "SELECT user_id FROM user WHERE username = ?";
        $stmt = $con->prepare($user_query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        
        if ($user) {
            $user_id = $user['user_id'];
            
            // Check if user is already assigned to the project
            $check_query = "SELECT * FROM project_users WHERE project_id = ? AND user_id = ?";
            $stmt = $con->prepare($check_query);
            $stmt->bind_param("ii", $project_id, $user_id);
            $stmt->execute();
            $existing_member = $stmt->get_result()->fetch_assoc();
            
            if (!$existing_member) {
                // Assign user to project
                $assign_query = "INSERT INTO project_users (project_id, user_id, role) VALUES (?, ?, 'member')";
                $stmt = $con->prepare($assign_query);
                $stmt->bind_param("ii", $project_id, $user_id);
                $stmt->execute();
            }
        }
    }
    
    // Refresh the page to show the updated members
    header("Location: project_view.php?project_id=" . $project_id);
    exit;
}

// Handle removing a member
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_user_id'])) {
    $remove_user_id = $_POST['remove_user_id'];
    
    $remove_query = "DELETE FROM project_users WHERE project_id = ? AND user_id = ?";
    $stmt = $con->prepare($remove_query);
    $stmt->bind_param("ii", $project_id, $remove_user_id);
    $stmt->execute();
    
    // Refresh the page to show the updated members
    header("Location: project_view.php?project_id=" . $project_id);
    exit;
}

// Handle deleting the project
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_project'])) {
    // Delete all entries related to this project in project_users table
    $delete_users_query = "DELETE FROM project_users WHERE project_id = ?";
    $stmt = $con->prepare($delete_users_query);
    $stmt->bind_param("i", $project_id);
    $stmt->execute();

    // Delete the project itself
    $delete_project_query = "DELETE FROM project WHERE project_id = ?";
    $stmt = $con->prepare($delete_project_query);
    $stmt->bind_param("i", $project_id);
    $stmt->execute();

    // Redirect to dashboard after deletion
    header("Location: ../pages/dashboard.php");
    exit;
}
// Handle task status update (drag-and-drop)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['task_id'], $_POST['new_status'])) {
    $task_id = $_POST['task_id'];
    $new_status = $_POST['new_status'];

    $update_query = "UPDATE task SET status = ? WHERE task_id = ?";
    $stmt = $con->prepare($update_query);
    $stmt->bind_param("si", $new_status, $task_id);
    if ($stmt->execute()) {
        echo "Success";
    } else {
        echo "Error updating task";
    }
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($project['project_name']); ?> - Project Details</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/styles.css">
    <style>

    .frame-container {
        display: flex;
        gap: 20px;
        justify-content: center;
        margin-top: 20px;
    }

    .frame {
        width: 250px;
        height: 400px; /* Fixed height for task frames */
        border: 1px solid var(--border-color);
        border-radius: var(--radius);
        padding: 10px;
        background-color: var(--card);
        color: var(--card-foreground);
        text-align: center;
        overflow-y: auto; /* Enables vertical scrolling */
    }

    .frame h5 {
        margin-bottom: 10px;
    }

    .task-item {
        width: 90%;
        background-color: var(--primary);
        color: white;
        padding: 10px;
        margin-bottom: 10px;
        cursor: move;
        border-radius: 5px;
        display: flex;
        align-items: center;
        justify-content: center;
        /* Prevent tasks from floating next to each other */
        display: block;
    }

    .progress {
        margin-top: 20px; /* Adds spacing above */
        margin-bottom: 20px; /* Adds spacing below */
        height: 32px; /* Desired height for the entire bar */
        background-color: var(--border-color); /* Sets the grey background */
        border-radius: var(--radius); /* Rounded corners */
        overflow: hidden; /* Ensures red part doesn't overflow */
    }

    .progress-bar {
        height: 100%; /* Ensures the colored bar fills the entire height */
        margin: 0; /* Removes any default margins */
        padding: 0; /* Removes any default padding */
        font-size: 16px; /* Adjusts text size */
        line-height: 32px; /* Centers the text vertically */
        background-color: var(--primary); /* Sets the color from primary variable */
        color: white; /* Sets text color from primary-acc variable */
        border-radius: var(--radius) 0 0 var(--radius); /* Matches the container’s corners */
    }
    /* Override Bootstrap's default background and text colors for list-group-item */
.list-group-item {
    background-color: var(--card); /* Use your custom background color */
    color: var(--card-foreground); /* Use your custom text color */
    border-color: var(--border-color); /* Use custom border color */
}

/* Change the badge background and text colors */
.badge-primary {
    background-color: var(--primary); /* Use custom primary color */
    color:white; /* Use custom accessible color (typically white or contrasting color) */
}

/* Optional: Customize the Kick button */
.btn-danger {
    background-color: var(--primary); /* Use primary color for button */
    border-color: var(--primary); /* Use primary color for border */
    color: white; /* Use accessible color for text */
}

/* Ensure hover states match your theme */
.list-group-item:hover {
    background-color: var(--muted); /* Adjust if a hover effect is needed */
}

.btn-danger:hover {
    background-color: var(--primary-acc); /* Optional: Adjust color on hover */
    color: var(--card); /* Ensure text contrasts with the hover color */
}


</style>

</head>
<body>

<!-- Navbar -->
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
                <nav class="nav">
                    <a href="../php/logout.php" class="nav-link">Logout</a>
                </nav>
            </div>
        </div>
    </center>
</header>

<div class="container mt-5">
    <div class="project-details">
        <h1 class="display-4"><?php echo htmlspecialchars($project['project_name']); ?></h1>
        <p class="lead"><?php echo htmlspecialchars($project['description']); ?></p>
        <div class="project-meta mt-4 mb-5">
            <span class="badge badge-info">Status: <?php echo ucfirst($project['status']); ?></span>
            <span class="badge badge-secondary">Created: <?php echo date('F d, Y', strtotime($project['created_at'])); ?></span>
            <span class="badge badge-warning">Due Date: <?php echo date('F d, Y', strtotime($project['due_date'])); ?></span>
        </div>

        <div class="progress mt-4 mb-4" style="height: 24px;">
            <div id="progress-bar" class="progress-bar bg-danger" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
        </div>


        <!-- New Task Button -->
        <a href="../pages/new_task.php" class="btn btn-success mb-4">+ Add New Task</a>

        <!-- Task Board -->
        <div class="frame-container">
            <?php foreach (['pending' => 'Pending', 'in_progress' => 'In Progress', 'completed' => 'Completed'] as $status_key => $status_label): ?>
                <div class="frame" id="frame-<?php echo $status_key; ?>" ondrop="drop(event, '<?php echo $status_key; ?>')" ondragover="allowDrop(event)">
                    <h5><?php echo $status_label; ?></h5>
                    <?php foreach ($tasks[$status_key] as $task): ?>
                        <div class="task-item" id="task-<?php echo $task['task_id']; ?>" draggable="true" ondragstart="drag(event)">
                            <strong><?php echo htmlspecialchars($task['task_name']); ?></strong><br>
                            <!-- Creation and Due Date -->
                            <small>
                                <?php echo date('d.m.Y', strtotime($task['created_at'])); ?> - 
                                <?php echo date('d.m.Y', strtotime($task['due_date'])); ?>
                            </small><br>
                            <!-- Assigned User -->
                            <small>Assigned to: <?php echo htmlspecialchars($task['assigned_user'] ?? 'Unassigned'); ?></small>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Project Team Members and Actions -->
    
        <h2 class="mt-5">Team Members</h2>
        <div class="list-group mb-4">
            <?php while ($member = $members_result->fetch_assoc()) { ?>
                <div class="list-group-item d-flex justify-content-between align-items-center">
                    <span><?php echo htmlspecialchars($member['username']); ?></span>
                    <span class="badge badge-pill badge-primary"><?php echo ucfirst($member['role']); ?></span>
                    <?php if ($is_owner && $member['role'] !== 'owner') { ?>
                        <form action="" method="POST" class="ml-2">
                            <input type="hidden" name="remove_user_id" value="<?php echo $member['user_id']; ?>">
                            <button type="submit" class="btn btn-danger btn-sm">Kick</button>
                        </form>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>

        <!-- Add Member Form -->
        <form action="" method="POST" class="form-inline mb-5">
            <label for="assigned_usernames" class="mr-2">Assign Users (comma separated):</label>
            <input type="text" id="assigned_usernames" name="assigned_usernames" class="form-control mr-2">
            <button type="submit" class="btn btn-primary">Add</button>
        </form>

        <!-- Project Actions -->
        <a href="../pages/dashboard.php" class="btn btn-outline-primary">Back to Dashboard</a>
        <?php if ($is_owner) { ?>
            <form action="" method="POST" class="mt-5">
                <input type="hidden" name="delete_project" value="1">
                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this project?');">Delete Project</button>
            </form>
        <?php } ?>
    </div>
</div>

<!-- Footer -->
<footer class="footer --primary text-center mt-5 py-3">
    <div class="container">
        <p>© 2024 TaskMaster. All rights reserved.</p>
        <nav>
            <a href="#">Terms</a> | <a href="#">Privacy</a> | <a href="#">Support</a>
        </nav>
    </div>
</footer>

<script>

const button = document.getElementById('toggle-darkmode');
const body = document.body;

button.addEventListener('click', () => {
    body.classList.toggle('darkmode');
});

    function allowDrop(event) {
    event.preventDefault();
}

function drag(event) {
    event.dataTransfer.setData("text", event.target.id);
}
document.addEventListener("DOMContentLoaded", function() {
        updateProgressBar();
    });

function drop(event, newStatus) {
    event.preventDefault();
    const taskId = event.dataTransfer.getData("text");
    const taskElement = document.getElementById(taskId);

    // Append the task element at the end of the frame to keep order
    const targetFrame = event.currentTarget;
    targetFrame.appendChild(taskElement);

    // Send AJAX request to update task status in the database
    const formData = new FormData();
    formData.append("task_id", taskId.replace("task-", ""));
    formData.append("new_status", newStatus);

    fetch("project_view.php?project_id=<?php echo $project_id; ?>", {
        method: "POST",
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        console.log(data);
        if (data.trim() === "Success") {
            console.log(`Task ${taskId} moved to ${newStatus}`);
        } else {
            console.error("Failed to update task status");
        }
    })
    .catch(error => console.error("Error:", error));
}
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

function drop(event, newStatus) {
    event.preventDefault();
    const taskId = event.dataTransfer.getData("text");
    const taskElement = document.getElementById(taskId);

    // Append the task element at the end of the frame to keep order
    const targetFrame = event.currentTarget;
    targetFrame.appendChild(taskElement);

    // Send AJAX request to update task status in the database
    const formData = new FormData();
    formData.append("task_id", taskId.replace("task-", ""));
    formData.append("new_status", newStatus);

    fetch("project_view.php?project_id=<?php echo $project_id; ?>", {
        method: "POST",
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        if (data.trim() === "Success") {
            // Update the progress bar after successful task status change
            updateProgressBar();
        } else {
            console.error("Failed to update task status");
        }
    })
    .catch(error => console.error("Error:", error));
}


</script>

</body>
</html>

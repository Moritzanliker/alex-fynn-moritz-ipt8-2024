<?php
session_start();
include("../php/connection.php");
include("../php/functions.php");
if($_SERVER['REQUEST_METHOD'] == "POST")
{
    $username = $_POST['username'];
    $password = $_POST['password'];

    if(!empty($username) && !empty($password) && !is_numeric($username))
    {
        //save to database
        $user_id = random_num(20);
        $query = "insert into user (username,password) values ('$username','$password')";

        mysqli_query($con, $query);

        header("Location: ../pages/login.php");
        die;
    }else
    {
        echo "<div class='error'>Please enter some valid information!</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f5f5f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .container {
            background-color: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0px 8px 30px rgba(0, 0, 0, 0.1);
            max-width: 360px;
            width: 100%;
        }

        h2, header {
            text-align: center;
            color: #333;
            font-size: 1.8rem;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: border 0.3s ease;
        }

        input[type="text"]:focus, input[type="password"]:focus {
            border-color: #28a745;
            outline: none;
        }

        button, input[type="submit"] {
            width: 100%;
            padding: 12px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover, input[type="submit"]:hover {
            background-color: #218838;
        }

        .switch {
            text-align: center;
            margin-top: 15px;
            font-size: 0.9rem;
        }

        .switch a {
            color: #007bff;
            text-decoration: none;
            font-weight: 500;
        }

        .switch a:hover {
            text-decoration: underline;
        }

        .error {
            text-align: center;
            color: red;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>Signup</header>
        <form method="post">
            <input id="text" type="text" name="username" placeholder="Enter your username" required>
            <input id="password" type="password" name="password" placeholder="Enter your password" required>
            <input type="submit" value="Signup">
        </form>
        <div class="switch">
            Already have an account? <a href="../pages/login.php">Login</a>
        </div>
        <div id="error-message" class="error-message"></div>
    </div>
</body>
</html>

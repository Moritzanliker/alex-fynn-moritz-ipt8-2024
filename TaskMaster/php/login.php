<?php 

session_start();

	include("connection.php");
	include("functions.php");


	if($_SERVER['REQUEST_METHOD'] == "POST")
	{
		//something was posted
		$username = $_POST['username'];
		$password = $_POST['password'];

		if(!empty($username) && !empty($password) && !is_numeric($username))
		{

			//read from database
			$query = "select * from user where username = '$username' limit 1";
			$result = mysqli_query($con, $query);

			if($result)
			{
				if($result && mysqli_num_rows($result) > 0)
				{

					$user_data = mysqli_fetch_assoc($result);
					
					if($user_data['password'] === $password)
					{

						$_SESSION['user_id'] = $user_data['user_id'];
						header("Location: index.php");
						die;
					}
				}
			}
			
			echo "wrong username or password!";
		}else
		{
			echo "wrong username or password!";
		}
	}

?>


<!DOCTYPE html>
<html>
<head>
	<title>Login</title>
</head>
<body>
<style>
    body {
      background-color: #f2f2f2;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      font-family: Arial, sans-serif;
    }
    
    .container {
      background-color: white;
      padding: 40px;
      border-radius: 10px;
      box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);
      width: 300px;
    }

    h2 {
      text-align: center;
      color: #333;
    }

    input[type="text"], input[type="password"] {
      width: 100%;
      padding: 10px;
      margin: 10px 0;
      border-radius: 5px;
      border: 1px solid #ccc;
    }

    button {
      width: 100%;
      padding: 10px;
      background-color: #28a745;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    button:hover {
      background-color: #218838;
    }

    .switch {
      text-align: center;
      margin-top: 20px;
    }

    .switch a {
      color: #007bff;
      text-decoration: none;
    }

    .switch a:hover {
      text-decoration: underline;
    }

    .error {
      color: red;
      text-align: center;
    }
  </style>
  
	<div id="box">
		<div class="container">
    	<div class="login form">
     		<header>Login</header>
     	<form method="post">
        		<input id="text" type="text" name="username" placeholder="Enter your email">
        		<input id="text" type="password" name="password" placeholder="Enter your password">
        	<div id="loginError" class="error-message"></div>
        		<input type="button" class="button" value="Login" onclick="login()">
      	</form>
     	 <div class="signup">
        	<span class="signup">Don't have an account?
				<a href="signup.php">Click to Signup</a><br><br>
        	</span>
      	 </div>
	  	</div>
	  	</div>
    </div>

</body>
</html>
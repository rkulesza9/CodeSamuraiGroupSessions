<?php
	session_start();

	function handle_login_form(){
		# isset function checks if the post variable with the given name exists
		# whatever the form is going to do, you will always check that the login button was pressed first before continuing
		$login_btn_clicked = isset($_POST["login-btn"]);
		if($login_btn_clicked != true){
			return false;
		}

		# this code will only execute if the login button was clicked
		# since the login button was clicked we know that the username and password post variables are set
		# store the value of those post variables for convenience
		$username = $_POST['username'];
		$password = $_POST['password'];

		# attempt to login
		# if login is successful > store username, primary key in session 
		# if login failed, indicate that to user
		if($db_id = login($username, $password)){
			handle_login_successful($db_id, $username);
		} else {
			handle_login_failure();
		}
		return true;
	}

	function handle_login_successful($db_id, $username){
		$_SESSION["id"] = $db_id;
		$_SESSION["username"] = $username;
		echo "<h1>Login Successful</h1>";
		echo "<p>You successfully logged in as $username!</p>";
	}

	function handle_login_failure(){
		echo "<h1>Login Failed</h1>";
		echo "<p>Your username or password was incorrect</p>";
		echo "<a href='index.html'>Try Again</a>";
	}

	function login($username, $password){
		include 'dbconfig.php';
		# I will use mysali prepare instead of mysqli query
		# this has built in protection against sql injection
		# ? represent a value you are inputting into the mysqli query
		# bind_param() binds the value of a variable to a ? in the original query
		$mysqli = new mysqli($db_server, $db_username, $db_password, $db_database);
		$query = "SELECT id FROM user where username=? and password=?";
		$stmt = $mysqli->prepare($query);
		$stmt->bind_param("ss", $username, $password);
		$stmt->bind_result($db_id);
		$stmt->execute();
		$stmt->fetch();
		$stmt->close();

		# if $db_id is not set to some value, then the query has an empty result
		# otherwise, $db_id is set to the primary key value associated with the user-inputted username, password
		if(isset($db_id)){
			return $db_id;
		} else {
			return false;
		}
	}

	function check_if_logged_in(){
		if(isset($_SESSION["id"])){
			$id = $_SESSION["id"];
			$username = $_SESSION["username"];
			echo "<h1>You Are Logged In!</h1>";
			echo "<p>Welcome, $username!</p>";

			echo "<form action='#' method='POST'>";
			echo "<input type='submit' name='logout-btn' value='logout' />";
			echo "</form>";
		} else {
			echo "<h1>You Are Not Logged In!</h1>";
			echo "<p><a href='index.html'>Click Here</a> if you want to log in!</p>";
		}
	}

	function handle_logout_form(){
		$logout_button_clicked = isset($_POST["logout-btn"]);
		if($logout_button_clicked){
			session_destroy();
			echo "<h1>You Logged Out Successfully!</h1>";
			echo "<p><a href='index.html'>Click Here</a> if you want to log back in!</p>";
			return true;
		}
		return false;
	}

	if(!handle_logout_form() and !handle_login_form()) check_if_logged_in();

?>
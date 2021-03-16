<?php

	session_start();	
	function LoginStatusMessage_Cookie(){
		if(isset($_POST['submit'])){
			$username = $_POST['username'];
			$password = $_POST['password'];

			$one_day = 86400;
			setcookie("username", $username, time() + $one_day);

			echo "<h1>A Cookie was created: username -> $username</h1>";
		} elseif(isset($_COOKIE["username"])) {
			$username = $_COOKIE["username"]; 
			?>

			<script>
				var username = getCookie("username");
				alert(`Javascript can access the cokie: username -> ${username}`);
			</script>

			<?php
			echo "<h1>The cookie username -> $username was found.</h1>";
		}
	}

	function LoginStatusMessage_Session(){
		if(isset($_POST['submit'])){

			$username = $_POST['username'];
			$password = $_POST['password'];

			$_SESSION["username"] = $username;

			echo "<h1>A Session Variable was created: username -> $username</h1>";
		} elseif(isset($_SESSION["username"])) {
			$username = $_SESSION["username"]; 
			?>

			<script>
				var session_id = getCookie("PHPSESSID");
				alert(`Javascript not able to access session variables. It can only access the session_id which is saved as a cookie. PHPSESSID = ${session_id}`);
			</script>

			<?php
			echo "<h1> The session variable username -> $username was found.</h1>";
		}
	}

	function RemoveCookies(){
		if(isset($_POST["delete_cookie"]) && isset($_COOKIE['username'])){
			$username = $_COOKIE['username'];
			setcookie("username", "", -1);
			?>
			<script>
				location.reload();
			</script>
			<?php
		}
	}

	function DestroySession(){
		if(isset($_POST["delete_session"]) && isset($_SESSION['username'])){
			$username = $_SESSION['username'];
			session_destroy();
			?>
			<script>
				location.reload();
			</script>
			<?php
		}
	}
?>
<html>
	<head>
		<title>Cookies &amp; Sessions</title>
		<style>
			table {
				border: 1px solid black;
			}
		</style>
		<script>
			function setCookie(cname, cvalue, exdays) {
			  var d = new Date();
			  d.setTime(d.getTime() + (exdays*24*60*60*1000));
			  var expires = "expires="+ d.toUTCString();
			  document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
			}
			function getCookie(cname) {
			  var name = cname + "=";
			  var decodedCookie = decodeURIComponent(document.cookie);
			  var ca = decodedCookie.split(';');
			  for(var i = 0; i <ca.length; i++) {
			    var c = ca[i];
			    while (c.charAt(0) == ' ') {
			      c = c.substring(1);
			    }
			    if (c.indexOf(name) == 0) {
			      return c.substring(name.length, c.length);
			    }
			  }
			  return "";
			}
		</script>
	</head>
	<body>
		<h1><?php RemoveCookies(); ?></h1>
		<h1><?php DestroySession(); ?></h1>
		<h1><?php LoginStatusMessage_Cookie(); ?></h1>
		<h1><?php LoginStatusMessage_Session(); ?></h1>
		<table>
			<form action='CookiesAndSessions.php' method='post'>
				<tr>
					<th>Username:</th>
					<td><input type='text' name='username'></td>
				</tr>

				<tr>
					<th>Password:</th>
					<td><input type='password' name='password'></td>
				</tr>

				<tr>
					<td><input type='submit' value='Login' name='submit'></td>
				</tr>
			</form>
		</table>

		<br>

		<table>
			<form action='CookiesAndSessions.php' method='post'>
				<tr>
					<td><input type='submit' value='Delete Cookie' name='delete_cookie'></td>
					<td><input type='submit' value='Delete Session' name='delete_session'></td>
				</tr>
			</form>
		</table>
	</body>
	<footer>
	</footer>
</html>
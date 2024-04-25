<?=unregistered_header("Register")?>


<html>
	<head>
		<meta charset="utf-8">
		<title>Login</title>
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body>
		<div class="form-content">
			<h1>Login</h1>
			<p class="form-redirect">  Not registered? <a href="index.php?page=register">Register here</a></p>
			<form action="index.php?page=authenticate" method="post">
				<input type="text" name="username" placeholder="Username" id="username" required>
				<input type="password" name="password" placeholder="Password" id="password" required>
				<input type="submit" value="Login">
			</form>
		</div>
	</body>
</html>


<?=footer()?>

<?=unregistered_header("Register")?>


<div class="form-content">
	<h1>Login</h1>
	<p class="form-redirect">  Not registered? <a href="index.php?page=register">Register here</a></p>
	<form action="index.php?page=authenticate" method="post">
		<input type="text" name="username" placeholder="Username" id="username" required>
		<input type="password" name="password" placeholder="Password" id="password" required>
		<input type="submit" value="Login">
	</form>
</div>


<?=footer()?>

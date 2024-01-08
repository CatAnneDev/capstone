<?php
// Visitor header, if user is not logged in
function unregistered_header($title) 
{
    echo <<<EOT
    <!DOCTYPE html>
    <html>
        <head>
            <meta charset="utf-8">
            <title>$title</title>
            <link rel="stylesheet" href="style.css" type="text/css"/>
            <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
        </head>
        <body>
            <header>
                <div class="content-wrapper">
                    <nav>
                    </nav>
                    <h1> </h1>
                    <div class="link-icons nav">
                        <a href="index.php?page=register">Register</a>
                        <a href="index.php?page=login">Login</a>
                    </div>
                </div>
            </header>
            <main>
    EOT;
}

// Shop header, if user is logged in
function shop_header($title) 
{
echo <<<EOT
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>$title</title>
		<link rel="stylesheet" href="style.css" type="text/css"/>
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body>
        <header>
            <div class="content-wrapper">
                <nav>
                    <a href="index.php?page=files">Files</a>
                    <a href="index.php?page=profile">Profile</a>
                    <a href="index.php?page=management">Management</a>
                </nav>
                <h1> </h1>
                <div class="link-icons nav">
                    <a href="index.php?page=logout">Logout</a>
                </div>
            </div>
        </header>
        <main>
EOT;
}

// Shop footer
function shop_footer()
{
echo <<<EOT
        </main>
        <footer>
            <div class="content-wrapper">
                <p>Tutorial source:</p>
                <p>Adams, D. (2019, March 22). Shopping Cart System with PHP and MySQL. CodeShack. https://codeshack.io/shopping-cart-system-php-mysql/</p>
            </div>
        </footer>
    </body>
</html>
EOT;
}

// connect to MySQL database named shoppingcart via localhost
function pdo_connect_mysql() 
{
    $DATABASE_HOST = 'localhost';
    $DATABASE_USERNAME = 'root';
    $DATABASE_PASSWORD = '';
    $DATABASE_NAME = 'capstone';

    // if there is an error with the connection, exit and display error
    try 
    {
    	return new PDO('mysql:host=' . $DATABASE_HOST . ';dbname=' . $DATABASE_NAME . ';charset=utf8', $DATABASE_USERNAME, $DATABASE_PASSWORD);
        echo "Connection to database successful.";
    } 
    catch (PDOException $exception) 
    {
    	exit('Failed to connect to database!');
    }
}

// redirects logged out users to the login page
function logged_out_redirect()
{
    if ($_SESSION['loggedin'] != true)
    {
        header('Location: index.php');
    }
}
?>

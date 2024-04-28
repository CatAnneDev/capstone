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

// Navigation header, if user is logged in
// inquriy views only inventory and history
function nav_inquiry($title) 
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
                        <a href="index.php?page=inventory">Inventory</a>
                        <a href="index.php?page=history">History</a>
                    </nav>
                    <h1> </h1>
                    <div class="nav">
                        <a href="index.php?page=logout">Logout</a>
                    </div>
                </div>
            </header>
            <main>
    EOT;
}

// Navigation header, if user is logged in
// employee views inventory, history, and orders
function nav_employee($title) 
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
                        <a href="index.php?page=inventory">Inventory</a>
                        <a href="index.php?page=history">History</a>
                        <a href="index.php?page=form_delivery">Delivery Form</a>
                        <a href="index.php?page=form_purchase">Purchase Form</a>
                    </nav>
                    <h1> </h1>
                    <div class="nav">
                        <a href="index.php?page=logout">Logout</a>
                    </div>
                </div>
            </header>
            <main>
    EOT;
}

// Navigation header, if user is logged in
// manager views inventory, history, orders, and users
function nav_manager($title) 
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
                        <a href="index.php?page=inventory">Inventory</a>
                        <a href="index.php?page=history">History</a>
                        <a href="index.php?page=form_delivery">Delivery Form</a>
                        <a href="index.php?page=form_purchase">Purchase Form</a>
                        <a href="index.php?page=users">Users</a>
                    </nav>
                    <h1> </h1>
                    <div class="nav">
                        <a href="index.php?page=logout">Logout</a>
                    </div>
                </div>
            </header>
            <main>
    EOT;
}

// Navigation header, if user is logged in
// manager views inventory, history, orders, and users
function nav_groupadmin($title) 
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
                        <a href="index.php?page=inventory">Inventory</a>
                        <a href="index.php?page=history">History</a>
                        <a href="index.php?page=form_delivery">Delivery Form</a>
                        <a href="index.php?page=form_purchase">Purchase Form</a>
                        <a href="index.php?page=users">Users</a>
                    </nav>
                    <h1> </h1>
                    <div class="nav">
                        <a href="index.php?page=logout">Logout</a>
                    </div>
                </div>
            </header>
            <main>
    EOT;
}

function nav_header($title) 
{
    // redirect non-users to login
    if ($_SESSION["loggedin"] != true)
    {
        header("Location: index.php");
    }
    elseif ($_SESSION["permission"] == "Inquiry")
    {
        nav_inquiry($title);
    }
    elseif ($_SESSION["permission"] == "Employee")
    {
        nav_employee($title);
    }
    elseif ($_SESSION["permission"] == "Manager")
    {
        nav_manager($title);
    }
    elseif ($_SESSION["permission"] == "GroupAdmin")
    {
        nav_groupadmin($title);
    }
}


// site footer
function footer()
{
echo <<<EOT
        </main>
        <footer>
            <div class="content-wrapper">
            </div>
        </footer>
    </body>
</html>
EOT;
}

// connect to MySQL database named capstone in localhost
function pdo_connect_mysqli() 
{
    $DATABASE_HOST = 'localhost';
    $DATABASE_USERNAME = 'root';
    $DATABASE_PASSWORD = '';
    $DATABASE_NAME = 'capstone';

    # if there is an error with the connection, exit and display error
    $conn = mysqli_connect($DATABASE_HOST, $DATABASE_USERNAME, $DATABASE_PASSWORD, $DATABASE_NAME);
    if ( mysqli_connect_errno() ) 
    {
        exit("Failed to connect to MySQL: " . mysqli_connect_error());
    }
    return $conn;
}


/*
function strip_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
function strip_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
# strip inputs of injection characters
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $username = strip_input($_POST["username"]);
    $password = strip_input($_POST["password"]);
}
*/
?>

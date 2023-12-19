<?php
// December 16 2023
// Catherine Seymour

function header($page_title)
{
    echo <<<EOT
    <!DOCTYPE html>
    <html lang="en-US">
        <head>
            <meta charset="utf-8">>
            <title>$page_title</title>
            <link rel="stylesheet" href="style.css" type="text/css" />
            <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css" />
        </head>

        <body>
            <header>
                <div class="content-wrapper">
                    <h1>$page_title</h1>
                    <div class="link-icons">
                        <a href="index.php?page=register">
                            <i class="fas fa-user-plus"></i>
                        </a>
                        <a href="index.php?page=login">
                            <i class="fas fa-sign-in-alt"></i>
                        </a>
                    </div>
                </div>
            </header>
        </body>
    EOT;
}


function footer()
{
    echo <<<EOT
        <footer>
            <div class="wrapper">
                <p> </p>
            </div>>
        </footer>
    </html>
    EOT;
}
?>

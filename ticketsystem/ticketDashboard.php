<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Ticket Dahboard</title>
    </head>
    <body>
        <h1>ticket dashboard</h1>
        <?php
            session_start();
            $fname = htmlspecialchars($_SESSION['fName']);
            echo $fname;
        ?>
    </body>
</html>
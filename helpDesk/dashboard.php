<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Dashboard</title>
    </head>
    <body>
        <?php
        // ?Verifies the employee if log in if not its send to the employee login page
            session_start();
            if(isset($_SESSION['empId'])){
                $fname = htmlspecialchars($_SESSION['fName']);
                echo $fname;
            }
            else{
                header("location: http://localhost/finalProyect/account/empLogin.php");
                exit();
            }
        ?>
        <nav>
            <a href="../includes/logout.php">Log Out</a>
        </nav>
    </body>
</html>
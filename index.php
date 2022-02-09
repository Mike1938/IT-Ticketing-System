<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="./static/css/styles.css">
        <title>IT On Demand</title>
    </head>
    <body>
        <nav>
            <a href="http://localhost/finalProyect/index.php">Home</a>
            <?php
                session_start();
                if(isset($_SESSION['empId'])){
                    echo "<a href='http://localhost/finalProyect/helpDesk/dashboard.php'>Dashboard</a>";
                    echo "<a href='./includes/loginFuncs.php'>Log Out</a>";
                }
                elseif(isset($_SESSION['cliId'])){
                    echo "<a href='http://localhost/finalProyect/ticketSystem/ticketDashboard.php'>Dashboard</a>";
                    echo "<a href='./includes/loginFuncs.php'>Log Out</a>";
                }
                else{
                    echo"<a href='http://localhost/finalProyect/account/cliCompleteRegistration.php'>Client Sign Up</a>";
                }
            ?>
        </nav>
        <section id="landing">
            <div id="title">
                <h1>IT On Demand</h1>
                <p>Giving you a helping hand on tech</p>
            </div>
            <div id="slogan">
                
            </div>
            <div id="buttons">
                <a href="http://localhost/finalProyect/account/clientLogin.php"><button>Client Log In</button></a>
                <a href="http://localhost/finalProyect/account/empLogin.php"><button>Employee Log In</button></a>
            </div>
        </section>
    </body>
</html>
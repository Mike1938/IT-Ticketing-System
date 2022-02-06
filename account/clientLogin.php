<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../static/css/styles.css">
        <title>Client Login</title>
    </head>
    <body>
        <?php
            $userErr = $passErr = "";
            if(isset($_GET["userErr"])){
                $userErr = $_GET["userErr"];
            }
            if(isset($_GET["pwdErr"])){
                $passErr = $_GET["pwdErr"];
            }
        ?>
        <nav>
            <a href="http://localhost/finalProyect/index.php">Home</a>
            <a href="http://localhost/finalProyect/account/clientSignup.php">Sign Up</a>
        </nav>
        <h1 class="loginHeader">Client Log In</h1>
        <form id="cliLogFrm" action="../includes/loginFuncs.php" method="POST">
            <div class="idContent">
                <label for="id">ID <span class="error"><?php echo $userErr?></span></label>
                <input class="styleInput" name="id" id="id" type="text" placeholder="ID">
            </div>
            <div class="passContent">
                <label for="pwd">Password <span class="error"><?php echo $passErr?></span></label>
                <input class="styleInput" name="pwd" id="pwd" type="password" placeholder="password">
            </div>
            <button name="clientLogin">Log in</button>
        </form>
    </body>
</html>
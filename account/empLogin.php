<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../static/css/styles.css">
        <title>Employee Log in</title>
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
            <a href="http://localhost/finalProyect/account/empSignup.php">Sign Up</a>
        </nav>
        <h1 class="loginHeader">Employee Log In</h1>
        <form id="empLogFrm" action="../includes/loginFuncs.php" method="POST">
            <div class="idContent">
                <label for="id">Employee Id <span class="error"><?php echo $userErr?></span></label>
                <input class="styleInput" id="id" name="id" type="text" placeholder="Employee ID">
            </div>
            <div class="passContent">
                <label for="pwd">Password <span class="error"><?php echo $passErr?></label>
                <input class="styleInput" id="password" name="pwd" type="password" placeholder="Password">
            </div>
            <button name="empLogin">Log in</button>
        </form>
        
    </body>
</html>
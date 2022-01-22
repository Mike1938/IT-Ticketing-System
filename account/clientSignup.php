<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Client Signup</title>
    </head>
    <body>
        <?php
        $fNameErr = $lNameErr = $compNameErr = $emailErr = $phoneErr = $pwdErr = "";
        if(isset($_GET["fName"])){
           $fNameErr = $_GET["fName"];
        }
        if(isset($_GET["lName"])){
            $lNameErr = $_GET["lName"];
        }
        if(isset($_GET["compName"])){
           $compNameErr = $_GET["compName"];
        }
        if(isset($_GET["email"])){
           $emailErr = $_GET["email"];
        }
        if(isset($_GET["phone"])){
           $phoneErr = $_GET["phone"];
        }
        if(isset($_GET["pwd"])){
           $pwdErr = $_GET["pwd"];
        }
        ?>
        <h1>New client Registration</h1>
        <section>
            <form action="../includes/signupFuncs.php" method="POST">
                <label for="fName">First Name <span class="error"><?php echo $fNameErr?></span></label>
                <input class="styleInput" id="fName" name="fName" type="text" placeholder="First Name">
                <label for="lName">Last Name <span class="error"><?php echo $lNameErr?></span></label>
                <input class="styleInput" id="lName" name="lName" type="text" placeholder="Last Name">
                <label for="compName">Company Name <span class="error"><?php echo $compNameErr?></span></label>
                <input class="styleInput" id="compName" name="compName" type="text" placeholder="Company Name">
                <label for="email">Email <span class="error"><?php echo $emailErr?></span></label>
                <input class="styleInput" id="email" name="email" type="email" placeholder="Email">
                <label for="phone">Phone Number <span class="error"><?php echo $phoneErr?></span></label>
                <input class="styleInput" id="phone" name="phone" type="text" placeholder="Phone Number">
                <label for="pwd">PassWord <span class="error"><?php echo $pwdErr?></span></label>
                <input class="styleInput" id="pwd" name="pwd" type="password" placeholder="Password">
                <button name="clientSign">Register</button>
            </form>
        </section>
    </body>
</html>
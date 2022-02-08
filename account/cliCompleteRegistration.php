<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../static/css/styles.css">
        <title>Complete Registration</title>
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
        ?>
        <div>
            <p>Find account</p>
            <?php
                $tempId = "";
                session_start();
                if(isset($_SESSION['tempId'])){
                    $tempId = $_SESSION['tempId'];
                    echo "
                        <p>ID was found</p>
                        <p>ID: {$tempId}</p>
                        <a href='../includes/logout.php'>Find Again</a>
                    ";
                }
                else{
                    echo"
                        <form action='../includes/signupFuncs.php' method='POST'>
                            <div id='cliRegLeft'>
                                <label for='fName'>First Name <span class='error'>{$fNameErr}</span></label>
                                <input class='styleInput' id='fName' name='fName' type='text' placeholder='First Name'>
                                <label for='lName'>Last Name <span class='error'>{$lNameErr}</span></label>
                                <input class='styleInput' id='lName' name='lName' type='text' placeholder='Last Name'>
                                <label for='compName'>Company Name <span class='error'>{$compNameErr}</span></label>
                                <input class='styleInput' id='compName' name='compName' type='text' placeholder='Company Name'>
                            </div>
                            <div id='cliRegRight'>
                                <label for='email'>Email <span class='error'>{$emailErr}</span></label>
                                <input class='styleInput' id='email' name='email' type='email' placeholder='Email'>
                                <label for='phone'>Phone Number <span class='error'>{$phoneErr}</span></label>
                                <input class='styleInput' id='phone' name='phone' type='text' placeholder='Phone Number'>
                            </div>
                            <button name='findId'>Find User Id</button>
                        </form>
                    ";
                }
            ?>
        </div>
        <div>
            <p>Create User Name and Password</p>
            <form method="POST" action="../includes/signupFuncs.php">
                <input name="userName" type="text">
                <input name='pwd' type="password">
                <button name="createPassUser">Create</button>
            </form>
        </div>
    </body>
</html>
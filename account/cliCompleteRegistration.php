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
        <nav>
            <a href="http://localhost/finalProyect/index.php">Home</a>
            <a href="http://localhost/finalProyect/account/clientLogin.php">Log In</a>
        </nav>
        <?php
            $fNameErr = $lNameErr = $compNameErr = $emailErr = $phoneErr = $pwdErr =  $accountErr = "";
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
            if(isset($_GET['account'])){
                $accountErr = $_GET['account'];
            }
        ?>
        <div id="findAccount">
            <p>Find account</p>
            <?php
                $tempId = "";
                session_start();
                if(isset($_SESSION['tempId'])){
                    $tempId = $_SESSION['tempId'];
                    echo "
                        <div id='foundId'>
                            <p>ID was found ID: {$tempId}</p>
                            <a href='../includes/logout.php'><button>Find ID Again</button></a>
                        </div>
                    ";
                }
                else{
                    echo"
                        <form id='regisFrm' action='../includes/signupFuncs.php' method='POST'>
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
        <div id="createUserPass">
            <p class="dashTitle">Create User Name and Password</p>
            <p class="error"><?php echo $accountErr?></p>
            <form method="POST" action="../includes/signupFuncs.php">
                <label for="userName">User Name</label>
                <input id="userName" class='styleInput' name="userName" type="text" placeholder="User Name">
                <label for="pwd">Password</label>
                <input id="pwd" class='styleInput' name='pwd' type="password" placeholder="password">
                <button name="createPassUser">Create</button>
            </form>
        </div>
    </body>
</html>
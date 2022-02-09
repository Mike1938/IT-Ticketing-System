<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../static/css/styles.css">
        <title>Client Signup</title>
    </head>
    <body>
        <?php
            // ?Verifies the employee if log in if not its send to the employee login page
            session_start();
            $uid = $fName = "";
            if(isset($_SESSION['empId'])){
                $fname = htmlspecialchars($_SESSION['fName']);
                $uid = htmlspecialchars($_SESSION['empId']);
            }
            else{
                header("location: http://localhost/finalProyect/account/empLogin.php");
                exit();
            }
            require_once "../dbConection.php";
            $conn = new mysqli($hn,$un,$pw, $db);
            if($conn->connect_error) die("There was a fatal error");
      
        // ? Waiting to recieve errors from form
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
        <nav>
            <a href="http://localhost/finalProyect/index.php">Home</a>
            <a href="http://localhost/finalProyect/helpDesk/dashboard.php">Dashboard</a>
            <a href="../includes/logout.php">Log Out</a>
        </nav>
        
        <section class="formSect">
            <h1>New client Registration</h1>
            <form id="regisFrm" action="../includes/signupFuncs.php" method="POST">
                <div id="cliRegLeft">
                    <label for="fName">First Name <span class="error"><?php echo $fNameErr?></span></label>
                    <input class="styleInput" id="fName" name="fName" type="text" placeholder="First Name">
                    <label for="lName">Last Name <span class="error"><?php echo $lNameErr?></span></label>
                    <input class="styleInput" id="lName" name="lName" type="text" placeholder="Last Name">
                    <label for="compName">Company Name <span class="error"><?php echo $compNameErr?></span></label>
                    <input class="styleInput" id="compName" name="compName" type="text" placeholder="Company Name">
                </div>
                <div id="cliRegRight">
                    <label for="email">Email <span class="error"><?php echo $emailErr?></span></label>
                    <input class="styleInput" id="email" name="email" type="email" placeholder="Email">
                    <label for="phone">Phone Number <span class="error"><?php echo $phoneErr?></span></label>
                    <input class="styleInput" id="phone" name="phone" type="text" placeholder="Phone Number">
                </div>
                <button name="clientSign">Register</button>
            </form>
        </section>
    </body>
</html>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Employee Signup</title>
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
            if(isset($_GET["position"])){
            $compNameErr = $_GET["position"];
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
        <h1>Employee Sign Up</h1>
        <form action="../includes/signupFuncs.php" method="POST">
            <label for="fName">First Name  <span class="error"><?php echo $fNameErr?></span></label>
            <input class="styleInput" id="fName" name="fName" type="text" placeholder="First Name">
            <label for="lName">Last Name <span class="error"><?php echo $lNameErr?></span></label>
            <input class="styleInput" id="lName" name="lName" type="text" placeholder="Last Name">
            <label for="email">Email <span class="error"><?php echo $emailErr?></label>
            <input id="email" name="email" type="email" placeholder="example@example.com">
            <label for="phone">Phone Number <span class="error"><?php echo $phoneErr?></label>
            <input id="phone" name="phone" type="text" placeholder="7871348372">
            <select name="position" id="position">
                <?php
                    // ? Connecting to database
                    require_once "../dbConection.php";
                    $conn = new mysqli($hn,$un,$pw, $db);
                    if($conn->connect_error) die("There was a fatal error");

                    // ? Querying the position table to fill the select
                    $query = "SELECT jobCode, position FROM jobposition";
                    $result = $conn->query($query);
                    if(!$result){
                        die("There was a problem {$conn->connect_error}");
                    }
                    if($result->num_rows > 0){
                        while($row = $result->fetch_assoc()){
                            echo "<option value='".$row['jobCode']."'>".$row['position']."</option>";
                        }
                    }
                    $result->close();
                    $conn->close();
                ?>
            </select>
            <label for="pass">Password <span class="error"><?php echo $pwdErr?></span></label>
            <input name="pwd" id="pwd" type="password" placeholder="password">
            <button id ="empSign" name="empSign">Register</button>
        </form>
    </body>
</html>
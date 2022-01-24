<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Employee Signup</title>
    </head>
    <body>
        <h1>Employee Sign Up</h1>
        <form action="../includes/signupFuncs.php" method="POST">
            <label for="fName">First Name</label>
            <input class="styleInput" id="fName" name="fName" type="text" placeholder="First Name">
            <label for="lName">Last Name</label>
            <input class="styleInput" id="lName" name="lName" type="text" placeholder="Last Name">
            <label for="email">Email</label>
            <input id="email" name="email" type="email" placeholder="example@example.com">
            <label for="phone">Phone Number</label>
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
                            echo "<option id='".$row['jobCode']."'>".$row['position']."</option>";
                        }
                    }
                    $result->close();
                    $conn->close();
                ?>
            </select>
            <button id ="empSign" name="empSign">Register</button>
        </form>
    </body>
</html>
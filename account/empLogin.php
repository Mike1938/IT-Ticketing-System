<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Employee Log in</title>
    </head>
    <body>
        <form action="../includes/loginFuncs.php" method="POST">
            <label for="id">Employee Id</label>
            <input id="id" name="id" type="text" placeholder="Employee ID">
            <label for="pwd">Password</label>
            <input id="password" name="pwd" type="password" placeholder="Password">
            <button name="empLogin">Log in</button>
        </form>
        
    </body>
</html>
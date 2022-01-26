<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Login</title>
</head>
<body>
    <section>
        <form action="../includes/loginFuncs.php" method="POST">
            <label for="id">ID</label>
            <input name="id" id="id" type="text" placeholder="ID">
            <label for="pwd">Password</label>
            <input name="pwd" id="pwd" type="password" placeholder="password">
            <button name="clientLogin">Log in</button>
        </form>
    </section>
</body>
</html>
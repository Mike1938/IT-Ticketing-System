<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Client Signup</title>
    </head>
    <body>
        <h1>New client Registration</h1>
        <section>
            <form action="../includes/signupFuncs.php" method="POST">
                <label for="fName">First Name</label>
                <input class="styleInput" id="fName" name="fName" type="text" placeholder="First Name">
                <label for="lName">Last Name</label>
                <input class="styleInput" id="lName" name="lName" type="text" placeholder="Last Name">
                <label for="compName">Company Name</label>
                <input class="styleInput" id="compName" name="compName" type="text" placeholder="Company Name">
                <label for="email">Email</label>
                <input class="styleInput" id="email" name="email" type="email" placeholder="Email">
                <label for="phone">Phone Number</label>
                <input class="styleInput" id="phone" name="phone" type="text" placeholder="Phone Number">
                <label for="pwd">PassWord</label>
                <input class="styleInput" id="pwd" name="pwd" type="password" placeholder="Password">
                <button name="clientSign">Register</button>
            </form>
        </section>
    </body>
</html>
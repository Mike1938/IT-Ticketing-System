<?php

// ? Connecting to the database
require_once '../dbConection.php';
$conn = new mysqli($hn,$un,$pw, $db);
if($conn->connect_error) die("There was a fatal error");
$query= "";
$urlLocation = "";
// ? function that cleans the form data
function test_input($data){
    $data = trim($data);
    $data= stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
// TODO make better validations
if(isset($_POST['clientSign'])){

    $validation = true;
    // ? Form Variables
    $fName = $lName = $compName = $email = $pwd = $phone = "";

    // ? Error Variables
    $fNameErr = $lNameErr = $compNameErr = $emailErr = $pwdErr = $phoneErr = "";

    // ? Checking form variables
    if(empty($_POST['fName'])){
        $fNameErr = "Cannot be left blank...";
        $validation = false;
    }else{
        $fName = test_input( $_POST['fName']);
        if(!preg_match("/^[a-zA-Z ]*$/", $fName)){
            $fNameErr = 'Only Letters...';
            $validation = false;
        }
    }
    if(empty($_POST['lName'])){
        $lNameErr = "Last Name cannot be left blank...";
        $validation = false;
    }else{
        $lName = test_input($_POST['lName']);
        if(!preg_match("/^[a-zA-Z ]*$/", $lName)){
            $lNameErr = 'Only Letters...';
            $validation = false;
        }
    }
    if(empty($_POST['compName'])){
        $compNameErr = "Company name cannot be left blank...";
        $validation = false;
    }
    else{
        $compName = test_input($_POST['compName']);
        if(strlen($compName) < 3){
            $validation = "Company Name must be 3 characters or more...";
        }
    }
    if(empty($_POST['pwd'])){
        $pwdErr = "Password cannot be left blank...";
        $validation = false;
    }else{
        $pwd = test_input($pwd);
        if(strlen($pwd) < 6){
            $validation = false;
            $pwdErr = "Password must be 6 characters or more...";
        }
        $hashPass = password_hash($pwd, PASSWORD_DEFAULT);
    }
    if(empty($_POST['email'])){
        $emailErr = "Email cannot be left blank...";
        $validation = false;
    }else{
        $email = test_input($_POST['email']);
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $validation = false;
            $emailErr = 'Email not formatted correctly...';
        }
    }
    if(empty($_POST['phone'])){
        $phoneErr = "Phone Number cannot be left blank...";
        $validation = false;
    }else{
        $phone = test_input($phone);
        if(!preg_match('/^[0-9]*$/', $phone)){
            $phoneErr = 'Only numbers...';
            $validation = false;
        }
    }
    if($validation){
        //* Creating the query to insert to the database
        $query = $conn->prepare("INSERT INTO user(fName, lName, companyName, email, pwd, phoneNumber) Values(?,?,?,?,?,?)");
        $query->bind_param('ssssss',$fName, $lName, $compName, $email, $hashPass, $phone);
        $urlLocation = "http://localhost/finalProyect/account/clientLogin.php";
    }else{
        // TODO Work on sending back the error on validation
        $goBackUrl = "http://localhost/finalProyect/account/clientSignup.php";
        $validateGoBack = "http://localhost/finalProyect/account/clientSignup.php";
        if($fNameErr){
            $goBackUrl = "{$goBackUrl}?fName={$fNameErr}";
        }
        if($lNameErr){
            if($goBackUrl != $validateGoBack){
                $goBackUrl = "{$goBackUrl}&lName={$lNameErr}";
            }else{
                $goBackUrl = "{$goBackUrl}?lName={$lNameErr}";
            }
        }
        if($compNameErr){
            if($goBackUrl != $validateGoBack){
                $goBackUrl = "{$goBackUrl}&compName={$compNameErr}";
            }else{
                $goBackUrl = "{$goBackUrl}?compName={$compNameErr}";
            }        
        }
        if($pwdErr){
            if($goBackUrl != $validateGoBack){
                $goBackUrl = "{$goBackUrl}&pwd={$pwdErr}";
            }else{
                $goBackUrl = "{$goBackUrl}?pwd={$pwdErr}";
            }
        }
        if($emailErr){
            if($goBackUrl != $validateGoBack){
                $goBackUrl = "{$goBackUrl}&email={$emailErr}";
            }else{
                $goBackUrl = "{$goBackUrl}?email={$emailErr}";
            }  
        }
        if($phoneErr){
            if($goBackUrl != $validateGoBack){
                $goBackUrl = "{$goBackUrl}&phone={$phoneErr}";
            }else{
                $goBackUrl = "{$goBackUrl}?phone={$phoneErr}";
            }  
        }
        header("location: {$goBackUrl}");
    }
}
elseif(isset($_POST['empSign'])){
    // function to add new employee
}
else{
    header("location: http://localhost/finalProyect/index.php");
}

// ? Executing the query to the database with the client or employee information

if($query->execute()){
    header("location: {$urlLocation}?added=Successfully Registered");
}else{
    echo "There was a problem";
}
$query->close();
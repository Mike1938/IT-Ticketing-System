<?php

// ? Connecting to the database
require_once '../dbConection.php';
$conn = new mysqli($hn,$un,$pw, $db);
if($conn->connect_error) die("There was a fatal error");

// TODO make better validations
if(isset($_POST['clientSign'])){

    $validation = true;
    // ? Form Variables
    $fName = $lName = $compName = $email = $pwd = $phone = "";

    // ? Error Variables
    $fNameErr = $lNameErr = $compNameErr = $emailErr = $pwdErr = $phoneErr = "";

    // ? Checking form variables
    if(empty($_POST['fName'])){
        $fNameErr = "Cannot be left blank";
    }else{
        $fName = $_POST['fName'];
    }
    if(empty($_POST['lName'])){
        $lNameErr = "Last Name cannot be left blank";
    }else{
        $lName = $_POST['lName'];
    }
    if(empty($_POST['compName'])){
        $compNameErr = "Company name cannot be left blank";
    }
    else{
        $compName = $_POST['compName'];
    }
    if(empty($_POST['pwd'])){
        $pwdErr = "Password cannot be left blank";
    }else{
        //TODO Code to hash the password
    }
    if(empty($_POST['email'])){
        $emailErr = "Email cannot be left blank";
    }else{
        $email = $_POST['email'];
    }
    if(empty($_POST['phone'])){
        $phoneErr = "Phone Number cannot be left blank";
    }else{
        $phone = $_POST['phone'];
    }

    //* Creating the query to insert to the database
    $query = "INSERT INTO user(fName, lName, companyName, email, pwd, phoneNumber) 
                Values('$fName', '$lName', '$compName', '$email', '$pwd', '$phone')";


}
elseif(isset($_POST['empSign'])){
    // function to add new employee
}

if($conn->query($query)){
    echo "Yay it worked!!";
}else{
    echo "There was a problem";
}
$conn->close();
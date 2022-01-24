<?php
$fName = "";
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
// ? This will validate most of the form input from client and employee signup forms
function formValidate($valArr){
    $sendInfo = array();
    $sendInfo['validate'] = true;
    $sendInfo['results'] = array();
     // ? Checking form variables
    if(empty($valArr['fName'])){
        $sendInfo['fNameErr'] = "Cannot be left blank...";
        $sendInfo['validate'] = false;
    }else{
        $fName = test_input( $_POST['fName']);
        $sendInfo['results']['fName'] = $fName;
        if(!preg_match("/^[a-zA-Z ]*$/", $fName)){
            $sendInfo['fNameErr'] = 'Only Letters...';
            $sendInfo['validate'] = false;
        }
    }
    if(empty($valArr['lName'])){
        $sendInfo['lNameErr'] = "Last Name cannot be left blank...";
        $sendInfo['validate'] = false;
    }else{
        $lName = test_input($_POST['lName']);
        $sendInfo['results']['lName'] = $lName;
        if(!preg_match("/^[a-zA-Z ]*$/", $lName)){
            $sendInfo['lNameErr'] = 'Only Letters...';
            $sendInfo['validate'] = false;
        }
    }
    if(empty($valArr['pwd'])){
        $sendInfo['pwdErr'] = "Password cannot be left blank...";
        $sendInfo['validate'] = false;
    }else{
        $pwd = test_input($_POST['pwd']);
        if(strlen($pwd) < 6){
            $sendInfo['validate'] = false;
            $sendInfo['pwdErr'] = "Password must be 6 characters or more...";
        }else{
            $sendInfo['results']['hashPass'] = password_hash($pwd, PASSWORD_DEFAULT);
        }
    }
    if(empty($valArr['email'])){
        $sendInfo['emailErr'] = "Email cannot be left blank...";
        $sendInfo['validate'] = false;
    }else{
        $email = test_input($_POST['email']);
        $sendInfo['results']['email'] = $email;
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $sendInfo['validate'] = false;
            $sendInfo['emailErr'] = 'Email not formatted correctly...';
        }
    }
    if(empty($valArr['phone'])){
        $sendInfo['phoneErr'] = "Phone Number cannot be left blank...";
        $sendInfo['validate'] = false;
    }else{
        $phone = test_input($_POST['phone']);
        $sendInfo['results']['phone'] = $phone;
        if(!preg_match('/^[0-9]*$/', $phone)){
            $sendInfo['phoneErr'] = 'Only numbers...';
            $sendInfo['validate'] = false;
        }
    }
    return $sendInfo;
}


if(isset($_POST['clientSign'])){

    $validation = true;
    // ? Form Variables
    $compName = "";

    // ? Error Variables
    $compNameErr = "";

    $checkVal = array("fName"=> $_POST['fName'],'lName' => $_POST['lName'],'pwd' => $_POST['pwd'], 'email'=> $_POST['email'], 'phone'=> $_POST['phone']);
    $valResults = formValidate($checkVal);
    $validation = $valResults['validate'];
    
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

    if($validation){
        //* Creating the query to insert to the database
        $query = $conn->prepare("INSERT INTO user(fName, lName, companyName, email, pwd, phoneNumber) Values(?,?,?,?,?,?)");
        $query->bind_param('ssssss',$valResults['results']['fName'], $valResults['results']['lName'], $compName, $valResults['results']['email'], $valResults['results']['hashPass'], $valResults['results']['phone']);
        $urlLocation = "http://localhost/finalProyect/account/clientLogin.php";
    }else{
        // TODO Work on sending back the error on validation
        $goBackUrl = "http://localhost/finalProyect/account/clientSignup.php";
        $validateGoBack = "http://localhost/finalProyect/account/clientSignup.php";
        if($valResults['fNameErr']){
            $goBackUrl = "{$goBackUrl}?fName={$valResults['fNameErr']}";
        }
        if($valResults['lNameErr']){
            if($goBackUrl != $validateGoBack){
                $goBackUrl = "{$goBackUrl}&lName={$valResults['lNameErr']}";
            }else{
                $goBackUrl = "{$goBackUrl}?lName={$valResults['lNameErr']}";
            }
        }
        if($compNameErr){
            if($goBackUrl != $validateGoBack){
                $goBackUrl = "{$goBackUrl}&compName={$compNameErr}";
            }else{
                $goBackUrl = "{$goBackUrl}?compName={$compNameErr}";
            }        
        }
        if($valResults['pwdErr']){
            if($goBackUrl != $validateGoBack){
                $goBackUrl = "{$goBackUrl}&pwd={$valResults['pwdErr']}";
            }else{
                $goBackUrl = "{$goBackUrl}?pwd={$valResults['pwdErr']}";
            }
        }
        if($valResults['emailErr']){
            if($goBackUrl != $validateGoBack){
                $goBackUrl = "{$goBackUrl}&email={$valResults['emailErr']}";
            }else{
                $goBackUrl = "{$goBackUrl}?email={$valResults['emailErr']}";
            }  
        }
        if($valResults['phoneErr']){
            if($goBackUrl != $validateGoBack){
                $goBackUrl = "{$goBackUrl}&phone={$valResults['phoneErr']}";
            }else{
                $goBackUrl = "{$goBackUrl}?phone={$valResults['fNameErr']}";
            }  
        }
        header("location: {$goBackUrl}");
    }
}
elseif(isset($_POST['empSign'])){
    $validation = true;

     // ? Checking form variables
    $checkVal = array("fName"=> $_POST['fName'],'lName' => $_POST['lName'],'pass' => $_POST['pwd'], 'email'=> $_POST['email'], 'phone'=> $_POST['phone']);
    $valResults = formValidate($checkVal);
    $validation = $valResults['validate'];

    if($validation){
        $query = $conn->prepare("INSERT INTO employee(fName, lName, email, pwd, phoneNumber, jobPosition) VALUES (?,?,?,?,?,?)");
    }

    // TODO WORK WITH ERROR HANDLING WHEN FORM DATA ISNT OK

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
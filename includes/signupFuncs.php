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
     if(isset($valArr['fName'])){
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
     }
     if(isset($valArr['lName'])){
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
     }
    if(isset($valArr['pwd'])){
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
    }
    if(isset($valArr['userName'])){
        if(empty($valArr['userName'])){
            $sendInfo['userNameErr'] = "User Name cannot be left blank";
            $sendInfo['validate'] = false;
        }else{
            $userName = test_input($valArr['userName']);
            if(strlen($userName) < 5){
                $sendInfo['validate'] = false;
                $sendInfo['userNameErr'] = "Must be greater than 5 characters";
            }
            $sendInfo['results']['userName'] = $userName;
        }
    }
    if(isset($valArr['email'])){
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
    }
    if(isset($valArr['phone'])){
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
    }
    return $sendInfo;
}
// ? function that depending on form errors it will sed the errors in the url
function sendErr($ErrArr, $url){
    $goBackUrl = $url;
    $validateGoBack = $url;
    
    if($ErrArr['fNameErr']){
        $goBackUrl = "{$goBackUrl}?fName={$ErrArr['fNameErr']}";
    }
    if($ErrArr['lNameErr']){
        if($goBackUrl != $validateGoBack){
            $goBackUrl = "{$goBackUrl}&lName={$ErrArr['lNameErr']}";
        }else{
            $goBackUrl = "{$goBackUrl}?lName={$ErrArr['lNameErr']}";
        }
    }
    if($ErrArr['pwdErr']){
        if($goBackUrl != $validateGoBack){
            $goBackUrl = "{$goBackUrl}&pwd={$ErrArr['pwdErr']}";
        }else{
            $goBackUrl = "{$goBackUrl}?pwd={$ErrArr['pwdErr']}";
        }
    }
    if($ErrArr['emailErr']){
        if($goBackUrl != $validateGoBack){
            $goBackUrl = "{$goBackUrl}&email={$ErrArr['emailErr']}";
        }else{
            $goBackUrl = "{$goBackUrl}?email={$ErrArr['emailErr']}";
        }  
    }
    if($ErrArr['phoneErr']){
        if($goBackUrl != $validateGoBack){
            $goBackUrl = "{$goBackUrl}&phone={$ErrArr['phoneErr']}";
        }else{
            $goBackUrl = "{$goBackUrl}?phone={$ErrArr['fNameErr']}";
        }  
    }
    return $goBackUrl;
}

if(isset($_POST['clientSign'])){

    $validation = true;
    // ? Form Variables
    $compName = "";

    // ? Error Variables
    $compNameErr = "";
    // ? Varibles to send function for validation
    $checkVal = array("fName"=> $_POST['fName'],'lName' => $_POST['lName'], 'email'=> $_POST['email'], 'phone'=> $_POST['phone']);
    $valResults = formValidate($checkVal);
    $validation = $valResults['validate'];
    
    // ? validate company name
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

// * if true it will start to form the query to insert to client if not it will send the client back to the same page with errors in the url
    if($validation){
        //* Creating the query to insert to the database
        $query = $conn->prepare("INSERT INTO user(fName, lName, companyName, email, phoneNumber) Values(?,?,?,?,?)");
        $query->bind_param('sssss',$valResults['results']['fName'], $valResults['results']['lName'], $compName, $valResults['results']['email'], $valResults['results']['phone']);
        $urlLocation = "http://localhost/finalProyect/account/clientLogin.php";
    }else{
        $url = "http://localhost/finalProyect/account/clientSignup.php";
        $resultUrl = sendErr($valResults, $url);
       
        if($compNameErr){
            if($resultUrl != $url){
                $resultUrl = "{$resultUrl}&compName={$compNameErr}";
            }else{
                $resultUrl = "{$resultUrl}?compName={$compNameErr}";
            }        
        }
        header("location: {$resultUrl}");
    }
}

// * Section of the employee form validation
elseif(isset($_POST['empSign'])){
    $validation = true;
    $position = "";
    $positionErr = "";
     // ? Checking form variables
    $checkVal = array("fName"=> $_POST['fName'],'lName' => $_POST['lName'],'pwd' => $_POST['pwd'], 'email'=> $_POST['email'], 'phone'=> $_POST['phone']);
    $valResults = formValidate($checkVal);
    $validation = $valResults['validate'];

    if(empty($_POST['position'])){
        $validation = false;
        $positionErr = "Position cannot be left blank";
    }else{
        $position = $_POST['position'];
        $valResults['positionErr'] = $positionErr;
    }

    if($validation){
        $query = $conn->prepare("INSERT INTO employee(fName, lName, email, pwd, phoneNumber, jobPosition) VALUES (?,?,?,?,?,?)");
        $query->bind_param('ssssss', $valResults['results']['fName'], $valResults['results']['lName'], $valResults['results']['email'], $valResults['results']['hashPass'],$valResults['results']['phone'], $position);
        $urlLocation = "http://localhost/finalProyect/account/empLogin.php";
    }else{
        $url = "http://localhost/finalProyect/account/empSignup.php";
        $url = sendErr($valResults, $url);
        header("location: {$url}");
    }
}
// ? This section will find the id of the client when completing his registration from his computer
elseif(isset($_POST['findId'])){
    $validation = true;
    // ? Form Variables
    $compName = "";

    // ? Error Variables
    $compNameErr = "";
    // ? Varibles to send function for validation
    $checkVal = array("fName"=> $_POST['fName'],'lName' => $_POST['lName'], 'email'=> $_POST['email'], 'phone'=> $_POST['phone']);
    $valResults = formValidate($checkVal);
    $validation = $valResults['validate'];
    
    // ? validate company name
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

// * if true it will start to form the query to insert to client if not it will send the client back to the same page with errors in the url
    if($validation){
        //* Creating the query to select to the database and find the id of the user
        $query = "SELECT fName, id, userName FROM user WHERE fName in (?) AND lName in (?) AND companyName in (?) AND email in (?) AND phoneNumber in (?)";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $query)){
            header("location: http://localhost/finalProyect/account/cliCompleteRegistration.php");
            exit();
        }
        mysqli_stmt_bind_param($stmt, 'sssss', $valResults['results']['fName'], $valResults['results']['lName'], $compName, $valResults['results']['email'], $valResults['results']['phone']);

        mysqli_stmt_execute($stmt);
        $data = mysqli_stmt_get_result($stmt);
        if($data->num_rows > 0){
            $row = $data->fetch_assoc();
            // ?This will verify if the account was already setUp
            if(isset($row['userName'])){
                header("location: http://localhost/finalProyect/account/clientLogin.php?account=Account already completed please sign in");
                exit();
            }else{
                session_start();
                $_SESSION['tempId'] = $row['id'];
                header("location: http://localhost/finalProyect/account/cliCompleteRegistration.php?account= Account Found");
                exit();
            }
        }else{
            header("location: http://localhost/finalProyect/account/cliCompleteRegistration.php?account= ID not found");
        }
    }
    else{
        $url = "http://localhost/finalProyect/account/cliCompleteRegistration.php";
        $resultUrl = sendErr($valResults, $url);
        
        if($compNameErr){
            if($resultUrl != $url){
                $resultUrl = "{$resultUrl}&compName={$compNameErr}";
            }else{
                $resultUrl = "{$resultUrl}?compName={$compNameErr}";
            }        
        }
        header("location: {$resultUrl}");
    }
}
// ? After finding id user will create password and user here it will verify the user and password and update the table and send to login page after.
elseif(isset($_POST['createPassUser'])){
    session_start();
    $tempId = "";
    if(isset($_SESSION['tempId'])){
        $tempId = $_SESSION['tempId'];
    }else{
        header("location: http://localhost/finalProyect/account/cliCompleteRegistration.php?account= Please find account id first");
        exit();
    }
    $validation = true;
    $checkVal = array('userName'=> $_POST['userName'], 'pwd'=>$_POST['pwd']);
    $valResults = formValidate($checkVal);
    $validation = $valResults['validate'];
    if($validation){
        $query = "UPDATE user SET userName = ?, pwd = ? WHERE id = ?";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $query)){
            header("location: http://localhost/finalProyect/account/cliCompleteRegistration.php");
            exit();
        }
        mysqli_stmt_bind_param($stmt,'ssi', $valResults['results']['userName'], $valResults['results']['hashPass'], $tempId);
        if(mysqli_stmt_execute($stmt)){
            $_SESSION = array();
            setcookie(session_name(), '', time() - 2592000, '/');
            session_destroy();
            header("location: http://localhost/finalProyect/account/clientLogin.php?account=Account Completed");
            exit();
        }
    }else{
        header("location: http://localhost/finalProyect/account/cliCompleteRegistration.php");
        exit();
    }
}
else{
    header("location: http://localhost/finalProyect/index.php");
    exit();
}

// ? Executing the query to the database with the client or employee information

if($query->execute()){
    header("location: {$urlLocation}?added=Successfully Registered");
}else{
    echo "There was a problem";
}
$query->close();
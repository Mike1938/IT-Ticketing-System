<?php
// ? Connectiong to database
require_once '../dbConection.php';
$conn = new mysqli($hn,$un,$pw, $db);
if($conn->connect_error) die("There was a fatal error");
$urlLocation = "";

$clientUrl = "http://localhost/finalProyect/account/clientLogin.php";

// ? Function to clean inputs
function test_input($data){
    $data = trim($data);
    $data= stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// ? This will validate the user input if empty
function validation($uid, $pass){
    $validate = true;
    $sendInfo = array();
    if(empty($uid)){
        $validate = false;
        $sendInfo['idErr'] = "User id cannot be left empty";
    }else{
        $sendInfo['id'] = test_input($uid);
    }
    if(empty($pass)){
        $validate = false;
        $sendInfo['pwdErr'] = "Password cannot be left empty";
    }else{
        $sendInfo['pwd'] = $pass;
    }
    $sendInfo['validation'] = $validate;
    return $sendInfo;
}

// ? This will check if the user id is in the database
function checkUser($conn,$uid,$url, $q){
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $q)){
        header("location: {$url}?error=An Error Ocurred please try again");
        exit();
    }
    mysqli_stmt_bind_param($stmt,'s', $uid);
    mysqli_stmt_execute($stmt);
    $data = mysqli_stmt_get_result($stmt);
    
    if($row = mysqli_fetch_assoc($data)){
        return $row;
    }else{
        return false;
    }
}

// ? Function that will log in the user if the password match
function loginUser($conn, $uid, $pass, $url, $q){
    $user = checkUser($conn, $uid,$url, $q);
    if($user === false){
        header("location: {$url}?userErr= User could not be found");
        exit();
    }

    $hashPass = $user["pwd"];
    $ValidatePass = password_verify($pass, $hashPass);
    if($ValidatePass){
        echo "Yay you are validated";
        // TODO Send to the client dashboard
        // TODO Creat session
    }else{
        header("location: {$url}?passErr= Incorrect password");
        exit();
    }
}

if(isset($_POST['empLogin'])){
    $query = "SELECT id, fName, lName, pwd FROM employee WHERE id = ?";
    $modifiedUrl = 'http://localhost/finalProyect/account/empLogin.php';
    $results = validation($_POST['id'], $_POST['pwd']);

    if($results['validation']){
        loginUser($conn,$results['id'], $results['pwd'], $modifiedUrl, $query);

    }else{
        if($results['idErr']){
            $modifiedUrl = "{$clientUrl}?idErr={$results['idErr']}";
        }
        if($results['pwdErr']){
            if($modifiedUrl != $clientUrl){
                $modifiedUrl = "{$clientUrl}?pwdErr={$results['pwdErr']}";
            }else{
                $modifiedUrl = "{$clientUrl}&pwdErr={$results['pwdErr']}";
            }
        }
        header("location: {$modifiedUrl}");
        exit();
    }
}
elseif(isset($_POST['clientLogin'])){
    $modifiedUrl = 'http://localhost/finalProyect/account/clientLogin.php';
    $results = validation($_POST['id'], $_POST['pwd']);
    $query = "SELECT id, fName, lName, pwd FROM user WHERE id = ?";
    
    if($results['validation']){
        loginUser($conn,$results['id'], $results['pwd'], $modifiedUrl, $query);

    }else{
        if($results['idErr']){
            $modifiedUrl = "{$clientUrl}?idErr={$results['idErr']}";
        }
        if($results['pwdErr']){
            if($modifiedUrl != $clientUrl){
                $modifiedUrl = "{$clientUrl}?pwdErr={$results['pwdErr']}";
            }else{
                $modifiedUrl = "{$clientUrl}&pwdErr={$results['pwdErr']}";
            }
        }
        header("location: {$modifiedUrl}");
        exit();
    }
    

}
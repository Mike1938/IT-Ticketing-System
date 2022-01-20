<?php echo "hello world";?>

<?php
    require_once 'dbConection.php';
    $conn = new mysqli($hn,$un,$pw, $db);
    if($conn->connect_error) die("There was a fatal error");
    else{
        echo "it worked";
    }
?>
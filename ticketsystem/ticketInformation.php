<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../static/css/styles.css">
        <title>Ticket Info</title>
    </head>
    <body>
        <?php
            // ?Verifies the client if log in if not its send to the client login page
            session_start();
            if(isset($_SESSION['cliId'])){
                $fname = htmlspecialchars($_SESSION['fName']);
                $uid = htmlspecialchars($_SESSION['cliId']);
            }
            else{
                header("location: http://localhost/finalProyect/account/clientLogin.php");
                exit();
            }
            require_once "../dbConection.php";
            $conn = new mysqli($hn,$un,$pw, $db);
            if($conn->connect_error) die("There was a fatal error");
            $ticketNum = "";
            // ? input clean up function
            function textCleanUp($text) {
                $text = strip_tags($text);
                $text = trim($text);
                $text = htmlspecialchars($text);
                return $text;
            }
            if(isset($_GET["ticketNum"])){
                $ticketNum = textCleanUp($_GET["ticketNum"]);
            }else{
                header("location: http://localhost/finalProyect/ticketSystem/ticketDashboard.php");
                exit();
            }

            // ? Going to verify if client match with the ticket and give the result of the ticket with product infor and user info
            $query = "SELECT tickets.ticketId, equipId, problem, ticketPosted, startDate, tStatus , employee.fName, employee.lName, employee.email, product.pName, product.model, product.releaseDate  
            FROM tickets
            INNER JOIN employee ON tickets.employeeID = employee.id
            INNER JOIN invoiceDetails ON tickets.equipId = invoiceDetails.equipmentID
            INNER JOIN product ON invoiceDetails.productID = product.id WHERE ticketId = ? AND userID = ?";
            $stmt = mysqli_stmt_init($conn);
            if(!mysqli_stmt_prepare($stmt, $query)){
                header("location: http://localhost/finalProyect/ticketSystem/ticketDashboard.php");
                exit();
            }
            mysqli_stmt_bind_param($stmt, "ii", $ticketNum, $uid);
            mysqli_stmt_execute($stmt);
            $data = mysqli_stmt_get_result($stmt);
            if($data->num_rows > 0){
                $row = $data->fetch_assoc();
                echo"
                <h1 id='ticketPageHead'>Ticket #{$ticketNum}</h1>
                <div id='allTickInfo'>
                    <div class='infoTickCards' id='userInfo'>
                        <p class='dashTitle'>Employee Information</p>
                        <p class='infoPara'><span>Employee Name</span>{$row['fName']} {$row['lName']}</p>
                        <p class='infoPara'><span>Email</span>{$row['email']}</p>
                    </div>
                    <div class='infoTickCards' id='prodDetails'>
                        <p class='dashTitle'>Product Details</p>
                        <p class='infoPara'><span>Product Name</span>{$row['pName']}</p>
                        <p class='infoPara'><span>Model</span>{$row['model']}</p>
                        <p class='infoPara'><span>Release Date</span>{$row['releaseDate']}</p>
                    </div>
                    <div class='infoTickCards' id='problemSection'>
                        <p class='dashTitle'>Ticket Details</p>
                        <p class='infoPara'><span>Equipment ID</span>{$row['equipId']}</p>
                        <p class='infoPara'><span>Problem</span>{$row['problem']}</p>
                        <p class='infoPara'><span>Ticket Post Date</span>{$row['ticketPosted']}</p>
                        <p class='infoPara'><span>Start Date</span>{$row['startDate']}</p>
                    </div>
                </div>
                ";
            }else{
                header("location: http://localhost/finalProyect/ticketSystem/ticketDashboard.php");
            }
            // ?waiting for the post of the comments
            // ?When post on comment it will insert comment to the database
            if(isset($_POST['postComment'])){
                $comment = $commentErr = "";
                $validation = true;
                if(empty($_POST['comment'])){
                    $commentErr = "Comment cannot be left blank";
                    $validation = false;
                    header("location: http://localhost/finalProyect/ticketSystem/ticketInformation.php?ticketNum={$ticketNum}&commentErr={$commentErr}");
                }else{
                    $comment = textCleanUp($_POST['comment']);
                }
                // validates that the comment if not left blank
                if($validation){
                    $query = "INSERT INTO comments(ticketID, comment, userID) VALUES (?,?,?)";
                    $stmt = mysqli_stmt_init($conn);
                    if(!mysqli_stmt_prepare($stmt, $query)){
                        header("location: http://localhost/finalProyect/ticketSystem/ticketInformation.php?ticketNum={$ticketNum}");
                        exit();
                    }
                    mysqli_stmt_bind_param($stmt,'isi',$ticketNum, $comment, $uid);
                    if(mysqli_stmt_execute($stmt)){
                        header("location: http://localhost/finalProyect/ticketSystem/ticketInformation.php?ticketNum={$ticketNum}");
                        exit();
                    }
                }
            }
           
        ?>
        <section id="comments">
            <p class='dashTitle'>Comments</p>
            <div id="commentArea">
                <?php
                    $commentErr = "";
                    if(isset($_GET['commentErr'])){
                        $commentErr = textCleanUp($_GET['commentErr']);
                    }
                     // ? query will search all the comments in this ticket
                    $query = "SELECT comment, userID, employeeID, Date_FORMAT(commentDate, '%b-%d %H:%i') AS 'commentDate' FROM comments WHERE ticketID = ?";
                    $stmt = mysqli_stmt_init($conn);
                    if(!mysqli_stmt_prepare($stmt, $query)){
                        header("location: http://localhost/finalProyect/ticketsystem/ticketInformation.php?ticketNum={$ticketNum}");
                        exit();
                    }
                    mysqli_stmt_bind_param($stmt, 'i', $ticketNum);
                    mysqli_stmt_execute($stmt);
                    $data = mysqli_stmt_get_result($stmt);
                    if($data->num_rows > 0){
                        while($row = $data->fetch_assoc()){
                            if(!empty($row["employeeID"])){
                            echo "
                                <p></p>
                                <p class='left'>{$row['comment']}</p>
                                <p class='leftInfo'>Sent: {$row['commentDate']}</p>
                                ";
                            }
                            else{
                                echo "
                                    <p class='right'>{$row['comment']}</p>
                                    <p class='rightInfo'>Sent: {$row['commentDate']}</p>
                                ";
                            }
                        }
                        echo "<p class='error'> {$commentErr}</p>";
                    }
                    $conn->close()
                ?>
            </div>
            <form id="commentfrm" method="POST">
                <input name="comment" type="text" placeholder="Aa">
                <button name="postComment">Send</button>
            </form>
        </section>
    </body>
</html>
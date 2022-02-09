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
        <nav>
            <a href="http://localhost/finalProyect/index.php">Home</a>
            <a href="http://localhost/finalProyect/helpDesk/dashboard.php">Dashboard</a>
            <a href="../includes/logout.php">Log Out</a>
        </nav>
        <?php
        // ?Verifies the employee if log in if not its send to the employee login page
            session_start();
            $uid = $fName = "";
            if(isset($_SESSION['empId'])){
                $fname = htmlspecialchars($_SESSION['fName']);
                $uid = htmlspecialchars($_SESSION['empId']);
            }
            else{
                header("location: http://localhost/finalProyect/account/empLogin.php");
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
                header("location: http://localhost/finalProyect/helpDesk/dashboard.php");
                exit();
            }

            // ? Going to verify if employee match with the ticket and give the result of the ticket with product infor and user info

            $query = "SELECT ticketId, equipId, problem, ticketPosted, startDate, tStatus , tickets.userId, user.fName, user.lName, user.companyName, user.email, user.phoneNumber, product.pName, product.model, product.releaseDate  
                    FROM tickets INNER JOIN user ON tickets.userId = user.id
                    INNER JOIN invoiceDetails ON tickets.equipId = invoiceDetails.equipmentID
                    INNER JOIN product ON invoiceDetails.productID = product.id WHERE ticketId = ? AND employeeID = ?";
            $stmt = mysqli_stmt_init($conn);
            if(!mysqli_stmt_prepare($stmt, $query)){
                header("location: http://localhost/finalProyect/helpDesk/dashboard.php");
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
                        <p class='dashTitle'>User Information</p>
                        <p class='infoPara'><span>User ID</span>{$row['userId']}</p>
                        <p class='infoPara'><span>Client Name</span>{$row['fName']} {$row['lName']}</p>
                        <p class='infoPara'><span>Company</span>{$row['companyName']}</p>
                        <p class='infoPara'><span>Email</span>{$row['email']}</p>
                        <p class='infoPara'><span>Phone Number</span>{$row['phoneNumber']}</p>
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
                header("location: http://localhost/finalProyect/helpDesk/dashboard.php");
            }
            // ?waiting for the post of the comments
            // ?When post on comment it will insert comment to the database
            if(isset($_POST['postComment'])){
                $comment = $commentErr = "";
                $validation = true;
                if(empty($_POST['comment'])){
                    $commentErr = "Comment cannot be left blank";
                    $validation = false;
                    header("location: http://localhost/finalProyect/helpDesk/ticketInfo.php?ticketNum={$ticketNum}&commentErr={$commentErr}");
                }else{
                    $comment = textCleanUp($_POST['comment']);
                }
                // validates that the comment if not left blank
                if($validation){
                    $query = "INSERT INTO comments(ticketID, comment, employeeID) VALUES (?,?,?)";
                    $stmt = mysqli_stmt_init($conn);
                    if(!mysqli_stmt_prepare($stmt, $query)){
                        header("location: http://localhost/finalProyect/helpDesk/ticketInfo.php?ticketNum={$ticketNum}");
                        exit();
                    }
                    mysqli_stmt_bind_param($stmt,'isi',$ticketNum, $comment, $uid);
                    if(mysqli_stmt_execute($stmt)){
                        header("location: http://localhost/finalProyect/helpDesk/ticketInfo.php?ticketNum={$ticketNum}");
                        exit();
                    }
                }
            }
            // ?This will submit the solution to ticket status
            if(isset($_POST['subitSolution'])){
                $solution = $solutionErr = "";
                $status = textCleanUp($_POST['status']);
                $validation = true;
                if(empty($_POST['solutionText'])){
                    $solutionErr = "Solution cannot be left blank";
                }else{
                    $solution = textCleanUp($_POST['solutionText']);
                }
                if($validation){
                    $query = "UPDATE ticketStatus SET tEnd = NOW(), solution = ? WHERE ticketID = ?";
                    $qStatus = "UPDATE ticket SET tStatus = ? WHERE id = ?";
                    // ?This will update solution and the time ended the ticket
                    $stmt = mysqli_stmt_init($conn);
                    if(!mysqli_stmt_prepare($stmt,$query)){
                        header("location: http://localhost/finalProyect/helpDesk/ticketInfo.php?ticketNum={$ticketNum}");
                        exit();
                    }
                    mysqli_stmt_bind_param($stmt,'si', $solution, $ticketNum);
                    $qFirstResult = mysqli_stmt_execute($stmt);

                    // ? This will change status of ticket table
                    $stmtTwo = mysqli_stmt_init($conn);
                    if(!mysqli_stmt_prepare($stmtTwo, $qStatus)){
                        header("location: http://localhost/finalProyect/helpDesk/ticketInfo.php?ticketNum={$ticketNum}");
                        exit();
                    }
                    mysqli_stmt_bind_param($stmtTwo, 'si', $status, $ticketNum);
                    $qSecondResult = mysqli_stmt_execute($stmtTwo);

                    if($qFirstResult && $qSecondResult){
                        header("location: http://localhost/finalProyect/helpDesk/dashboard.php");
                        exit();
                    }
                    

                }
            }
           
        ?>
        <section id="solution" class="infoTickCards">
            <p class="dashTitle">Complete Ticket</p>
            <form id="solutionFrm" method="POST">
                <select class="styleInput" name="status" id="status">
                    <option value="Complete">Complete</option>
                    <option value="Abandon">Abandon</option>
                </select>
                <textarea  name="solutionText" id="probBlock" cols="55" rows="5"></textarea>
                <button name="subitSolution">Submit</button>
            </form>
        </section>
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
                        header("location: http://localhost/finalProyect/helpDesk/ticketInfo.php?ticketNum={$ticketNum}");
                        exit();
                    }
                    mysqli_stmt_bind_param($stmt, 'i', $ticketNum);
                    mysqli_stmt_execute($stmt);
                    $data = mysqli_stmt_get_result($stmt);
                    if($data->num_rows > 0){
                        while($row = $data->fetch_assoc()){
                            if(!empty($row["userID"])){
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
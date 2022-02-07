<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../static/css/styles.css">
        <title>Dashboard</title>
    </head>
    <body>
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
        ?>
        <?php
            //? Function to clean inputs 
            function textCleanUp($text) {
                $text = strip_tags($text);
                $text = trim($text);
                $text = htmlspecialchars($text);
                return $text;
            }
            // ? Section when the employee accepts the ticket is updates the ticket table and insert data to the ticket status table
            if(isset($_POST['acceptTick'])){
                // ? Update in ticket
                $ticketId = textCleanUp($_POST['acceptTick']);
                $upQuery = "UPDATE ticket SET tStatus = 'Active' WHERE id = ?";
                $stmt = mysqli_stmt_init($conn);
                if(!mysqli_stmt_prepare($stmt,$upQuery)){
                    header("location: http://localhost/finalProyect/helpDesk/dashboard.php");
                    exit();
                }
                mysqli_stmt_bind_param($stmt, "i", $ticketId);
                
                // ? Insert into ticket Status
                $statusQuery = "INSERT INTO ticketStatus(ticketID, employeeID) VALUES (?, ?)";
                $insertStmt = mysqli_stmt_init($conn);
                if(!mysqli_stmt_prepare($insertStmt,$statusQuery)){
                    header("location: http://localhost/finalProyect/helpDesk/dashboard.php");
                    exit();
                }
                mysqli_stmt_bind_param($insertStmt, "ii", $ticketId, $uid);

                // ? going to execute the to query to the db
                if(mysqli_stmt_execute($stmt) && mysqli_stmt_execute($insertStmt)){
                    header("location: http://localhost/finalProyect/helpDesk/dashboard.php?accept=Ticket Accepted");
                    exit();
                }
            }
        ?>
        <nav>
            <a href="http://localhost/finalProyect/index.php">Home</a>
            <a href="../includes/logout.php">Log Out</a>
        </nav>
        <h1>Dashboard</h1>
        <div id="shortcuts">

        </div>
        <section id="dashContent">
            <div id="openTickets">
                <p class="dashTitle">Open Tickets</p>
                <?php
                    $query = "SELECT ticketId, ticketPosted, startDate, tStatus FROM tickets WHERE tStatus = 'Active' AND employeeID = ? ";
                    $stmt = mysqli_stmt_init($conn);
                    if(!mysqli_stmt_prepare($stmt, $query)){
                        header("location: http://localhost/finalProyect/helpDesk/dashboard.php");
                        exit();
                    }
                    mysqli_stmt_bind_param($stmt, 'i',$uid);
                    mysqli_stmt_execute($stmt);
                    $data = mysqli_stmt_get_result($stmt);
                    if($data->num_rows > 0){
                        while($row = $data->fetch_assoc()){
                            echo
                            "
                            <div class='pendingCards'>
                                <div class = 'penCardHeader'>
                                    <p>Ticket #{$row['ticketId']}</p>
                                </div>
                                <p class ='ticketInfo'><span>Ticket Posted</span> {$row['ticketPosted']}</p>
                                <p class ='ticketInfo'><span>Start Date</span> {$row['startDate']}</p>
                                <p class ='ticketInfo'><span>Status</span> {$row['tStatus']}</p>
                                <div class='buttonSec'>
                                    <form method='GET'>
                                        <button>View More</button>
                                    </form>
                                </div>
                                
                            </div>
                            ";
                        }
                    }
                ?>
            </div>
            <div id="pendingTickets">
                <p class="dashTitle">Pending Tickets</p>
                <?php
                    // ? This will fetch all the pending tickets from the db
                    $query = "SELECT ticket.id, problem, DATE_FORMAT(ticketDate,'%M-%d-%Y %H:%i') as ticketDate, tStatus, userID, companyName FROM ticket INNER JOIN user ON ticket.userID = user.id WHERE tStatus ='Pending'";
                    $results = $conn->query($query);
                    if(!$results){
                        die("There was a problem {$conn->connect_error}");
                    }
                    if($results->num_rows > 0){
                        while($row = $results->fetch_assoc()){
                            echo 
                            "<div class = 'pendingCards'>
                                <div class ='penCardHeader'>
                                    <p>Ticket #{$row['id']}</p>
                                    <p>{$row['companyName']}</p>
                                    <p>User Id:{$row['userID']}</p>
                                </div>
                                <p class ='ticketInfo'><span>Problem</span>{$row['problem']}</p>
                                <p class ='ticketInfo'><span>Ticket Date</span>{$row['ticketDate']}</p>
                                <p class ='ticketInfo'><span>Status</span>{$row['tStatus']}</p>
                                <div class='buttonSec'>
                                    <form method='POST'>
                                        <button name='acceptTick' value='{$row['id']}'>Accept Ticket</button>
                                    </form>
                                    <a><button>View More</button></a>  
                                </div>
                                                           
                            </div>";
                        }
                    }
                    $conn->close();
                ?>
            </div>
        </section>
        
    </body>
</html>
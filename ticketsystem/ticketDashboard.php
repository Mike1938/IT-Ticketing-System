<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../static/css/styles.css">
        <title>Ticket Dahboard</title>
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
        ?>
        <nav>
            <a href="http://localhost/finalProyect/index.php">Home</a>
            <a href="../includes/logout.php">Log Out</a>
        </nav>
        <section id="infoContent">
            <h1>Ticket Dashboard</h1>
            <section id="options">
                <?php
                    $problemErr = "";
                    if(isset($_GET['problem'])){
                        $problemErr = textCleanUp($_GET['problem']);
                    }
                    // ? Function to clean form inputs
                    function textCleanUp($text) {
                        $text = strip_tags($text);
                        $text = trim($text);
                        $text = htmlspecialchars($text);
                        return $text;
                    }
                    require_once "../dbConection.php";
                    $conn = new mysqli($hn,$un,$pw, $db);
                    if($conn->connect_error) die("There was a fatal error");

                    // ? Section to send ticket data to db
                    if($_SERVER['REQUEST_METHOD'] == 'POST'){
                        if(isset($_POST['createBtn'])){
                            echo "hello";
                            $problem = textCleanUp($_POST['problem']);
                            if(strlen($problem) < 10){
                                header("location: http://localhost/finalProyect/ticketSystem/ticketDashboard.php?problem= needs to be 10 characters or more");
                                exit();
                            }
                            $equipId = textCleanUp($_POST['equipmentId']);
                            $query = "INSERT INTO ticket(userID, equipmentID, problem) VALUES (?,?,?)";
                            $stmt = mysqli_stmt_init($conn);
                            if(!mysqli_stmt_prepare($stmt, $query)){
                                header("location: http://localhost/finalProyect/ticketSystem/ticketDashboard.php?error=There was an error");
                                exit();
                            }
                            mysqli_stmt_bind_param($stmt,'iis',$uid, $equipId, $problem);
                            mysqli_stmt_execute($stmt);
                            header("location: http://localhost/finalProyect/ticketSystem/ticketDashboard.php?ticket=Ticket was submitted");
                        }
                    }
                ?>
                <div id="createT">
                    <p class="title">Create Ticket</p>
                    <form id="createTicketFrm" method="POST">
                        <div>
                            <label for="equipment">Select problem device</label>
                            <select name="equipmentId" id="equipment">
                                <?php
                                    // ?This will grab all the computer that the client logged in have to create a ticket
                                    $query = "SELECT equipId, ProductName FROM clientProducts WHERE user = ?";
                                    $stmt = mysqli_stmt_init($conn);
                                    if(!mysqli_stmt_prepare($stmt, $query)){
                                        header("location: http://localhost/finalProyect/ticketSystem/ticketDashboard.php");
                                        exit();
                                    }
                                    mysqli_stmt_bind_param($stmt, 'i', $uid);
                                    mysqli_stmt_execute($stmt);
                                    $data = mysqli_stmt_get_result($stmt);
                                    if($data->num_rows > 0){
                                        while($row = $data->fetch_assoc()){
                                            echo "<option value= '{$row['equipId']}'>ID: {$row['equipId']} - {$row['ProductName']}</option>";
                                        }
                                    }else{
                                        echo "<option>Products not available</option>";
                                    }
                                ?>
                            </select>
                        </div>
                        <div>
                            <label for="probBlock">Problem <span class="error"><?php echo $problemErr?></span></label>
                            <textarea name="problem" id="probBlock" cols="55" rows="5"></textarea>
                        </div>
                        <button name="createBtn">Submit</button>
                    </form>
                </div>
                
                <div id="openT">
                    <p class="title">Open Tickets</p>
                    <div id="ticketList">
                        <?php
                            // ? This will show the current open tickets
                            $query = "SELECT id, equipmentID, problem, ticketDate, tStatus FROM ticket WHERE userID =?";
                            $stmt = mysqli_stmt_init($conn);
                            if(!mysqli_stmt_prepare($stmt, $query)){
                                header("location: http://localhost/finalProyect/ticketSystem/ticketDashboard.php");
                                exit();
                            }
                            mysqli_stmt_bind_param($stmt, "i", $uid);
                            mysqli_stmt_execute($stmt);
                            $result = mysqli_stmt_get_result($stmt);
                            if($result->num_rows > 0){
                                while($row = $result->fetch_assoc()){
                                    echo 
                                        "<div class='ticketCard'>
                                            <p>Ticket:{$row['id']}</p>
                                            <p>Equipment ID:{$row['equipmentID']}</p>
                                            <p>Posted:{$row['ticketDate']}</p>
                                            <p>Status:{$row['tStatus']}</p>
                                            <p class='desc'>Problem:{$row['problem']}</p>
                                            <button value='{$row['id']}'>View</button>
                                        </div>";
                                }
                            }else{
                                echo "
                                    <div class='ticketCard'>
                                        <p class = 'notFound'> No Active Tickets</p>
                                    </div>";
                            }
                            $conn->close()
                        ?>
                    </div>
                </div>
            </section>       
        </section>
        
    </body>
</html>
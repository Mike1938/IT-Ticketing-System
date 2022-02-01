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
            <h1>ticket dashboard</h1>
            <section id="options">
                <?php
                    require_once "../dbConection.php";
                    $conn = new mysqli($hn,$un,$pw, $db);
                    if($conn->connect_error) die("There was a fatal error");
                ?>
                <div id="createT">
                    <p class="title">Create Ticket</p>
                </div>
                
                <div id="openT">
                    <p class="title">Open tickets</p>
                    <?php
                        $query = "SELECT id, equipmentID, problem, ticketDate FROM ticket INNER JOIN ticketStatus ON ticket.id = ticketStatus.ticketID WHERE tStatus = 'Active'";
                        $result = $conn->query($query);
                        if(!$result){
                            die("There was an error");
                        }
                        if($result->num_rows > 0){
                            while($row = $result->fetch_assoc()){
                                echo "<p>{$row['ticketID']}</p>";
                            }
                        }else{
                            echo "<p> No actvie tickets</p>";
                        }

                    ?>
                </div>
            </section>       
        </section>
        
    </body>
</html>
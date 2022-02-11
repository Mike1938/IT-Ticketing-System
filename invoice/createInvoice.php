<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../static/css/styles.css">
        <title>Create Invoice</title>
    </head>
    <body>
        <?php
            // ?Verifies the employee if log in if not its send to the employee login page
            session_start();
            $uid = $fName = $user = "";
            if(isset($_SESSION['empId'])){
                $fname = htmlspecialchars($_SESSION['fName']);
                $uid = htmlspecialchars($_SESSION['empId']);
            }
            else{
                header("location: http://localhost/finalProyect/account/empLogin.php");
                exit();
            }
            if(isset($_GET['userId'])){
                $user = textCleanUp($_GET['userId']);
            }
            require_once "../dbConection.php";
            $conn = new mysqli($hn,$un,$pw, $db);
            if($conn->connect_error) die("There was a fatal error");
            //? Function to clean inputs 
            function textCleanUp($text) {
                $text = strip_tags($text);
                $text = trim($text);
                $text = htmlspecialchars($text);
                return $text;
            }
            $userErr = "";
            if(isset($_GET['user'])){
                $userErr = textCleanUp($_GET['user']);
            }
        ?>
        <nav>
            <a href="http://localhost/finalProyect/index.php">Home</a>
            <a href="http://localhost/finalProyect/helpDesk/dashboard.php">Dashboard</a>
            <a href="../includes/logout.php">Log Out</a>
        </nav>
        <section id="invoiceContent">
            <div id="dashBoardHeadCont">
                <h1 id="dashboardHead">Invoice page</h1>
            </div>
            <div id="findUser">
                <?php
                    // ?It will verify if the user already selected to display something else
                    if(!empty($user)){
                        echo"
                        <div id ='userFoundCont'>
                            <p class= 'nameFound'>User Id: {$user}</p>
                            <form id='selectAgainFrm' method='POST'>
                                <button name='changeUser'>Change User</button>
                            </form>
                        </div>
                        ";
                    }else{
                        echo"
                        <form id='findPhoneFrm' name='test' method='GET'>
                            <label for='phone'>Phone Number</label>
                            <input class='styleInput' id='phone' name='phoneNumber' type='text' placeholder='7778889999'>
                            <button name='findUserBut'>Search</button>
                        </form>
                        ";
                    }
                ?>
                <p class="selectedUser"><?php echo $userErr?></p>
                <div id="SearchResults">
                    <?php
                        // ?this area will focus on searchin the user by phone. It takes the form input of a phonenumber and search it on the db
                        $phone = $phoneErr = "";
                        $validation = true;
                        $selectUser = "";
                        if(isset($_GET["findUserBut"])){
                            if(isset($_GET['phoneNumber'])){
                                if(empty($_GET['phoneNumber'])){
                                    $phoneErr = "Cannot be left blank";
                                    $validation = false;
                                }else{
                                    $phone = textCleanUp($_GET['phoneNumber']);
                                }
                            }
                            if($validation){
                                $query = "SELECT id, fName, lName, email FROM user WHERE phoneNumber = ?";
                                $stmt = mysqli_stmt_init($conn);
                                if(!mysqli_stmt_prepare($stmt,$query)){
                                    header("location: http://localhost/finalProyect/invoice/createInvoice.php?problem= There was a problem please try again");
                                    exit();
                                }
                                mysqli_stmt_bind_param($stmt,'s', $phone);
                                mysqli_stmt_execute($stmt);
                                $data = mysqli_stmt_get_result($stmt);
                                if($data->num_rows > 0){
                                    while($row = $data->fetch_assoc()){
                                        echo"
                                            <div class='findInfoCards'>
                                                <p class= 'nameFound'>{$row['fName']} {$row['lName']}</p>
                                                <p class ='ticketInfo'><span>Email</span> {$row['email']}</p>
                                                <p class ='ticketInfo'><span>User ID</span> {$row['id']}</p>
                                                <form method='GET'>
                                                    <button value='{$row['id']}' name='selectUser'>Select</button>
                                                </form>
                                            </div>
                                        ";
                                    }
                                }else{
                                    echo"
                                        <div>
                                            <p class='notFound'>No results Found</p>
                                        </div>
                                    ";
                                }
                            }else{
                                header("location: http://localhost/finalProyect/invoice/createInvoice.php?phone={$phoneErr}");
                                exit();
                            }

                        }
                        // ?this will put user id in url to be taken by another query later
                        if(isset($_GET['selectUser'])){
                            $selectUser = textCleanUp($_GET['selectUser']);
                            header("location: http://localhost/finalProyect/invoice/createInvoice.php?user=User Selected&userId={$selectUser}");
                            exit();
                        }
                        // ?This section will complete the invoice it will insert to invoice and invoice details information about the transaction  
                        if(isset($_POST['completeInvoice'])){
                            if($user){
                                $lastID = "";
                                $product = textCleanUp($_POST['products']);
                                $qInvoice = "INSERT INTO invoice(userID, employeeID) VALUES (?,?)";
                                $stmt = mysqli_stmt_init($conn);
                                if(!mysqli_stmt_prepare($stmt, $qInvoice)){
                                    header("location: http://localhost/finalProyect/invoice/createInvoice.php");
                                    exit();
                                }
                                mysqli_stmt_bind_param($stmt,'ii', $user, $uid);
                                mysqli_stmt_execute($stmt);
                                $lastID = $stmt->insert_id;

                                // *statement 2
                                $qInvDetails = "INSERT INTO invoiceDetails(invoiceID, productID) VALUES (?,?)";
                                $stmtTwo = mysqli_stmt_init($conn);
                                if(!mysqli_stmt_prepare($stmtTwo, $qInvDetails)){
                                    header("location: http://localhost/finalProyect/invoice/createInvoice.php");
                                    exit();
                                }
                                mysqli_stmt_bind_param($stmtTwo,'ii', $lastID, $product);
                                mysqli_stmt_execute($stmtTwo);
                                header("location: http://localhost/finalProyect/invoice/createInvoice.php?complete=Invoice Completed");
                            }
                            else{
                                header("location: http://localhost/finalProyect/invoice/createInvoice.php?user=Please Select User");
                                exit();
                            }
                            
                        }
                        // ?This section will clear the session for the selected user
                        if(isset($_POST['changeUser'])){
                            header("location: http://localhost/finalProyect/invoice/createInvoice.php");
                            exit();
                        }
                    ?>
                </div>
            </div>
            <div id="invoiceSect">
                <form method="POST">
                    <label class= 'nameFound'  for="products">Select Product</label>
                    <select class='styleInput' name="products" id="products">
                        <?php
                            $query = "SELECT id, pName FROM product";
                            $stmt = mysqli_stmt_init($conn);
                            if(!mysqli_stmt_prepare($stmt, $query)){
                                header("location: http://localhost/finalProyect/invoice/createInvoice.php");
                                exit();
                            }
                            mysqli_stmt_execute($stmt);
                            $data = mysqli_stmt_get_result($stmt);
                            if($data->num_rows > 0){
                                while($row = $data->fetch_assoc()){
                                    echo"<option value='{$row['id']}'>ID:{$row['id']} - {$row['pName']}</option>";
                                }
                            }
                            unset($_SESSION['selectedUser']);
                        ?>
                    </select>
                    <button name="completeInvoice">Complete</button>
                </form>
            </div>
        </section>
        
        

    </body>
</html>
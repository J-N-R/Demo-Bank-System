<html>

 <head> <title>Login Check / Home Page</title> </head>

 <body>

 <div style = "font-size: 150%">

 <?php


    include "dbconfig.php";

//  Die footers. Helpful for outputting closing HTML in a DIE() case
    $dieFooterLoginAgain = "\n<a href=\".\" style=\"font-size: 200%\">Login Page</a>\n\n </div>\n\n </body>\n\n</html>";
    $dieFooterGoBack = "\n<a href=\"login.php\" style=\"font-size: 200%\">Go Back</a>\n\n </div>\n\n </body>\n\n</html>";    

// IF USER DID NOT LOG IN, DIE AND REDIRECT TO LOGIN
    if(!isset($_POST["username"]) && !isset($_COOKIE["cid"]))
        die("<p style='font-size: 150%'>No Login Session Detected, or previous Login Session expired. Please log in again.</p> $dieFooterLoginAgain");


///// COOKIE LOG IN ///// Normally this is bad unsafe practice, but fine for this demonstration
// Global variables $results and $conn that can be used to login with or without set cookie
$results = 0;
$conn = mysqli_connect($host, $user, $password, $database) OR die("<p style=\"font-size: 150%\">Cannot connect to Database.</p> $dieFooterGoBack");
if(!isset($_COOKIE["cid"])) {


// Initialize important variables
    $login = $_POST["username"];
    $pass  = $_POST["password"];

    $sql = "SELECT * FROM CPS3740.Customers WHERE login = '$login'";

 
// SQL FIND USER
    $results = mysqli_query($conn, $sql)                       OR die("<p style=\"font-size: 150%\">SQL Query Error or Database connection Error. Please try again.</p> $dieFooterGoBack");
   
    if(mysqli_num_rows($results) == 0)
        die("<p style=\"font-size: 150%\">Login '<b>$login</b>' doesn't exist in the database.</p> $dieFooterLoginAgain");

    
// Login exists. NEW SQL, FIND USER + PASSWORD
    mysqli_free_result($results);

    $sql = "SELECT * FROM CPS3740.Customers WHERE login = '$login' AND password = '$pass'";

    $results = mysqli_query($conn, $sql)                       OR die("<p style=\"font-size: 150%\">SQL Query Error or Database connection Error. Please try again.</p> $dieFooterGoBack");

    if(mysqli_num_rows($results) == 0) 
        die("<p style=\"font-size: 150%\">Password '<b>$pass</b>' does not match for existing login '<b>$login</b>'</p> $dieFooterLoginAgain"); 
}

// COOKIE LOG IN
else {
    $sql = "SELECT * FROM CPS3740.Customers WHERE id='" . $_COOKIE['cid'] . "'";

    $results = mysqli_query($conn, $sql)                       OR die("<p style=\"font-size: 150%\">SQL Query Error or Database connection Error. Please try again.</p> $dieFooterGoBack");

    if(mysqli_num_rows($results) == 0)
         die("<p style=\"font-size: 150%\">Bad Cookie. Please Log in Again.</p> $dieFooterLoginAgain");
}

// USER AUTHENTICATED. SAVE COOKIES, INITIALIZE VARIABLES
    $row = mysqli_fetch_assoc($results);

    setcookie("cid", $row["id"], time() + (60 * 10)); // 10 Minutes Expire
    setcookie("name", $row["name"], time() + (60 * 10));

    $name = $row["name"];
    
    date_default_timezone_set('America/New_York'); 
    $age  = date_diff(date_create($row["DOB"]), date_create('today'))->y;
    $address = $row["street"] . ", " . $row["city"] . ", " . $row["zipcode"];
    $img = $row["img"];
    $ID = $row["id"];

// USER AUTHENTICATED. Load home page.
    echo "<form action=\"logout.php\" style=\"margin-bottom: 0%\">";
    echo "\n  <input type=\"submit\" value=\"User Logout\" style=\"font-size: 125%\">";
    echo "\n</form>";

    echo "\n<br>Your IP: <b>" . $_SERVER['REMOTE_ADDR'] . "</b>";
    echo "\n<br>Your browser and OS: " . $_SERVER['HTTP_USER_AGENT'];

// TEST IF FROM KEAN
    if( substr($_SERVER['REMOTE_ADDR'], 0, 3)  == '10.' || substr($_SERVER['REMOTE_ADDR'], 0, 8) == '131.125.') 
        echo "\n<br>You <b>ARE</b> from Kean University.";
    else
        echo "\n<br>You are <b>NOT</b> from Kean University.";

    echo "\n<p style=\"font-size: 120%; margin-top: 0%; margin-bottom: 0%\">";
    echo "\n  <br>Welcome Customer: <b>$name</b>";
    echo "\n  <br>Address: $address";
    echo "\n  <br>Age: <b>$age</b>";
    echo "\n</p>";

    echo "\n\n<img src=\"data:image/jpeg;base64," . base64_encode($img) . "\"/</img>";

    echo "\n\n<hr>";


// PRINT TRANSACTIONS
    $sql = "SELECT * FROM CPS3740_2021S.Money_rivejona WHERE cid='$ID'";

    if($results = mysqli_query($conn, $sql))


    //  IF NO TRANSACTIONS
        if(($rows = mysqli_num_rows($results)) == 0) 
            echo "\n<p style=\"font-size: 120%; margin-bottom: 0%; margin-top: 0%\">There are <b>0</b> Transactions for <b>$name</b>.</p>\n";

    //  IF TRANSACTIONS FOUND
        else {

        //  Print table header    
            echo "\n<p style=\"font-size: 120%; margin-bottom: 0%; margin-top: 0%\">There are <b>$rows</b> Transactions for <b>$name</b>:</p>\n";

            echo "\n<table border=\"2\" style=\"font-size: 125%\">";
            echo "\n<tr>\n <th>ID</th>\n <th>Code</th>\n <th>Type</th>\n <th>Amount</th>\n <th>Source</th>\n <th>Date Time</th>\n <th>Note</th>\n</tr>";


        //  PRINT SQL RESULTS
            $balance = 0;

            while($row = mysqli_fetch_assoc($results)) {
                $sql = "SELECT name FROM CPS3740.Sources WHERE id='" . $row["sid"] . "'";

                if(is_null($row["note"]))
                    $row["note"] = " ";
         
   
            //  SECOND SQL QUERY FOR SOURCE INFORMATION
                if($result = mysqli_query($conn, $sql)) {

                    $row2 = mysqli_fetch_assoc($result);

                    echo "\n<tr>\n <td>".$row["mid"]."</td>\n <td>".$row["code"]."</td>\n";
                
                //  FORMAT FOR WITHDRAW
                    if($row["type"] == "W") {
                        echo " <td>Withdraw</td>\n <td style=\"color:red\" align=\"right\">-".$row["amount"]."</td>\n <td>".$row2["name"]."</td>\n <td>".$row["mydatetime"]."</td>\n <td>".$row["note"]."</td>\n</tr>";
                        $balance -= $row["amount"];
                    }

                //  FORMAT FOR DEPOSIT
                    else {
                        echo " <td>Deposit</td>\n <td style=\"color:blue\" align=\"right\">".$row["amount"]."</td>\n <td>".$row2["name"]."</td>\n <td>".$row["mydatetime"]."</td>\n <td>".$row["note"]."</td>\n</tr>";
                        
                        $balance += $row["amount"];
                    }

                }
                else
                    echo "</table><br>Error while constructing table. SQL or Connection Problem";
            }
         // TABLE FINISHED PRINTING

            echo "\n</table>\n";

            echo "\n<p style=\"font-size: 120%; margin-top: 0%\"> Total Balance: <span style=\";";
            
            if ($balance < 0)
                echo "color:red\">$balance</span> </p>\n";
            else
                echo "color:blue\">$balance</span> </p>\n";
    

        }
    else
        echo "\nError connecting to database or Error with SQL Query. Please try again.<br>";

   

    echo "\n<form action=\"add_transaction.php\" style=\"display: inline\">";
    echo "\n   <br>  <input type=\"submit\" value=\"Add Transaction\" style=\"font-size: 125%\">";
    echo "\n</form>\n";
 
    echo "\n<a href = \"display_transaction.php\">Display and update transaction</a>";
    echo "\n<a href = \"display.php\">Display stores</a><br>";

    echo "\n<form action=\"search.php\" method=\"get\">";
    echo "\n   <br>Keyword:";
    echo "\n   <input type=\"text\" name=\"keyword\" required=\"required\" style=\"font-size: 125%\">";
    echo "\n   <input type=\"submit\" value=\"Search transaction\" style=\"font-size: 125%\">";
    echo "\n</form>";

 ?>

 </div>

 </body>
 
</html>

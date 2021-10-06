<html>

 <head> <title>Update Transaction</title> </head>

 <body>

 <div style='font-size: 150%'>

 <?php

    include "dbconfig.php";

    $dieFooter      = "\n<br><a href='login.php'>Go Back</a>\n </div>\n\n </body>\n\n</html>";
    $dieFooterLogin = "\n<br><a href='.'>Login Page</a>\n </div>\n\n </body>\n\n</html>";


//  IF COOKIE NOT SET, DIE
    if(!isset($_COOKIE["cid"]))
        die("Error. Cookie invalid or expired. Please login again. $dieFooterLogin");


//  INITIALIZE VARIABLES, SQL QUERY
    $conn = mysqli_connect($host, $user, $password, $database)   OR die("Error connecting to Database. Please try again later. $dieFooter");

    $sql = "SELECT * FROM CPS3740_2021S.Money_rivejona WHERE cid=" . $_COOKIE['cid'];

    $results = mysqli_query($conn, $sql)                        OR die("Error connecting to Database. Please try again later. $dieFooter");

    if(mysqli_num_rows($results) == 0)
        die("No Transactions Found for user <b>" . $_COOKIE['name'] . "</b>. $dieFooter");
        
//  PRINT TABLE (Code modified from login.php)
    echo "\n<a href='logout.php'>User Logout</a> <br>";

    echo "\n\n<h1 style='margin-bottom: 0%'>Update Transaction</h1>";

    echo "\n\n<p style='font-size: 150%; margin-bottom: 1%; margin-top: 1.25%'>You can only update the <b>Note</b> column.</p>";

    echo "\n\n<form action='update_transaction.php' method='post'>";

    echo "\n<table style='font-size: 150%' border='2'>";

    echo "\n<tr>\n <th>ID</th>\n <th>Code</th>\n <th>Type</th>\n <th>Amount</th>\n <th>Source</th>\n <th>Date Time</th>\n <th>Note</th>\n <th>Delete</th>\n</tr>";

    $balance = 0;
    $i = 0;

    while($row = mysqli_fetch_assoc($results)) {
       $sql = "SELECT name FROM CPS3740.Sources WHERE id='" . $row["sid"] . "'";

           if(is_null($row["note"]))
               $row["note"] = "";

       //  SECOND SQL QUERY FOR SOURCE INFORMATION
           if($result = mysqli_query($conn, $sql)) {
               $row2 = mysqli_fetch_assoc($result);

               echo "\n<input type='hidden' name='mid[$i]' value='".$row['mid']."'>";

               echo "\n<tr>\n <td>".$row["mid"]."</td>\n <td>".$row["code"]."</td>\n";

           //  FORMAT FOR WITHDRAW
               if($row["type"] == "W") {
                   echo " <td>Withdraw</td>\n <td style=\"color:red\" align=\"right\">-".$row["amount"]."</td>";
                   $balance -= $row["amount"];
               }
           //  FORMAT FOR DEPOSIT
               else {
                   echo " <td>Deposit</td>\n <td style=\"color:blue\" align=\"right\">".$row["amount"]."</td>";
                   $balance += $row["amount"];
               }

               echo "\n <td>".$row2["name"]."</td>\n <td>".$row["mydatetime"]."</td>\n <td bgcolor='yellow'><input type='text' value='".$row["note"]."' name=note[$i] style='background-color:yellow; font-size: 100%'></td>\n <td><input type='checkbox' name='cdelete[$i]' value='Y' style='transform: scale(2); width: 92.5%'></td>\n</tr>";
           }
           else
               echo "</table><br>Error while constructing table. SQL or Connection Problem";

       $i++;
    }

    echo "\n</table>";

    echo "\n<input type='hidden' name='i' value='$i'><input type='hidden' name='cid' value='".$_COOKIE['cid']."'>";
    
    if($balance < 0)
        echo "\n<p style='font-size: 150%; margin-top: 1%'>Total Balance: <span style='color:red'>$balance</span></p>";
    else
        echo "\n<p style='font-size: 150%; margin-top: 1%'>Total Balance: <span style='color:blue'>$balance</span></p>";


    echo "\n<input type='submit' value='Update Transaction' style='font-size: 150%'>";
    echo "\n</form>";
    echo "\n<br><a href='login.php'>Go Back</a>";
                                                                                                                                                                                                         
 ?>

 </div>

 </body>

</html>

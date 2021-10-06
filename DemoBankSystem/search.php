<html>

 <head> <title>Search Notes</title> </head>

 <body>

 <div style='font-size: 150%'>

 <?php

    include "dbconfig.php";

    $dieFooter = "\n <a href='login.php' style='font-size: 150%'>Go Back</a>\n\n </div>\n\n </body>\n\n</html>";
    $dieFooterLogin = "\n <a href='.' style='font-size: 150%'>Login Page</a>\n\n </div>\n\n </body>\n\n</html>";

//  IF COOKIE NOT SET OR KEYWORD
    if(!isset($_COOKIE['cid']))
        die("<p style='font-size: 150%'>Error. Cookie is invalid or has expired. Please login again.</p>$dieFooterLogin");

    if(!isset($_GET["keyword"]))
        die("<p style='font-size: 150%'>Error. No search word detected.</p>$dieFooter");

    echo "<h1>Search Keyword: '" . $_GET["keyword"] . "'</h1>\n";


//  INITIALIZE VARIABLES
    $conn = mysqli_connect($host, $user, $password, $database)  OR  die("<p style='font-size: 150%'>Error connecting to database.</p>$dieFooter");

    $sql = "SELECT * FROM CPS3740_2021S.Money_rivejona WHERE cid=" . $_COOKIE['cid'];
    
    if($_GET["keyword"] != "*")
        $sql .= " AND note LIKE '%" . $_GET["keyword"] . "%'";

    $results = mysqli_query($conn, $sql) OR die("<p style='font-size: 150%'>Error connecting to database.</p>$dieFooter");


//  IF NO RESULTS
    if(mysqli_num_rows($results) == 0)
        die("<p style='font-size: 150%'>No results found.</p> $dieFooter");


//  PRINT TABLE (code from Login.php)
    echo "<table style='font-size: 150%' border='2'>";

    echo "\n<tr>\n <th>ID</th>\n <th>Code</th>\n <th>Type</th>\n <th>Amount</th>\n <th>Source</th>\n <th>Date Time</th>\n <th>Note</th>\n</tr>";

    while($row = mysqli_fetch_assoc($results)) {
        $sql = "SELECT name FROM CPS3740.Sources WHERE id='" . $row["sid"] . "'";

        if(is_null($row["note"]))
            $row["note"] = " ";

        //  SECOND SQL QUERY FOR SOURCE INFORMATION
        if($result = mysqli_query($conn, $sql)) {
            
            $row2 = mysqli_fetch_assoc($result);

            echo "\n<tr>\n <td>".$row["mid"]."</td>\n <td>".$row["code"]."</td>\n";

            //  FORMAT FOR WITHDRAW
            if($row["type"] == "W")
               echo " <td>Withdraw</td>\n <td style=\"color:red\" align=\"right\">-".$row["amount"]."</td>\n <td>".$row2["name"]."</td>\n <td>".$row["mydatetime"]."</td>\n <td>".$row["note"]."</td>\n</tr>";

            //  FORMAT FOR DEPOSIT
            else 
               echo " <td>Deposit</td>\n <td style=\"color:blue\" align=\"right\">".$row["amount"]."</td>\n <td>".$row2["name"]."</td>\n <td>".$row["mydatetime"]."</td>\n <td>".$row["note"]."</td>\n</tr>";
        }
        else
           echo "</table><br>Error while constructing table. SQL or Connection Problem";
   }

   echo "\n</table>";
   
   echo "\n<br><form action='login.php'>\n <input type='submit' value='Go Back' style='font-size: 150%'>\n</form>";


 ?>


</body>

</html>

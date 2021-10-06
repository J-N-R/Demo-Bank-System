<html> 

 <head> <title>Add Transaction</title> </head>

 <body>

 <div style = "font-size: 200%">

 <a href = "logout.php">User logout</a>

 <h1 style = "margin-top: 1%; margin-bottom: 1.25%">Add Transaction</h1>

<?php

include "dbconfig.php";


// Messages to help die
$dieFooter = "\n<br><a href='login.php'>Go Back</a>\n </div>\n </body>\n</html>"; 
$dieFooterLogin = "\n<a href='.'>Login Page</a>\n </div>\n </body>\n</html>";


if(!isset($_COOKIE['cid']))
    die("<p style='font-size: 150%'>Your cookie is expired. Please login again.</p> $dieFooterLogin");

// Initialize variables and SQL Query
$conn = mysqli_connect($host, $user, $password, $database);

$sql = "SELECT * FROM CPS3740_2021S.Money_rivejona WHERE cid=" . $_COOKIE['cid'];

$balance = 0;

// Calculate Balance
if($results = mysqli_query($conn, $sql)) 
 
    while($row = mysqli_fetch_assoc($results)) 
        if($row["type"] == "D") 
            $balance += $row["amount"]; 
        else
            $balance -= $row["amount"];

else
    die("ERROR Connecting to database. Please try again later. $dieFooter");
       

// Print form
echo "\n <span style='font-size: 125%'><b>" . $_COOKIE['name'] . "'s</b> current balance is: <b>" . $balance . "</b></span>";
echo "\n<form action = \"insert_transaction.php\" method = \"post\">";
echo "\n   <br>Transaction code: <input type = \"text\" name = \"code\" style='font-size: 100%' required><br>";
echo "\n   <span style='margin-left: 7.1rem'>";
echo "\n      Amount: <input type = \"number\" name = \"amount\" style='font-size: 100%' required><br>";
echo "\n   </span>";

echo "\n        <input type = \"radio\" id = \"deposit\" name = \"type\" value = \"D\" style='margin-left: 15.6rem; transform: scale(2); height: 3%'>";
echo "\n        <label for = \"deposit\" style='font-size: 125%'>Deposit</label>&nbsp&nbsp&nbsp"; 
echo "\n        <input type = \"radio\" id = \"withdraw\" name = \"type\" value = \"W\" style='transform: scale(2); height: 3%'>";
echo "\n        <label for = \"withdraw\" style='font-size: 125%'>Withdraw</label>\n";

$sql = "SELECT * FROM CPS3740.Sources";

// SQL Retrieve sources
if($results = mysqli_query($conn, $sql)) {

    echo "\n   <br><br><span style='margin-left: 1.3rem'>Select a Source:</span> \n   <select name = \"source\" style='font-size: 100%'>";

    while($row = mysqli_fetch_assoc($results))
        echo "\n      <option value = \"" . $row['id'] . "\">" . $row['name'] . "</option>";

    echo "\n   </select>";
}
else
    die("ERROR Connecting to database. Please try later. $dieFooter");

echo "\n\n <br><span style='margin-left: 9.75rem'>Note:</span> <input type = \"text\" name = \"note\" style='font-size: 100%'>";
echo "\n   <br><input type = \"submit\" value = \"submit\" style='font-size: 150%; width: 20.85%; margin-left: 14.7rem'>";
echo "\n</form>";

echo "\n\n<br><a href='login.php'>Go Back</a>";

?>

</div>

</body>
</html>

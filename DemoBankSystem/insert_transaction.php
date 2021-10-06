<html>

 <head></head>

 <body>

 <div style='font-size: 150%'>

 <?php

    include "dbconfig.php";

//  Message to help with die process
    $dieFooter = "\n </div>\n\n </body>\n</html>";


//  IF EXPIRED COOKIE, DIE
    if(!isset($_COOKIE['cid']))
        die("<p style='font-size: 150%'>Your cookie is expired, please login again.\n <br><a href='.'>Login Page</a></p>$dieFooter");

    
//  INITIALIZE DATABASE CONNECTION
    $conn = mysqli_connect($host, $user, $password, $database);

    
// GET BALANCE
   $sql = "SELECT * FROM CPS3740_2021S.Money_rivejona WHERE cid=" . $_COOKIE['cid'];

   $balance = 0;

   if($results = mysqli_query($conn, $sql))

      while($row = mysqli_fetch_assoc($results))
          
          if($row["type"] == "D")
              $balance += $row["amount"];
          else
              $balance -= $row["amount"];

   else
        die("ERROR Connecting to database. Please try again later. (1)$dieFooter");


//  IF TYPE NOT SELECTED, DIE
    if(!isset($_POST["type"]))
        die("<p style='font-size: 150%'>Error. Type is not set. Please try again.\n <br><a href='add_transaction.php'>Go Back</a></p>$dieFooter");


//  IF AMOUNT IS EMPTY (zero or less), DIE   (note, i made amount required in form. I felt it was a more user friendly decision then check and cancel when it is too late)
    if($_POST["amount"] <= 0)
        die("<p style='font-size: 150%'>Error. Empty deposit/withdraw amount. Please try again. (invalid amount: " . $_POST["amount"] . ". must be a positive number)\n <br><a href='add_transaction.php'>Go Back</a> </p>$dieFooter");


//  IF WITHDRAW AMOUNT GREATER THAN BALANCE, DIE
    if($_POST["type"] == "W" && $_POST["amount"] > $balance)
        die("<p style='font-size: 150%'>Error. Your balance, <b>$balance</b>, is too small for your withdraw amount, <b>" . $_POST["amount"] . "</b>. \n <br><a href='add_transaction.php'>Go Back</a></p>$dieFooter");


//  IF CODE EXISTS ALREADY
    $sql = "SELECT * FROM CPS3740_2021S.Money_rivejona WHERE code='" . $_POST["code"] . "'";

    if($results = mysqli_query($conn, $sql)) {
        if(mysqli_num_rows($results) > 0)
            die("<p style='font-size: 150%'>Error. Code already exists in database. Please try again with a different code.\n <br><a href='add_transaction.php'>Go Back</a></p>$dieFooter");
    }
    else
        die("ERROR Connecting to database. Please try again later. (2)$dieFooter");


//  ALL GOOD. INSERT INTO DATABASE
    $sql = "INSERT INTO CPS3740_2021S.Money_rivejona (code, cid, sid, type, amount, mydatetime";

    if(isset($_POST["note"]))
        $sql .= ", note) values ( '" . $_POST["code"] . "', " . $_COOKIE['cid'] . ", " . $_POST["source"] . ", '" . $_POST["type"] . "', " . $_POST["amount"] . ", NOW(), '" . $_POST["note"] . "')";

    else
        $sql .= ") values ( '" . $_POST["code"] . "', " . $_COOKIE['cid'] . ", " . $_POST["source"] . ", '" . $_POST["type"] . "', " . $_POST["amount"] . ", NOW())"; 


    if(mysqli_query($conn, $sql)) 
        
        if($_POST["type"] == "D")
            die("<p style='font-size: 150%'>Success! Deposited <b>$ " . $_POST["amount"] . "</b> into <b>" . $_COOKIE["name"] . "'s</b> account.\n <br> <a href='add_transaction.php'>Go Back</a></p>$dieFooter");
        else
            die("<p style='font-size: 150%'>Success! Withdrew <b>$ " . $_POST["amount"] . "</b> from <b>" . $_COOKIE["name"] . "'s</b> account.\n <br> <a href='add_transaction.php'>Go Back</a></p>$dieFooter");
    
    else
        die("ERROR Connecting to database, or error with transaction. Please try again later. (3)$dieFooter");
    

        

 ?>

 </div>
 
 </body>

</html>

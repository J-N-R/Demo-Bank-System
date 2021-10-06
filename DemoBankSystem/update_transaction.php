<html>
 
 <head></head>

 <body>

 <div style='font-size: 150%'>

 <?php

   include "dbconfig.php";


   $dieFooter      = "\n <br><a href='login.php'>Go Back</a>\n </div>\n\n </body>\n\n</html>";

   echo "<a href='logout.php'>User Logout</a><br>";
   
   $conn = mysqli_connect($host, $user, $password, $database)     OR die("Error connecting to database. $dieFooter");

   $deleted = 0;
   $updated = 0;

// ITERATE THROUGH EVERY TRANSACTION. UPDATE / DELETE AS NECESSARY.
   for($i=0; $i < $_POST["i"]; $i++)  {
       $sql = "SELECT * FROM CPS3740_2021S.Money_rivejona WHERE mid=" . $_POST['mid'][$i];
       
       $results = mysqli_query($conn, $sql)                       OR die("Error connecting to databse. $dieFooter");

       $row = mysqli_fetch_assoc($results)                        OR die("Error connecting to databse. $dieFooter");


    // DELETE IF CHECKMARK SELECTED   
       if(isset($_POST['cdelete'][$i])) {
           $sql = "DELETE FROM CPS3740_2021S.Money_rivejona WHERE mid=" . $_POST['mid'][$i];

           if(mysqli_query($conn, $sql)) {
               echo "\nSuccessfully Deleted Transaction ID: <b>" . $_POST['mid'][$i] . "</b> Code: <b>$sql</b><br>";
               $deleted++;
           }
           else
               echo "\nError Connecting to database for transaction $i<br>";
       }

    // UPDATE NOTE IF NOT DELETED
       elseif($row['note'] != $_POST['note'][$i]) {
           $sql = "UPDATE CPS3740_2021S.Money_rivejona SET note ='" . $_POST['note'][$i] . "', mydatetime = NOW() WHERE mid=" . $_POST['mid'][$i];
           
           if(mysqli_query($conn, $sql)) {
              echo "\nSuccessfully Updated Transaction ID: <b>" . $_POST['mid'][$i] . "</b> Code: <b>$sql</b><br>";
              $updated++;
           }
           else
              echo "\nError Connecting to database for transaction $i<br>";
       }
    // SKIP IF NEITHER
    }
   
    echo "\n<br>Finished deleting <b>$deleted</b> records and updating <b>$updated</b> transactions.";

    echo "\n<br><br><a href='login.php'>Go Back</a>";
 ?>

 </div>

 </body>

</html>

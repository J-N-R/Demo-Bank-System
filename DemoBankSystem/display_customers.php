<html>

 <head> <title>Display Customers rivejona</title> </head>

 <body>

 <?php

    include "dbconfig.php";
    

//  Die message to help print information if the program terminates early
    $dieFooter = "\n <p style='font-size: 250%; color:RED'>Error Connecting to Database.</p>\n\n </body> \n\n</html>";
    echo "<h1 style='font-size: 350%; margin-left: 32.5rem'>All Existing Customers</h1>";


//  Initialize SQL connection and query
    $conn = mysqli_connect($host, $user, $password, $database)      OR die($dieFooter);

//  (All but User Avatar)
    $sql = "SELECT id, name, login, password, DOB, gender, street, city, state, zipcode FROM CPS3740.Customers";
    $results = mysqli_query($conn, $sql)                            OR die($dieFooter);


//  If no results, print "No results," then die.
    if(mysqli_num_rows($results) == 0)
        die("<p style='font-size: 250%; color:RED'>No Customers Found</p>");


//  Otherwise, print table
    echo "\n <table border='2' style='font-size: 200%'>";

    echo "\n <tr>\n  <th>ID</th>\n  <th>Name</th>\n  <th>Login</th>\n  <th>Password</th>\n  <th>DOB</th>\n  <th>Gender</th>\n  <th>Street</th>\n  <th>City</th>\n  <th>State</th>\n  <th>Zipcode</th>\n </tr>";

    while($row = mysqli_fetch_assoc($results)) {

        echo "\n <tr>";

        foreach($row as $value)
            echo "\n  <td>$value</td>";

        echo "\n </tr>"; 
        
    }

    echo "\n </table>";

    
 ?>

 </body>

</html>

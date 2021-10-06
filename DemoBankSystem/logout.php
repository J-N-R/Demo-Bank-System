<html>

 <head> <title> rivejona Project Logout </title> </head>

 <body>


 <?php

 if(isset($_COOKIE['cid']))
     setcookie("cid", "", time() - 3600);

 if(isset($_COOKIE['name']))
     setcookie("name", "", time() - 3600);

 echo "<p style = \"font-size: 250%\"> You have Successfully been logged out.<br>";

 echo "\n<form action=\".\">";
 echo "\n   <input type=\"submit\" value=\"Go Back to login\" style=\"font-size: 250%\">";
 echo "\n</form>";
 

 ?>


 </body>

</html>

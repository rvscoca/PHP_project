<?php
session_start();
if(isset($_SESSION['username']))
{
echo '<a href="index.php">{index}</a> | ';
echo "Bonjour " .$_SESSION['username'] . " | ";
echo "<a href=\"logout.php\">Logout</a> | ";
if ($_SESSION['admin'] != 0) {
  echo '<a href="admin.php">Administration</a> <br/><br/>';
}
}
include_once "db_con.php";
include_once "connect_db.php";
$con = new DB_con();
?>


<!DOCTYPE html>
<html>
  <head>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  </head>
  <body>
    <section id="wrapper">
      <p>
        <a href="products.php">Products</a>
      </p>

    <?php
    $con->displayAllProd();
    ?>

    </section>
  </body>
</html>

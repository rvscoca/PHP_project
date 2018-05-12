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
    <title>Inscription</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  </head>
  <body>
    <section id="wrapper">
      <form method="post" action="inscription.php"> <br /><br />
        <span class="title">Name :</span><br /><input type="text" required placeholder="username" name="username" /> <br /><br />
        <span class="title">Email :</span><br /><input type="email" required placeholder="email" name="email" /> <br /><br />
        <span class="title">Password :</span><br /><input type="password" required placeholder="password" name="password"/> <br /><br />
        <span class="title">Password confirmation:</span><br /><input type="password" required placeholder="password_confirmation" name="password_confirmation" /> <br /><br />
        <input type="submit" value="Register" class="myButton" />
      </form>
    </section>
  </body>
</html>

<?php
  if(isset($_POST['username'])
  && isset($_POST['email'])
  && isset($_POST['password'])
  && isset($_POST['password_confirmation'])){
    $con = new DB_con();
    $con->createUser($_POST['username'], $_POST['email'], $_POST['password'], $_POST['password_confirmation'], 0);
  }
?>

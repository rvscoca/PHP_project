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
    <title>Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  </head>
  <body>
    <section id="wrapper">
      <form method="post">
        <span class="title">Email :</span><br />
        <input name="email" type="text" placeholder="email" required /> <br /><br />
        <span class="title">Password :</span><br />
        <input name="password" type="password" placeholder="password" required /> <br /><br />
        <input type="submit" value="connect"/>
      </form>
    </section>
  </body>
</html>

<?php

  if(isset($_POST['email'], $_POST['password']))
  {
    $con = new DB_con();
    $queryLogin = $con->bdd->prepare('SELECT email, password, username, admin FROM users');
    $queryLogin->execute();
    $login = $queryLogin->fetchAll();
    foreach($login as $value) {
      if($_POST['email'] == $value['email'] && password_verify($_POST['password'], $value['password']))
      {
        $_SESSION['email'] = $value['email'];
        $_SESSION['username'] = $value['username'];
        $_SESSION['admin'] = $value['admin'];
        header('Location: index.php');
      }
      else {
        echo "User not found. Try again !";
      }
    }

}
?>

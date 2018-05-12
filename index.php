<?php
session_start();
if(isset($_SESSION['username']))
{
include_once "db_con.php";
echo "Bonjour " .$_SESSION['username'] . " | ";
echo "<a href=\"logout.php\">Logout</a> | ";
if ($_SESSION['admin'] != 0) {
  echo '<a href="admin.php">Administration</a> <br/><br/>';
}
}
else {
  header("Location: login.php");
}

?>

<!DOCTYPE html>
<html>
  <head>
  </head>
  <body>
    <section id="wrapper">

      <form method="post" action="index.php">
        <input type="text" placeholder="search products" name="searchQ" /><br/>
        <input type="number" placeholder="Price min" name="PriceMin" min="0" value = "0"/><br/>
        <input type="number" placeholder="Price max" name="PriceMax" min="0" value = "1000"/><br/>
        <select name="catId">
          <?php
          $con = new DB_con();
          $queryProd = $con->bdd->prepare('SELECT name, id FROM categories');
          $queryProd->execute();
          $prodinfos = $queryProd->fetchAll();
          foreach($prodinfos as $value)
          {
          echo "<option value='" . $value['id'] ."'>" .$value['name'] ."</option>";
          }
          ?>
        </select>
        <input type="submit" value="Display" name="display" />
      </form>
      <?php
        if(isset($_POST['searchQ']))
        {
          $con = new DB_con();
          $con->searchQ($_POST['searchQ'], $_POST['catId'], $_POST['PriceMax'], $_POST['PriceMin']);
        }
      ?>

      <br/><br/>
      <a href="products.php">Products</a> <br/>


    </section>
  </body>
</html>

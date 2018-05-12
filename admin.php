<?php
session_start();
if(isset($_SESSION['username']))
{
echo '<a href="index.php">{index}</a> | ';
echo "Bonjour " .$_SESSION['username'] . " | ";
echo "<a href=\"logout.php\">Logout</a> | ";
if ($_SESSION['admin'] != 0) {
  echo '<a href="admin.php">Administration</a> <br/><br/>';

include_once "db_con.php";
include_once "connect_db.php";

$con = new DB_con();
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Admin</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  </head>
  <body>
    <div id="create_user"> <!--CREER USER-->

      <h2>Create a new user :</h2>
        <form method="post" action="admin.php">
          <input type="text" required placeholder="Username" name="username_to_create" />
          <input type="text" required placeholder="Email" name="email_to_create" />
          <input type="password" required placeholder="Password" name="password_to_create"/>
          <input type="password" required placeholder="Password confirmation" name="password_confirmation_to_create" /><br /><br />
          <span class="is_admin">Is admin ? | </span>
          Yes <input type="radio" value="1" name="admin" />
          No <input type="radio" value="0" name="admin" checked/><br /><br />
          <input type="submit" value="Create" />
        </form>
        <?php
          if(isset($_POST['username_to_create'])
          && isset($_POST['email_to_create'])
          && isset($_POST['password_to_create'])
          && isset($_POST['password_confirmation_to_create']))
          {
            $con->createUser($_POST['username_to_create'], $_POST['email_to_create'], $_POST['password_to_create'], $_POST['password_confirmation_to_create'], $_POST['admin']);
          }
        ?>
    </div>

    <div id="delete_user"> <!--DELETE USER-->
      <h2>Delete a user :</h2>
        <form method="post" action="admin.php">
          <input type="text" placeholder="Username" name="user_to_delete" />
          <input type="submit" name="Delete" value="Delete" />
        </form>
        <?php
          if(isset($_POST['user_to_delete']))
          {
            $con->deleteUser($_POST['user_to_delete']);
          }
        ?>
    </div>

    <div id="edit_user"> <!--EDIT USER-->
      <h2>Edit a user :</h2>
        <form method="post" action="admin.php">
          <input type="text" placeholder="Username" name="user_to_edit" required/>
          <input type="text" placeholder="New username" name="new_username" />
          <input type="email" placeholder="New email" name="new_email" />
          <input type="password" name="new_password" placeholder="New password" />
          <input type="password" name="new_password_confirmation" placeholder="New password confirmation" /><br /><br />
          <span class="is_admin">Is admin ? | </span>
          Yes <input type="radio" value="1" name="is_admin" />
          No <input type="radio" value="0" name="is_admin" checked/><br /><br />
          <input type="submit" value="Edit" name="edit" />
        </form>
        <?php
          if(isset($_POST['user_to_edit']))
          {
            $con->editUser();
          }
        ?>
    </div>
    <div id="display_user"> <!--DISPLAY USER-->
      <h2>Display a user :</h2>
        <form method="post" action="admin.php">
          <input type="text" placeholder="username" name="user_to_display" />
          <input type="submit" value="Display" name="display" />
        </form>
        <?php
          if(isset($_POST['user_to_display']))
          {
            $con->displayUser();
          }
        ?>
    </div>

<br />-------------------------------------------------------------------------------<br />
<!--PRODUCT MANAGEMENT-->

<div id="create_user"> <!--CREATE PRODUCT-->
  <h2>Create a new Product :</h2>
    <form method="post" action="admin.php">
      <input type="text" required placeholder="Product name" name="prodName" />
      <input type="number" required placeholder="Product price" name="prodPrice" />
      <select name="category_id">
        <?php
        $queryProd = $con->bdd->prepare('SELECT name, id FROM categories');
        $queryProd->execute();
        $prodinfos = $queryProd->fetchAll();
        foreach($prodinfos as $value)
        {
        echo "<option value='" . $value['id'] ."'>" .$value['name'] ."</option>";
        }
        ?>
      </select>
      <input type="submit" value="Add product" />
    </form>
    <?php
      if(isset($_POST['prodName'])
      && isset($_POST['prodPrice'])
      && isset($_POST['category_id']))
      {
        $con->addProd($_POST['prodName'], $_POST['prodPrice'], $_POST['category_id']);
      }
    ?>
</div>

<div id="delete_product"> <!--DELETE PRODUCT-->
  <h2>Delete a product :</h2>
    <form method="post" action="admin.php">
      <input type="text" placeholder="Product name" name="product_to_delete" />
      <input type="submit" name="Delete" value="Delete" />
    </form>
    <?php
      if(isset($_POST['product_to_delete']))
      {
        $con->deleteProd($_POST['product_to_delete']);
      }
    ?>

    <div id="edit_product"> <!--EDIT PRODUCT-->
      <h2>Edit a product :</h2>
        <form method="post" action="admin.php">
          <input type="text" required placeholder="Product name" name="prodName_to_edit" />
          <input type="text" required placeholder="New product name" name="prodName2" />
          <input type="number" required placeholder="Product price" name="prodPrice2" />
          <select name="category_id2">
            <?php
            $queryProd = $con->bdd->prepare('SELECT name, id FROM categories');
            $queryProd->execute();
            $prodinfo = $queryProd->fetchAll();
            foreach($prodinfo as $value)
            {
            echo "<option value='" . $value['id'] ."'>" .$value['name'] ."</option>";
            }
            ?>
          </select>
          <input type="submit" value="Edit product" />
        </form>
        <?php
          if(isset($_POST['prodName_to_edit'])
          && isset($_POST['prodName2'])
          && isset($_POST['prodPrice2'])
          && isset($_POST['category_id2'])){
            $con->editProd();
          }
        ?>
    </div>

    <div id="display_product"> <!--DISPLAY PRODUCT-->
      <h2>Display a product :</h2>
        <form method="post" action="admin.php">
          <input type="text" placeholder="Product name" name="product_to_display" />
          <input type="submit" value="Display" name="display" />
        </form>
        <?php
          if(isset($_POST['product_to_display']))
          {
            $con->displayProd($_POST['product_to_display']);
          }
        ?>
    </div>
<p>
    <div id="create_user"> <!--CREATE CATEGORY-->
      <h2>Create a new CATEGORY :</h2>
        <form method="post" action="admin.php#create_user">
          <input type="text" required placeholder="Name of Category " name="cat_name" />
          <select name="parent_id">
            <?php
            $queryProd = $con->bdd->prepare('SELECT name, id FROM categories');
            $queryProd->execute();
            $prodinfos = $queryProd->fetchAll();
            echo "<option value='0' selected> _Parent </option>";
            foreach($prodinfos as $value)
            {
            echo "<option value='" . $value['id'] ."'>" .$value['name'] ."</option>";
            }
            ?>
          </select>
          <input type="submit" value="Add product" />
        </form>
        <?php
          if(isset($_POST['cat_name']))
          {
            $con->addCat($_POST['cat_name'], $_POST['parent_id']);
          }
        ?>
    </div>





  </body>
</html>

<?php
}
}
else{
  header('Location: index.php');
}
?>

<?php
include_once("connect_db.php");

class DB_con
{
  public $bdd;
  public $servername = "localhost";
  public $username = "root";
  public $password = "root";
  public $port = 3306;
  public $dbname = "pool_php_rush";

  public function __construct()
  {
    $this->bdd = connect_db($this->servername, $this->username, $this->password, $this->port, $this->dbname);
  }

  public function createUser($username, $email, $passwd, $passwd2, $admin)
  {
    if ($this->checkUsername($username) && $this->checkMail($email) && $this->checkPasswd($passwd, $passwd2))
    {
      $hash = password_hash($passwd, PASSWORD_BCRYPT);
      $sql = $this->bdd->prepare('INSERT INTO users (username, password, email, admin) VALUES (?, ?, ?, ?)');
      $sql->bindParam(1, $username);
      $sql->bindParam(2, $hash);
      $sql->bindParam(3, $email);
      $sql->bindParam(4, $admin);
      $sql->execute();
      echo "User created.";
    }
  }

  public function deleteUser($username)
  {
    if(isset($_POST['user_to_delete']))
    {
      $deleteUser = $this->bdd->prepare('DELETE FROM users WHERE username = ?');
      $deleteUser->bindParam(1, $_POST['user_to_delete']);
      $deleteUser->execute();
      echo "User deleted";
    }
  }

  public function editUser()
  {
    if(isset($_POST['username']))
    {
      if (!isset($_POST['new_username']) && !isset($_POST['new_email']) && isset($_POST['is_admin']))
      {
        $editUser = $this->bdd->prepare('UPDATE users SET admin = ? WHERE username = ?');
        $editUser->execute(array($_POST['is_admin'], $_POST['user_to_edit']));
        echo "User admin edited";
      }
    }
    if(!isset($_POST['new_username']) && isset($_POST['new_email']) && isset($_POST['is_admin']))
    {
      if ($this->checkMail($_POST['new_email']))
      {
        $editUser = $this->bdd->prepare('UPDATE users SET email = ?, admin = ? WHERE username = ?');
        $editUser->execute(array($_POST['new_email'], $_POST['is_admin'], $_POST['user_to_edit']));
        echo "User Mail et admin edited";
      }
    }
    if(isset($_POST['new_username']) && isset($_POST['new_email']) && isset($_POST['is_admin']))
    {
      if ($this->checkMail($_POST['new_email']) )
      {
        $editUser = $this->bdd->prepare('UPDATE users SET email = ?, username = ?, admin = ? WHERE username = ?');
        $editUser->execute(array($_POST['new_email'], $_POST['new_username'], $_POST['is_admin'], $_POST['user_to_edit']));
        echo "Username, email et admin edited";
      }
    }
    if(isset($_POST['new_username']) && !isset($_POST['new_email']) && !isset($_POST['is_admin']))
    {
      $editUser = $this->bdd->prepare('UPDATE users SET username = ? WHERE username = ?');
      $editUser->execute(array($_POST['new_username'],  $_POST['user_to_edit']));
      echo "Username edited";
    }
    if(isset($_POST['new_username']) && isset($_POST['new_email']) && !isset($_POST['is_admin']))
    {
      if ($this->checkMail($_POST['new_email']))
      {
        $editUser = $this->bdd->prepare('UPDATE users SET username = ?, email = ? WHERE username = ?');
        $editUser->execute(array($_POST['new_username'], $_POST['new_email'], $_POST['user_to_edit']));
        echo "Username et email edited";
      }
    }
    if(!isset($_POST['new_username']) && isset($_POST['new_email']) && !isset($_POST['is_admin']))
    {
      if ($this->checkMail($_POST['new_email']) )
      {
        $editUser = $this->bdd->prepare('UPDATE users SET email = ? WHERE username = ?');
        $editUser->execute(array($_POST['new_email'], $_POST['user_to_edit']));
        echo "User email edited";
      }
    }
    if(isset($_POST['new_username']) && !isset($_POST['new_email']) && isset($_POST['is_admin']))
    {
      $editUser = $this->bdd->prepare('UPDATE users SET username = ?, admin = ? WHERE username = ?');
      $editUser->execute(array($_POST['new_username'],$_POST['is_admin'], $_POST['user_to_edit']));
      echo "Username et admin edited";
    }
  }

  public function displayUser()
  {
    if(isset($_POST['user_to_display'])){
      $displayUser = $this->bdd->prepare('SELECT username, email FROM users WHERE username = ?');
      $displayUser->bindParam(1, $_POST['user_to_display']);
      $displayUser->execute();
      $infos = $displayUser->fetchAll();
      if (count($infos) == 0)
      {
        echo "User not found...";
        return FALSE;
      }
      foreach ($infos as $value)
      {
        echo "User : " . $value['username'] . " | Mail : " . $value['email'] . "." ;
      }
    }
  }

  public function checkUsername($username)
  {
    $checkUsername = $this->bdd->prepare('SELECT username FROM users');
    $checkUsername->execute();
    $userUsername = $checkUsername->fetchAll();
    foreach($userUsername as $value)
    {
      if(isset($username) && ($username == $value['username']))
      {
        echo "Username already taken.";
        return FALSE;
      }
    }
    if (isset($username) && (strlen($username) <= 3 || strlen($username) > 10)){
      echo "Invalid username.";
      return FALSE;
    }
    else
    {
      return TRUE;
    }
  }

  public function checkPasswd($passwd, $passwd2)
  {
    if (isset($passwd) && isset($passwd2) && ((strlen($passwd) < 3 || strlen($passwd) > 10) || $passwd != $passwd2))
    {
      echo "Wrong password.";
      return FALSE;
    }
    else
    {
      return TRUE;
    }
  }

  public function checkMail($mail)
  {
    $checkMail = $this->bdd->prepare('SELECT email FROM users');
    $checkMail->execute();
    $userMail = $checkMail->fetchAll();
    foreach($userMail as $value) {
      if(isset($mail) && ($mail == $value['email']))
      {
        echo "Email already taken.";
        return FALSE;
      }
    }
    if (isset($mail) && (filter_var($mail, FILTER_VALIDATE_EMAIL) == FALSE))
    {
      echo "Wrong email.";
      return FALSE;
    }
    else
    {
      return TRUE;
    }
  }

  public function addProd($name, $price, $category_id)
  {
      $sql = $this->bdd->prepare('INSERT INTO products (name, price, category_id) VALUES (?, ?, ?)');
      $sql->execute(array($name, $price, $category_id));
      echo "Product created.";
  }

  public function addCat($name, $parent_id)
  {
      $conCat = $this->bdd->prepare('INSERT INTO categories (name, parent_id) VALUES (?, ?)');
      $conCat->execute(array($name, $parent_id));
      echo "Product created.";
  }


  public function deleteProd($prod_to_del)
  {
    $deleteProd = $this->bdd->prepare('DELETE FROM products WHERE name = ?');
    $deleteProd->bindParam(1, $prod_to_del);
    $deleteProd->execute();
    echo "Product deleted.";
  }

  public function editProd()
  {
    if(isset($_POST['prodName2']) && isset($_POST['prodPrice2']) && isset($_POST['category_id2']))
    {
      $editProd = $this->bdd->prepare('UPDATE products SET name = ?, price = ?, category_id = ? WHERE name = ?');
      $editProd->bindParam(1, $_POST['prodName2']);
      $editProd->bindParam(2, $_POST['prodPrice2']);
      $editProd->bindParam(3, $_POST['category_id2']);
      $editProd->bindParam(4, $_POST['prodName_to_edit']);
      $editProd->execute();
      echo "Product edited.";
    }
    elseif(!isset($_POST['prodName2']) || !isset($_POST['prodPrice2']) || !isset($_POST['category_id2']))
    {
      echo "Please enter product parameters !";
    }
  }

  public function displayProd($prod_name)
  {
    $displayProd = $this->bdd->prepare('SELECT products.name as nameProd, categories.name as nameCat, price FROM products INNER JOIN categories ON category_id = categories.id WHERE products.name = ?');
    $displayProd->execute(array($prod_name));
    $infosProduct = $displayProd->fetchAll();
    if (count($infosProduct) == 0)
    {
      echo "Product not found.";
      return FALSE;
    }
    foreach ($infosProduct as $value)
    {
      echo "Product Category : " . $value['nameCat'] ." | Product name : " . $value['nameProd'] . " | Price : " . $value['price'] . " €." ;
    }
  }

  public function displayAllProd()
  {
    $displayProd = $this->bdd->prepare('SELECT products.name as nameProd, categories.name as nameCat, price FROM products INNER JOIN categories ON category_id = categories.id');
    $displayProd->execute();
    $infosProduct = $displayProd->fetchAll();
    foreach ($infosProduct as $value)
    {
      echo "<a href=\"products.php?product=" . $value['nameProd'] . "\">" . $value['nameProd'] . "</a><br />" ;

    if (isset($_GET['product']) && $_GET['product'] == $value['nameProd']) {
      echo "Product Category : " . $value['nameCat'] ." | Product name : " . $value['nameProd'] . " | Price : " . $value['price'] . " €.<br />" ;
    }
    }
  }

  public function checkProdPrice($prod_price)
  {
    if($prod_price >= 0)
    {
      return TRUE;
    }
    else
    {
      echo "Invalid Product price.";
      return FALSE;
    }
  }


    public function searchQ($searchQ, $catId, $priceMax, $priceMin){
      $query = $this->bdd->prepare('SELECT products.name as prodName, categories.name as catName, products.price
      FROM products INNER JOIN categories ON products.category_id = categories.id
      WHERE products.name LIKE ? AND products.category_id = ? AND (products.price <= ? and products.price >= ?)
      ORDER BY products.price ASC');
      $query->bindValue(1, "%$searchQ%");
      $query->bindValue(2, "$catId");
      $query->bindValue(3, "$priceMax");
      $query->bindValue(4, "$priceMin");
      $query->execute();
      $results = $query->fetchAll();
      foreach ($results as $value){
          echo "<pres>" . $value['catName'] . " \t||\t " .$value['prodName'] . " \t||\t " .$value['price'] . "€ </pre><br />\n";
        }
        if(!isset($value['prodName'])){
        echo 'Nothing found';
      }
      }



}
?>

<?php
 session_start();
include_once 'databases/database.php';
 
$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 
//Create
if (isset($_POST['create'])) {
  if($_SESSION['role'] == 0 || $_SESSION['role'] == 1){
    try {

        $stmt = $conn->prepare("INSERT INTO tbl_products_a190409(FLD_PRODUCT_ID,
          FLD_PRODUCT_NAME, FLD_PRICE, FLD_BRAND, FLD_TYPE,
          FLD_QUANTITY, FLD_WARRANTYLENGTH) VALUES(:pid, :name, :price, :brand,
          :type, :quantity, :warrantylength)");
      
          $stmt->bindParam(':pid', $pid, PDO::PARAM_STR);
          $stmt->bindParam(':name', $name, PDO::PARAM_STR);
          $stmt->bindParam(':price', $price, PDO::PARAM_STR);
          $stmt->bindParam(':brand', $brand, PDO::PARAM_STR);
          $stmt->bindParam(':type', $type, PDO::PARAM_STR);
          $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
          $stmt->bindParam(':warrantylength', $warrantylength, PDO::PARAM_INT);



          
          $pid = $_POST['pid'];
          $name = $_POST['name'];
          $price = $_POST['price'];
          $brand = $_POST['brand'];
          $type = $_POST['type'];
          $quantity = $_POST['quantity'];
          $warrantylength = $_POST['warrantylength'];


          $uploadDir = 'static/images/';
          $imageFilename = $pid . '.jpg'; 
          $uploadFile = $uploadDir . $imageFilename;
          move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile);
          
          $stmt->execute();
      }
  
    catch(PDOException $e)
    {
        echo "Error: " . $e->getMessage();
    }
  }
  else{
    echo "<script>alert('You are not allowed to add product, because you do not have the permission.');</script>";
  }
}
 


//Update
if (isset($_POST['update'])) {
  if($_SESSION['role'] == 0 || $_SESSION['role'] == 1){
    try {
  
        $stmt = $conn->prepare("UPDATE tbl_products_a190409 SET FLD_PRODUCT_ID = :pid,
          FLD_PRODUCT_NAME = :name, FLD_PRICE = :price, FLD_BRAND = :brand,
          FLD_TYPE = :type, FLD_QUANTITY = :quantity, FLD_WARRANTYLENGTH = :warrantylength
          WHERE FLD_PRODUCT_ID = :oldpid");
      
          $stmt->bindParam(':pid', $pid, PDO::PARAM_STR);
          $stmt->bindParam(':name', $name, PDO::PARAM_STR);
          $stmt->bindParam(':price', $price, PDO::PARAM_STR);
          $stmt->bindParam(':brand', $brand, PDO::PARAM_STR);
          $stmt->bindParam(':type', $type, PDO::PARAM_STR);
          $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
          $stmt->bindParam(':warrantylength', $warrantylength, PDO::PARAM_INT);
          $stmt->bindParam(':oldpid', $oldpid, PDO::PARAM_STR);
          
          $pid = $_POST['pid'];
          $name = $_POST['name'];
          $price = $_POST['price'];
          $brand = $_POST['brand'];
          $type = $_POST['type'];
          $quantity = $_POST['quantity'];
          $warrantylength = $_POST['warrantylength'];
          $oldpid = $_POST['oldpid'];

          // #####################################################################
          //UPDATE IMAGE
          $uploadDir = 'static/images/';
          $imageFilename = $pid . '.jpg';
          $uploadFile = $uploadDir . $imageFilename;
          move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile);
          // #####################################################################
          
          $stmt->execute();
      
          header("Location: products.php");
      }
  
    catch(PDOException $e)
    {
        echo "Error: " . $e->getMessage();
    }
  }
  else{
    echo "<script>alert('You are not allowed to update product, because you do not have the permission.');</script>";
  }
}
 






//Delete
if (isset($_GET['delete'])) {
  if($_SESSION['role'] == 0 || $_SESSION['role'] == 1){
    try {
          $stmt = $conn->prepare("DELETE FROM tbl_products_a190409 WHERE FLD_PRODUCT_ID = :pid");
          $stmt->bindParam(':pid', $pid, PDO::PARAM_STR);
          $pid = $_GET['delete'];
          
          unlink('static/images/' . $pid . '.jpg');
          $stmt->execute();
      
          header("Location: products.php");
      }
  
    catch(PDOException $e)
    {
        echo "Error: " . $e->getMessage();
    }
  }
  else{
    echo "<script>alert('You are not allowed to delete product, because you do not have the permission.');</script>";
  }
}
 


//Edit
if (isset($_GET['edit'])) {
 
  try {
 
        $stmt = $conn->prepare("SELECT * FROM tbl_products_a190409 WHERE FLD_PRODUCT_ID = :pid");
        
        $stmt->bindParam(':pid', $pid, PDO::PARAM_STR);
        
        $pid = $_GET['edit'];
        
        $stmt->execute();
    
        $editrow = $stmt->fetch(PDO::FETCH_ASSOC);
    }
 
  catch(PDOException $e)
  {
      echo "Error: " . $e->getMessage();
  }
}
 
  $conn = null;
?>
<?php
  session_start();
include_once 'databases/database.php';
 
$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 
//Create
if (isset($_POST['addproduct'])) {
 
  try {
 
    //select all from tbl_orders_details_a190409 and check whether the product is already exits. if yes just add the quantity. else if you just insert as usual
    $stmt = $conn->prepare("SELECT * FROM tbl_orders_details_a190409 WHERE FLD_ORDER_ID = :oid AND FLD_PRODUCT_ID = :pid");
    $stmt->bindParam(':oid', $oid, PDO::PARAM_STR);
    $stmt->bindParam(':pid', $pid, PDO::PARAM_STR);
    $oid = $_POST['oid'];
    $pid = $_POST['pid'];
    $stmt->execute();
    $result = $stmt->fetchAll();

    //if quantity is zero or less than zero, show error message
    if ($_POST['quantity'] <= 0) {
      echo "";
    }
    else{
      
      if ($stmt->rowCount() > 0) {
        $stmt = $conn->prepare("UPDATE tbl_orders_details_a190409 SET FLD_ORDER_DETAIL_QUANTITY = FLD_ORDER_DETAIL_QUANTITY + :quantity WHERE FLD_ORDER_ID = :oid AND FLD_PRODUCT_ID = :pid");
        $stmt->bindParam(':oid', $oid, PDO::PARAM_STR);
        $stmt->bindParam(':pid', $pid, PDO::PARAM_STR);
        $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
        $oid = $_POST['oid'];
        $pid = $_POST['pid'];
        $quantity= $_POST['quantity'];
        $stmt->execute();
      }
      else{

        $stmt = $conn->prepare("INSERT INTO tbl_orders_details_a190409(FLD_ORDER_DETAIL_ID,
          FLD_ORDER_ID, FLD_PRODUCT_ID, FLD_ORDER_DETAIL_QUANTITY) VALUES(:did, :oid,
          :pid, :quantity)");
      
        $stmt->bindParam(':did', $did, PDO::PARAM_STR);
        $stmt->bindParam(':oid', $oid, PDO::PARAM_STR);
        $stmt->bindParam(':pid', $pid, PDO::PARAM_STR);
        $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
          
        $did = uniqid('D', true);
        $oid = $_POST['oid'];
        $pid = $_POST['pid'];
        $quantity= $_POST['quantity'];
        
        $stmt->execute();
      }
    }
  }
 
  catch(PDOException $e)
  {
      echo "Error: " . $e->getMessage();
  }
  $_GET['oid'] = $oid;
}
 
//Delete
if (isset($_GET['delete'])) {
 
  try {
 
    $stmt = $conn->prepare("DELETE FROM tbl_orders_details_a190409 where FLD_ORDER_DETAIL_ID = :did");
    $stmt->bindParam(':did', $did, PDO::PARAM_STR);
    $did = $_GET['delete'];
    $stmt->execute();
 
    header("Location: orders_details.php?oid=".$_GET['oid']);
    }
 
  catch(PDOException $e)
  {
      echo "Error: " . $e->getMessage();
  }
}

 
?>
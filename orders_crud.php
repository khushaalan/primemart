<?php
 session_start();
include_once 'databases/database.php';
 
$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 
//Create
if (isset($_POST['create'])) {
  if($_SESSION['role'] == 0 || $_SESSION['role'] == 1 || $_SESSION['role'] == 2){
    try {
  
      $stmt = $conn->prepare("INSERT INTO tbl_orders_a190409(FLD_ORDER_ID, FLD_STAFF_ID,
        FLD_CUSTOMER_ID, FLD_ORDER_DATE) VALUES(:oid, :sid, :cid, :orderdate)");
    
      $stmt->bindParam(':oid', $oid, PDO::PARAM_STR);
      $stmt->bindParam(':sid', $sid, PDO::PARAM_STR);
      $stmt->bindParam(':cid', $cid, PDO::PARAM_STR);
      $stmt->bindParam(':orderdate', $orderdate, PDO::PARAM_STR);
        
      $oid = uniqid('O', true);
      $sid = $_POST['sid'];
      $cid = $_POST['cid'];
      $orderdate = $_POST['orderdate'];
      
      $stmt->execute();
      }
  
    catch(PDOException $e)
    {
        echo "Error: " . $e->getMessage();
    }
  }
  else{
    echo "<script>alert('You are not allowed to add order, because you do not have the permission.');</script>";
  }
}
 
//Update
if (isset($_POST['update'])) {
  if($_SESSION['role'] == 0 || $_SESSION['role'] == 1){
    try {
  
      $stmt = $conn->prepare("UPDATE tbl_orders_a190409 SET FLD_STAFF_ID = :sid,
        FLD_CUSTOMER_ID = :cid, FLD_ORDER_DATE = :orderdate WHERE FLD_ORDER_ID = :oid");
    
      $stmt->bindParam(':oid', $oid, PDO::PARAM_STR);
      $stmt->bindParam(':sid', $sid, PDO::PARAM_STR);
      $stmt->bindParam(':cid', $cid, PDO::PARAM_STR);
      $stmt->bindParam(':orderdate', $orderdate, PDO::PARAM_STR);
        
      $oid = $_POST['oid'];
      $sid = $_POST['sid'];
      $cid = $_POST['cid'];
      $orderdate = $_POST['orderdate'];
      
      $stmt->execute();
  
      header("Location: orders.php");
      }
  
    catch(PDOException $e)
    {
        echo "Error: " . $e->getMessage();
    }
  }
  else{
    echo "<script>alert('You are not allowed to update order, because you do not have the permission.');</script>";
  }
}
 
//Delete
if (isset($_GET['delete'])) {
  if($_SESSION['role'] == 0 || $_SESSION['role'] == 1){
    try {
  
      $stmt = $conn->prepare("DELETE FROM tbl_orders_a190409 WHERE FLD_ORDER_ID = :oid");
      $stmt->bindParam(':oid', $oid, PDO::PARAM_STR);   
      $oid = $_GET['delete'];
      $stmt->execute();

      // Delete related order details
      $stmt = $conn->prepare("DELETE FROM tbl_orders_details_a190409 WHERE FLD_ORDER_ID = :oid");
      $stmt->bindParam(':oid', $oid, PDO::PARAM_STR);
      $stmt->execute();
  
      header("Location: orders.php");

      
      }
  
    catch(PDOException $e)
    {
        echo "Error: " . $e->getMessage();
    }
  }
  else{
    echo "<script>alert('You are not allowed to delete order, because you do not have the permission.');</script>";
  }
}
 
//Edit
if (isset($_GET['edit'])) {
   
    try {
 
    $stmt = $conn->prepare("SELECT * FROM tbl_orders_a190409 WHERE FLD_ORDER_ID = :oid");
   
    $stmt->bindParam(':oid', $oid, PDO::PARAM_STR);
       
    $oid = $_GET['edit'];
     
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
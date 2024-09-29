<?php
 session_start();
include_once 'databases/database.php';
 
$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 
//Create
if (isset($_POST['create'])) {
  if($_SESSION['role'] == 0 || $_SESSION['role'] == 1){
    try {
  
      $stmt = $conn->prepare("INSERT INTO tbl_customers_a190409(FLD_CUSTOMER_ID, FLD_CUSTOMER_FIRST_NAME,
        FLD_CUSTOMER_LAST_NAME, FLD_CUSTOMER_GENDER, FLD_CUSTOMER_ADDRESS, FLD_CUSTOMER_PHONE) VALUES(:cid, :fname, :lname,
        :gender, :address, :phone)");
    
      $stmt->bindParam(':cid', $cid, PDO::PARAM_STR);
      $stmt->bindParam(':fname', $fname, PDO::PARAM_STR);
      $stmt->bindParam(':lname', $lname, PDO::PARAM_STR);
      $stmt->bindParam(':gender', $gender, PDO::PARAM_STR);
      $stmt->bindParam(':address', $address, PDO::PARAM_STR);
      $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
        
      $cid = $_POST['cid'];
      $fname = $_POST['fname'];
      $lname = $_POST['lname'];
      $gender =  $_POST['gender'];
      $address = $_POST['address'];
      $phone = $_POST['phone'];
        
      $stmt->execute();
      }
  
    catch(PDOException $e)
    {
        echo "Error: " . $e->getMessage();
    }
  }
  else{
    echo "<script>alert('You are not allowed to add customer, because you do not have the permission.');</script>";
  }
}
 
//Update
if (isset($_POST['update'])) {
  if($_SESSION['role'] == 0 || $_SESSION['role'] == 1){
    try {
  
      $stmt = $conn->prepare("UPDATE tbl_customers_a190409 SET FLD_CUSTOMER_ID = :cid,
        FLD_CUSTOMER_FIRST_NAME = :fname, FLD_CUSTOMER_LAST_NAME = :lname,
        FLD_CUSTOMER_GENDER = :gender, FLD_CUSTOMER_ADDRESS = :address ,FLD_CUSTOMER_PHONE = :phone
        WHERE FLD_CUSTOMER_ID = :oldcid");
    
      $stmt->bindParam(':cid', $cid, PDO::PARAM_STR);
      $stmt->bindParam(':fname', $fname, PDO::PARAM_STR);
      $stmt->bindParam(':lname', $lname, PDO::PARAM_STR);
      $stmt->bindParam(':gender', $gender, PDO::PARAM_STR);
      $stmt->bindParam(':address', $address, PDO::PARAM_STR);
      $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
      $stmt->bindParam(':oldcid', $oldcid, PDO::PARAM_STR);
        
      $cid = $_POST['cid'];
      $fname = $_POST['fname'];
      $lname = $_POST['lname'];
      $gender =  $_POST['gender'];
      $address = $_POST['address'];
      $phone = $_POST['phone'];
      $oldcid = $_POST['oldcid'];
        
      $stmt->execute();
  
      header("Location: customers.php");
      }
  
    catch(PDOException $e)
    {
        echo "Error: " . $e->getMessage();
    }
  }
  else{
    echo "<script>alert('You are not allowed to update customer, because you do not have the permission.');</script>";
  }
}
 
//Delete
if (isset($_GET['delete'])) {
  if($_SESSION['role'] == 0 || $_SESSION['role'] == 1){
    try {
  
      $stmt = $conn->prepare("DELETE FROM tbl_customers_a190409 WHERE FLD_CUSTOMER_ID = :cid");
    
      $stmt->bindParam(':cid', $cid, PDO::PARAM_STR);
        
      $cid = $_GET['delete'];
      
      $stmt->execute();
  
      header("Location: customers.php");
      }
  
    catch(PDOException $e)
    {
        echo "Error: " . $e->getMessage();
    }
  }
  else{
    echo "<script>alert('You are not allowed to delete customer, because you do not have the permission.');</script>";
  }
}
 
//Edit
if (isset($_GET['edit'])) {
   
  try {
 
    $stmt = $conn->prepare("SELECT * FROM tbl_customers_a190409 WHERE FLD_CUSTOMER_ID = :cid");
   
    $stmt->bindParam(':cid', $cid, PDO::PARAM_STR);
       
    $cid = $_GET['edit'];
     
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
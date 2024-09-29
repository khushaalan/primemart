<?php
 session_start();

include_once 'databases/database.php';
 
$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 
//Create
if (isset($_POST['create'])) {
  if($_SESSION['role'] == 0){
    try {
  
      $stmt = $conn->prepare("INSERT INTO tbl_staffs_a190409(FLD_STAFF_ID, FLD_STAFF_PASSWORD, FLD_STAFF_ROLE, FLD_STAFF_FIRST_NAME, FLD_STAFF_LAST_NAME,
        FLD_STAFF_GENDER, FLD_STAFF_PHONE, FLD_STAFF_EMAIL, FLD_STAFF_POSITION) VALUES(:sid, :spassword, :role, :fname, :lname, :gender,
        :phone, :email, :position)");
    
      $stmt->bindParam(':sid', $sid, PDO::PARAM_STR);
      $stmt->bindParam(':spassword', $spassword, PDO::PARAM_STR);
      $stmt->bindParam(':role', $role, PDO::PARAM_INT);
      $stmt->bindParam(':fname', $fname, PDO::PARAM_STR);
      $stmt->bindParam(':lname', $lname, PDO::PARAM_STR);
      $stmt->bindParam(':gender', $gender, PDO::PARAM_STR);
      $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
      $stmt->bindParam(':email', $email, PDO::PARAM_STR);
      $stmt->bindParam(':position', $position, PDO::PARAM_STR);
        
      $sid = $_POST['sid'];
      $spassword = sha1($_POST['spassword']);
      $role = $_POST['role'];
      $fname = $_POST['fname'];
      $lname = $_POST['lname'];
      $gender =  $_POST['gender'];
      $phone = $_POST['phone'];
      $email = $_POST['email'];
      $position = $_POST['position'];
          
      $stmt->execute();
      }
  
    catch(PDOException $e)
    {
        echo "Error: " . $e->getMessage();
    }
  }
  else{
    echo "<script>alert('You are not allowed to create staff.')</script>";
    echo "<script>window.location.href='staffs.php'</script>";
    exit();
  }
}
 
//Update
if (isset($_POST['update'])) {
  if($_SESSION['role'] == 0 || $_SESSION['role'] == 1){
    try {
  
      $stmt = $conn->prepare("UPDATE tbl_staffs_a190409 SET
        FLD_STAFF_ID = :sid, FLD_STAFF_PASSWORD = :spassword, FLD_STAFF_ROLE = :role,
        FLD_STAFF_FIRST_NAME = :fname, FLD_STAFF_LAST_NAME = :lname, FLD_STAFF_GENDER = :gender,
        FLD_STAFF_PHONE = :phone, FLD_STAFF_EMAIL = :email, FLD_STAFF_POSITION = :position
        WHERE FLD_STAFF_ID = :oldsid");
    
      $stmt->bindParam(':sid', $sid, PDO::PARAM_STR);
      $stmt->bindParam(':spassword', $spassword, PDO::PARAM_STR);
      $stmt->bindParam(':role', $role, PDO::PARAM_INT);
      $stmt->bindParam(':fname', $fname, PDO::PARAM_STR);
      $stmt->bindParam(':lname', $lname, PDO::PARAM_STR);
      $stmt->bindParam(':gender', $gender, PDO::PARAM_STR);
      $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
      $stmt->bindParam(':email', $email, PDO::PARAM_STR);
      $stmt->bindParam(':position', $position, PDO::PARAM_STR);
      $stmt->bindParam(':oldsid', $oldsid, PDO::PARAM_STR);
        
      $sid = $_POST['sid'];
      $spassword = $_POST['spassword'];
      $role = $_POST['role'];
      $fname = $_POST['fname'];
      $lname = $_POST['lname'];
      $gender = $_POST['gender'];
      $phone = $_POST['phone'];
      $email = $_POST['email'];
      $position = $_POST['position'];
      $oldsid = $_POST['oldsid'];
          
      $stmt->execute();

      //update session
      $_SESSION["username"] = $sid;
      $_SESSION["password"] = $spassword;
      $_SESSION["role"] = $role;
  
      header("Location: staffs.php");
      }
  
    catch(PDOException $e)
    {
        echo "Error: " . $e->getMessage();
    }
  }
  else{
    echo "<script>alert('You are not allowed to update staff, because you do not have the permission.');</script>";
  }
}
 
//Delete
if (isset($_GET['delete'])) {
  if($_SESSION['role'] == 0){
    if ($_GET['delete']=="STF001") {
      echo "<script>alert('You are not allowed to delete this staff.')</script>";
      echo "<script>window.location.href='staffs.php'</script>";
      exit();
    }
    else{
      try {
    
        $stmt = $conn->prepare("DELETE FROM tbl_staffs_a190409 where FLD_STAFF_ID = :sid");
        $stmt->bindParam(':sid', $sid, PDO::PARAM_STR);
        $sid = $_GET['delete'];
        $stmt->execute();

        //delete session
        if ($_SESSION["username"] == $sid) {
          session_destroy();
          header("Location: index.php");
        }
        else{
          header("Location: staffs.php");
        }
      }
    
      catch(PDOException $e)
      {
          echo "Error: " . $e->getMessage();
      }
    }
  }
  else{
    echo "<script>alert('You are not allowed to delete staff, because you do not have the permission.')</script>";
    echo "<script>window.location.href='staffs.php'</script>";
    exit();
  }
}
 
//Edit
if (isset($_GET['edit'])) {
   
  try {
 
    $stmt = $conn->prepare("SELECT * FROM tbl_staffs_a190409 where FLD_STAFF_ID = :sid");
   
    $stmt->bindParam(':sid', $sid, PDO::PARAM_STR);
       
    $sid = $_GET['edit'];
     
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
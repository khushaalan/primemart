<?php
  include_once 'customers_crud.php';
  if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>


<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Prime Camera Mart : Customers</title>
  <link href="bootstrap-3.3.7-dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <?php include_once 'nav_bar.php'; ?>

  <div class="container-fluid">
    <div class="row">
      <div class="col-xs-12 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
        <div class="page-header">
          <h2>Add New Customer</h2>
        </div>
        <form action="customers.php" method="post">
          <!-- CUSTOMER ID -->
          <div class="form-group">
            <label for="customerid" class="col-sm-3 control-label">Customer ID</label>
            <div class="col-sm-9">
              <input class="form-control" id="csutomerid" placeholder="Customer ID" name="cid" type="text" value="<?php if(isset($_GET['edit'])) echo $editrow['FLD_CUSTOMER_ID']; ?>" required> <br>
            </div>
          </div>
          <!-- FIRST NAME -->
          <div class="form-group">
            <label for="firstname" class="col-sm-3 control-label">First Name</label>
            <div class="col-sm-9">
              <input class="form-control" id="firstname" placeholder="First Name" name="fname" type="text" value="<?php if(isset($_GET['edit'])) echo $editrow['FLD_CUSTOMER_FIRST_NAME']; ?>" required> <br>
            </div>
          </div>
          <!-- LAST NAME -->
          <div class="form-group">
            <label for="lastname" class="col-sm-3 control-label">Last Name</label>
            <div class="col-sm-9">
              <input class="form-control" id="lastname" placeholder="Last Name" name="lname" type="text" value="<?php if(isset($_GET['edit'])) echo $editrow['FLD_CUSTOMER_LAST_NAME']; ?>" required> <br>
            </div>
          </div>
          <!-- GENDER -->
          <div class="form-group">
            <label for="gender" class="col-sm-3 control-label">Gender</label>
            <div class="col-sm-9">
              <div class="radio">
                <label>
                <input id="gender" name="gender" type="radio" value="Male" <?php if(isset($_GET['edit'])) if($editrow['FLD_CUSTOMER_GENDER']=="Male") echo "checked"; ?> required> Male
                </label>
              </div>
              <div class="radio">
                <label>
                  <input id="gender" name="gender" type="radio" value="Female" <?php if(isset($_GET['edit'])) if($editrow['FLD_CUSTOMER_GENDER']=="Female") echo "checked"; ?>> Female
                </label>
              </div>
            </div>
          </div>
          <!-- ADDRESS -->
          <div class="form-group">
            <label for="address" class="col-sm-3 control-label">Address</label>
            <div class="col-sm-9">
              <input class="form-control" id="address" placeholder="Address" name="address" type="text" value="<?php if(isset($_GET['edit'])) echo $editrow['FLD_CUSTOMER_ADDRESS']; ?>" required> <br>
            </div>
          </div>
          <!-- PHONE NUMBER -->
          <div class="form-group">
            <label for="phonenumber" class="col-sm-3 control-label">Phone Number</label>
            <div class="col-sm-9">
              <input class="form-control" id="phonenumber" name="phone" placeholder="Phone Number" type="text" value="<?php if(isset($_GET['edit'])) echo $editrow['FLD_CUSTOMER_PHONE']; ?>" pattern="\+60\d{2}-\d{7,8}" required> <br>
            </div>
          </div>
          
          <div class="form-group">
            <label for="productcurrentimage" class="col-sm-3 control-label"></label>
            <div class="col-sm-offset-3 col-sm-9">
              <?php if (isset($_GET['edit'])) { ?>
              <input type="hidden" name="oldcid" value="<?php echo $editrow['FLD_CUSTOMER_ID']; ?>">
              <button class="btn btn-default" type="submit" name="update"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Update</button>
              <?php } else { ?>
                <button class="btn btn-default" type="submit" name="create"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Create</button>
              <?php } ?>
              <button class="btn btn-default" type="reset"><span class="glyphicon glyphicon-erase" aria-hidden="true"></span> Clear</button>
            </div>
          </div>
        </form>
      </div>
    </div>



    <hr>
    <div class="row">
      <div class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-9 col-md-offset-2">
        <div class="page-header">
          <h2>Customer List</h2>
        </div>
        <table class="table table-striped table-bordered">
          <tr>
            <th>Customer ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Gender</th>
            <th>Address</th>
            <th>Phone Number</th>
            <th>Actions</th>
          </tr>
          <?php
            // Read
            $per_page = 5;
            if (isset($_GET["page"]))
              $page = $_GET["page"];
            else
              $page = 1;
            $start_from = ($page-1) * $per_page;
            try {
              $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
              $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stmt = $conn->prepare("SELECT * FROM tbl_customers_a190409 LIMIT $start_from, $per_page");
              $stmt->execute();
              $result = $stmt->fetchAll();
            }
            catch(PDOException $e){
                  echo "Error: " . $e->getMessage();
            }
            foreach($result as $readrow) {
            ?>
            <tr>
              <td><?php echo $readrow['FLD_CUSTOMER_ID']; ?></td>
              <td><?php echo $readrow['FLD_CUSTOMER_FIRST_NAME']; ?></td>
              <td><?php echo $readrow['FLD_CUSTOMER_LAST_NAME']; ?></td>
              <td><?php echo $readrow['FLD_CUSTOMER_GENDER']; ?></td>
              <td><?php echo $readrow['FLD_CUSTOMER_ADDRESS']; ?></td>
              <td><?php echo $readrow['FLD_CUSTOMER_PHONE']; ?></td>

              <td>
                <a href="customers.php?edit=<?php echo $readrow['FLD_CUSTOMER_ID']; ?>" class="btn btn-success btn-xs" role="button"> Edit </a>
                <a href="customers.php?delete=<?php echo $readrow['FLD_CUSTOMER_ID']; ?>" onclick="return confirm('Are you sure to delete?');" class="btn btn-danger btn-xs" role="button">Delete</a>
              </td>
            </tr>
          <?php }  $conn = null; ?>
        </table>
      </div>
    </div>

    <!-- PAGINATION -->
    <div class="row">
      <div class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <nav>
            <ul class="pagination">
            <?php
            try {
              $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
              $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
              $stmt = $conn->prepare("SELECT * FROM tbl_customers_a190409");
              $stmt->execute();
              $result = $stmt->fetchAll();
              $total_records = count($result);
            }
            catch(PDOException $e){
                  echo "Error: " . $e->getMessage();
            }
            $total_pages = ceil($total_records / $per_page);
            ?>
            <?php if ($page==1) { ?>
              <li class="disabled"><span aria-hidden="true">«</span></li>
            <?php } else { ?>
              <li><a href="customers.php?page=<?php echo $page-1 ?>" aria-label="Previous"><span aria-hidden="true">«</span></a></li>
            <?php
            }
            for ($i=1; $i<=$total_pages; $i++)
              if ($i == $page)
                echo "<li class=\"active\"><a href=\"customers.php?page=$i\">$i</a></li>";
              else
                echo "<li><a href=\"customers.php?page=$i\">$i</a></li>";
            ?>
            <?php if ($page==$total_pages) { ?>
              <li class="disabled"><span aria-hidden="true">»</span></li>
            <?php } else { ?>
              <li><a href="customers.php?page=<?php echo $page+1 ?>" aria-label="Previous"><span aria-hidden="true">»</span></a></li>
            <?php } ?>
          </ul>
        </nav>
      </div>
    </div>
  

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script src="bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>
</body>
</html>
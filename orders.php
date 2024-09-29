<?php
  include_once 'orders_crud.php';
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
  <title>Prime Camera Mart : Orders</title>
  <link href="bootstrap-3.3.7-dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>
<body>

  <?php include_once 'nav_bar.php'; ?>
  
  <div class="container-fluid">
    <div class="row">
      <div class="col-xs-12 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
        <div class="page-header">
          <h2>Add New Order</h2>
        </div>
        <form action="orders.php" method="post">
          <!-- ORDER ID -->
          <div class="form-group">
            <label for="orderid" class="col-sm-3 control-label">Order ID</label>
            <div class="col-sm-9">
              <input class="form-control" id="orderid" placeholder="Order ID" name="oid" type="text" readonly value="<?php if(isset($_GET['edit'])) echo $editrow['FLD_ORDER_ID']; ?>" required> <br>
            </div>
          </div>
          <!-- ORDER DATE -->
          <div class="form-group">
            <label for="orderdate" class="col-sm-3 control-label">Order Date</label>
            <div class="col-sm-9">
              <input class="form-control" id="orderdate" placeholder="Order Date" name="orderdate" type="text" readonly value="<?php if(isset($_GET['edit'])) echo $editrow['FLD_ORDER_DATE']; else echo date('d-m-Y H:i:s'); ?>" style=color:red; required> <br>
            </div>
          </div>
          <!-- STAFF ID -->
          <div class="form-group">
            <label for="staffid" class="col-sm-3 control-label">Staff ID</label>
            <div class="col-sm-9">
              <select name="sid" class="form-control" id="staffid" required>
              <?php
                try {
                  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $stmt = $conn->prepare("SELECT * FROM tbl_staffs_a190409");
                  $stmt->execute();
                  $result = $stmt->fetchAll();
                }
                catch(PDOException $e){
                      echo "Error: " . $e->getMessage();
                }
                foreach($result as $staffrow) {
                ?>
                  <?php if((isset($_GET['edit'])) && ($editrow['FLD_STAFF_ID']==$staffrow['FLD_STAFF_ID'])) { ?>
                    <option value="<?php echo $staffrow['FLD_STAFF_ID']; ?>" selected><?php echo $staffrow['FLD_STAFF_FIRST_NAME']." ".$staffrow['FLD_STAFF_LAST_NAME'];?></option>
                  <?php } else { ?>
                    <option value="<?php echo $staffrow['FLD_STAFF_ID']; ?>"><?php echo $staffrow['FLD_STAFF_FIRST_NAME']." ".$staffrow['FLD_STAFF_LAST_NAME'];?></option>
                  <?php } ?>
                <?php
                } // while
                $conn = null;
                ?>
              </select> <br>
            </div>
          </div>
          <!-- CUSTOMER ID -->
          <div class="form-group">
            <label for="customerid" class="col-sm-3 control-label">Customer ID</label>
            <div class="col-sm-9">
              <select name="cid" class="form-control" id="customerid" required>
              <?php
                try {
                  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $stmt = $conn->prepare("SELECT * FROM tbl_customers_a190409");
                  $stmt->execute();
                  $result = $stmt->fetchAll();
                }
                catch(PDOException $e){
                      echo "Error: " . $e->getMessage();
                }
                foreach($result as $custrow) {
                ?>
                  <?php if((isset($_GET['edit'])) && ($editrow['FLD_CUSTOMER_ID']==$custrow['FLD_CUSTOMER_ID'])) { ?>
                    <option value="<?php echo $custrow['FLD_CUSTOMER_ID']; ?>" selected><?php echo $custrow['FLD_CUSTOMER_FIRST_NAME']." ".$custrow['FLD_CUSTOMER_LAST_NAME'];?></option>
                  <?php } else { ?>
                    <option value="<?php echo $custrow['FLD_CUSTOMER_ID']; ?>"><?php echo $custrow['FLD_CUSTOMER_FIRST_NAME']." ".$custrow['FLD_CUSTOMER_LAST_NAME'];?></option>
                  <?php } ?>
                <?php
                } // while
                $conn = null;
                ?>
              </select> <br>
            </div>
          </div>

          <div class="form-group">
            <div class="col-sm-offset-3 col-sm-9">
              <?php if (isset($_GET['edit'])) { ?>
              <button type="submit" name="update" class="btn btn-default"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Update</button>
              <?php } else { ?>
                <button type="submit" name="create" class="btn btn-default"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Create</button>
              <?php } ?>
              <button type="reset" class="btn btn-default"><span class="glyphicon glyphicon-erase" aria-hidden="true"></span> Clear</button>
            </div>
          </div>
        </form>
      </div>
    </div>


    <hr>
    <div class="row">
      <div class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-9 col-md-offset-2">
        <div class="page-header">
          <h2>Order List</h2>
        </div>
        <table class="table table-striped table-bordered">
          <tr>
            <th>Order ID</th>
            <th>Order Date</th>
            <th>Staff ID</th>
            <th>Customer ID</th>
            <th>Actions</th>
          </tr>

          <?php
            $per_page = 5;
            if (isset($_GET["page"]))
              $page = $_GET["page"];
            else
              $page = 1;
            $start_from = ($page-1) * $per_page;
            try {
              $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
              $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
              $sql = "SELECT * FROM tbl_orders_a190409, tbl_staffs_a190409, tbl_customers_a190409 WHERE ";
              $sql = $sql."tbl_orders_a190409.FLD_STAFF_ID = tbl_staffs_a190409.FLD_STAFF_ID and ";
              $sql = $sql."tbl_orders_a190409.FLD_CUSTOMER_ID = tbl_customers_a190409.FLD_CUSTOMER_ID LIMIT $start_from, $per_page";
              $stmt = $conn->prepare($sql);
              $stmt->execute();
              $result = $stmt->fetchAll();
            }
            catch(PDOException $e){
                  echo "Error: " . $e->getMessage();
            }
            foreach($result as $orderrow) {
            ?>
            <tr>
              <td><?php echo $orderrow['FLD_ORDER_ID']; ?></td>
              <td><?php echo $orderrow['FLD_ORDER_DATE']; ?></td>
              <td><?php echo $orderrow['FLD_STAFF_FIRST_NAME']." ".$orderrow['FLD_STAFF_LAST_NAME'] ?></td>
              <td><?php echo $orderrow['FLD_CUSTOMER_FIRST_NAME']." ".$orderrow['FLD_CUSTOMER_LAST_NAME'] ?></td>
              <td>
                <a href="orders_details.php?oid=<?php echo $orderrow['FLD_ORDER_ID']; ?>" class="btn btn-warning btn-xs" role="button">Details</a>
                <a href="orders.php?edit=<?php echo $orderrow['FLD_ORDER_ID']; ?>" class="btn btn-success btn-xs" role="button"> Edit </a>
                <a href="orders.php?delete=<?php echo $orderrow['FLD_ORDER_ID']; ?>" onclick="return confirm('Are you sure to delete?');" class="btn btn-danger btn-xs" role="button">Delete</a>
              </td>
            </tr>
          <?php } $conn = null;  ?>
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
              $sql = "SELECT * FROM tbl_orders_a190409, tbl_staffs_a190409, tbl_customers_a190409 WHERE ";
              $sql = $sql."tbl_orders_a190409.FLD_STAFF_ID = tbl_staffs_a190409.FLD_STAFF_ID and ";
              $sql = $sql."tbl_orders_a190409.FLD_CUSTOMER_ID = tbl_customers_a190409.FLD_CUSTOMER_ID";
              $stmt = $conn->prepare($sql);
              // $stmt = $conn->prepare("SELECT * FROM tbl_orders_a190409");
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
              <li><a href="orders.php?page=<?php echo $page-1 ?>" aria-label="Previous"><span aria-hidden="true">«</span></a></li>
            <?php
            }
            for ($i=1; $i<=$total_pages; $i++)
              if ($i == $page)
                echo "<li class=\"active\"><a href=\"orders.php?page=$i\">$i</a></li>";
              else
                echo "<li><a href=\"orders.php?page=$i\">$i</a></li>";
            ?>
            <?php if ($page==$total_pages) { ?>
              <li class="disabled"><span aria-hidden="true">»</span></li>
            <?php } else { ?>
              <li><a href="orders.php?page=<?php echo $page+1 ?>" aria-label="Previous"><span aria-hidden="true">»</span></a></li>
            <?php } ?>
          </ul>
        </nav>
      </div>
    </div>


  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script src="bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>
</body>
</html>
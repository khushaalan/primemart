<?php
  include_once 'orders_details_crud.php';
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
  <title>Prime Camera Mart : Order Details</title>
  <link href="bootstrap-3.3.7-dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>
<body>

  <?php include_once 'nav_bar.php'; ?>
  <?php
  try {
      $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $stmt = $conn->prepare("SELECT * FROM tbl_orders_a190409, tbl_staffs_a190409,
          tbl_customers_a190409 WHERE
          tbl_orders_a190409.FLD_STAFF_ID = tbl_staffs_a190409.FLD_STAFF_ID AND
          tbl_orders_a190409.FLD_CUSTOMER_ID = tbl_customers_a190409.FLD_CUSTOMER_ID AND
          FLD_ORDER_ID  = :oid");
      $stmt->bindParam(':oid', $oid, PDO::PARAM_STR);
      $oid = $_GET['oid'];
      $stmt->execute();
      $readrow = $stmt->fetch(PDO::FETCH_ASSOC);
    }
  catch(PDOException $e) {
      echo "Error: " . $e->getMessage();
  }
  $conn = null;
  ?>

  <div class="container-fluid">
    <div class="row">
      <div class="col-xs-12 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">
        <div class="panel panel-default">
          <div class="panel-heading"><strong>Order Details</strong></div>
          <div class="panel-body">
              Below are details of the order.
          </div>
          <table class="table">
            <tr>
              <td class="col-xs-4 col-sm-4 col-md-4"><strong>Order ID</strong></td>
              <td><?php echo $readrow['FLD_ORDER_ID'] ?></td>
            </tr>
            <tr>
              <td><strong>Order Date</strong></td>
              <td><?php echo $readrow['FLD_ORDER_DATE'] ?></td>
            </tr>
            <tr>
              <td><strong>Staff</strong></td>
              <td><?php echo $readrow['FLD_STAFF_FIRST_NAME']." ".$readrow['FLD_STAFF_LAST_NAME'] ?></td>
            </tr>
            <tr>
              <td><strong>Customer</strong></td>
              <td><?php echo $readrow['FLD_CUSTOMER_FIRST_NAME']." ".$readrow['FLD_CUSTOMER_LAST_NAME'] ?></td>
            </tr>
          </table>
        </div>
      </div>
    </div>

    <div class="row">
    <div class="col-xs-12 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
      <div class="page-header">
        <h2>Add a Product</h2>
      </div>
      <form action="orders_details.php" method="post" class="form-horizontal" name="frmorder" id="forder">
          <!-- PRODUCT ID -->
          <div class="form-group">
            <label for="prd" class="col-sm-3 control-label">Product</label>
            <div class="col-sm-9">
              <select name="pid" class="form-control" id="prd">
                <option value="" selected>Please select</option>
                <?php
                try {
                  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $stmt = $conn->prepare("SELECT * FROM tbl_products_a190409");
                  $stmt->execute();
                  $result = $stmt->fetchAll();
                }
                catch(PDOException $e){
                      echo "Error: " . $e->getMessage();
                }
                foreach($result as $productrow) {
                ?>
                  <option value="<?php echo $productrow['FLD_PRODUCT_ID']; ?>"><?php echo $productrow['FLD_BRAND']." ".$productrow['FLD_PRODUCT_NAME']; ?></option>
                <?php } $conn = null; ?>
              </select>
            </div>
          </div>
          <!-- QUANTITY -->
          <div class="form-group">
            <label for="qty" class="col-sm-3 control-label">Quantity</label>
            <div class="col-sm-9">
              <input name="quantity" type="number" class="form-control" id="qty" min=1 >
              <input name="oid" type="hidden" value="<?php echo $readrow['FLD_ORDER_ID'] ?>"> <br>
              <button class="btn btn-default" type="submit" name="addproduct" onclick="validateForm()"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add Product</button>
              <button class="btn btn-default" type="reset"><span class="glyphicon glyphicon-erase" aria-hidden="true"></span> Clear</button>
            </div>
          </div>
      </form>
    </div>
  </div>


  <div class="row">
    <div class="col-xs-12 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
      <div class="page-header">
        <h2>Products in This Order</h2>
      </div>
      <table class="table table-striped table-bordered">
        <tr>
          <th>Order Detail ID</th>
          <th>Product</th>
          <th>Quantity</th>
          <th>Actions</th>
        </tr>
        <?php
        try {
          $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
          $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $conn->prepare("SELECT * FROM tbl_orders_details_a190409,
              tbl_products_a190409 WHERE
              tbl_orders_details_a190409.FLD_PRODUCT_ID = tbl_products_a190409.FLD_PRODUCT_ID AND
              FLD_ORDER_ID = :oid");
            $stmt->bindParam(':oid', $oid, PDO::PARAM_STR);
            $oid = $_GET['oid'];
          $stmt->execute();
          $result = $stmt->fetchAll();
        }
        catch(PDOException $e){
              echo "Error: " . $e->getMessage();
        }
        foreach($result as $detailrow) {
        ?>
        <tr>
          <td><?php echo $detailrow['FLD_ORDER_DETAIL_ID']; ?></td>
          <td><?php echo $detailrow['FLD_PRODUCT_NAME']; ?></td>
          <td><?php echo $detailrow['FLD_ORDER_DETAIL_QUANTITY']; ?></td>
          <td>
            <a href="orders_details.php?delete=<?php echo $detailrow['FLD_ORDER_DETAIL_ID']; ?>&oid=<?php echo $_GET['oid']; ?>" onclick="return confirm('Are you sure to delete?');" class="btn btn-danger btn-xs" role="button">Delete</a>
          </td>
        </tr>
        <?php
        }
        $conn = null;
        ?>
      </table>
    </div>
  </div>

  <div class="row">
    <div class="col-xs-12 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
    <a href="#" role="button" class="btn btn-primary btn-lg btn-block" data-toggle="modal" data-target="#paymentModal">Add Payment Information</a>

      <div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="paymentModalLabel">Add Payment Information</h4>
            </div>
            <div class="modal-body">
              <form id="paymentForm">
                <div class="form-group">
                  <label for="name">Your Name</label>
                  <input type="text" class="form-control" id="name" placeholder="Your Name" value="Khushaalan Arjunan" required>
                </div>
                <div class="form-group">
                  <label for="bank">Bank Name</label>
                  <input type="text" class="form-control" id="bank" placeholder="Bank Name" value="Public Bank" required>
                </div>
                <div class="form-group">
                  <label for="swift">SWIFT Code</label>
                  <input type="text" class="form-control" id="swift" placeholder="SWIFT" value="PBBEMYKLXXX" required>
                </div>
                <div class="form-group">
                  <label for="accountNumber">Account Number</label>
                  <input type="text" class="form-control" id="accountNumber" placeholder="Account Number" value="1111221111" required>
                </div>
                <div class="form-group">
                  <label for="iban">IBAN (optional)</label>
                  <input type="text" class="form-control" id="iban" placeholder="IBAN">
                </div>
                
                <button type="button" class="btn btn-primary" onclick="addPaymentInformation()">Generate Invoice</button>
                <button class="btn btn-default" type="button" onclick="clearPaymentForm()">Clear</button>
              </form>
            </div>
          </div>
        </div>
      </div>

      <!-- <a href="invoice.php?oid=<?php echo $_GET['oid']; ?>" target="_blank" role="button" class="btn btn-primary btn-lg btn-block" onclick="return validateProducts()">Generate Invoice</a> -->
    </div>
  </div>
  <br>




  <script type="text/javascript">
    function validateForm() {
  
        var x = document.forms["frmorder"]["pid"].value;
        var y = document.forms["frmorder"]["quantity"].value;
        //var x = document.getElementById("prd").value;
        //var y = document.getElementById("qty").value;
        if (x == null || x == "") {
            alert("Product must be selected");
            document.forms["frmorder"]["pid"].focus();
            //document.getElementById("prd").focus();
            return false;
        }
        if (y == null || y == "") {
            alert("Quantity must be filled out");
            document.forms["frmorder"]["quantity"].focus();
            //document.getElementById("qty").focus();
            return false;
        }      
        return true;
    }

    // function validateProducts() {
    //     var productRows = document.querySelectorAll('.table.table-striped.table-bordered tr').length - 1;
    //     if (productRows === 0) {
    //         alert("Please add at least one product before generating the invoice.");
    //         return false;
    //     }
    //     return true;
    // }

    function addPaymentInformation() {
        var name = document.getElementById("name").value;
        var bank = document.getElementById("bank").value;
        var swift = document.getElementById("swift").value;
        var accountNumber = document.getElementById("accountNumber").value;
        var iban = document.getElementById("iban").value;
        var productRows = document.querySelectorAll('.table.table-striped.table-bordered tr').length - 1;


        if (productRows === 0) {
            alert("Please add at least one product before generating the invoice.");
            return false;
        }
        else{

          if (name == null || name == "") {
              alert("Your Name must be filled out");
              document.getElementById("name").focus();
              return false;
          }
          if (bank == null || bank == "") {
              alert("Bank Name must be filled out");
              document.getElementById("bank").focus();
              return false;
          }
          if (swift == null || swift == "") {
              alert("SWIFT Code must be filled out");
              document.getElementById("swift").focus();
              return false;
          }

          var swiftRegex = /^[a-zA-Z]{4}[a-zA-Z]{2}[a-zA-Z]{2}[a-zA-Z]{3}$/;
          if (!swiftRegex.test(swift)) {
              alert("SWIFT Code should be in the format of XXXXYYZZAAA(Bank Code, Country Code, Location Code, Branch Code)");
              document.getElementById("swift").focus();
              return false;
          }
          
          if (accountNumber == null || accountNumber == "") {
              alert("Account Number must be filled out");
              document.getElementById("accountNumber").focus();
              return false;
          }
          var accountNumberRegex = /^[0-9]{10}$/;
          if (!accountNumberRegex.test(accountNumber)) {
              alert("Account Number should be in the format of 10 digits");
              document.getElementById("accountNumber").focus();
              return false;
          }
          var invoiceURL = "invoice.php?oid=<?php echo $_GET['oid']; ?>&name=" + encodeURIComponent(name) + "&bank=" + encodeURIComponent(bank) + "&swift=" + encodeURIComponent(swift) + "&accountNumber=" + encodeURIComponent(accountNumber) + "&iban=" + encodeURIComponent(iban);
          window.location.href = invoiceURL;
          return true;
        }

          
      
    }

    function clearPaymentForm() {
        document.getElementById("paymentForm").reset();
    }

  </script>


  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script src="bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>
</body>
</html>
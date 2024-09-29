<?php
  session_start();
  include_once 'databases/database.php';
  if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>
<?php
  try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare("SELECT * FROM tbl_orders_a190409, tbl_staffs_a190409,
      tbl_customers_a190409, tbl_orders_details_a190409 WHERE
      tbl_orders_a190409.FLD_STAFF_ID = tbl_staffs_a190409.FLD_STAFF_ID AND
      tbl_orders_a190409.FLD_CUSTOMER_ID = tbl_customers_a190409.FLD_CUSTOMER_ID AND
      tbl_orders_a190409.FLD_ORDER_ID = tbl_orders_details_a190409.FLD_ORDER_ID AND
      tbl_orders_a190409.FLD_ORDER_ID = :oid");
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




<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">  
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Prime Camera Mart : Invoice</title>
  <link href="bootstrap-3.3.7-dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="row">
    <div class="col-xs-6 text-center">
      <br>
        <img src="primecameramart.svg" width="60%" height="60%">
    </div>
    <div class="col-xs-6 text-right">
      <h1>INVOICE</h1>
      <h5>Order: <?php echo $readrow['FLD_ORDER_ID'] ?></h5>
      <h5>Date: <?php echo $readrow['FLD_ORDER_DATE'] ?></h5>
    </div>
  </div>

  <hr>
  <div class="row">

    <div class="col-xs-5">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h4>From: Prime Camera Mart Sdn. Bhd.</h4>
        </div>
        <div class="panel-body">
          <p>
          12, Hilir Sungai Keluang 1 <br>
          Bayan Lepas Industrial Park Phase 4 <br>
          11900 Bayan Lepas <br>
          Pulau Pinang <br>
          </p>
        </div>
      </div>
    </div>

    <div class="col-xs-5 col-xs-offset-2 text-right">
        <div class="panel panel-default">
            <div class="panel-heading">
              <h4>To : <?php echo $readrow['FLD_CUSTOMER_FIRST_NAME']." ".$readrow['FLD_CUSTOMER_LAST_NAME'] ?></h4>
            </div>
            <div class="panel-body">
              <p>
                <?php
                  echo formatAddress($readrow['FLD_CUSTOMER_ADDRESS']);

                  function formatAddress($address) {
                    $addressParts = explode(", ", $address);
                    if (count($addressParts) >= 3) {
                        $addressFormatted = $addressParts[0] . "<br>" . $addressParts[1] . "<br>" . $addressParts[2];
                        if (isset($addressParts[3])) {
                            $addressFormatted .= "<br>" . $addressParts[3];
                        }
                        return $addressFormatted;
                    }
                    return $address;
                  }
                ?>
              </p>
            </div>
        </div>
    </div>
  </div>

  <table class="table table-bordered">
    <tr>
      <th>No</th>
      <th>Product</th>
      <th class="text-right">Quantity</th>
      <th class="text-right">Price(RM)/Unit</th>
      <th class="text-right">Total(RM)</th>
    </tr>
    <?php
    $grandtotal = 0;
    $counter = 1;
    try {
      $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $conn->prepare("SELECT * FROM tbl_orders_details_a190409,
          tbl_products_a190409 where 
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
      <td><?php echo $counter; ?></td>
      <td><?php echo $detailrow['FLD_PRODUCT_NAME']; ?></td>
      <td class="text-right"><?php echo $detailrow['FLD_ORDER_DETAIL_QUANTITY']; ?></td>
      <td class="text-right"><?php echo $detailrow['FLD_PRICE']; ?></td>
      <td class="text-right"><?php echo $detailrow['FLD_PRICE']*$detailrow['FLD_ORDER_DETAIL_QUANTITY']; ?></td>
    </tr>
    <?php
      $grandtotal = $grandtotal + $detailrow['FLD_PRICE']*$detailrow['FLD_ORDER_DETAIL_QUANTITY'];
      $counter++;
    } // while
    ?>
    <tr>
      <td colspan="4" class="text-right">Grand Total</td>
      <td class="text-right"><?php echo $grandtotal ?></td>
    </tr>
  </table>
  
  <div class="row">
    <div class="col-xs-5">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h4>Bank Details</h4>
        </div>
        
        <div class="panel-body">
          <?php
            $name = isset($_GET['name']) ? $_GET['name'] : '';
            $bank = isset($_GET['bank']) ? $_GET['bank'] : '';
            $swift = isset($_GET['swift']) ? $_GET['swift'] : '';
            $accountNumber = isset($_GET['accountNumber']) ? $_GET['accountNumber'] : '';
            $iban = isset($_GET['iban']) ? $_GET['iban'] : '';
          ?>
          <p>Your Name : <?php echo $name; ?></p>
          <p>Bank Name : <?php echo $bank; ?></p>
          <p>SWIFT : <?php echo $swift; ?></p>
          <p>Account Number : <?php echo $accountNumber; ?></p>
          <p>IBAN : <?php echo $iban; ?></p>
      </div>

      </div>
      </div>
    <div class="col-xs-7">
      <div class="span7">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h4>Contact Details</h4>
          </div>
          <div class="panel-body">
            <p> Staff: <?php echo $readrow['FLD_STAFF_FIRST_NAME']." ".$readrow['FLD_STAFF_LAST_NAME'] ?> </p>
            <p> Email: <?php echo $readrow['FLD_STAFF_EMAIL'] ?> </p>
            <p><br></p>
            <p><br></p>
            <p>Computer-generated invoice. No signature is required.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
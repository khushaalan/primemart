<?php
include_once 'databases/database.php';

try {
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $stmt = $conn->prepare("SELECT * FROM tbl_products_a190409 WHERE FLD_PRODUCT_ID = :productId");
  $stmt->bindParam(':productId', $_POST['productId'], PDO::PARAM_STR);
  $stmt->execute();

  $readrow = $stmt->fetch(PDO::FETCH_ASSOC);

    echo '<div class="container-fluid">';
    echo '  <div class="row">';
        if ($readrow['FLD_PRODUCT_ID'] == "" ) {
            echo "No image";
        }
        else { ?>
            <div class="col-md-12 text-center">
                <img src="static/images/<?php echo $readrow['FLD_PRODUCT_ID'] ?>.jpg" alt="Product Image" class="img-thumbnail img-responsive">
            </div>
        <?php }
    echo '    </div>';
    echo '    <br>';

    echo '    <div class="row">';
    echo '      <div class="panel panel-default">';
    echo '        <div class="panel-heading"><strong>Product Details</strong></div>';
    echo '          <div class="panel-body">';
    echo '              Below are specifications of the product.';
    echo '          </div>';
    echo '          <table class="table">';
    echo '            <tr>';
    echo '              <td class="col-xs-4 col-sm-4 col-md-4"><strong>Product ID</strong></td>';
    echo '              <td>' . $readrow['FLD_PRODUCT_ID'] . '</td>';
    echo '            </tr>';
    echo '            <tr>';
    echo '              <td><strong>Name</strong></td>';
    echo '              <td>' . $readrow['FLD_PRODUCT_NAME'] . '</td>';
    echo '            </tr>';
    echo '            <tr>';
    echo '              <td><strong>Price</strong></td>';
    echo '              <td>RM ' . $readrow['FLD_PRICE'] . '</td>';
    echo '            </tr>';
    echo '            <tr>';
    echo '              <td><strong>Brand</strong></td>';
    echo '              <td>' . $readrow['FLD_BRAND'] . '</td>';
    echo '            <tr>';
    echo '              <td><strong>Type</strong></td>';
    echo '              <td>' . $readrow['FLD_TYPE'] . '</td>';
    echo '            </tr>';
    echo '            <tr>';
    echo '              <td><strong>Quantity</strong></td>';
    echo '              <td>' . $readrow['FLD_QUANTITY'] . '</td>';
    echo '            </tr>';
    echo '            <tr>';
    echo '              <td><strong>Warranty Length</strong></td>';
    echo '              <td>' . $readrow['FLD_WARRANTYLENGTH'] . '</td>';
    echo '            </tr>';
    echo '          </table>';
    echo '      </div>';
    echo '    </div>';



    echo '  </div>';
    echo '</div>';
} catch (PDOException $e) {
  echo "Error: " . $e->getMessage();
}

$conn = null;
?>

<?php
include_once 'products_crud.php';
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

  <title>Prime Camera Mart : Products</title>
  
  <link href="bootstrap-3.3.7-dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
  <!-- <link href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css" rel="stylesheet"> -->
  <!-- <link href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap.min.css" rel="stylesheet"> -->
  
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap.min.css">
  

  <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script src="bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>

  <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
  
  <script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>

  
  

<script>
   $(document).ready(function() {
      let table = $('#myTable').DataTable({
        
         "lengthMenu": [[5, 10, 20, 30, -1], [5, 10, 20, 30, 'All']],
         "pageLength": 5,
         "order": [[1, "asc"]],
         "columnDefs": [
            { "searchable": false, "targets": [2,7] }
         ],
         
          buttons: [
            {
              extend: 'excelHtml5',
              text: '<span class="glyphicon glyphicon-export"></span> Export to Excel',
              titleAttr: 'Export to Excel',
              exportOptions: {
                columns: [0, 1, 3, 4, 5, 6]
              }
            }
          ]
      });
      $('#exportBtn').on('click', function() {
        table.button('.buttons-excel').trigger();
      });
   });
</script>

</head>
<body>
  <?php include_once 'nav_bar.php'; ?>

  <!-- the bootstrap modal -->
  <div class="modal fade" id="productDetailsModal" tabindex="-1" role="dialog" aria-labelledby="productDetailsModalLabel" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="productDetailsModalLabel">Product Details</h4>
        </div>
        <div class="modal-body" id="productDetailsContent">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  <!--  -->

  <div class="container-fluid">
    <div class="row">
      <div class="col-xs-12 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
        <div class="page-header">
          <h2>Create New Product</h2>
        </div>
        <form action="products.php" method="post" enctype="multipart/form-data" onsubmit="return validateForm();">
          <!-- PRODUCT ID -->
          <div class="form-group">
            <label for="productid" class="col-sm-3 control-label">Product ID</label>
            <div class="col-sm-9">
              <input class="form-control" id="productid" name="pid" type="text" placeholder="Product ID" value="<?php if(isset($_GET['edit'])) echo $editrow['FLD_PRODUCT_ID']; ?>" <?php if(isset($_GET['edit'])) echo 'readonly'; ?> required> <br>
            </div>
          </div>  
          <!-- NAME -->
          <div class="form-group">
            <label for="productname" class="col-sm-3 control-label">Name</label>
            <div class="col-sm-9">
              <input class="form-control" id="productname" name="name" type="text" placeholder="Product Name" value="<?php if(isset($_GET['edit'])) echo $editrow['FLD_PRODUCT_NAME']; ?>" required> <br>
            </div>
          </div>
          <!-- PRICE -->
          <div class="form-group">
            <label for="productprice" class="col-sm-3 control-label">Price (RM)</label>
            <div class="col-sm-9">
              <input class="form-control" id="productprice" name="price" type="number" placeholder="Product Price" value="<?php if(isset($_GET['edit'])) echo $editrow['FLD_PRICE']; ?>" min="0.0" step="0.01" required> <br>
            </div>
          </div>
          <!-- BRAND -->
          <div class="form-group">
            <label for="productbrand" class="col-sm-3 control-label">Brand</label>
            <div class="col-sm-9">
              <select class="form-control" id="productbrand" name="brand" required>
                <option value="Sony" <?php if(isset($_GET['edit'])) if($editrow['FLD_BRAND']=="Sony") echo "selected"; ?>>Sony</option> 
                <option value="Canon" <?php if(isset($_GET['edit'])) if($editrow['FLD_BRAND']=="Canon") echo "selected"; ?>>Canon</option> 
                <option value="Nikon" <?php if(isset($_GET['edit'])) if($editrow['FLD_BRAND']=="Nikon") echo "selected"; ?>>Nikon</option>
              </select><br>
            </div>
          </div>
          <!-- TYPE -->
          <div class="form-group">
            <label for="producttype" class="col-sm-3 control-label">Type</label>
            <div class="col-sm-9">
              <select class="form-control" id="producttype" name="type" required>
                <option value="Compact_Camera" <?php if(isset($_GET['edit'])) if($editrow['FLD_TYPE']=="Compact Camera") echo "selected"; ?>>Compact Camera</option>
                <option value="Mirrorless(Non-DSLR)" <?php if(isset($_GET['edit'])) if($editrow['FLD_TYPE']=="Mirrorless(Non-DSLR)") echo "selected"; ?>>Mirrorless (Non-DSLR)</option>
              </select>
              <br>
            </div>
          </div>
          <!-- QUANTITY -->
          <div class="form-group">
            <label for="productquantity" class="col-sm-3 control-label">Quantity</label>
            <div class="col-sm-9">
              <input class="form-control" id="productquantity" name="quantity" type="number" placeholder="Product Quantity" value="<?php if(isset($_GET['edit'])) echo $editrow['FLD_QUANTITY']; ?>" required> <br>
            </div>
          </div>
          <!-- WARRANTY LENGTH -->
          <div class="form-group">
            <label for="productwarrantylength" class="col-sm-3 control-label">Warranty Length</label>
            <div class="col-sm-9">
              <input class="form-control" id="productwarrantylength" name="warrantylength" type="number" value="<?php if(isset($_GET['edit'])) echo $editrow['FLD_WARRANTYLENGTH']; ?>" placeholder="Warranty Length" required> <br>
            </div>
          </div>
          <!-- IMAGE UPLOAD -->
          <div class="form-group">
            <label for="productuploadimage" class="col-sm-3 control-label">Image (Upload an image file):</label>
            <div class="col-sm-9">
              <input class="form-control" id="productuploadimage" type="file" name="image" accept="image/*"> <br>
            </div>
          </div>
          <!-- CURRENT IMAGE -->
          <div class="form-group">
          <?php if (isset($_GET['edit'])) { ?>
            <label for="productuploadimage" class="col-sm-3 control-label">Current Image:</label>
            <div class="col-sm-9">
              <img id="productcurrentimage" type="hidden" src="static/images/<?php echo $editrow['FLD_PRODUCT_ID'] . '.jpg'; ?>" alt="Product Image" width="150" height="150" style="border-radius:20px;"> <br>
            </div>
          </div>

          <div class="form-group">
            <label for="productcurrentimage" class="col-sm-3 control-label"></label>
            <div class="col-sm-offset-3 col-sm-9">
              <input type="hidden" name="old_image" value="<?php echo $editrow['FLD_PRODUCT_ID'] . '.jpg'; ?>">
              <!-- ##################################################################### -->
              <input type="hidden" name="oldpid" value="<?php echo $editrow['FLD_PRODUCT_ID']; ?>">
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
          <h2>Product List</h2>
        </div>

        <table id="myTable" class="table table-striped table-bordered">
          <thead>
            <tr>
              <th>Product ID</th>
              <th>Name</th>
              <th>Price (RM)</th>
              <th>Brand</th>
              <th>Type</th>
              <th>Quantity</th>
              <th>Warranty Length</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php
              // Read
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
              foreach($result as $readrow) {
              ?>   
              <tr>
                <td><?php echo $readrow['FLD_PRODUCT_ID']; ?></td>
                <td><?php echo $readrow['FLD_PRODUCT_NAME']; ?></td>
                <td><?php echo $readrow['FLD_PRICE']; ?></td>
                <td><?php echo $readrow['FLD_BRAND']; ?></td>
                <td><?php echo $readrow['FLD_TYPE']; ?></td>
                <td><?php echo $readrow['FLD_QUANTITY']; ?></td>
                <td><?php echo $readrow['FLD_WARRANTYLENGTH']; ?></td>
                <td class="col-md-2 col-xs-2">
                  <button class="btn btn-warning btn-xs" onclick="loadProductDetails('<?php echo $readrow['FLD_PRODUCT_ID']; ?>')">Details</button>
                  <a href="products.php?edit=<?php echo $readrow['FLD_PRODUCT_ID']; ?>" class="btn btn-success btn-xs" role="button"> Edit </a>
                  <a href="products.php?delete=<?php echo $readrow['FLD_PRODUCT_ID']; ?>" onclick="return confirm('Are you sure to delete?');" class="btn btn-danger btn-xs" role="button">Delete</a>
                </td>
              </tr>
              <?php } $conn = null; ?>
          </tbody>
        </table>
         <!-- EXPORT BUTTON to the center -->
         <div class="text-center">
          <button id="exportBtn" class="btn btn-primary">
            <span class="glyphicon glyphicon-export"></span> Export to Excel
          </button>
        </div>
      </div>
    </div>
    
    
  
  <script>
    function loadProductDetails(productId) {
      $.ajax({
        url: 'product_details_ajax.php',
        method: 'POST',
        data: { productId: productId },
        success: function(response) {
          $('#productDetailsContent').html(response);
          $('#productDetailsModal').modal('show');
        },
        error: function() {
          alert('Error loading product details.');
        }
      });
    }
  </script>
</body>
<script>
    function validateForm() {
        var isEdit = <?php echo isset($_GET['edit']) ? 'true' : 'false'; ?>;
        if (isEdit) {
            return true;
        }

        var imageInput = document.querySelector('input[name="image"]');
        var imageFile = imageInput.files[0];

        if (!imageFile) {
            alert('Oops! You have not selected an image file.');
            return false; 
        }
        return true;
    }
</script>
</html>
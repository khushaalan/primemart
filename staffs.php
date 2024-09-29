<?php
  include_once 'staffs_crud.php';

  if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
  }
  if ($_SESSION['role'] == 2) {
      echo "<script>alert('You are not allowed to view staff list, because you do not have the permission.');</script>";
      echo "<script>window.location.href='index.php';</script>";
      exit();
  }
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Prime Camera Mart : Staffs</title>
  <link href="bootstrap-3.3.7-dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
 
</head>
<body>
  <?php include_once 'nav_bar.php'; ?>

  <div class="container-fluid">
    <div class="row">
      <div class="col-xs-12 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
        <div class="page-header">
          <h2>Add New Staff</h2>
        </div>
        <form action="staffs.php" method="post">
          <!-- STAFF ID -->
          <div class="form-group">
            <label for="sid" class="col-sm-3 control-label">Staff ID</label>
            <div class="col-sm-9">
              <input class="form-control" id="sid" placeholder="Staff ID" name="sid" type="text" value="<?php if(isset($_GET['edit'])) echo $editrow['FLD_STAFF_ID']; ?>" required> <br>
            </div>
          </div>
          <!-- STAFF PASSWORD -->
          <div class="form-group">
            <label for="spassword" class="col-sm-3 control-label"> Staff Password</label>
            <div class="col-sm-9">
              <input class="form-control" id="spassword" placeholder="Staff Password" name="spassword" type="text" value="<?php if(isset($_GET['edit'])) echo $editrow['FLD_STAFF_PASSWORD']; ?>" aria-label="readonly input" <?php if(isset($_GET['edit'])) echo "readonly" ?>> <br>
            </div>
          </div>
          <!-- STAFF ROLE -->
          <div class="form-group">
            <label for="role" class="col-sm-3 control-label">Staff Role</label>
            <div class="col-sm-9">
              <select name="role" class="form-control" id="role" required>
                <option value="0" <?php if(isset($_GET['edit'])) if($editrow['FLD_STAFF_ROLE']=="0") echo "selected"; ?>>Admin</option>
                <option value="1" <?php if(isset($_GET['edit'])) if($editrow['FLD_STAFF_ROLE']=="1") echo "selected"; ?>>Supervisor</option>
                <option value="2" <?php if(isset($_GET['edit'])) if($editrow['FLD_STAFF_ROLE']=="2") echo "selected"; ?>>Normal Staff</option>
              </select> <br>
            </div>
          <!-- FIRST NAME -->
          <div class="form-group">
            <label for="fname" class="col-sm-3 control-label">First Name</label>
            <div class="col-sm-9">
              <input class="form-control" id="fname" placeholder="First Name" name="fname" type="text" value="<?php if(isset($_GET['edit'])) echo $editrow['FLD_STAFF_FIRST_NAME']; ?>" required> <br>
            </div>
          </div>
          <!-- LAST NAME -->
          <div class="form-group">
            <label for="lname" class="col-sm-3 control-label">Last Name</label>
            <div class="col-sm-9">
              <input class="form-control" id="lname" placeholder="Last Name" name="lname" type="text" value="<?php if(isset($_GET['edit'])) echo $editrow['FLD_STAFF_LAST_NAME']; ?>" required> <br>
            </div>
          </div>
          <!-- GENDER -->
          <div class="form-group">
            <label for="gender" class="col-sm-3 control-label">Gender</label>
            <div class="col-sm-9">
              <div class="radio">
                <label>
                <input id="gender" name="gender" type="radio" value="Male" <?php if(isset($_GET['edit'])) if($editrow['FLD_STAFF_GENDER']=="Male") echo "checked"; ?> required> Male
                </label>
              </div>
              <div class="radio">
                <label>
                  <input id="gender" name="gender" type="radio" value="Female" <?php if(isset($_GET['edit'])) if($editrow['FLD_STAFF_GENDER']=="Female") echo "checked"; ?>> Female
                </label>
              </div>
            </div>
          </div>
          <!-- PHONE NUMBER -->
          <div class="form-group">
            <label for="phone" class="col-sm-3 control-label">Phone Number</label>
            <div class="col-sm-9">
              <input class="form-control" id="phone" placeholder="Phone Number" name="phone" type="text" value="<?php if(isset($_GET['edit'])) echo $editrow['FLD_STAFF_PHONE']; ?>" pattern="\+60\d{2}-\d{7,8}" required> <br>
            </div>
          </div>
          <!-- EMAIL ADDRESS -->
          <div class="form-group">
            <label for="email" class="col-sm-3 control-label">Email Address</label>
            <div class="col-sm-9">
              <input class="form-control" id="email" placeholder="Email Address" name="email" type="text" value="<?php if(isset($_GET['edit'])) echo $editrow['FLD_STAFF_EMAIL']; ?>" pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$" required> <br>
            </div>
          </div>
          <!-- POSITION -->
          <div class="form-group">
            <label for="position" class="col-sm-3 control-label">Position</label>
            <div class="col-sm-9">
              <select name="position" class="form-control" id="position" required>
                <option value="Sales Consultant" <?php if(isset($_GET['edit'])) if($editrow['FLD_STAFF_POSITION']=="Sales Consultant") echo "selected"; ?>>Sales Consultant</option>
                <option value="Senior Sales Consultant" <?php if(isset($_GET['edit'])) if($editrow['FLD_STAFF_POSITION']=="Senior Sales Consultant") echo "selected"; ?>>Senior Sales Consultant</option>
                <option value="Branch Manager" <?php if(isset($_GET['edit'])) if($editrow['FLD_STAFF_POSITION']=="Branch Manager") echo "selected"; ?>>Branch Manager</option>
              </select> <br>
            </div>
          </div>

          <div class="form-group">
            <div class="col-sm-offset-3 col-sm-9">
            <?php if (isset($_GET['edit'])) { ?>
              <input type="hidden" name="oldsid" value="<?php echo $editrow['FLD_STAFF_ID']; ?>">
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
          <h2>Staff List</h2>
        </div>
        <table class="table table-striped table-bordered">
          <tr>
            <th>Staff ID</th>
            <th>Staff Password</th>
            <th>Staff Role</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Gender</th>
            <th>Phone Number</th>
            <th>Email Address</th>
            <th>Position</th>
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
              $stmt = $conn->prepare("SELECT * FROM tbl_staffs_a190409 LIMIT $start_from, $per_page");
              $stmt->execute();
              $result = $stmt->fetchAll();
            }
            catch(PDOException $e){
                  echo "Error: " . $e->getMessage();
            }
            foreach($result as $readrow) {
            ?>
            <tr>
              <td><?php echo $readrow['FLD_STAFF_ID']; ?></td>
              <td><?php if ($readrow['FLD_STAFF_PASSWORD'] == ''){ echo 'Not set'; } else if ($readrow['FLD_STAFF_PASSWORD'] != ''){ echo $readrow['FLD_STAFF_PASSWORD']; } ?></td>
              <td>
                <?php 
                  if($readrow['FLD_STAFF_ROLE']==0) echo "Admin"; 
                  else if($readrow['FLD_STAFF_ROLE']==1) echo "Supervisor"; 
                  else echo "Normal Staff";
                ?>
              </td>


              <td><?php echo $readrow['FLD_STAFF_FIRST_NAME']; ?></td>
              <td><?php echo $readrow['FLD_STAFF_LAST_NAME']; ?></td>
              <td><?php echo $readrow['FLD_STAFF_GENDER']; ?></td>
              <td><?php echo $readrow['FLD_STAFF_PHONE']; ?></td>
              <td><?php echo $readrow['FLD_STAFF_EMAIL']; ?></td>
              <td><?php echo $readrow['FLD_STAFF_POSITION']; ?></td>
              <td>
                <a href="staffs.php?edit=<?php echo $readrow['FLD_STAFF_ID']; ?>" class="btn btn-success btn-xs" role="button"> Edit </a>
                <a href="staffs.php?delete=<?php echo $readrow['FLD_STAFF_ID']; ?>" onclick="return confirm('Are you sure to delete?');" class="btn btn-danger btn-xs" role="button">Delete</a>
              </td>
            </tr>
            <?php } $conn = null; ?>
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
              $stmt = $conn->prepare("SELECT * FROM tbl_staffs_a190409");
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
              <li><a href="staffs.php?page=<?php echo $page-1 ?>" aria-label="Previous"><span aria-hidden="true">«</span></a></li>
            <?php
            }
            for ($i=1; $i<=$total_pages; $i++)
              if ($i == $page)
                echo "<li class=\"active\"><a href=\"staffs.php?page=$i\">$i</a></li>";
              else
                echo "<li><a href=\"staffs.php?page=$i\">$i</a></li>";
            ?>
            <?php if ($page==$total_pages) { ?>
              <li class="disabled"><span aria-hidden="true">»</span></li>
            <?php } else { ?>
              <li><a href="staffs.php?page=<?php echo $page+1 ?>" aria-label="Previous"><span aria-hidden="true">»</span></a></li>
            <?php } ?>
          </ul>
        </nav>
      </div>
    </div>

    

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script src="bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>
</body>
</html>
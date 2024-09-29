<?php
session_start();

include_once 'databases/database.php';

$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error_message = "Both username and password are required.";
    }
    else{
        $password = sha1($password);

        try{
            $stmt = $conn->prepare("SELECT FLD_STAFF_ID, FLD_STAFF_PASSWORD, FLD_STAFF_ROLE FROM tbl_staffs_a190409 WHERE FLD_STAFF_ID = :username");
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $username = $result['FLD_STAFF_ID'];
            $dbPassword = $result['FLD_STAFF_PASSWORD'];
            $dbRole = $result['FLD_STAFF_ROLE'];


            if ($password === $dbPassword) {
                $_SESSION['username'] = $username;
                $_SESSION['password'] = $dbPassword;
                $_SESSION['role'] = $dbRole;
                header("location: index.php");
            } else {
                $error_message = "Incorrect username or password.";
            }
        }
        catch(PDOException $e){
            echo "Error: " . $e->getMessage();
        }
    }
    
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Prime Camera Mart Login</title>
    <link href="bootstrap-3.3.7-dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        body{
            background-color: #f5f5f5;
        }
        .page-header{
            color: #000;
        }
        .form-group{
            color: #000;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
                <div class="page-header">
                    <h2 class="text-center">Prime Camera Mart Login Page</h2>
                </div>
        
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="form-group">
                    <label for="username" class="col-sm-3 control-label">Username</label>
                        <div class="col-sm-9">
                            <input class="form-control" type="text" class="form-control" id="username" name="username" placeholder="Username" value="STF001" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password" class="col-sm-3 control-label">Password</label>
                        <div class="col-sm-9">
                            <input class="form-control" type="password" class="form-control" id="password" name="password" placeholder="Password" value="STF001" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-sm-3 control-label"></label>
                        <div class="col-sm-offset-3 col-sm-9">
                            <button class="btn btn-primary" type="submit" name="create"><span class="glyphicon glyphicon-log-in" aria-hidden="true"></span> Login</button>
                            <button class="btn btn-default" type="reset"><span class="glyphicon glyphicon-erase" aria-hidden="true"></span> Clear</button>
                        </div>
                    </div>
                </form>
                <?php if (isset($error_message)) { ?>
                    <div class="alert alert-danger" role="alert">
                        <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                        <?php echo $error_message; ?>
                    </div>
                    
                <?php } ?>
            </div>
        </div>
    </div>


    <img src="primecameramart.svg" alt="" class="img-fluid" style="width: 100%;">

    

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>
</body>
</html>

<?php
require 'vendor/autoload.php';
require 'config.php';
include('image_check.php');
use Aws\DynamoDb\DynamoDbClient;
use Aws\Common\Enum\Region;
use Aws\DynamoDb\Enum\Type;
use Aws\DynamoDb\Enum\AttributeAction;
use Aws\DynamoDb\Enum\ReturnValue;
use Aws\DynamoDb\Enum\KeyType;
use Aws\S3\S3Client;
$client = DynamoDbClient::factory($config);
$s3 = S3Client::factory($config);
if($_SERVER['REQUEST_METHOD'] == "POST")
    {
    
    
        $userFName     = @$_POST['FirstName'];
	$userSName     = @$_POST['Surname'];
	$userNName	= @$_POST['Nicname'];
    	$userDOB	= @$_POST['DOB'];
        $userEmail    = @$_POST['email'];
        $password     = @$_POST['password'];
    
    
        $name = @$_FILES['imgupload']['name'];
        $size = @$_FILES['imgupload']['size'];
        $tmp =  @$_FILES['imgupload']['tmp_name'];
        $ext =  @getExtension($name);
        $actual_image_name = time().".".$ext;
        
    $profileId = rand(0,50000000);    
    
    $response = $client->putItem(array(
    "TableName" => $tableName,
    "Item" => $client->formatAttributes(array(
        "Id" =>  $profileId,
        "FirstName" => $userFName,
	"Surname" => $userSName,
	"Nicname" => $userNName,
        "useremail" => $userEmail,
        "password" => md5($password),
        "profileImagename" => $actual_image_name,
        "createddate" => date('D-M-Y h:i:s')
        )
    ),
    "ReturnConsumedCapacity" => 'TOTAL'
));
    
  
echo "Consumed capacity: " . $response["ConsumedCapacity"]["CapacityUnits"] . PHP_EOL;
  
                       
                       $result = $s3->upload(
                                    $bucket,
                                    $actual_image_name,
                                    fopen($tmp,'rb'),'public-read'
                                );
     header('Location: login.php');                    
    } 
    
     
    
?>


     <?php include('includes/header.php') ?>

        <div id="page-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                             User Registration
                        </h1>
                    </div>
                </div>
                <!-- /.row -->

                <div class="row">
                    <div class="col-lg-4">

                        <form role="form" method="post" action="" enctype="multipart/form-data" name="regForm" id="regForm">

                            <div class="form-group">
                                <label>FirstName</label>
                                <input class="form-control" name="FirstName">
                            </div>
				
                            <div class="form-group">
                                <label>Surname</label>
                                <input class="form-control" name="Surname">
                            </div>

                            <div class="form-group">
                                <label>Nicname</label>
                                <input class="form-control" name="Nicname">
                            </div>
                            <div class="form-group">
                                <label>DOB</label>
                                <input class="form-control" name="DOB">
                            </div>

                            <div class="form-group">
                                <label>Email</label>
                                <input class="form-control" name="email">
                            </div>

			    <div class="form-group">
                                <label>Password</label>
                                <input class="form-control" type="password" name="password" id="password">
                            </div>
                            
                            <div class="form-group">
                                <label>Confirm Password</label>
                                <input class="form-control" type="password" name="confpassword">
                            </div>
                            
                            <div class="form-group">
                                <label>Profile Picture</label>
                                <input type="file" name="imgupload">
                            </div>

                            <button type="submit" class="btn btn-primary">Register</button>
                            <button type="reset" class="btn btn-primary">Reset</button>

                        </form>

                    </div>
                </div>
                <!-- /.row -->

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <?php include('includes/footer.php') ?>

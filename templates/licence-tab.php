
<?php 
if(isset($_POST['licence_submit'])){
	$licence_key_uno = (isset($_POST['licence_key_uno']))?$_POST['licence_key_uno']:"";

	update_option('licence_key_uno', $licence_key_uno);
    
    $checkLicence = checkLk();
		$valid = $checkLicence['valid'];
$msg = $checkLicence['msg'];
		if($valid)
	     echo '<p class="alert alert-success text-center">License key updated successfully</p>';
	 else 
	 	echo '<p class="alert alert-danger text-center">'.$msg.'</p>';
}


$licence_key_uno = get_option('licence_key_uno');





?>

<div class="container1">


 <div class="col-md-8" style="margin: 20px 0;">
          <form class="form-horizontal row-border" action="" method="POST">
            <div class="form-group">
              <label class="col-md-2">License key</label>
              <div class="col-md-10">
                <input type="text" name="licence_key_uno" class="form-control" placeholder="Enter public key" value="<?= (isset($licence_key_uno))?$licence_key_uno:"" ?>">
              </div>
            </div>

           
             <div class="form-group">
             
              <div class="col-md-3">
                <input type="submit" name="licence_submit" class="form-control btn btn-primary" value="Save">
              </div>
            </div>
            
           
           
            
            
          </form>
        </div>

        </div>

<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
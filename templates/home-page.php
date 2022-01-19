<?php 

if ( ! defined( 'ABSPATH' ) ) {

	exit; // Exit if accessed directly.

}





$message='';

if(!isset($_GET['tab'])) $_GET['tab']='general';

?>

<h2>Dynamics NAV Integration</h2>

<div class="wrap uno">

	<nav class="nav-tab-wrapper woo-nav-tab-wrapper">

		<a href="<?php echo admin_url();?>admin.php?page=uno&amp;tab=licence" class="nav-tab <?php echo ($_GET['tab']=='licence'?'nav-tab-active':'');?>">License Key</a>

		<a href="<?php echo admin_url();?>admin.php?page=uno&amp;tab=general" class="nav-tab <?php echo ($_GET['tab']=='general'?'nav-tab-active':'');?>">General</a>

		<a href="<?php echo admin_url();?>admin.php?page=uno&amp;tab=products" class="nav-tab <?php echo ($_GET['tab']=='products'?'nav-tab-active':'');?>">Products</a>

		<a href="<?php echo admin_url();?>admin.php?page=uno&amp;tab=orders" class="nav-tab <?php echo ($_GET['tab']=='orders'?'nav-tab-active':'');?>">Orders</a>

		<a href="<?php echo admin_url();?>admin.php?page=uno&amp;tab=about" class="nav-tab <?php echo ($_GET['tab']=='about'?'nav-tab-active':'');?>">About</a>
		

	</nav>

	<h1 class="screen-reader-text">General</h1>

	<div id="NavPageContent">

		<?php

		$checkLicence = checkLk();
		$valid = $checkLicence['valid'];
		$msg = $checkLicence['msg'];

		if(isset($_GET['tab']) && $_GET['tab']=='general'){
			if($valid){
				include UNOPATH.'/templates/general-tab.php';
				
			}
			else{
				echo "<div class='error'>".$msg."</div>";
			}
			

		}elseif(isset($_GET['tab']) && $_GET['tab']=='products'){

			if($valid){
				include UNOPATH.'/templates/product-tab.php';
				
			}
			else{
				echo "<div class='error'>".$msg."</div>";
			}

			

		}elseif(isset($_GET['tab']) && $_GET['tab']=='orders'){

			if($valid){
				include UNOPATH.'/templates/orders-tab.php';
				
			}
			else{
				echo "<div class='error'>".$msg."</div>";
			}

			

		}elseif(isset($_GET['tab']) && $_GET['tab']=='about'){

			include UNOPATH.'/templates/about-tab.php';

			

		}
		elseif(isset($_GET['tab']) && $_GET['tab']=='licence'){

			include UNOPATH.'/templates/licence-tab.php';

		}



		?>

	</div>

</div>

<style type="text/css">
	.error { color: red; font-size: 17px; padding: 16px 6px !important; max-width: 100%;text-align: center;margin-top: 23px;top: 20px; position: relative;
}
</style>
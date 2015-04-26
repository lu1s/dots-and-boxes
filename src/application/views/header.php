<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title><?php if(isset($title)) echo $title.' - '; ?>Dots&amp;Boxes</title>
	<?php if(isset($description)): ?>
		<meta name="description" content="<?php echo $description; ?>" />
	<?php endif; ?> 
	<link rel="shortcut icon" href="<?php echo base_url('img/favicon.ico'); ?>"/>
	<link href='http://fonts.googleapis.com/css?family=Averia+Serif+Libre:700' rel='stylesheet' type='text/css' />
	<link href='http://fonts.googleapis.com/css?family=Oxygen' rel='stylesheet' type='text/css'>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
	<script type="text/javascript">
		var urls = {
			logout : "<?php echo site_url('auth/logout'); ?>",
			get_username: "<?php echo site_url('auth/ajax_get_username'); ?>",
			profile: "#" // TODO: Add profile URL once controller is deployed
		}
	</script>
	<script type="text/javascript" src="<?php echo base_url('js/global.js'); ?>"></script>

	<link href="<?php echo base_url('css/style.css'); ?>" rel="stylesheet" type="text/css" />


<?php if(isset($cssfiles)): foreach($cssfiles as $file): ?>
	<link rel="stylesheet" type="text/css" href="<?php echo $file; ?>" />
<?php endforeach; endif; ?>

<?php if(isset($jsfiles)): foreach($jsfiles as $file): ?>
	<script src="<?php echo $file; ?>" type="text/javascript"></script>
<?php endforeach; endif; ?>

<?php if(isset($head_jsvars)): ?>
	<script type="text/javascript">
		<?php foreach($head_jsvars as $var): ?>
				var <?php echo $var['name']; ?> = <?php echo json_encode($var['data']); ?>;
		<?php endforeach; ?>
	</script>
<?php endif; ?>

</head>
<body>
	<div class="header">
		<a href="<?php echo site_url(''); ?>"><img 
			class="left" 
			src="<?php  echo base_url('img/logo.png'); ?>" 
			alt="Dots&amp;Boxes"
			style="width:70px; height:70px"
		/></a>
		<div class="left title">
			<h1>Dots&amp;Boxes</h1>
			<h2>Connect the dots, complete a box.</h2>
		</div>

		<?php if($username != false): ?>

		<div class="right account">
			Welcome, <a href="<?php echo site_url('account'); ?>"><?php echo $username; ?></a>. | <a href="<?php echo site_url('auth/logout'); ?>">logout</a>
		</div>


		<?php else: ?>

			<div class="right account notsigned">
				<a href="<?php echo site_url('auth/login'); ?>">login</a> | 
				<a href="<?php echo site_url('auth/register'); ?>">sign up</a>
			</div>

		<?php endif; ?>
		
		<div class="clear"></div>


	</div>

	<div class="menu_frame">
			<div class="menu">
				<a class="first" href="<?php echo site_url(''); ?>">Home</a>
				<a href="#">Instructions</a>
				<a href="<?php echo site_url('about/us'); ?>">About</a>
				<a href="#">High scores</a>
				<a class="last" href="<?php echo site_url('play'); ?>">Play</a>

				<div class="clear"></div>
			</div>
	</div>

	<div id="main">
	
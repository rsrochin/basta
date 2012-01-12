<!-- Developed by Juvasoft Web Solutions -->
<?php require "elements/head.php"; ?>
<?php require "elements/header.php"; ?>
<div class="container <?=$request['controller'];?>">
	<?php require_once $actionFile; ?>
</div><!--.container-->
<?php require "elements/footer.php"; ?>
<!-- Developed by Juvasoft Web Solutions -->
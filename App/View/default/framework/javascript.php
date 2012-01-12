<?php
$coreJSFiles = array('jquery-1.6.2.min','jquery-ui-1.8.custom.min',
	'jquery.retraso','modernizr-1.6.min',
	'bootstrap-tabs','bootstrap-alerts',
	'bootstrap-modal','bootstrap-buttons','main');
$javascriptFiles = array_unique(array_merge($coreJSFiles, $core['files']['js']));
?>
<script type="text/javascript" src="<?php echo $baseUrl; ?>scripts/js.php?mod=<?php echo implode(',', $javascriptFiles); ?>"></script>
<?php
$generic = array();
$css = array_unique(array_merge($generic, $core['files']['css']));
?>
<link type="text/css" href="<?=$baseUrl; ?>css/css.php?mod=<?php echo implode(',', $css); ?>" rel='stylesheet' />
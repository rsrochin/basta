<?php
$corejs = array('jquery-1.6.2.min','jquery-ui-1.8.custom.min',
                'jquery.retraso','modernizr-1.6.min','main',
                'bootstrap-tabs','bootstrap-alerts',
                'bootstrap-modal','bootstrap-buttons');

$js = array_unique(array_merge($corejs, $js));
?>
<script type="text/javascript" src="<?=$baseUrl; ?>scripts/js.php?mod=<?php echo implode(',', $js); ?>"></script>
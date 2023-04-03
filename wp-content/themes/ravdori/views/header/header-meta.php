<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="theme-color" content="#03499A">
<link rel="icon" href="<?php echo TEMPLATE; ?>/images/general/favicon.ico" type="image/x-icon">
<link rel="shortcut icon" href="<?php echo TEMPLATE; ?>/images/general/favicon.ico" type="image/x-icon">

<!-- Facebook Open Graph API -->
<meta property="fb:app_id" content="666465286777871"/>

<!-- Font Awesome -->
<!-- Font Awesome -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

<title><?php wp_title(' | ', true, 'left'); ?></title>

<!-- Google Fonts -->
<?php
	global $google_fonts;
	
	if ($google_fonts) : foreach ($google_fonts as $key => $val) :
		printf ("<link href='%s' rel='stylesheet'>", $val);
	endforeach; endif;
?>

<?php $ga_code = get_field( 'acf-options-google-analytics-tracking-ID', 'options' );  ?>

<?php if ( $ga_code ): ?>

<!-- Google Analytics -->
<script>
window.ga=window.ga||function(){(ga.q=ga.q||[]).push(arguments)};ga.l=+new Date;
ga('create', '<?php echo $ga_code; ?>', 'auto');
ga('send', 'pageview');
</script>
<script async src='https://www.google-analytics.com/analytics.js'></script>
<!-- End Google Analytics -->

<?php endif; ?>
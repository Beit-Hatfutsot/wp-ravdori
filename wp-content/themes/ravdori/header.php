<!DOCTYPE html>
<html <?php language_attributes(); ?>>

	<head>

		<?php
		
			get_template_part('views/header/header', 'meta');

            BH_load_theme_styles();

            BH_load_theme_scripts();

			wp_head();

    	?>
		
		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
			<script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
		<![endif]-->

    </head>
	
	<body <?php body_class(); ?>>
		 
           <?php
			
            get_template_part('views/header/facebook-api');
			
			get_template_part('views/header/header');
			
			// The mobile menu
			get_template_part('views/components/offcanvas-menu'); 

		 ?>


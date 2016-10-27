<?php
global $wizardSessionManager;
//$wizardSessionManager->checkTimeout();
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

	<head>

		<?php
		
			get_template_part('views/header/header', 'meta');

            BH_load_theme_styles();

            BH_load_theme_scripts();

			wp_head();


		//  global $wizardSessionManager;
       // $wizardSessionManager->destroy();

/*
        echo '<pre style="background: lightgray; text-align: left;direction: ltr;">';
        echo '<strong> Session:  </strong>';
        var_dump($_SESSION);

        echo 'POST: ';
        var_dump($_POST);
        echo '</pre>';
*/
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
		 ?>

